-- phpMyAdmin SQL Dump
-- version 4.2.0-dev
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 17, 2013 at 12:42 PM
-- Server version: 5.6.14
-- PHP Version: 5.5.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `skeleton`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE IF NOT EXISTS `accounts` (
  `idAccount` int(14) unsigned NOT NULL AUTO_INCREMENT,
  `idUser` int(14) unsigned NOT NULL,
  `idService` varchar(25) NOT NULL,
  `photoPath` varchar(255) NOT NULL,
  `accountCode` varchar(60) NOT NULL,
  `type` int(1) unsigned NOT NULL,
  `userEmail` varchar(255) NOT NULL,
  `userName` varchar(255) NOT NULL,
  `userLastName` varchar(255) NOT NULL,
  `userLink` varchar(255) NOT NULL,
  `accessToken` varchar(255) NOT NULL,
  `activeStatus` int(1) unsigned NOT NULL,
  `accountStatus` int(1) unsigned NOT NULL,
  `accountCreateTime` int(12) unsigned NOT NULL,
  PRIMARY KEY (`idAccount`),
  KEY `idUser` (`idUser`,`accountStatus`),
  KEY `idService` (`idService`),
  KEY `accountCode` (`accountCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `actions`
--

CREATE TABLE IF NOT EXISTS `actions` (
  `idPage` int(10) NOT NULL,
  PRIMARY KEY (`idPage`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `activationCodes`
--

CREATE TABLE IF NOT EXISTS `activationCodes` (
  `idCode` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `idUser` int(8) unsigned NOT NULL,
  `activationCode` varchar(40) NOT NULL,
  `codeStatus` int(1) unsigned NOT NULL,
  `codeType` int(10) NOT NULL,
  `makeDate` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idCode`),
  KEY `idUser` (`idUser`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `authors`
--

CREATE TABLE IF NOT EXISTS `authors` (
  `idAuthor` int(10) NOT NULL AUTO_INCREMENT,
  `idContent` int(10) NOT NULL,
  `googlePlusId` varchar(30) NOT NULL,
  `authorStatus` int(1) NOT NULL,
  PRIMARY KEY (`idAuthor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `bannerAreas`
--

CREATE TABLE IF NOT EXISTS `bannerAreas` (
  `idBannerArea` int(10) NOT NULL AUTO_INCREMENT,
  `bannerAreaName` varchar(255) NOT NULL,
  `bannerAreaAlias` varchar(255) NOT NULL,
  `bannerAreaStatus` int(1) NOT NULL,
  PRIMARY KEY (`idBannerArea`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `bannerLocaleData`
--

CREATE TABLE IF NOT EXISTS `bannerLocaleData` (
  `idBannerLocalePhoto` int(10) NOT NULL AUTO_INCREMENT,
  `idBanner` int(10) NOT NULL,
  `idLang` int(2) NOT NULL,
  `idPhoto` int(10) NOT NULL,
  `bannerLocalePhotoStatus` int(1) NOT NULL,
  PRIMARY KEY (`idBannerLocalePhoto`),
  KEY `idBanner` (`idBanner`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE IF NOT EXISTS `banners` (
  `idBanner` int(14) NOT NULL AUTO_INCREMENT,
  `idContent` int(14) NOT NULL,
  `idPhoto` int(14) NOT NULL,
  `idBannerArea` int(10) NOT NULL,
  `bannerUrl` varchar(255) NOT NULL,
  `showInNewTab` int(1) NOT NULL,
  `isDisplayed` int(1) NOT NULL,
  `bannerStatus` int(1) NOT NULL,
  `bannerPosition` int(10) NOT NULL,
  PRIMARY KEY (`idBanner`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `blocks`
--

CREATE TABLE IF NOT EXISTS `blocks` (
  `idBlock` int(14) unsigned NOT NULL AUTO_INCREMENT,
  `idPage` int(14) unsigned NOT NULL,
  `idContent` int(14) unsigned NOT NULL,
  `blockType` int(1) unsigned NOT NULL,
  `searchType` int(1) unsigned NOT NULL,
  `blockStatus` int(1) unsigned NOT NULL,
  PRIMARY KEY (`idBlock`),
  KEY `idPage` (`idPage`),
  KEY `searchType` (`searchType`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE IF NOT EXISTS `cities` (
  `idCity` int(10) NOT NULL AUTO_INCREMENT,
  `idContent` int(10) NOT NULL,
  `idLocation` int(10) NOT NULL,
  `locationLat` decimal(15,7) NOT NULL,
  `locationLng` decimal(15,7) NOT NULL,
  `locationZoom` int(5) NOT NULL,
  `cityAlias` varchar(255) NOT NULL,
  `cityPosition` int(10) NOT NULL,
  `cityStatus` int(1) NOT NULL,
  PRIMARY KEY (`idCity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `idComment` int(14) unsigned NOT NULL AUTO_INCREMENT,
  `idCity` int(10) NOT NULL,
  `idParentComment` int(14) unsigned NOT NULL,
  `idFor` int(14) unsigned NOT NULL,
  `forType` int(10) unsigned NOT NULL,
  `idUser` int(10) unsigned NOT NULL,
  `userName` varchar(255) NOT NULL,
  `userEmail` varchar(255) NOT NULL,
  `commentText` text NOT NULL,
  `childrenCount` int(3) NOT NULL,
  `commentIp` varchar(30) NOT NULL,
  `commentTime` int(14) NOT NULL,
  `positiveVotes` int(10) NOT NULL,
  `negativeVotes` int(10) NOT NULL,
  `commentStatus` int(1) NOT NULL,
  PRIMARY KEY (`idComment`),
  KEY `idParentComment` (`idParentComment`,`idFor`,`forType`),
  KEY `idFor` (`idFor`,`forType`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `commentVotes`
--

CREATE TABLE IF NOT EXISTS `commentVotes` (
  `idVote` int(10) NOT NULL AUTO_INCREMENT,
  `idComment` int(10) NOT NULL,
  `idUser` int(10) NOT NULL,
  `voteIp` varchar(255) NOT NULL,
  `voteType` int(1) NOT NULL,
  `voteTime` int(14) NOT NULL,
  `voteStatus` int(1) NOT NULL,
  PRIMARY KEY (`idVote`),
  KEY `idComment` (`idComment`,`voteStatus`),
  KEY `isVoteUser` (`idComment`,`idUser`,`voteStatus`),
  KEY `commentRating` (`idComment`,`voteType`,`voteStatus`),
  KEY `spamFilter` (`voteIp`,`voteStatus`,`voteTime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contentBlocks`
--

CREATE TABLE IF NOT EXISTS `contentBlocks` (
  `idBlock` int(10) NOT NULL,
  PRIMARY KEY (`idBlock`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `contentLangs`
--

CREATE TABLE IF NOT EXISTS `contentLangs` (
  `idContentLang` int(14) unsigned NOT NULL AUTO_INCREMENT,
  `idContent` int(14) unsigned NOT NULL,
  `idLang` int(5) unsigned NOT NULL,
  `contentLangStatus` int(1) NOT NULL,
  PRIMARY KEY (`idContentLang`),
  KEY `idContent` (`idContent`,`idLang`,`contentLangStatus`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contentPages`
--

CREATE TABLE IF NOT EXISTS `contentPages` (
  `idPage` int(10) NOT NULL AUTO_INCREMENT,
  `idContent` int(10) NOT NULL,
  `idPhoto` int(10) NOT NULL,
  `idAuthor` int(10) NOT NULL,
  `pageAlias` varchar(255) NOT NULL,
  `addDate` date NOT NULL,
  `pageType` int(1) NOT NULL,
  `pageStatus` int(1) NOT NULL,
  PRIMARY KEY (`idPage`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contents`
--

CREATE TABLE IF NOT EXISTS `contents` (
  `idContent` int(14) unsigned NOT NULL AUTO_INCREMENT,
  `idDefaultLang` int(5) unsigned NOT NULL,
  `contentStatus` int(1) NOT NULL,
  PRIMARY KEY (`idContent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `fieldsContent`
--

CREATE TABLE IF NOT EXISTS `fieldsContent` (
  `idField` int(14) unsigned NOT NULL AUTO_INCREMENT,
  `idContent` int(14) unsigned NOT NULL,
  `idLang` int(5) unsigned NOT NULL,
  `idFieldName` int(8) unsigned NOT NULL,
  `processType` int(1) unsigned NOT NULL,
  `fieldContent` mediumtext NOT NULL,
  `fieldStatus` int(1) NOT NULL,
  PRIMARY KEY (`idField`),
  KEY `idContent` (`idContent`,`idLang`,`idFieldName`,`fieldStatus`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `fieldsNames`
--

CREATE TABLE IF NOT EXISTS `fieldsNames` (
  `idFieldName` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `fieldName` varchar(45) NOT NULL,
  PRIMARY KEY (`idFieldName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `forgotPasswords`
--

CREATE TABLE IF NOT EXISTS `forgotPasswords` (
  `idForgotCode` int(10) NOT NULL AUTO_INCREMENT,
  `idCode` int(10) NOT NULL,
  `newPassword` varchar(255) NOT NULL,
  PRIMARY KEY (`idForgotCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `galleries`
--

CREATE TABLE IF NOT EXISTS `galleries` (
  `idGallery` int(14) unsigned NOT NULL AUTO_INCREMENT,
  `galleryStatus` int(1) unsigned NOT NULL,
  PRIMARY KEY (`idGallery`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `galleriesPhotos`
--

CREATE TABLE IF NOT EXISTS `galleriesPhotos` (
  `idGalleryPhoto` int(15) unsigned NOT NULL AUTO_INCREMENT,
  `idGallery` int(14) unsigned NOT NULL,
  `idPhoto` int(14) unsigned NOT NULL,
  `galleryPhotoPosition` int(8) unsigned NOT NULL,
  `galleryPhotoStatus` int(1) unsigned NOT NULL,
  PRIMARY KEY (`idGalleryPhoto`),
  KEY `idPhoto` (`idPhoto`),
  KEY `idGallery` (`idGallery`,`galleryPhotoPosition`,`galleryPhotoStatus`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `langs`
--

CREATE TABLE IF NOT EXISTS `langs` (
  `idLang` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `idPhoto` int(14) unsigned DEFAULT NULL,
  `isoName` varchar(6) NOT NULL,
  `langName` varchar(45) NOT NULL,
  `langUrl` varchar(10) NOT NULL,
  `defaultStatus` int(1) unsigned NOT NULL,
  `langStatus` int(1) unsigned NOT NULL,
  PRIMARY KEY (`idLang`),
  KEY `isoName` (`isoName`),
  KEY `langUrl` (`langUrl`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `langs`
--

INSERT INTO `langs` (`idLang`, `idPhoto`, `isoName`, `langName`, `langUrl`, `defaultStatus`, `langStatus`) VALUES
(1, 618, 'ru_RU', 'Русский', 'ru', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE IF NOT EXISTS `location` (
  `idLocation` int(14) unsigned NOT NULL AUTO_INCREMENT,
  `idParentLocation` int(14) unsigned NOT NULL,
  `idContent` int(10) NOT NULL,
  `locationType` int(2) unsigned NOT NULL,
  `locationLat` decimal(19,14) NOT NULL,
  `locationLng` decimal(19,14) NOT NULL,
  `addressType` int(1) NOT NULL DEFAULT '0',
  `validCustomAddress` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idLocation`),
  KEY `idParentLocation` (`idParentLocation`),
  KEY `locationLat` (`locationLat`,`locationLng`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `idPage` int(10) NOT NULL,
  PRIMARY KEY (`idPage`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `idPage` int(14) unsigned NOT NULL AUTO_INCREMENT,
  `idRoute` int(14) unsigned NOT NULL DEFAULT '0',
  `idContent` int(14) unsigned NOT NULL DEFAULT '0',
  `pageStatus` int(1) unsigned NOT NULL,
  `systemStatus` int(10) NOT NULL DEFAULT '2',
  `pageType` int(1) unsigned NOT NULL,
  PRIMARY KEY (`idPage`),
  KEY `idRoute` (`idRoute`,`idContent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `photos`
--

CREATE TABLE IF NOT EXISTS `photos` (
  `idPhoto` int(14) unsigned NOT NULL AUTO_INCREMENT,
  `idContent` int(14) unsigned NOT NULL,
  `idUser` int(10) unsigned NOT NULL,
  `photoPath` varchar(50) NOT NULL,
  `photoStatus` int(1) unsigned NOT NULL,
  PRIMARY KEY (`idPhoto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE IF NOT EXISTS `profiles` (
  `idUser` int(10) unsigned NOT NULL,
  `idAvatar` int(10) unsigned NOT NULL,
  `profilePhone` varchar(40) NOT NULL,
  `profileAddress` varchar(255) NOT NULL,
  `profileEmailStatus` int(1) NOT NULL DEFAULT '2',
  PRIMARY KEY (`idUser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`idUser`, `idAvatar`, `profilePhone`, `profileAddress`, `profileEmailStatus`) VALUES
(1, 0, '+380633750893', '', 2);

-- --------------------------------------------------------

--
-- Table structure for table `rmProfiles`
--

CREATE TABLE IF NOT EXISTS `rmProfiles` (
  `idUser` int(14) unsigned NOT NULL,
  `profileName` varchar(255) NOT NULL,
  `profileLastname` varchar(255) NOT NULL,
  `profileEmail` varchar(255) NOT NULL,
  `profilePassword` varchar(255) NOT NULL,
  `profileStatus` int(14) NOT NULL,
  PRIMARY KEY (`idUser`),
  KEY `userEmail` (`profileEmail`),
  KEY `userStatus` (`profileStatus`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `rmProfiles`
--

INSERT INTO `rmProfiles` (`idUser`, `profileName`, `profileLastname`, `profileEmail`, `profilePassword`, `profileStatus`) VALUES
(1, 'Rademade', 'Rademade', 'yes@rademade.com', '3bee5301f0a843c894137b11214491c911964a98', 1);

-- --------------------------------------------------------

--
-- Table structure for table `rmUsers`
--

CREATE TABLE IF NOT EXISTS `rmUsers` (
  `idUser` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idRole` int(1) unsigned NOT NULL,
  `userStatus` int(1) NOT NULL,
  PRIMARY KEY (`idUser`),
  KEY `idRole` (`idRole`),
  KEY `userStatus` (`userStatus`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `rmUsers`
--

INSERT INTO `rmUsers` (`idUser`, `idRole`, `userStatus`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `idRole` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `shortDesc` varchar(30) NOT NULL,
  `adminAccess` int(1) unsigned NOT NULL,
  `userAccess` int(1) NOT NULL,
  `programmerAccess` int(1) NOT NULL,
  `hierarchy` int(3) NOT NULL,
  PRIMARY KEY (`idRole`),
  UNIQUE KEY `getHierarchy` (`hierarchy`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`idRole`, `shortDesc`, `adminAccess`, `userAccess`, `programmerAccess`, `hierarchy`) VALUES
(1, 'Programmer', 1, 0, 1, 1),
(2, 'Main admin', 1, 0, 0, 2),
(3, 'Country manager', 2, 0, 0, 3),
(4, 'Simple manager', 3, 0, 0, 4),
(5, 'Simple user', 0, 1, 0, 5);

-- --------------------------------------------------------

--
-- Table structure for table `routing`
--

CREATE TABLE IF NOT EXISTS `routing` (
  `idRoute` int(14) NOT NULL AUTO_INCREMENT,
  `type` int(1) NOT NULL,
  `name` varchar(100) NOT NULL,
  `module` varchar(50) NOT NULL,
  `controller` varchar(100) NOT NULL,
  `action` varchar(50) NOT NULL,
  `url` varchar(255) NOT NULL,
  `defaultParams` text NOT NULL,
  `routeStatus` int(1) NOT NULL,
  PRIMARY KEY (`idRoute`),
  KEY `routeStatus` (`routeStatus`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2001 ;

--
-- Dumping data for table `routing`
--

INSERT INTO `routing` (`idRoute`, `type`, `name`, `module`, `controller`, `action`, `url`, `defaultParams`, `routeStatus`) VALUES
(1, 1, 'upload-image', 'admin', 'image', 'upload', '/image/upload', '[]', 1),
(2, 1, 'image-delete', 'admin', 'image', 'delete', '/image/delete/:id', '[]', 1),
(3, 1, 'image-settings', 'admin', 'image', 'settings', '/image/settings/:id', '[]', 1),
(4, 1, 'index-ajax', 'admin', 'index', 'ajax', '/admin/ajax', '{}', 1),
(5, 1, 'admin-error-categories-list', 'admin', 'errors', 'categories-list', '/admin/errors-categories/:page', '{"page":1}', 1),
(6, 1, 'admin-error-list', 'admin', 'errors', 'list', '/admin/errors-categories/:idLog/list/:page', '{}', 1),
(7, 1, 'admin-error-add', 'admin', 'errors', 'add', '/admin/errors-categories/:idLog/add/:page', '{}', 1),
(8, 1, 'admin-error-edit', 'admin', 'errors', 'edit', '/admin/errors-categories/:idLog/edit/:id/:page', '{}', 1),
(9, 1, 'admin-error-ajax', 'admin', 'errors', 'ajax', '/admin/error/ajax', '{}', 1),
(10, 1, 'admin-option-list', 'admin', 'option', 'list', '/admin/option/:idCategory/:page/', '{"page":1, "idCategory":0}', 1),
(11, 1, 'admin-option-add', 'admin', 'option', 'add', '/admin/option/:idCategory/add/:page/', '[]', 1),
(12, 1, 'admin-option-edit', 'admin', 'option', 'edit', '/admin/option/:idCategory/edit/:id/:page/', '[]', 1),
(13, 1, 'admin-option-ajax', 'admin', 'option', 'ajax', '/admin/option/:idCategory/ajax', '[]', 1),
(14, 1, 'admin-user-index', 'admin', 'user', 'index', '/admin/user/:page/', '{"page":1}', 1),
(15, 1, 'admin-user-add', 'admin', 'user', 'add', '/admin/user/add/:page/', '[]', 1),
(16, 1, 'admin-user-edit', 'admin', 'user', 'edit', '/admin/user/edit/:id/:page/', '[]', 1),
(17, 1, 'admin-user-ajax', 'admin', 'user', 'ajax', '/admin/user/ajax', '[]', 1),
(18, 1, 'admin-lang-index', 'admin', 'lang', 'index', '/admin/language/:page/', '{"page":1}', 1),
(19, 1, 'admin-lang-add', 'admin', 'lang', 'add', '/admin/language/add/:page/', '[]', 1),
(20, 1, 'admin-lang-edit', 'admin', 'lang', 'edit', '/admin/language/edit/:id/:page/', '[]', 1),
(21, 1, 'admin-lang-ajax', 'admin', 'lang', 'ajax', '/admin/language/ajax', '[]', 1),
(22, 1, 'admin-user-settings', 'admin', 'user', 'settings', '/admin/user/settings/:id/:page', '[]', 1),
(51, 1, 'admin-gallery-photos', 'admin', 'gallery', 'photos', '/admin/:type/:idParent/gallery/photos/:id', '{}', 1),
(52, 1, 'admin-gallery-ajax', 'admin', 'gallery', 'ajax', '/admin/gallery/ajax', '[]', 1),
(53, 1, 'admin-gallery-photo-upload', 'admin', 'gallery', 'upload', '/admin/gallery/photo/upload/:id', '[]', 1),
(54, 1, 'admin-gallery-photo-qq-upload', 'admin', 'gallery', 'qq-upload', '/admin/gallery/photo/qq-upload/:id', '{}', 1),
(60, 1, 'admin-news-list', 'admin', 'news', 'list', '/admin/page-news/:page/', '{"page":1}', 1),
(61, 1, 'admin-news-add', 'admin', 'news', 'add', '/admin/page-news/add/:page/', '[]', 1),
(62, 1, 'admin-news-edit', 'admin', 'news', 'edit', '/admin/page-news/edit/:id/:page/', '[]', 1),
(63, 1, 'admin-news-ajax', 'admin', 'news', 'ajax', '/admin/page-news/ajax', '[]', 1),
(68, 1, 'admin-settings-list', 'admin', 'settings', 'list', '/admin/settings', '{}', 1),
(69, 1, 'admin-city-list', 'admin', 'city', 'list', '/admin/city/:page/', '{"page":1}', 1),
(70, 1, 'admin-city-add', 'admin', 'city', 'add', '/admin/city/add/:page/', '[]', 1),
(71, 1, 'admin-city-edit', 'admin', 'city', 'edit', '/admin/city/edit/:id/:page/', '[]', 1),
(72, 1, 'admin-city-ajax', 'admin', 'city', 'ajax', '/admin/city/ajax', '[]', 1),
(77, 1, 'admin-page-list', 'admin', 'page', 'list', '/admin/page/:page/', '{"page":1}', 1),
(78, 1, 'admin-page-add', 'admin', 'page', 'add', '/admin/page/add/:page/', '[]', 1),
(79, 1, 'admin-page-edit', 'admin', 'page', 'edit', '/admin/page/edit/:id/:page/', '[]', 1),
(80, 1, 'admin-page-ajax', 'admin', 'page', 'ajax', '/admin/page/ajax', '[]', 1),
(93, 1, 'admin-banner-list', 'admin', 'banner', 'list', '/admin/banner-area/:idBannerArea/banner/:page', '{"page":1}', 1),
(94, 1, 'admin-banner-add', 'admin', 'banner', 'add', '/admin/banner-area/:idBannerArea/banner/:page/add', '{}', 1),
(95, 1, 'admin-banner-edit', 'admin', 'banner', 'edit', '/admin/banner-area/:idBannerArea/banner/:page/edit/:id', '{}', 1),
(96, 1, 'admin-banner-ajax', 'admin', 'banner', 'ajax', '/admin/banner-area/:idBannerArea/banner/ajax', '{}', 1),
(97, 1, 'admin-comment-list', 'admin', 'comment', 'list', '/admin/comment/:idFor/:forType/:page', '{"idFor":0,"forType":0,"page":1}', 1),
(98, 1, 'admin-comment-edit', 'admin', 'comment', 'edit', '/admin/comment/edit/:idFor/:forType/:id/:page/', '{"idFor":0,"forType":0}', 1),
(99, 1, 'admin-comment-ajax', 'admin', 'comment', 'ajax', '/admin/comment/ajax', '{}', 1),
(100, 1, 'admin-tag-list', 'admin', 'tag', 'list', '/admin/tags/:page/', '{"page":1}', 1),
(101, 1, 'admin-tag-add', 'admin', 'tag', 'add', '/admin/tag/add/:page/', '{}', 1),
(102, 1, 'admin-tag-edit', 'admin', 'tag', 'edit', '/admin/tag/edit/:id/:page/', '{}', 1),
(103, 1, 'admin-tag-ajax', 'admin', 'tag', 'ajax', '/admin/tag/ajax', '{}', 1),
(111, 1, 'admin-banner-area-list', 'admin', 'banner-area', 'list', '/admin/banner-areas/:page', '{"page":1}', 1),
(112, 1, 'admin-banner-area-add', 'admin', 'banner-area', 'add', '/admin/banner-area/:page/add', '{}', 1),
(113, 1, 'admin-banner-area-edit', 'admin', 'banner-area', 'edit', '/admin/banner-area/:page/edit/:id', '{}', 1),
(114, 1, 'admin-banner-area-ajax', 'admin', 'banner-area', 'ajax', '/admin/banner-area/ajax', '{}', 1),
(115, 1, 'admin-author-list', 'admin', 'author', 'list', '/admin/authors/:page', '{"page":1}', 1),
(116, 1, 'admin-author-add', 'admin', 'author', 'add', '/admin/author/:page/add', '{}', 1),
(117, 1, 'admin-author-edit', 'admin', 'author', 'edit', '/admin/author/:page/edit/:id', '{}', 1),
(118, 1, 'admin-author-ajax', 'admin', 'author', 'ajax', '/admin/author/ajax', '{}', 1),
(1000, 1, 'index', 'public', 'index', 'index', '/', '{}', 1);

-- --------------------------------------------------------

--
-- Table structure for table `subscription`
--

CREATE TABLE IF NOT EXISTS `subscription` (
  `idSubscription` int(10) NOT NULL AUTO_INCREMENT,
  `subscriptionEmail` varchar(255) NOT NULL,
  `subscriptionDate` int(14) NOT NULL,
  `subscriptionType` int(1) NOT NULL,
  `subscriptionStatus` int(1) NOT NULL,
  PRIMARY KEY (`idSubscription`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `subscriptionCodes`
--

CREATE TABLE IF NOT EXISTS `subscriptionCodes` (
  `idCode` int(10) NOT NULL AUTO_INCREMENT,
  `idSubscription` int(10) NOT NULL,
  `activationCode` varchar(255) NOT NULL,
  `codeStatus` int(1) NOT NULL,
  `makeDate` int(14) NOT NULL,
  PRIMARY KEY (`idCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `idTag` int(10) NOT NULL AUTO_INCREMENT,
  `idContent` int(10) NOT NULL,
  `tagAlias` varchar(255) NOT NULL,
  `tagStatus` int(1) NOT NULL,
  PRIMARY KEY (`idTag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `_errorLog`
--

CREATE TABLE IF NOT EXISTS `_errorLog` (
  `idLog` int(14) NOT NULL AUTO_INCREMENT,
  `logName` varchar(120) NOT NULL,
  PRIMARY KEY (`idLog`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `_errorLogRow`
--

CREATE TABLE IF NOT EXISTS `_errorLogRow` (
  `idLogRow` int(14) NOT NULL AUTO_INCREMENT,
  `idLog` int(14) NOT NULL,
  `errorTime` datetime NOT NULL,
  `errorText` text NOT NULL,
  `errorServer` text NOT NULL,
  `errorRequestData` text NOT NULL,
  `errorUrl` varchar(255) NOT NULL,
  `errorStatus` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idLogRow`),
  KEY `idLog` (`idLog`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
