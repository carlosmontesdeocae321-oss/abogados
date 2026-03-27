-- Add cargo and featured flag for abogados
ALTER TABLE abogados
  ADD COLUMN cargo VARCHAR(80) NULL,
  ADD COLUMN destacado TINYINT(1) NOT NULL DEFAULT 0;