-- 23.01.14

INSERT INTO routing (idRoute, type, name, module, controller, action, url, defaultParams, routeStatus) VALUES
(127, 1, 'admin-medical-doctor-list', 'admin', 'medical_doctor', 'list', '/admin/medical/doctors/:page', '{"page":1}', 1),
(127+1, 1, 'admin-medical-doctor-add', 'admin', 'medical_doctor', 'add', '/admin/medical/doctors/:page/add', '{}', 1),
(127+2, 1, 'admin-medical-doctor-edit', 'admin', 'medical_doctor', 'edit', '/admin/medical/doctor/:page/edit/:id', '{}', 1),
(127+3, 1, 'admin-medical-doctor-ajax', 'admin', 'medical_doctor', 'ajax', '/admin/medical/doctor/ajax', '{}', 1);

CREATE TABLE IF NOT EXISTS `medicalDoctors` (
  `idDoctor` int(11) NOT NULL AUTO_INCREMENT,
  `idContent` int(11) NOT NULL,
  `idPhoto` int(11) NOT NULL,
  `idPost` int(11) NOT NULL,
  `isHead` int(11) NOT NULL,
  `doctorStatus` int(11) NOT NULL,
  PRIMARY KEY (`idDoctor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE  `medicalDoctors` DROP  `isHead` ;


-- 29.01.14

ALTER TABLE  `medicalDoctors` ADD  `googleAccessToken` VARCHAR( 255 ) NOT NULL AFTER  `idPost` ;

ALTER TABLE  `medicalDoctors` ADD  `googleAccessTokenTimeLeft` INT NOT NULL AFTER  `googleAccessToken` ;

ALTER TABLE  `medicalDoctors` CHANGE  `googleAccessTokenTimeLeft`  `googleAccessTokenExpireTime` INT( 11 ) NOT NULL ;

ALTER TABLE  `medicalDoctors` CHANGE  `googleAccessToken`  `googleAccessToken` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;

-- 31.01.14

ALTER TABLE  `medicalDoctors` DROP  `googleAccessToken` ,
DROP  `googleAccessTokenExpireTime` ;

ALTER TABLE  `medicalDoctors` ADD  `idUser` INT NOT NULL AFTER  `idDoctor` ;