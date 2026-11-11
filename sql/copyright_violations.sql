-- --------------------------------------------------------
-- Table structure for table `copyright_violations`   (FP9)
--
-- One row per web page that uses Project ABCD artwork without
-- permission (for example a Pinterest pin of one of our pictures).
--
--   id      unique number for the violation
--   url     link to the page that has the copied picture
--   status  Created   = we found it and logged it
--           Contacted = a letter was sent to the site/owner
--           Closed    = they removed the copyrighted picture
--
-- abcd_copyrights.php also creates this table automatically the first
-- time an admin opens the page, so importing this file is optional.
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `copyright_violations` (
  `id`     int(11)      NOT NULL AUTO_INCREMENT,
  `url`    varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('Created','Contacted','Closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Created',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Example row (the reference from the FP9 description):
-- INSERT INTO `copyright_violations` (`url`, `status`)
-- VALUES ('https://in.pinterest.com/pin/1051942425432035133/', 'Created');
