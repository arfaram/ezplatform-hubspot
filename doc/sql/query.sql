--
-- Table structure for table `hubspot_apikey`
--

DROP TABLE IF EXISTS `hubspot_apikey`;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `hubspot_apikey` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `api_key` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `timestamp` datetime DEFAULT NULL,
  `data` json DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Table structure for table `hubspot_broadcast_info`
--

DROP TABLE IF EXISTS `hubspot_broadcast_info`;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `hubspot_broadcast_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL,
  `content_id` int(11) NOT NULL,
  `version` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `u_channel_name` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `u_channel_id` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `u_schedule_time` int(11) DEFAULT NULL,
  `u_option` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `b_broadcast_guid` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `b_status` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `b_trigger_at` int(11) DEFAULT NULL,
  `b_is_published` tinyint(1) DEFAULT NULL,
  `b_is_failed` tinyint(1) DEFAULT NULL,
  `b_data` json DEFAULT NULL,
  `u_channel_type` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `content_id_idx` (`content_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;