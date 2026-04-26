# Sistema Web de Gestión y Venta de Pasajes — Transporte Interprovincial Ecuador

> **Instrucción al agente:** Construye el sistema **completo y funcional** de principio a fin. No apliques control de versiones, no crees ramas de Git, no fragmentes en releases. El objetivo es tener el sistema 100% operativo para validarlo con el equipo y luego hacer ingeniería inversa para organizarlo en repositorio. Entrega **todo** — backend, frontend, base de datos, Docker — funcionando de forma integrada.

---

## 1. Visión General

Plataforma web para cooperativas de transporte interprovincial del Ecuador que permite:

- Administrar la operación interna de la cooperativa (buses, rutas, frecuencias, personal).
- Vender pasajes tanto en ventanilla (oficinista) como en línea (usuario final).
- Validar boletos digitales con código QR desde dispositivos móviles (chofer/controlador).
- Gestionar la hoja de ruta mensual/diaria de cada cooperativa.

El sistema es **multi-cooperativa**: cada cooperativa opera en su propio espacio de datos pero sobre la misma plataforma.

---

## 2. Stack Tecnológico

Define el stack que mejor domine el agente, respetando las siguientes restricciones obligatorias:

| Capa | Requerimiento obligatorio |
|------|--------------------------|
| Contenedores | Docker + Docker Compose |
| Base de datos | Relacional (PostgreSQL recomendado) |
| Autenticación | JWT o sesiones con roles |
| QR | Generación y escaneo desde navegador móvil |
| Pagos | PayPal y/o tarjeta de crédito (integración real o sandbox) |
| Correo | SMTP para notificaciones y boletos |

---

## 3. Roles de Usuario

### 3.1 Administrador de Cooperativa (Gerente)
- Acceso total al sistema dentro de su cooperativa.
- Gestiona: buses, rutas, frecuencias, horarios, oficinistas, choferes, controladores.
- Configura categorías de asientos, tipos de bus y tarifas.
- Asigna frecuencias (otorgadas por la ANT) a sus buses.
- Habilita o deshabilita rutas para la venta.
- Ve reportes de ventas, ocupación y validaciones.

### 3.2 Oficinista
- Vende boletos presencialmente en ventanilla.
- Busca frecuencias disponibles por origen, destino y fecha.
- Registra datos del pasajero (nombre, cédula, tipo: normal/niño/discapacitado).
- Selecciona asientos disponibles e imprime/genera el boleto.
- **Regla:** Solo puede vender para frecuencias **habilitadas** y cuyo bus **no haya partido** aún desde el punto de origen de esa venta.
- Puede realizar cambios de bus cuando un bus asignado queda fuera de servicio, siempre que el bus de reemplazo esté disponible en esa provincia/terminal.
- Ve la hoja de ruta del día para su terminal.

### 3.3 Usuario Final (Pasajero en línea)
- Se registra y compra boletos desde el navegador (web responsive).
- Busca frecuencias por origen, destino y fecha; aplica filtros.
- Selecciona asientos desde un mapa visual (estilo boletería de cine/aerolínea) con distinción ventana/pasillo y estado disponible/ocupado.
- Paga en línea (PayPal / tarjeta). Recibe boleto digital con QR por correo y en pantalla.
- Tiene historial de compras. Si cierra el navegador, sus compras persisten.
- Puede solicitar reembolso vía correo (se le indica acercarse a oficina).

### 3.4 Chofer / Controlador (Personal del Bus)
- Accede desde el navegador de su celular.
- Escanea el código QR del pasajero para validarlo.
- La validación registra: pasajero abordó / no abordó.
- **Puede vender boletos** para pasajeros que suben en paradas intermedias, siempre que el bus ya haya partido del punto de origen (complementario a la restricción del oficinista).
- Ve el manifiesto de pasajeros de su frecuencia en tiempo real.

---

## 4. Módulos del Sistema

### 4.1 Gestión de Cooperativa (Admin)

#### 4.1.1 Buses
- CRUD de buses: placa, marca de carrocería, categoría, foto, capacidad total.
- Configuración de distribución de asientos por bus (ej. 20 ventana + 20 pasillo, pisos, cama, semi-cama, etc.).
- Estado del bus: activo, en mantenimiento, fuera de servicio.

#### 4.1.2 Rutas y Frecuencias
- **Ruta:** Define el trayecto base (ej. Ambato → Quito) con sus paradas intermedias opcionales (ej. Latacunga, Salcedo). Puede ser directo o con paradas.
- **Frecuencia:** Es una ruta a la que se le asigna un horario fijo (ej. salida 06:00). Son las frecuencias que la ANT otorga a cada cooperativa.
- **Hoja de Ruta:** Documento operativo (diario, semanal o mensual) donde el oficinista/admin asigna manualmente qué bus cubre qué frecuencia en qué fecha. Esta asignación es la que **habilita** la frecuencia para la venta.
  - Si el bus principal ya tiene frecuencia asignada, los buses restantes se asignan a paradas intermedias.
  - Si una ruta es **directa**, el chofer NO vende boletos intermedios.
- CRUD de rutas y frecuencias.
- Gestión de paradas: agregar, reordenar, eliminar paradas de una ruta.

#### 4.1.3 Personal
- CRUD de usuarios internos: oficinistas, choferes, controladores.
- Asignación de rol y terminal/cooperativa.
- Perfil del usuario editable según la cooperativa.

#### 4.1.4 Tarifas y Categorías
- Categorías de asiento (ej. estándar, VIP, cama, semi-cama).
- Tipos de pasajero: adulto, niño, persona con discapacidad (con descuento correspondiente).
- Precio base por ruta + modificadores por categoría de asiento y tipo de pasajero.

---

### 4.2 Módulo de Venta — Oficinista

1. **Buscador de frecuencias:** origen, destino, fecha. Muestra solo rutas habilitadas en la hoja de ruta.
2. **Selección de pasajeros:** cuántos viajan, tipo de cada uno.
3. **Selección de asientos:** mapa visual del bus con disponibilidad en tiempo real.
4. **Registro de datos del pasajero:** nombre, número de cédula o pasaporte, tipo.
5. **Confirmación y cobro:** registra el pago como efectivo/tarjeta en ventanilla.
6. **Emisión del boleto:** imprimible y/o digital con QR.
7. **Control de corte:** la venta se bloquea automáticamente si el bus ya partió del punto de origen de esa terminal.

---

### 4.3 Módulo de Venta — Usuario Final (Online)

1. **Búsqueda pública:** sin necesidad de cuenta para buscar, con cuenta para comprar.
2. **Filtros:** fecha, hora de salida, tipo de bus, precio, paradas.
3. **Detalle de frecuencia:** bus asignado, paradas, duración estimada, asientos disponibles.
4. **Selector de asientos visual:** mapa interactivo tipo boletería de cine/aerolínea. Diferencia ventana / pasillo, disponible / ocupado / seleccionado.
5. **Datos de pasajeros:** un formulario por pasajero (nombre, documento, tipo).
6. **Pago en línea:** PayPal y/o tarjeta. Sandbox aceptado si no hay producción.
7. **Boleto digital:** generado con QR único por pasajero. Enviado por correo y disponible en el historial.
8. **Historial de compras:** accesible desde el perfil del usuario.
9. **Solicitud de reembolso:** formulario que genera correo a la cooperativa indicando que el pasajero debe acercarse a oficina.

---

### 4.4 Módulo de Personal del Bus (Chofer / Controlador)

1. **Vista desde móvil:** diseño responsive optimizado para celular.
2. **Escaneo de QR:** usa la cámara del celular desde el navegador (sin app nativa).
3. **Validación de boleto:** muestra nombre del pasajero, asiento, origen/destino, estado (válido / ya validado / inválido).
4. **Manifiesto de viaje:** lista de todos los pasajeros de la frecuencia con estado de abordaje.
5. **Venta en ruta:** puede vender boletos para paradas intermedias **después** de que el bus haya partido del origen. Solo aplica en rutas con paradas (no en rutas directas).

---

## 5. Reglas de Negocio Críticas

| # | Regla |
|---|-------|
| 1 | Una frecuencia solo está disponible para venta cuando tiene bus asignado en la hoja de ruta (habilitación manual). |
| 2 | El oficinista **no puede** vender boletos si el bus ya partió del punto de origen de su terminal. |
| 3 | El chofer **sí puede** vender boletos para pasajeros que suben en paradas intermedias, siempre que el bus ya esté en ruta. |
| 4 | En rutas directas (sin paradas), el chofer no realiza ventas intermedias. |
| 5 | Los asientos vendidos se bloquean en tiempo real para evitar doble venta. |
| 6 | Cada cooperativa solo gestiona sus propios buses, rutas y frecuencias. |
| 7 | Las frecuencias son otorgadas por la ANT; la cooperativa las recibe y asigna buses a ellas. |
| 8 | Un bus en mantenimiento o fuera de servicio no puede asignarse a una frecuencia. |
| 9 | Un bus no puede cubrir dos frecuencias simultáneas. |
| 10 | El reembolso es solo presencial; el sistema solo registra la solicitud y notifica por correo. |

---

## 6. Boleto Digital

Cada boleto debe contener:

- Nombre completo del pasajero.
- Número de documento.
- Tipo de pasajero (adulto / niño / discapacitado).
- Cooperativa.
- Origen → Destino.
- Fecha y hora de salida.
- Número de asiento y tipo.
- Número de bus / placa.
- Código QR único (vinculado al ID del boleto en la BD).
- Precio pagado.
- Número de transacción / referencia de pago.

---

## 7. Notificaciones por Correo

| Evento | Destinatario |
|--------|-------------|
| Compra exitosa | Pasajero (adjunta boleto con QR) |
| Solicitud de reembolso | Cooperativa + confirmación al pasajero |
| Cambio de bus asignado | Pasajero afectado (si aplica) |

---

## 8. Despliegue con Docker ⚠️ OBLIGATORIO

El sistema completo debe desplegarse con `docker-compose up`. El `docker-compose.yml` debe incluir al menos:

- Servicio de base de datos (PostgreSQL o equivalente).
- Servicio de backend/API.
- Servicio de frontend (o servido desde el backend).
- Variables de entorno en `.env` (credenciales, claves de API de pagos, SMTP, secreto JWT).
- Volumen persistente para la base de datos.
- Red interna entre servicios.

Incluir un `README.md` con instrucciones claras de instalación y arranque.

---

## 9. Consideraciones de Diseño y UX

- El selector de asientos debe ser **visualmente intuitivo**: inspirado en apps de cine o aerolíneas.
- El módulo del chofer debe ser **extremadamente simple** y usable con una mano desde un celular.
- Los formularios de venta del oficinista deben ser **rápidos**: mínimo de clics para completar una venta.
- El sistema debe ser responsive en todos sus módulos públicos y del personal del bus.

---

## 10. Entregable Esperado del Agente

El agente debe producir:

- [ ] Código fuente completo del backend (API REST o equivalente).
- [ ] Código fuente completo del frontend (todos los módulos y roles).
- [ ] Script(s) de base de datos: esquema + datos semilla (cooperativa de prueba, usuarios de ejemplo para cada rol).
- [ ] `docker-compose.yml` funcional.
- [ ] `.env.example` con todas las variables necesarias documentadas.
- [ ] `README.md` con instrucciones de arranque local.
- [ ] Sistema 100% operativo al ejecutar `docker-compose up`.

> **No se requiere** control de versiones, ramas de Git, documentación de releases, ni versionado semántico. El foco es un sistema funcional completo listo para revisión del equipo.
