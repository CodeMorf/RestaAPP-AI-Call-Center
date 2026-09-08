# RestaAPP AI Call Center

Módulo oficial de telefonía inteligente para RestaAPP.

## Objetivo

RestaAPP AI Call Center permite que cada restaurante conecte su propio agente de ElevenLabs y un teléfono Android con SIM para convertir su número habitual en un canal de atención, pedidos, reservas y SMS integrado con RestaAPP.

### Principio de arquitectura

**ElevenLabs conversa. RestaAPP consulta, valida y ejecuta. Android conecta la línea móvil.**

RestaAPP no implementa un motor paralelo de conversación. El Agent ID configurado por el restaurante conserva la voz, personalidad, idioma y lógica conversacional de ElevenLabs. El módulo únicamente publica herramientas de negocio seguras para que el agente pueda consultar información real y ejecutar acciones autorizadas.

## Precio recomendado

- Nombre comercial: **RestaAPP AI Call Center**
- Precio base sugerido: **RD$1,990/mes por restaurante**
- Precio configurable por Super Admin.
- ElevenLabs, SIM, minutos, SMS y plan móvil son contratados por el cliente.

## Flujo principal

1. El restaurante activa el módulo.
2. Configura su Agent ID y credencial de ElevenLabs.
3. RestaAPP verifica la conexión y genera la plantilla recomendada de herramientas.
4. El restaurante muestra un QR de vinculación.
5. RestaAPP Phone Gateway para Android escanea el QR.
6. El teléfono queda asociado al restaurante/sucursal sin guardar secretos permanentes dentro del QR.
7. ElevenLabs atiende la conversación.
8. Cuando necesita información o debe ejecutar una acción, llama a una herramienta de RestaAPP.
9. RestaAPP valida permisos, restaurante, sucursal, productos, precios, disponibilidad, impuestos, entrega y total.
10. RestaAPP registra la acción y devuelve una respuesta simple al agente.

## Pedidos por teléfono

El agente nunca debe inventar productos, precios, variantes, disponibilidad, promociones, cargos, impuestos o tiempos.

Flujo recomendado:

`search_menu -> get_product -> validate_fulfillment -> validate_cart -> confirmación verbal -> create_order`

### Delivery

El agente identifica que el cliente quiere entrega y solicita los datos naturales que falten. RestaAPP valida:

- dirección escrita;
- ubicación/latitud/longitud cuando esté disponible;
- sucursal que atiende la zona;
- cobertura;
- cargo de entrega;
- horario;
- disponibilidad;
- tiempo estimado disponible en el sistema.

Si falta información, la herramienta responde cuáles datos debe preguntar ElevenLabs. Laravel no controla la conversación.

### Recogida / Pickup / Para llevar

El agente identifica la modalidad y RestaAPP valida:

- sucursal;
- dirección de recogida;
- horario;
- disponibilidad;
- tiempo de preparación;
- instrucciones aplicables.

`pickup`, `recogida` y `takeaway/para llevar` se normalizan internamente sin obligar al cliente a usar palabras específicas.

## Herramientas ElevenLabs

Conjunto inicial recomendado:

1. `get_restaurant_context`
2. `search_menu`
3. `get_product`
4. `validate_fulfillment`
5. `validate_cart`
6. `create_order`
7. `get_order_status`
8. `find_customer`
9. `upsert_customer`
10. `check_reservation`
11. `create_reservation`
12. `send_customer_sms`

Opcionales y controladas por permisos:

13. `update_order`
14. `cancel_order`
15. `transfer_to_human`

No se crean herramientas distintas por tipo de producto. `search_menu` debe resolver pizzas, bebidas, postres, combos y cualquier categoría existente usando los datos reales de RestaAPP.

## Seguridad del pedido

`validate_cart` debe devolver un `cart_token` temporal, firmado e idempotente asociado a los IDs reales de productos, variantes, cantidades, precios, impuestos, cargos, sucursal y modalidad.

`create_order` solamente puede operar con un token vigente y una confirmación explícita del cliente. El servidor vuelve a validar el carrito antes de crear la orden.

## QR de vinculación

El QR contiene solamente información de vinculación temporal:

- `pairing_token` de un solo uso;
- `restaurant_public_id`;
- `branch_public_id` opcional;
- `expires_at`;
- `version`.

Nunca contiene la API key de ElevenLabs.

El Android intercambia el token por una credencial revocable y limitada al dispositivo. La identidad del restaurante y sucursal se resuelve siempre del lado servidor.

## Android Gateway

Nombre: **RestaAPP Phone Gateway**

Funciones previstas:

- escaneo QR;
- asociación del dispositivo;
- información básica de SIM y número cuando Android lo permite;
- llamadas entrantes/salientes mediante las APIs permitidas por Android;
- envío/recepción de SMS sujeto a permisos y rol correspondiente;
- heartbeat/estado del dispositivo;
- identificación de llamada y asociación con RestaAPP;
- integración con el Agent ID asignado por el servidor.

### Compatibilidad de audio GSM

El puente de audio bidireccional entre una llamada celular GSM y ElevenLabs debe certificarse por dispositivo/versión de Android. Ser la aplicación de teléfono predeterminada no garantiza por sí solo acceso universal para capturar e inyectar audio de llamada. El módulo no debe anunciar compatibilidad universal hasta superar pruebas físicas de los modelos soportados.

## Integración con RestaAPP existente

El paquete Laravel debe respetar la arquitectura modular actual de RestaAPP basada en `Modules/`, `module.json`, Service Providers, rutas web/API y migraciones de módulo. Debe reutilizar la infraestructura AI existente cuando sea aplicable y no duplicar conversaciones/consumo que ya estén resueltos por `Aitools`.

## UX

La interfaz del restaurante debe mostrar mensajes comerciales y claros. Los detalles técnicos se registran únicamente en logs/documentación/diagnóstico.

Ejemplos de estados visibles:

- `No hay llamadas todavía.`
- `Vincula tu primer teléfono.`
- `Configura tu agente de ElevenLabs.`
- `Configura la ubicación de la sucursal.`
- `No hay datos para mostrar.`

## Estructura del repositorio

- `docs/` — arquitectura, flujo de pedidos, seguridad y plantilla de agente.
- `contracts/` — contratos JSON para Kotlin, pairing y tools.
- `laravel/Modules/AiCallCenter/` — módulo instalable compatible con RestaAPP.
- `android-gateway/` — aplicación nativa Kotlin RestaAPP Phone Gateway.

## Proveedor de IA

**ElevenLabs únicamente.** No implementar OpenAI, Gemini, Claude, Groq u otros proveedores dentro de este módulo.

## Autoría

CodeMorf
