-- Adminer 4.7.5 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';

SET NAMES utf8mb4;

DROP DATABASE IF EXISTS `peerassessDB`;
CREATE DATABASE `peerassessDB` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `peerassessDB`;

DROP TABLE IF EXISTS `courses`;
CREATE TABLE `courses`
(
    `id`          bigint(20) unsigned                     NOT NULL AUTO_INCREMENT,
    `user_id`     bigint(20) unsigned                     NOT NULL,
    `title`       varchar(50) COLLATE utf8mb4_unicode_ci           DEFAULT NULL,
    `status`      char(1) COLLATE utf8mb4_unicode_ci      NOT NULL DEFAULT '0',
    `code`        varchar(10) COLLATE utf8mb4_unicode_ci           DEFAULT NULL,
    `description` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
    `ac_year`     timestamp                               NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_at`  timestamp                               NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  timestamp                               NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs`
(
    `id`         bigint(20) unsigned                 NOT NULL AUTO_INCREMENT,
    `connection` text COLLATE utf8mb4_unicode_ci     NOT NULL,
    `queue`      text COLLATE utf8mb4_unicode_ci     NOT NULL,
    `payload`    longtext COLLATE utf8mb4_unicode_ci NOT NULL,
    `exception`  longtext COLLATE utf8mb4_unicode_ci NOT NULL,
    `failed_at`  timestamp                           NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `forms`;
CREATE TABLE `forms`
(
    `id`         bigint(20) unsigned                     NOT NULL AUTO_INCREMENT,
    `session_id` bigint(20) unsigned                     NOT NULL,
    `name`       varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `mark`       char(1) COLLATE utf8mb4_unicode_ci DEFAULT '0' COMMENT '0-5',
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups`
(
    `id`         bigint(20) unsigned                     NOT NULL AUTO_INCREMENT,
    `session_id` bigint(20) unsigned                     NOT NULL,
    `name`       varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `mark`       char(1) COLLATE utf8mb4_unicode_ci DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`
(
    `id`        int(10) unsigned                        NOT NULL AUTO_INCREMENT,
    `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `batch`     int(11)                                 NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 12
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets`
(
    `email`      varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `token`      varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `created_at` timestamp                               NULL DEFAULT NULL,
    KEY `password_resets_email_index` (`email`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `questions`;
CREATE TABLE `questions`
(
    `id`          bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `form_id`     bigint(20) unsigned NOT NULL,
    `description` text COLLATE utf8mb4_unicode_ci,
    `type`        char(1) COLLATE utf8mb4_unicode_ci DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `reviews`;
CREATE TABLE `reviews`
(
    `id`           bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `sender_id`    bigint(20) unsigned NOT NULL,
    `recipient_id` bigint(20) unsigned NOT NULL,
    `question_id`  bigint(20) unsigned NOT NULL,
    `mark`         char(1) COLLATE utf8mb4_unicode_ci   DEFAULT '0' COMMENT '0-5',
    `comment`      text COLLATE utf8mb4_unicode_ci,
    `answer`       char(255) COLLATE utf8mb4_unicode_ci DEFAULT 'n' COMMENT '[Y]es/[N]o',
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions`
(
    `id`           bigint(20) unsigned                     NOT NULL AUTO_INCREMENT,
    `course_id`    bigint(20) unsigned                     NOT NULL,
    `title`        varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `deadline`     timestamp                               NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `instructions` text COLLATE utf8mb4_unicode_ci         NOT NULL,
    `open_data`    timestamp                               NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_at`   timestamp                               NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`   timestamp                               NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `student_course`;
CREATE TABLE `student_course`
(
    `user_id`    bigint(20) unsigned NOT NULL,
    `course_id`  bigint(20) unsigned NOT NULL,
    `created_at` timestamp           NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp           NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `student_course_user_id_unique` (`user_id`),
    UNIQUE KEY `student_course_course_id_unique` (`course_id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `user_group`;
CREATE TABLE `user_group`
(
    `user_id`  bigint(20) unsigned NOT NULL,
    `group_id` bigint(20) unsigned NOT NULL,
    UNIQUE KEY `user_group_user_id_unique` (`user_id`),
    UNIQUE KEY `user_group_group_id_unique` (`group_id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`
(
    `id`                bigint(20) unsigned                     NOT NULL AUTO_INCREMENT,
    `email`             varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `email_verified_at` timestamp                               NULL     DEFAULT NULL,
    `fname`             varchar(255) COLLATE utf8mb4_unicode_ci          DEFAULT NULL,
    `lname`             varchar(255) COLLATE utf8mb4_unicode_ci          DEFAULT NULL,
    `department`        varchar(255) COLLATE utf8mb4_unicode_ci          DEFAULT NULL,
    `reg_num`           varchar(6) COLLATE utf8mb4_unicode_ci            DEFAULT NULL,
    `instructor`        char(1) COLLATE utf8mb4_unicode_ci      NOT NULL DEFAULT '0' COMMENT '0 -> student, 1 -> instructor',
    `admin`             char(1) COLLATE utf8mb4_unicode_ci      NOT NULL DEFAULT '0' COMMENT 'boolean',
    `password`          varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `remember_token`    varchar(100) COLLATE utf8mb4_unicode_ci          DEFAULT NULL,
    `created_at`        timestamp                               NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`        timestamp                               NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `last_login`        timestamp                               NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

SET NAMES utf8mb4;
INSERT INTO `users` (`id`, `email`, `email_verified_at`, `fname`, `lname`, `department`, `reg_num`, `instructor`, `admin`, `password`, `remember_token`, `created_at`, `updated_at`, `last_login`) VALUES
(1,	'dgiankakis@citycollege.sheffield.eu',	'2019-12-03 21:43:48',	'Damianos',	'Giankakis',	'admin',	'',	'0',	'1',	'$2y$10$ze/vyyQir2SBaGrUlcZ59uDP7oz/EZwOf75R5NiFDrazSOiaCU9iO',	'kVF9JylL5Itfp8xI533JyhOtNeEgWzZPZ7xkQlKI3FAHmpwPO0mZ6pZgt7lp',	'2019-12-03 21:40:59',	'2019-12-03 21:43:58',	'2019-12-03 21:40:59');
INSERT INTO `users` (`id`, `email`, `email_verified_at`, `fname`, `lname`, `department`, `reg_num`, `instructor`, `admin`, `password`, `remember_token`, `created_at`, `updated_at`, `last_login`) VALUES
(2,	'istamatopoulou@citycollege.sheffield.eu',	'2019-12-03 22:04:48',	'Ioanna',	'Stamatopoulou',	'admin',	NULL,	'1',	'0',	'$2y$10$YbCkEvrAgn7Pdiz7lJU28eeTokSJit9fuG7vZcneshd/EPU5Q/XKu',	'PBdepPPr3CwCA4KhvFMAeXMWneOyyh8qVyJT1yzK4dCXvUlmhQ32ZvJzBY8r',	'2019-12-03 22:03:28',	'2019-12-03 22:07:03',	'2019-12-03 22:03:29');
-- 2019-12-03 19:18:15
