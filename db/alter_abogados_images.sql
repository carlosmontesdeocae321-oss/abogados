-- Add separate image columns for abogados: carnet (thumbnail) and full profile
ALTER TABLE abogados
  ADD COLUMN IF NOT EXISTS `foto_carnet` VARCHAR(512) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `foto_full` VARCHAR(512) DEFAULT NULL;
