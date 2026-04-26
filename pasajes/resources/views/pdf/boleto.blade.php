<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Boleto #{{ $boleto->id }} — {{ $boleto->hojaRuta->frecuencia->ruta->cooperativa->nombre }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; margin: 0; padding: 20px; color: #1a1a2e; background: #fff; }
        .header { background: #1e3a8a; color: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 22px; }
        .header p { margin: 4px 0 0; font-size: 12px; opacity: 0.8; }
        .grid { display: table; width: 100%; }
        .row { display: table-row; }
        .cell { display: table-cell; padding: 8px 12px; border-bottom: 1px solid #e5e7eb; font-size: 12px; width: 50%; }
        .label { color: #6b7280; font-size: 10px; text-transform: uppercase; }
        .value { font-weight: bold; font-size: 13px; }
        .qr-section { text-align: center; margin-top: 20px; padding-top: 20px; border-top: 2px dashed #d1d5db; }
        .qr-section img { width: 150px; height: 150px; }
        .badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 10px; font-weight: bold; background: #dcfce7; color: #166534; }
        .warning { background: #fef3c7; color: #92400e; padding: 10px; border-radius: 6px; font-size: 11px; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $boleto->hojaRuta->frecuencia->ruta->cooperativa->nombre }}</h1>
        <p>Sistema de Pasajes — Boleto Digital Oficial</p>
    </div>

    <div style="margin-bottom: 10px;">
        <span class="badge">{{ strtoupper($boleto->estado) }}</span>
        <span style="font-size: 18px; font-weight: 900; margin-left: 10px;">
            {{ $boleto->origen_abordaje }} → {{ $boleto->destino_desembarque }}
        </span>
    </div>

    <div class="grid">
        <div class="row">
            <div class="cell"><div class="label">Pasajero</div><div class="value">{{ $boleto->nombre_pasajero }}</div></div>
            <div class="cell"><div class="label">Cédula</div><div class="value">{{ $boleto->cedula_pasajero }}</div></div>
        </div>
        <div class="row">
            <div class="cell"><div class="label">Fecha de viaje</div><div class="value">{{ $boleto->hojaRuta->fecha->format('d/m/Y') }}</div></div>
            <div class="cell"><div class="label">Hora de salida</div><div class="value">{{ \Carbon\Carbon::parse($boleto->hojaRuta->frecuencia->hora_salida)->format('H:i') }}</div></div>
        </div>
        <div class="row">
            <div class="cell"><div class="label">Asiento</div><div class="value" style="color:#1d4ed8">{{ $boleto->asiento->numero }} ({{ $boleto->asiento->tipo }})</div></div>
            <div class="cell"><div class="label">Tipo pasajero</div><div class="value">{{ $boleto->tipo_pasajero }}</div></div>
        </div>
        <div class="row">
            <div class="cell"><div class="label">Bus / Placa</div><div class="value">{{ $boleto->hojaRuta->bus->placa }}</div></div>
            <div class="cell"><div class="label">Precio</div><div class="value" style="color:#166534">${{ $boleto->precio }}</div></div>
        </div>
        <div class="row">
            <div class="cell"><div class="label">Resolución ANT</div><div class="value">{{ $boleto->hojaRuta->frecuencia->resolucion_ant }}</div></div>
            <div class="cell"><div class="label">Nro. Boleto</div><div class="value">#{{ $boleto->id }}</div></div>
        </div>
    </div>

    <div class="qr-section">
        <p style="font-size:11px; color:#6b7280; margin-bottom:8px;">
            Código QR — Presenta este documento al subir al bus
        </p>
        {!! QrCode::format('svg')->size(150)->generate(url('/chofer/validar/' . $boleto->qr_code)) !!}
        <p style="font-size:9px; color:#9ca3af; margin-top:6px; font-family:monospace;">{{ $boleto->qr_code }}</p>
    </div>

    <div class="warning">
        ⚠️ Este boleto digital es el único documento válido para abordar. El comprobante impreso de ventanilla es solo referencia de compra y no sirve para abordar.
    </div>
</body>
</html>
