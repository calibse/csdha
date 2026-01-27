/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `academic_periods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `academic_periods` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `academic_term_id` bigint(20) unsigned NOT NULL,
  `year_label` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `head_of_student_services` varchar(100) DEFAULT NULL,
  `branch_director` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `academic_periods_academic_term_id_foreign` (`academic_term_id`),
  CONSTRAINT `academic_periods_academic_term_id_foreign` FOREIGN KEY (`academic_term_id`) REFERENCES `academic_terms` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `academic_terms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `academic_terms` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `system` varchar(20) NOT NULL,
  `term_number` tinyint(3) unsigned NOT NULL,
  `label` varchar(30) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `chk_system` CHECK (`system` in ('semester','trimester','quarter'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `accom_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `accom_reports` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint(20) unsigned NOT NULL,
  `approved_ar_filepath` varchar(255) DEFAULT NULL,
  `current_step` varchar(20) NOT NULL,
  `status` varchar(20) NOT NULL,
  `comments` text DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `returned_at` timestamp NULL DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `president_user_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `filepath` varchar(255) DEFAULT NULL,
  `file_updated` tinyint(1) DEFAULT NULL,
  `file_updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `accom_reports_event_id_foreign` (`event_id`),
  KEY `accom_reports_president_user_id_foreign` (`president_user_id`),
  CONSTRAINT `accom_reports_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`),
  CONSTRAINT `accom_reports_president_user_id_foreign` FOREIGN KEY (`president_user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `chk_current_step` CHECK (`current_step` in ('officers','president','adviser')),
  CONSTRAINT `chk_status` CHECK (`status` in ('draft','pending','returned','approved'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `category` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `item_id` bigint(20) NOT NULL,
  `date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `announcements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `announcements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `introduction` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `announcements_user_id_foreign` (`user_id`),
  CONSTRAINT `announcements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `audit_trail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audit_trail` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `action` varchar(10) NOT NULL,
  `table_name` varchar(100) NOT NULL,
  `column_names` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`column_names`)),
  `primary_key` bigint(20) unsigned NOT NULL,
  `request_id` char(26) DEFAULT NULL,
  `request_ip` varchar(45) DEFAULT NULL,
  `request_url` text DEFAULT NULL,
  `request_method` varchar(10) DEFAULT NULL,
  `request_time` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  CONSTRAINT `chk_action` CHECK (`action` in ('insert','update','delete')),
  CONSTRAINT `chk_request_method` CHECK (`request_method` in ('GET','POST','PUT','PATCH','DELETE','OPTIONS'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `audit_trail_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audit_trail_data` (
  `action` varchar(10) DEFAULT NULL,
  `table_name` varchar(100) DEFAULT NULL,
  `column_names` longtext DEFAULT NULL,
  `primary_key` bigint(20) DEFAULT NULL,
  `request_id` char(26) DEFAULT NULL,
  `request_ip` varchar(45) DEFAULT NULL,
  `request_url` text DEFAULT NULL,
  `request_method` varchar(10) DEFAULT NULL,
  `request_time` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `audit_trail_data_copy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audit_trail_data_copy` (
  `action` varchar(10) DEFAULT NULL,
  `table_name` varchar(100) DEFAULT NULL,
  `column_names` longtext DEFAULT NULL,
  `primary_key` bigint(20) DEFAULT NULL,
  `request_id` char(26) DEFAULT NULL,
  `request_ip` varchar(45) DEFAULT NULL,
  `request_url` text DEFAULT NULL,
  `request_method` varchar(10) DEFAULT NULL,
  `request_time` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `audit_trigger_variables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audit_trigger_variables` (
  `changed_cols` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `courses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `acronym` varchar(8) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `unarchived` tinyint(4) GENERATED ALWAYS AS (if(`deleted_at` is null,1,NULL)) STORED,
  PRIMARY KEY (`id`),
  UNIQUE KEY `courses_name_unarchived_unique` (`name`,`unarchived`),
  UNIQUE KEY `courses_acronym_unarchived_unique` (`acronym`,`unarchived`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `event_attachment_sets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_attachment_sets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint(20) unsigned NOT NULL,
  `caption` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_attachment_sets_event_id_foreign` (`event_id`),
  CONSTRAINT `event_attachment_sets_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `event_attachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_attachments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_attachment_set_id` bigint(20) unsigned NOT NULL,
  `image_filepath` varchar(255) NOT NULL,
  `preview_filepath` varchar(255) NOT NULL,
  `orientation` varchar(15) NOT NULL,
  `standalone` tinyint(1) NOT NULL,
  `full_width` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_attachments_event_attachment_set_id_foreign` (`event_attachment_set_id`),
  CONSTRAINT `event_attachments_event_attachment_set_id_foreign` FOREIGN KEY (`event_attachment_set_id`) REFERENCES `event_attachment_sets` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `event_attendances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_attendances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `course_id` bigint(20) unsigned DEFAULT NULL,
  `student_year_id` bigint(20) unsigned DEFAULT NULL,
  `student_section_id` bigint(20) unsigned DEFAULT NULL,
  `event_date_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `events_attendance_student_id_foreign` (`student_id`),
  KEY `event_attendances_course_id_foreign` (`course_id`),
  KEY `event_attendances_student_year_id_foreign` (`student_year_id`),
  KEY `event_attendances_student_section_id_foreign` (`student_section_id`),
  KEY `event_attendances_event_date_id_foreign` (`event_date_id`),
  CONSTRAINT `event_attendances_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`),
  CONSTRAINT `event_attendances_event_date_id_foreign` FOREIGN KEY (`event_date_id`) REFERENCES `event_dates` (`id`),
  CONSTRAINT `event_attendances_student_section_id_foreign` FOREIGN KEY (`student_section_id`) REFERENCES `student_sections` (`id`),
  CONSTRAINT `event_attendances_student_year_id_foreign` FOREIGN KEY (`student_year_id`) REFERENCES `student_years` (`id`),
  CONSTRAINT `events_attendance_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `event_attendees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_attendees` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_student_id` bigint(20) unsigned NOT NULL,
  `event_date_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `eval_mail_sent` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `event_attendees_event_student_id_foreign` (`event_student_id`),
  KEY `event_attendees_event_date_id_foreign` (`event_date_id`),
  CONSTRAINT `event_attendees_event_date_id_foreign` FOREIGN KEY (`event_date_id`) REFERENCES `event_dates` (`id`),
  CONSTRAINT `event_attendees_event_student_id_foreign` FOREIGN KEY (`event_student_id`) REFERENCES `event_students` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `event_dates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_dates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint(20) unsigned NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `public_id` bigint(20) unsigned DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `event_dates_public_id_unique` (`public_id`),
  KEY `event_dates_event_id_foreign` (`event_id`),
  CONSTRAINT `event_dates_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `event_deliverable_assignee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_deliverable_assignee` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_deliverable_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_deliverable_assignee_event_deliverable_id_foreign` (`event_deliverable_id`),
  KEY `event_deliverable_assignee_user_id_foreign` (`user_id`),
  CONSTRAINT `event_deliverable_assignee_event_deliverable_id_foreign` FOREIGN KEY (`event_deliverable_id`) REFERENCES `event_deliverables` (`id`),
  CONSTRAINT `event_deliverable_assignee_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `event_deliverable_tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_deliverable_tasks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_deliverable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_done` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_deliverable_tasks_event_deliverable_id_foreign` (`event_deliverable_id`),
  CONSTRAINT `event_deliverable_tasks_event_deliverable_id_foreign` FOREIGN KEY (`event_deliverable_id`) REFERENCES `event_deliverables` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `event_deliverables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_deliverables` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `event_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_deliverables_event_id_foreign` (`event_id`),
  CONSTRAINT `event_deliverables_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `event_editor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_editor` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_editor_event_id_foreign` (`event_id`),
  KEY `event_editor_user_id_foreign` (`user_id`),
  CONSTRAINT `event_editor_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`),
  CONSTRAINT `event_editor_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `event_eval_forms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_eval_forms` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint(20) unsigned NOT NULL,
  `introduction` text DEFAULT NULL,
  `overall_satisfaction` varchar(255) DEFAULT NULL,
  `content_relevance` varchar(255) DEFAULT NULL,
  `speaker_effectiveness` varchar(255) DEFAULT NULL,
  `engagement_level` varchar(255) DEFAULT NULL,
  `duration` varchar(255) DEFAULT NULL,
  `topics_covered` varchar(255) DEFAULT NULL,
  `suggestions_for_improvement` varchar(255) DEFAULT NULL,
  `future_topics` varchar(255) DEFAULT NULL,
  `overall_experience` varchar(255) DEFAULT NULL,
  `additional_comments` varchar(255) DEFAULT NULL,
  `acknowledgement` text DEFAULT NULL,
  `default` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `event_eval_form_questions_default_unique` (`default`),
  KEY `event_eval_form_questions_event_id_foreign` (`event_id`),
  CONSTRAINT `event_eval_form_questions_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `event_evaluation_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_evaluation_tokens` (
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `event_evaluations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_evaluations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint(20) unsigned NOT NULL,
  `overall_satisfaction` tinyint(4) NOT NULL,
  `content_relevance` tinyint(4) NOT NULL,
  `speaker_effectiveness` tinyint(4) NOT NULL,
  `engagement_level` tinyint(4) NOT NULL,
  `duration` tinyint(4) NOT NULL,
  `topics_covered` text NOT NULL,
  `suggestions_for_improvement` text NOT NULL,
  `future_topics` text NOT NULL,
  `overall_experience` text NOT NULL,
  `additional_comments` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `feature_topics_covered` tinyint(1) NOT NULL DEFAULT 0,
  `feature_suggestions_for_improvement` tinyint(1) NOT NULL DEFAULT 0,
  `feature_future_topics` tinyint(1) NOT NULL DEFAULT 0,
  `feature_overall_experience` tinyint(1) NOT NULL DEFAULT 0,
  `feature_additional_comments` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `event_evaluations_event_id_foreign` (`event_id`),
  CONSTRAINT `event_evaluations_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `event_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_links` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `url` varchar(2000) NOT NULL,
  `event_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_links_event_id_foreign` (`event_id`),
  CONSTRAINT `event_links_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `event_officer_attendees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_officer_attendees` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_date_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_officer_attendees_event_date_id_foreign` (`event_date_id`),
  KEY `event_officer_attendees_user_id_foreign` (`user_id`),
  CONSTRAINT `event_officer_attendees_event_date_id_foreign` FOREIGN KEY (`event_date_id`) REFERENCES `event_dates` (`id`),
  CONSTRAINT `event_officer_attendees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `event_participant_courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_participant_courses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint(20) unsigned NOT NULL,
  `course_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_participant_courses_event_id_foreign` (`event_id`),
  KEY `event_participant_courses_course_id_foreign` (`course_id`),
  CONSTRAINT `event_participant_courses_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`),
  CONSTRAINT `event_participant_courses_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `event_participants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_participants` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint(20) unsigned NOT NULL,
  `student_year_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_participants_event_id_foreign` (`event_id`),
  KEY `event_participants_student_year_id_foreign` (`student_year_id`),
  CONSTRAINT `event_participants_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`),
  CONSTRAINT `event_participants_student_year_id_foreign` FOREIGN KEY (`student_year_id`) REFERENCES `student_years` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `event_regis_forms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_regis_forms` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint(20) unsigned NOT NULL,
  `introduction` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_regis_forms_event_id_foreign` (`event_id`),
  CONSTRAINT `event_regis_forms_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `event_registrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_registrations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `public_id` bigint(20) unsigned DEFAULT NULL,
  `event_id` bigint(20) unsigned NOT NULL,
  `token` char(26) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `event_student_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `event_registrations_token_unique` (`token`),
  UNIQUE KEY `event_registrations_public_id_unique` (`public_id`),
  KEY `event_registrations_event_id_foreign` (`event_id`),
  KEY `event_registrations_event_student_id_foreign` (`event_student_id`),
  CONSTRAINT `event_registrations_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`),
  CONSTRAINT `event_registrations_event_student_id_foreign` FOREIGN KEY (`event_student_id`) REFERENCES `event_students` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `event_students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_students` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` varchar(20) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `suffix_name` varchar(50) DEFAULT NULL,
  `course_id` bigint(20) unsigned NOT NULL,
  `student_year_id` bigint(20) unsigned NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `student_section_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_students_course_id_foreign` (`course_id`),
  KEY `event_students_student_year_id_foreign` (`student_year_id`),
  KEY `event_students_student_section_id_foreign` (`student_section_id`),
  CONSTRAINT `event_students_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`),
  CONSTRAINT `event_students_student_section_id_foreign` FOREIGN KEY (`student_section_id`) REFERENCES `student_sections` (`id`),
  CONSTRAINT `event_students_student_year_id_foreign` FOREIGN KEY (`student_year_id`) REFERENCES `student_years` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `description` text DEFAULT NULL,
  `letter_of_intent` varchar(255) DEFAULT NULL,
  `venue` varchar(255) DEFAULT NULL,
  `accomplishment_report` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `narrative` text DEFAULT NULL,
  `gpoa_activity_id` bigint(20) unsigned NOT NULL,
  `public_id` bigint(20) unsigned DEFAULT NULL,
  `tag` varchar(15) DEFAULT NULL,
  `participant_type` varchar(15) DEFAULT NULL,
  `automatic_attendance` tinyint(1) NOT NULL,
  `accept_evaluation` tinyint(1) NOT NULL,
  `timezone` varchar(50) NOT NULL DEFAULT 'UTC',
  `evaluation_delay_hours` tinyint(3) unsigned NOT NULL DEFAULT 24,
  `banner_filepath` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `events_public_id_unique` (`public_id`),
  KEY `events_gpoa_activity_id_foreign` (`gpoa_activity_id`),
  CONSTRAINT `events_gpoa_activity_id_foreign` FOREIGN KEY (`gpoa_activity_id`) REFERENCES `gpoa_activities` (`id`),
  CONSTRAINT `chk_participant_type` CHECK (`participant_type` in ('students','officers'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `funds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `funds` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `collected` decimal(8,2) NOT NULL,
  `spent` decimal(8,2) NOT NULL,
  `remaining` decimal(8,2) NOT NULL,
  `event_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `funds_event_id_foreign` (`event_id`),
  CONSTRAINT `funds_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `gpoa_activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gpoa_activities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `gpoa_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `objectives` text NOT NULL,
  `participants` varchar(100) NOT NULL,
  `proposed_budget` decimal(8,2) DEFAULT NULL,
  `comments` text DEFAULT NULL,
  `adviser_user_id` bigint(20) unsigned DEFAULT NULL,
  `president_user_id` bigint(20) unsigned DEFAULT NULL,
  `president_approved_at` timestamp NULL DEFAULT NULL,
  `adviser_approved_at` timestamp NULL DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `status` varchar(20) NOT NULL,
  `current_step` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `number_of_participants` smallint(5) unsigned NOT NULL,
  `gpoa_activity_fund_source_id` bigint(20) unsigned DEFAULT NULL,
  `gpoa_activity_partnership_type_id` bigint(20) unsigned DEFAULT NULL,
  `gpoa_activity_mode_id` bigint(20) unsigned NOT NULL,
  `gpoa_activity_type_id` bigint(20) unsigned NOT NULL,
  `public_id` bigint(20) unsigned DEFAULT NULL,
  `officers_submitted_at` timestamp NULL DEFAULT NULL,
  `president_submitted_at` timestamp NULL DEFAULT NULL,
  `president_returned_at` timestamp NULL DEFAULT NULL,
  `adviser_returned_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `gpoa_activities_public_id_unique` (`public_id`),
  KEY `gpoa_activities_gpoa_id_foreign` (`gpoa_id`),
  KEY `gpoa_activities_adviser_user_id_foreign` (`adviser_user_id`),
  KEY `gpoa_activities_president_user_id_foreign` (`president_user_id`),
  KEY `gpoa_activities_gpoa_activity_fund_source_id_foreign` (`gpoa_activity_fund_source_id`),
  KEY `gpoa_activities_gpoa_activity_partnership_type_id_foreign` (`gpoa_activity_partnership_type_id`),
  KEY `gpoa_activities_gpoa_activity_mode_id_foreign` (`gpoa_activity_mode_id`),
  KEY `gpoa_activities_gpoa_activity_type_id_foreign` (`gpoa_activity_type_id`),
  CONSTRAINT `gpoa_activities_adviser_user_id_foreign` FOREIGN KEY (`adviser_user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `gpoa_activities_gpoa_activity_fund_source_id_foreign` FOREIGN KEY (`gpoa_activity_fund_source_id`) REFERENCES `gpoa_activity_fund_sources` (`id`),
  CONSTRAINT `gpoa_activities_gpoa_activity_mode_id_foreign` FOREIGN KEY (`gpoa_activity_mode_id`) REFERENCES `gpoa_activity_modes` (`id`),
  CONSTRAINT `gpoa_activities_gpoa_activity_partnership_type_id_foreign` FOREIGN KEY (`gpoa_activity_partnership_type_id`) REFERENCES `gpoa_activity_partnership_types` (`id`),
  CONSTRAINT `gpoa_activities_gpoa_activity_type_id_foreign` FOREIGN KEY (`gpoa_activity_type_id`) REFERENCES `gpoa_activity_types` (`id`),
  CONSTRAINT `gpoa_activities_gpoa_id_foreign` FOREIGN KEY (`gpoa_id`) REFERENCES `gpoas` (`id`),
  CONSTRAINT `gpoa_activities_president_user_id_foreign` FOREIGN KEY (`president_user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `chk_status` CHECK (`status` in ('draft','pending','returned','approved','rejected')),
  CONSTRAINT `chk_current_step` CHECK (`current_step` in ('officers','president','adviser'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `gpoa_activity_authors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gpoa_activity_authors` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `gpoa_activity_id` bigint(20) unsigned NOT NULL,
  `officer_user_id` bigint(20) unsigned NOT NULL,
  `role` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gpoa_activity_authors_gpoa_activity_id_foreign` (`gpoa_activity_id`),
  KEY `gpoa_activity_authors_officer_user_id_foreign` (`officer_user_id`),
  CONSTRAINT `gpoa_activity_authors_gpoa_activity_id_foreign` FOREIGN KEY (`gpoa_activity_id`) REFERENCES `gpoa_activities` (`id`),
  CONSTRAINT `gpoa_activity_authors_officer_user_id_foreign` FOREIGN KEY (`officer_user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `chk_role` CHECK (`role` in ('event head','co-head'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `gpoa_activity_event_heads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gpoa_activity_event_heads` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `gpoa_activity_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `role` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gpoa_activity_event_heads_gpoa_activity_id_foreign` (`gpoa_activity_id`),
  KEY `gpoa_activity_event_heads_user_id_foreign` (`user_id`),
  CONSTRAINT `gpoa_activity_event_heads_gpoa_activity_id_foreign` FOREIGN KEY (`gpoa_activity_id`) REFERENCES `gpoa_activities` (`id`),
  CONSTRAINT `gpoa_activity_event_heads_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `chk_role` CHECK (`role` in ('event head','co-head'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `gpoa_activity_fund_sources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gpoa_activity_fund_sources` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `unarchived` tinyint(4) GENERATED ALWAYS AS (if(`deleted_at` is null,1,NULL)) STORED,
  PRIMARY KEY (`id`),
  UNIQUE KEY `gpoa_activity_fund_sources_name_unarchived_unique` (`name`,`unarchived`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `gpoa_activity_modes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gpoa_activity_modes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `unarchived` tinyint(4) GENERATED ALWAYS AS (if(`deleted_at` is null,1,NULL)) STORED,
  PRIMARY KEY (`id`),
  UNIQUE KEY `gpoa_activity_modes_name_unarchived_unique` (`name`,`unarchived`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `gpoa_activity_participants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gpoa_activity_participants` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_year_id` bigint(20) unsigned NOT NULL,
  `gpoa_activity_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gpoa_activity_participants_student_year_id_foreign` (`student_year_id`),
  KEY `gpoa_activity_participants_gpoa_activity_id_foreign` (`gpoa_activity_id`),
  CONSTRAINT `gpoa_activity_participants_gpoa_activity_id_foreign` FOREIGN KEY (`gpoa_activity_id`) REFERENCES `gpoa_activities` (`id`),
  CONSTRAINT `gpoa_activity_participants_student_year_id_foreign` FOREIGN KEY (`student_year_id`) REFERENCES `student_years` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `gpoa_activity_partnership_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gpoa_activity_partnership_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `unarchived` tinyint(4) GENERATED ALWAYS AS (if(`deleted_at` is null,1,NULL)) STORED,
  PRIMARY KEY (`id`),
  UNIQUE KEY `gpoa_activity_partnership_types_name_unarchived_unique` (`name`,`unarchived`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `gpoa_activity_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gpoa_activity_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `unarchived` tinyint(4) GENERATED ALWAYS AS (if(`deleted_at` is null,1,NULL)) STORED,
  PRIMARY KEY (`id`),
  UNIQUE KEY `gpoa_activity_types_name_unarchived_unique` (`name`,`unarchived`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `gpoas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gpoas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `academic_period_id` bigint(20) unsigned NOT NULL,
  `creator_user_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `public_id` bigint(20) unsigned DEFAULT NULL,
  `report_filepath` varchar(255) DEFAULT NULL,
  `accom_report_filepath` varchar(255) DEFAULT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  `closer_user_id` bigint(20) unsigned DEFAULT NULL,
  `active` tinyint(4) GENERATED ALWAYS AS (if(`closed_at` is null,1,NULL)) STORED,
  `report_file_updated` tinyint(1) DEFAULT NULL,
  `report_file_updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `gpoas_public_id_unique` (`public_id`),
  UNIQUE KEY `gpoas_active_unique` (`active`),
  KEY `gpoas_academic_period_id_foreign` (`academic_period_id`),
  KEY `gpoas_adviser_user_id_foreign` (`creator_user_id`),
  KEY `gpoas_closer_user_id_foreign` (`closer_user_id`),
  CONSTRAINT `gpoas_academic_period_id_foreign` FOREIGN KEY (`academic_period_id`) REFERENCES `academic_periods` (`id`),
  CONSTRAINT `gpoas_closer_user_id_foreign` FOREIGN KEY (`closer_user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `gpoas_creator_user_id_foreign` FOREIGN KEY (`creator_user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `meetings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `meetings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `venue` varchar(255) NOT NULL,
  `agenda` varchar(255) NOT NULL,
  `participants` int(11) NOT NULL,
  `minutes_file` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `meetings_user_id_foreign` (`user_id`),
  CONSTRAINT `meetings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `partnerships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `partnerships` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_name` varchar(255) NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `benefits` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `links` varchar(255) NOT NULL,
  `accomplished_by` varchar(255) NOT NULL,
  `officer` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `partnerships_user_id_foreign` (`user_id`),
  CONSTRAINT `partnerships_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `resource_type_id` bigint(20) unsigned NOT NULL,
  `resource_action_type_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `permissions_resource_type_id_foreign` (`resource_type_id`),
  KEY `permissions_resource_action_type_id_foreign` (`resource_action_type_id`),
  CONSTRAINT `permissions_resource_action_type_id_foreign` FOREIGN KEY (`resource_action_type_id`) REFERENCES `resource_action_types` (`id`),
  CONSTRAINT `permissions_resource_type_id_foreign` FOREIGN KEY (`resource_type_id`) REFERENCES `resource_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `platforms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `platforms` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `progress` decimal(5,2) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `platforms_user_id_foreign` (`user_id`),
  CONSTRAINT `platforms_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `position_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `position_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `position_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `position_category` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `position_id` bigint(20) unsigned NOT NULL,
  `position_category_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `position_category_position_id_foreign` (`position_id`),
  KEY `position_category_position_category_id_foreign` (`position_category_id`),
  CONSTRAINT `position_category_position_category_id_foreign` FOREIGN KEY (`position_category_id`) REFERENCES `position_categories` (`id`),
  CONSTRAINT `position_category_position_id_foreign` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `position_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `position_permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `position_id` bigint(20) unsigned NOT NULL,
  `permission_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `position_permissions_position_id_foreign` (`position_id`),
  KEY `position_permissions_permission_id_foreign` (`permission_id`),
  CONSTRAINT `position_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`),
  CONSTRAINT `position_permissions_position_id_foreign` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `positions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `positions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `position_order` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `positions_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `resource_action_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `resource_action_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `resource_action_types_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `resource_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `resource_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `resource_types_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `signup_invitations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `signup_invitations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `invite_code` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `position_id` bigint(20) unsigned DEFAULT NULL,
  `is_accepted` tinyint(1) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `email_sent` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `signup_invitations_position_id_foreign` (`position_id`),
  CONSTRAINT `signup_invitations_position_id_foreign` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `student_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_sections` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `section` varchar(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `unarchived` tinyint(4) GENERATED ALWAYS AS (if(`deleted_at` is null,1,NULL)) STORED,
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_sections_section_unarchived_unique` (`section`,`unarchived`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `student_years`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_years` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `year` varchar(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `label` varchar(15) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `unarchived` tinyint(4) GENERATED ALWAYS AS (if(`deleted_at` is null,1,NULL)) STORED,
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_years_year_unarchived_unique` (`year`,`unarchived`),
  UNIQUE KEY `student_years_label_unarchived_unique` (`label`,`unarchived`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `students` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` varchar(20) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `suffix_name` varchar(10) DEFAULT NULL,
  `course_id` bigint(20) unsigned NOT NULL,
  `student_year_id` bigint(20) unsigned NOT NULL,
  `student_section_id` bigint(20) unsigned NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `public_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `students_student_id_unique` (`student_id`),
  UNIQUE KEY `students_email_unique` (`email`),
  UNIQUE KEY `students_public_id_unique` (`public_id`),
  KEY `students_course_id_foreign` (`course_id`),
  KEY `students_student_year_id_foreign` (`student_year_id`),
  KEY `students_student_section_id_foreign` (`student_section_id`),
  CONSTRAINT `students_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`),
  CONSTRAINT `students_student_section_id_foreign` FOREIGN KEY (`student_section_id`) REFERENCES `student_sections` (`id`),
  CONSTRAINT `students_student_year_id_foreign` FOREIGN KEY (`student_year_id`) REFERENCES `student_years` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_google_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_google_accounts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `google_id` text NOT NULL,
  `token` text NOT NULL,
  `refresh_token` text DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_google_accounts_user_id_unique` (`user_id`),
  UNIQUE KEY `user_google_accounts_google_id_unique` (`google_id`) USING HASH,
  CONSTRAINT `user_google_accounts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `suffix_name` varchar(10) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `role_id` bigint(20) unsigned DEFAULT NULL,
  `position_id` bigint(20) unsigned DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `avatar_filepath` varchar(255) DEFAULT NULL,
  `google_id` text DEFAULT NULL,
  `google_token` text DEFAULT NULL,
  `google_refresh_token` text DEFAULT NULL,
  `google_expires_at` datetime DEFAULT NULL,
  `public_id` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `unarchived` tinyint(4) GENERATED ALWAYS AS (if(`deleted_at` is null,1,NULL)) STORED,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_public_id_unique` (`public_id`),
  UNIQUE KEY `users_position_id_unarchived_unique` (`position_id`,`unarchived`),
  UNIQUE KEY `users_username_unarchived_unique` (`username`,`unarchived`),
  UNIQUE KEY `users_email_unarchived_unique` (`email`,`unarchived`),
  UNIQUE KEY `users_google_id_unarchived_unique` (`google_id`,`unarchived`) USING HASH,
  KEY `users_role_id_foreign` (`role_id`),
  CONSTRAINT `users_position_id_foreign` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`),
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'0001_01_01_000000_create_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4,'2025_03_12_141818_create_positions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5,'2025_03_13_000000_create_roles_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6,'2025_03_13_000445_update_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7,'2025_03_16_085937_create_events_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8,'2025_04_05_084804_create_resource_action_types_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9,'2025_04_05_084805_create_resource_types_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10,'2025_04_05_084806_create_permissions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11,'2025_04_05_095944_create_position_permissions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12,'2025_04_18_145337_create_meetings_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13,'2025_04_21_063950_create_funds_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14,'2025_04_21_141024_create_platforms_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (15,'2025_04_21_143624_create_partnerships_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (16,'2025_05_12_124248_create_activity_logs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (17,'2025_05_17_054013_create_courses_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (18,'2025_05_17_055059_create_students_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (19,'2025_05_19_073151_create_events_attendance_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (20,'2025_05_21_123612_create_personal_access_tokens_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (21,'2025_05_23_070609_create_announcements_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (22,'2025_05_25_104341_create_events_deliverables_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (23,'2025_05_25_104557_create_events_deliverables_tasks_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (24,'2025_05_25_111954_create_events_deliverable_assignee_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (25,'2025_05_26_083644_create_event_editor_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (26,'2025_06_01_095307_create_table_signup_invitations_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (27,'2025_06_02_181147_update_signup_invitations_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (28,'2025_06_15_081514_create_event_dates_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (29,'2025_06_15_085841_update_events_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (30,'2025_06_15_100214_update_event_dates_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (31,'2025_06_20_015814_create_student_years_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (32,'2025_06_20_015828_create_student_sections_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (33,'2025_06_20_021455_update_students_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (34,'2025_06_20_025722_change_columns_in_students_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (35,'2025_06_20_031349_change_columns_in_students_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (36,'2025_06_20_032414_change_columns_in_students_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (37,'2025_06_20_050540_rename_events_attendance_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (38,'2025_06_20_052913_add_columns_to_event_attendances_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (39,'2025_07_03_065749_create_gspoa_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (40,'2025_07_04_011308_change_columns_in_positions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (41,'2025_07_04_015717_create_gspoa_editors_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (42,'2025_07_04_024622_change_columns_in_gspoas_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (43,'2025_07_07_170122_create_gspoa_events_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (44,'2025_07_07_204806_create_gspoa_event_participants_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (45,'2025_07_07_215310_create_event_participants_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (46,'2025_07_08_043018_change_columns_in_events_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (47,'2025_07_10_080520_create_academic_terms_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (48,'2025_07_10_084419_create_academic_years_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (49,'2025_07_10_091210_create_gpoas_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (50,'2025_07_10_092135_create_gpoa_activities_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (51,'2025_07_10_115812_create_gpoa_activity_authors_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (52,'2025_07_11_061758_create_position_categories_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (53,'2025_07_11_062504_create_position_category_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (54,'2025_07_11_062739_create_gpoa_activity_types_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (55,'2025_07_11_063029_change_columns_in_gpoa_activities_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (56,'2025_07_11_064923_create_gpoa_activity_partnership_types_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (57,'2025_07_11_065026_create_gpoa_activity_fund_sources_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (58,'2025_07_11_065101_create_gpoa_activity_modes_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (59,'2025_07_11_065232_change_columns_in_gpoa_activities_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (60,'2025_07_11_142509_create_gpoa_activity_event_heads_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (61,'2025_07_12_064013_create_gpoa_activity_participants_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (62,'2025_07_12_074426_change_columns_in_student_years_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (63,'2025_07_16_091339_change_columns_in_gpoas_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (64,'2025_07_21_092048_change_columns_in_events_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (65,'2025_07_21_124740_change_columns_in_events_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (66,'2025_07_26_041219_create_event_registrations_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (67,'2025_07_26_080251_change_columns_in_gpoas_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (68,'2025_07_26_080537_change_columns_in_gpoa_activities_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (69,'2025_07_26_080651_change_columns_in_events_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (70,'2025_07_26_080748_change_columns_in_users_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (71,'2025_07_26_081058_change_columns_in_students_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (72,'2025_07_26_140357_change_columns_in_gpoas_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (73,'2025_07_27_035545_change_columns_in_gpoas_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (74,'2025_08_01_043243_change_columns_in_event_attendances_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (75,'2025_08_01_045846_change_columns_in_event_dates_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (76,'2025_08_02_073208_create_event_eval_forms_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (77,'2025_08_04_050935_rename_event_eval_form_questions_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (78,'2025_08_04_055308_change_columns_in_student_years_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (79,'2025_08_06_092625_create_audit_trail_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (80,'2025_08_09_092732_change_columns_in_events_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (81,'2025_08_09_121417_create_event_students_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (82,'2025_08_09_123211_change_columns_in_event_registrations_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (83,'2025_08_11_070859_change_columns_in_event_students_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (84,'2025_08_11_075541_create_event_attendees_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (85,'2025_08_11_082304_create_event_regis_form_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (86,'2025_08_12_090717_create_accom_report_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (87,'2025_08_14_071949_change_columns_in_gpoa_activities_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (88,'2025_08_17_105920_change_columns_in_events_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (89,'2025_08_19_052811_change_columns_in_event_students_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (90,'2025_08_20_032447_create_event_officer_attendees_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (91,'2025_08_20_111415_change_columns_in_positions_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (92,'2025_08_23_082142_create_event_attachments_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (93,'2025_08_23_084348_create_event_attachments_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (94,'2025_09_01_051635_change_columns_in_event_dates_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (95,'2025_09_01_100302_create_event_evaluations_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (96,'2025_09_04_090243_change_columns_in_users_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (97,'2025_09_17_092633_change_columns_in_signup_invitations_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (98,'2025_09_18_024934_change_columns_in_users_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (99,'2025_09_18_062839_change_columns_in_users_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (100,'2025_09_20_054249_create_event_evaluation_tokens_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (101,'2025_09_21_055003_change_columns_in_event_evaluations_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (102,'2025_09_26_121515_change_columns_in_events_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (103,'2025_09_26_123827_change_columns_in_event_attendees_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (104,'2025_09_26_125000_change_columns_in_events_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (105,'2025_09_27_064308_change_columns_in_events_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (106,'2025_09_28_153750_change_columns_in_event_evaluations_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (107,'2025_09_29_084620_change_columns_in_academic_periods_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (108,'2025_09_29_125428_change_columns_in_gpoas_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (109,'2025_10_04_115501_create_user_google_accounts_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (110,'2025_10_07_040747_change_columns_in_student_sections_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (111,'2025_10_07_041240_change_columns_in_student_years_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (112,'2025_10_07_042153_change_columns_in_courses_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (113,'2025_10_07_083908_change_columns_gpoa_activity_modes_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (114,'2025_10_07_085013_change_columns_in_gpoa_activity_types_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (115,'2025_10_07_085302_change_columns_in_gpoa_activity_partnership_types_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (116,'2025_10_07_085527_change_columns_in_gpoa_activity_fund_sources_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (117,'2025_10_13_050617_change_columns_in_gpoas_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (118,'2025_10_16_100614_change_columns_in_gpoas_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (119,'2025_10_17_065642_create_event_participant_courses_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (120,'2025_10_18_030748_change_columns_in_audit_trail_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (121,'2025_10_31_022613_change_columns_in_accom_reports_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (122,'2025_10_31_024218_change_columns_in_accom_reports_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (123,'2025_10_31_024343_change_columns_in_gpoas_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (124,'2025_11_07_015804_change_columns_in_accom_reports_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (125,'2025_11_07_015924_change_columns_in_gpoas_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (126,'2025_11_28_081951_change_columns_in_events_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (127,'2025_11_30_095622_create_event_links_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (128,'2026_01_10_162318_change_columns_in_audit_trail_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (129,'2026_01_10_163253_drop_gspoas_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (130,'2026_01_13_072418_create_audit_trigger_variables_table',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (131,'2026_01_13_073049_create_audit_trail_data_table',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (132,'2026_01_14_093233_create_audit_trail_data_copy_table',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (133,'2026_01_14_124813_change_columns_in_audit_trigger_variables_table',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (134,'2026_01_15_004343_change_audit_trail_table',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (135,'2026_01_27_023944_change_columns_in_event_dates_table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (136,'2026_01_27_032534_change_columns_in_event_dates_table',6);
