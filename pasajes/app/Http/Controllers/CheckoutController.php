<?php

namespace App\Http\Controllers;

use App\Jobs\EnviarBoletoAprobado;
use App\Jobs\GenerarPdfBoleto;
use App\Models\Boleto;
use App\Models\Comprobante;
use App\Services\PayPalService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use RuntimeException;

class CheckoutController extends Controller
{
    public function create(Boleto $boleto, PayPalService $payPalService): RedirectResponse
    {
        $this->autorizar($boleto);

        if ($boleto->estado !== 'Pendiente') {
            return back()->with('error', 'Este boleto ya no requiere pago en linea.');
        }

        try {
            $order = $payPalService->createOrder(
                $boleto,
                route('checkout.paypal.success', ['boleto' => $boleto->id]),
                route('checkout.paypal.cancel', ['boleto' => $boleto->id]),
            );

            session()->put('paypal_order_' . $boleto->id, $order['id']);

            return redirect()->away($order['approve_url']);
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function success(Request $request, Boleto $boleto, PayPalService $payPalService): RedirectResponse
    {
        $this->autorizar($boleto);

        $orderId = $request->string('token')->toString();
        $sessionOrderId = session()->get('paypal_order_' . $boleto->id);

        if (! $orderId) {
            $orderId = (string) $sessionOrderId;
        }

        if (! $orderId || ($sessionOrderId && $orderId !== $sessionOrderId)) {
            return redirect()->route('boleto.ver', $boleto)->with('error', 'No se pudo verificar la orden de PayPal.');
        }

        try {
            $capture = $payPalService->captureOrder($orderId);

            if (($capture['status'] ?? null) !== 'COMPLETED') {
                return redirect()->route('boleto.ver', $boleto)->with('error', 'El pago no fue completado en PayPal.');
            }

            DB::transaction(function () use ($boleto, $capture): void {
                Comprobante::updateOrCreate(
                    ['boleto_id' => $boleto->id],
                    [
                        'imagen_url' => 'paypal-sandbox',
                        'estado' => 'Aprobado',
                        'validado_por' => null,
                        'validado_at' => now(),
                        'observaciones' => 'Pago aprobado mediante PayPal Sandbox.',
                        'metodo_pago' => 'paypal',
                        'referencia_pago' => $capture['capture_id'] ?? null,
                        'metadata' => $capture['payload'] ?? null,
                    ]
                );

                $boleto->update(['estado' => 'Validado']);
            });

            GenerarPdfBoleto::dispatch($boleto);
            EnviarBoletoAprobado::dispatch($boleto);

            session()->forget('paypal_order_' . $boleto->id);

            return redirect()->route('boleto.ver', $boleto)->with('success', 'Pago aprobado por PayPal. Tu boleto ya esta validado.');
        } catch (RuntimeException $e) {
            return redirect()->route('boleto.ver', $boleto)->with('error', $e->getMessage());
        }
    }

    public function cancel(Boleto $boleto): RedirectResponse
    {
        $this->autorizar($boleto);

        return redirect()->route('boleto.ver', $boleto)->with('error', 'Pago cancelado por el usuario en PayPal.');
    }

    private function autorizar(Boleto $boleto): void
    {
        $authId = (int) (Auth::id() ?? 0);
        abort_unless((int) $boleto->user_id === $authId, 403);
    }
}