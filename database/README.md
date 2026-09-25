# database/

Ubicación oficial del esquema versionado. **En la Fase 1 está vacía a propósito**: todavía no se
creó, ejecutó ni modificó ninguna migración ni tabla.

- `migrations/`: scripts SQL incrementales, con el nombre `NNNN_descripcion.sql`
  (por ejemplo `0001_baseline.sql`). Se aplican una sola vez y en orden, y quedan registrados
  en una tabla `schema_migrations` (Fase 5).
- `seeds/`: datos de ejemplo o iniciales, **sin datos personales reales ni credenciales**.

## Legacy (todavía vigente)

- `db/*.sql`: esquemas parciales, alters, seeds y dumps históricos. No existe un esquema único.
- Tablas que el propio código PHP crea o altera en tiempo de ejecución: `citas_consulta`,
  `consultas_clientes` (columna `ciudad`) e `indicators`.

La Fase 5 consolidará todo lo anterior en una migración `0001_baseline.sql`, obtenida del esquema
real de producción, y eliminará el DDL de los endpoints HTTP.
