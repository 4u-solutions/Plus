# ************************************************************
# Sequel Pro SQL dump
# Versión 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: localhost (MySQL 5.5.5-10.4.21-MariaDB)
# Base de datos: cicamsis_db
# Tiempo de Generación: 2022-07-26 02:25:24 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Volcado de tabla admin_access
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_access`;

CREATE TABLE `admin_access` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `naccess` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `archaccess` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `iconaccess` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `publc` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `groupacc` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admin_access` WRITE;
/*!40000 ALTER TABLE `admin_access` DISABLE KEYS */;

INSERT INTO `admin_access` (`id`, `naccess`, `archaccess`, `iconaccess`, `publc`, `groupacc`, `created_at`, `updated_at`)
VALUES
	(1,'Roles','roles','layers','1','Configuración','2022-07-25 11:41:06','2022-07-25 11:41:06'),
	(2,'Usuarios','users','layers','1','Configuración','2022-07-25 11:41:06','2022-07-25 11:41:06'),
	(3,'Configuración','permissions','layers','1','Configuración','2022-07-25 11:41:06','2022-07-25 11:41:06'),
	(6,'Ficha de registro','ficha','archive','1','Postulantes','2022-07-25 11:41:06',NULL);

/*!40000 ALTER TABLE `admin_access` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla admin_access_roles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_access_roles`;

CREATE TABLE `admin_access_roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_role` bigint(20) unsigned NOT NULL,
  `id_access` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `admin_access_roles_id_role_foreign` (`id_role`),
  KEY `admin_access_roles_id_access_foreign` (`id_access`),
  CONSTRAINT `admin_access_roles_id_access_foreign` FOREIGN KEY (`id_access`) REFERENCES `admin_access` (`id`) ON DELETE CASCADE,
  CONSTRAINT `admin_access_roles_id_role_foreign` FOREIGN KEY (`id_role`) REFERENCES `admin_roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Volcado de tabla admin_roles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_roles`;

CREATE TABLE `admin_roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nameRole` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admin_roles` WRITE;
/*!40000 ALTER TABLE `admin_roles` DISABLE KEYS */;

INSERT INTO `admin_roles` (`id`, `nameRole`, `created_at`, `updated_at`)
VALUES
	(1,'Sadmin','2022-07-25 09:57:35','2022-07-25 09:57:35');

/*!40000 ALTER TABLE `admin_roles` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla admin_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_users`;

CREATE TABLE `admin_users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `usersys` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `statusUs` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mail` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `roleUS` bigint(20) unsigned NOT NULL,
  `superuser` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_users_usersys_unique` (`usersys`),
  KEY `admin_users_roleus_foreign` (`roleUS`),
  CONSTRAINT `admin_users_roleus_foreign` FOREIGN KEY (`roleUS`) REFERENCES `admin_roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admin_users` WRITE;
/*!40000 ALTER TABLE `admin_users` DISABLE KEYS */;

INSERT INTO `admin_users` (`id`, `name`, `usersys`, `password`, `statusUs`, `mail`, `roleUS`, `superuser`, `created_at`, `updated_at`, `remember_token`)
VALUES
	(1,'Super admin','sadmin','$2y$10$PuMNRVHJ3YRUI1RwukptVu3KqLe8SD2hAG1LeDse/P9Mh7jFQy0Pe','1',NULL,1,1,'2022-07-25 09:58:38','2022-07-25 09:58:38',NULL);

/*!40000 ALTER TABLE `admin_users` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla admin_usersadmin
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_usersadmin`;

CREATE TABLE `admin_usersadmin` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sysuser` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sysuserpass` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `typeuser` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Volcado de tabla migrations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;

INSERT INTO `migrations` (`id`, `migration`, `batch`)
VALUES
	(1,'2019_12_14_000001_create_personal_access_tokens_table',1),
	(2,'2021_02_10_004756_create_admin_roles_table',1),
	(3,'2021_02_10_005223_create_admin_users_table',1),
	(4,'2021_02_10_230758_create_admin_access_table',1),
	(5,'2021_02_10_233048_create_admin_access_roles_table',1),
	(6,'2021_09_27_191338_create_admin_usersadmin_table',1);

/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla personal_access_tokens
# ------------------------------------------------------------

DROP TABLE IF EXISTS `personal_access_tokens`;

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
