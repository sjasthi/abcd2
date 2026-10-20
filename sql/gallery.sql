-- Gallery table for Project ABCD2 (pictures and videos shown on gallery.php).
-- gallery.php also creates this table automatically the first time it loads,
-- so importing this file is optional.

CREATE TABLE IF NOT EXISTS `gallery` (
  `Gallery_Id`   INT(11) NOT NULL AUTO_INCREMENT,
  `Title`        VARCHAR(100) NOT NULL,
  `Caption`      VARCHAR(500) DEFAULT NULL,
  `Media_Type`   ENUM('image','video') NOT NULL,
  `File_Path`    VARCHAR(255) DEFAULT NULL,  -- images/gallery/... for uploaded files
  `Video_Url`    VARCHAR(255) DEFAULT NULL,  -- YouTube link (File_Path is NULL then)
  `Youtube_Link` VARCHAR(255) DEFAULT NULL,  -- optional YouTube link attached to any item (shown as a YouTube icon)
  `Uploaded_By`  VARCHAR(100) DEFAULT NULL,
  `Created_Time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Gallery_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
