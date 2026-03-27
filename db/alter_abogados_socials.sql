-- Add social media fields to abogados
ALTER TABLE abogados
  ADD COLUMN facebook VARCHAR(255) NULL,
  ADD COLUMN instagram VARCHAR(255) NULL,
  ADD COLUMN linkedin VARCHAR(255) NULL,
  ADD COLUMN twitter VARCHAR(255) NULL,
  ADD COLUMN whatsapp VARCHAR(60) NULL;
