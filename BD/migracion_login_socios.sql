-- Ejecutar una sola vez sobre la base biblioteca2.
-- Con password NULL, el sistema acepta la cedula como contrasena inicial.
-- El socio puede cambiarla luego desde su panel.
ALTER TABLE socios ADD COLUMN password VARCHAR(255) DEFAULT NULL AFTER cedula;
