-- Ask ABCD (RAG chatbot) knowledge-base table.
-- rag_functions.php also creates it automatically on first use; this file is for
-- importing by hand in phpMyAdmin (database abcd_db > Import).
-- One row per dress entry. Rebuild its contents with:  php rag_ingest.php --force

CREATE TABLE IF NOT EXISTS `rag_chunks` (
  `id`              INT NOT NULL AUTO_INCREMENT,
  `dress_id`        INT NOT NULL,
  `title`           VARCHAR(150) NOT NULL,
  `chunk_text`      TEXT NOT NULL,
  `content_hash`    CHAR(64) NOT NULL,
  `embedding`       MEDIUMTEXT NULL COMMENT 'JSON array of floats, NULL when no embedding key',
  `embedding_model` VARCHAR(64) NULL,
  `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_rag_dress` (`dress_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
