# Sistema de Pasajes Interprovincial

Plataforma multi-cooperativa para gestion y venta de pasajes en Ecuador.

## Stack implementado

- Laravel 12 + Livewire 4
- PostgreSQL (Docker)
- Mailpit SMTP (Docker)
- Cola de trabajos (queue worker en Docker)
- QR (Simple QrCode + escaneo movil con html5-qrcode)
- Pago en linea con PayPal Sandbox (Checkout + capture)

## Levantar con Docker

Desde la carpeta `pasajes` ejecutar:

```bash
docker compose up --build
```

Servicios disponibles:

- App: http://localhost:8000
- Mailpit UI: http://localhost:8025
- PostgreSQL: localhost:5432

El contenedor `app` ejecuta automaticamente:

- `composer install`
- `npm install` y `npm run build`
- migraciones y seeders
- `php artisan serve`

## Variables de entorno

Archivo base: `.env.example`

Variables clave:

- `DB_*`: configuradas para servicio `pgsql` del compose
- `MAIL_*`: configuradas para `mailpit`
- `PAYPAL_MODE=sandbox`
- `PAYPAL_CLIENT_ID` y `PAYPAL_CLIENT_SECRET`: credenciales sandbox reales

Sin credenciales de PayPal el boton de pago mostrara error controlado.

## Usuarios de prueba (Seeder)

- Admin: `admin@cooperativa.com` / `password`
- Oficinista: `oficinista@cooperativa.com` / `password`
- Chofer: `chofer@cooperativa.com` / `password`
- Usuario final: `usuario@gmail.com` / `password`

Cooperativa precargada: **Cooperativa de Transportes Banos**

## Flujo principal

1. Usuario final busca frecuencias desde inicio.
2. Selecciona asiento y genera boleto pendiente.
3. Puede pagar por:
	- PayPal Sandbox (validacion automatica del boleto).
	- Transferencia (sube comprobante para revision).
4. Al aprobar pago:
	- Boleto pasa a `Validado`.
	- Se genera PDF en cola.
	- Se envia notificacion por correo.

## Reglas de negocio implementadas

- Scope multi-cooperativa para `Bus`, `Ruta`, `Frecuencia`, `HojaRuta`, `User`.
- Oficinista no puede vender si el bus ya partio.
- Chofer puede validar QR y vender en ruta.
- Bloqueo de doble venta de asiento con transaccion y lock.

## Comandos utiles

```bash
docker compose exec app php artisan test
docker compose exec app php artisan migrate:fresh --seed
docker compose logs -f app
docker compose logs -f queue
```
