-- Increase cargo column length to accommodate long job titles
ALTER TABLE abogados
  MODIFY COLUMN cargo VARCHAR(255) NULL;