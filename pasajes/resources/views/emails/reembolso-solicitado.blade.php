<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de Reembolso</title>
</head>
<body style="font-family: Arial, sans-serif; background:#0f0f10; color:#f5f5f5; padding:24px;">
    <div style="max-width:640px; margin:0 auto; background:#18181b; border:1px solid #3f3f46; border-radius:12px; padding:24px;">
        <h2 style="margin-top:0; color:#fbbf24;">
            {{ $copiaPasajero ? 'Hemos recibido tu solicitud de reembolso' : 'Nueva solicitud de reembolso recibida' }}
        </h2>

        <p>
            {{ $copiaPasajero
                ? 'Tu solicitud fue registrada correctamente. Para finalizar el proceso debes acercarte a oficina con tu documento de identidad.'
                : 'Se ha registrado una nueva solicitud de reembolso y requiere gestión presencial en oficina.' }}
        </p>

        <ul>
            <li><strong>Boleto:</strong> #{{ $boleto->id }}</li>
            <li><strong>Pasajero:</strong> {{ $boleto->nombre_pasajero }} ({{ $boleto->cedula_pasajero }})</li>
            <li><strong>Ruta:</strong> {{ $boleto->origen_abordaje }} -> {{ $boleto->destino_desembarque }}</li>
            <li><strong>Fecha viaje:</strong> {{ $boleto->hojaRuta->fecha->format('d/m/Y') }}</li>
            <li><strong>Motivo:</strong> {{ $solicitud->motivo }}</li>
            <li><strong>Estado:</strong> {{ $solicitud->estado }}</li>
        </ul>

        <p style="color:#a1a1aa; font-size:13px; margin-bottom:0;">
            Este correo es informativo y forma parte del flujo de reembolso presencial.
        </p>
    </div>
</body>
</html>
