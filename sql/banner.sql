ALTER TABLE  `banners` DROP  `idBannerArea` ,
DROP  `bannerUrl` ,
DROP  `showInNewTab` ,
DROP  `isDisplayed` ;

DELETE FROM `medoptima`.`routing` WHERE `routing`.`idRoute` = 111 LIMIT 1;
DELETE FROM `medoptima`.`routing` WHERE `routing`.`idRoute` = 112 LIMIT 1;
DELETE FROM `medoptima`.`routing` WHERE `routing`.`idRoute` = 113 LIMIT 1;
DELETE FROM `medoptima`.`routing` WHERE `routing`.`idRoute` = 114 LIMIT 1;

DELETE FROM `medoptima`.`routing` WHERE `routing`.`idRoute` = 93 LIMIT 1;
DELETE FROM `medoptima`.`routing` WHERE `routing`.`idRoute` = 94 LIMIT 1;
DELETE FROM `medoptima`.`routing` WHERE `routing`.`idRoute` = 95 LIMIT 1;
DELETE FROM `medoptima`.`routing` WHERE `routing`.`idRoute` = 96 LIMIT 1;

INSERT INTO routing (idRoute, type, name, module, controller, action, url, defaultParams, routeStatus) VALUES
(93, 1, 'admin-banner-list', 'admin', 'banner', 'list', '/admin/banners/:page', '{"page":1}', 1),
(93+1, 1, 'admin-banner-add', 'admin', 'banner', 'add', '/admin/banners/:page/add', '{}', 1),
(93+2, 1, 'admin-banner-edit', 'admin', 'banner', 'edit', '/admin/banner/:page/edit/:id', '{}', 1),
(93+3, 1, 'admin-banner-ajax', 'admin', 'banner', 'ajax', '/admin/banner/ajax', '{}', 1);

-- 25.01.14

ALTER TABLE  `banners` CHANGE  `idContent`  `bannerName` VARCHAR( 255 ) NOT NULL ;

ALTER TABLE  `banners` ADD  `idQuote` INT NOT NULL AFTER  `idBanner` ;

ALTER TABLE  `banners` ADD  `showOnMain` INT NOT NULL AFTER  `bannerName` ;

ALTER TABLE  `banners` ADD  `showOnClinic` INT NOT NULL AFTER  `showOnMain` ;