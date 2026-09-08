# RestaAPP AI Call Center - Plan de negocio y ejecución

**Fecha:** 2026-09-08  
**Precio comercial recomendado:** RD$1,990/mes por restaurante  
**Proveedor IA/voz:** ElevenLabs únicamente

## Regla de arquitectura
**ElevenLabs conversa. RestaAPP valida y ejecuta. Android conecta la línea móvil.**

## Decisión fiscal
- NCF local permanece en `Modules/DgiiFiscalRd` / `FiscalReceiptService`.
- e-CF permanece en `Modules/FiscalRd` y debe respetar su gate de proveedor/readiness.
- El AI Call Center nunca asigna NCF/e-NCF.
- La IA crea la orden comercial; la emisión fiscal sigue en el flujo existente de orden/pago/fiscal de RestaAPP.

## Gates obligatorios antes de producción
1. Auditoría real de órdenes pickup/delivery, clientes, direcciones, pagos y fiscalidad.
2. Contrato API versionado y autenticado.
3. Aislamiento por restaurante/sucursal.
4. API key de ElevenLabs cifrada en servidor.
5. Idempotencia y protección contra replay.
6. Cálculo de totales únicamente en RestaAPP.
7. Regresión fiscal NCF/e-CF.
8. Pruebas reales del Gateway Android.

## Cronograma
- Gate 0 - Auditoría sin cambios: 2-3 días hábiles.
- Gate 1 - Contrato API/seguridad: 2 días hábiles.
- Fase 1 - API Laravel: 5 días hábiles.
- Fase 2 - Integración fiscal segura: 2 días hábiles.
- Fase 3 - ElevenLabs: 2-3 días hábiles.
- Fase 4 - Gateway Kotlin Android: 5-7 días hábiles.
- Fase 5 - QA/seguridad: 5 días hábiles.
- Piloto controlado: 3-5 días hábiles.

**Ruta controlada estimada al piloto:** 26-32 días hábiles.

## Producción
**No ejecutar todavía la migración en producción.** Primero staging, pruebas end-to-end, regresión fiscal y piloto con 1-3 restaurantes.
