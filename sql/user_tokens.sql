-- ---------------------------------------------------------------------
-- user_tokens: one table for BOTH email-link features
--   * Issue #7  : email validation after registration  (type = 'verify')
--   * Issue #11 : "Forgot password" reset links         (type = 'reset')
--
-- The raw token is NEVER stored. We store its SHA-256 hash, so a leaked
-- database dump cannot be used to activate accounts or reset passwords.
-- Run this once on an existing database (phpMyAdmin > SQL tab), or
-- re-import abcd_db.sql which now contains it too.
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_tokens` (
  `id`         int(11)              NOT NULL AUTO_INCREMENT,
  `user_id`    int(11)              NOT NULL,
  `type`       enum('verify','reset') NOT NULL,
  `token_hash` char(64)             NOT NULL,
  `expires_at` datetime             NOT NULL,
  `used_at`    datetime             DEFAULT NULL,
  `created_at` datetime             NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `token_hash` (`token_hash`),
  KEY `user_type` (`user_id`,`type`),
  CONSTRAINT `user_tokens_user_fk` FOREIGN KEY (`user_id`)
      REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
