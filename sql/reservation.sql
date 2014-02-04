-- 01.02.14

CREATE TABLE IF NOT EXISTS `medicalReservationServices` (
  `idReservationService` int(11) NOT NULL AUTO_INCREMENT,
  `idReservation` int(11) NOT NULL,
  `idService` int(11) NOT NULL,
  `reservationServiceStatus` int(11) NOT NULL,
  PRIMARY KEY (`idReservationService`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `medicalReservations` (
  `idReservation` int(11) NOT NULL AUTO_INCREMENT,
  `idDoctor` int(11) NOT NULL,
  `idGoogleEvent` int(11) NOT NULL,
  `visitorName` int(11) NOT NULL,
  `visitorPhone` int(11) NOT NULL,
  `visitorNotes` int(11) NOT NULL,
  `timeCreated` int(11) NOT NULL,
  `timeVisit` int(11) NOT NULL,
  `timeFinalVisit` int(11) NOT NULL,
  `timeLastSaved` int(11) NOT NULL,
  `timeLastSynced` int(11) NOT NULL,
  `reservationStatus` int(11) NOT NULL,
  PRIMARY KEY (`idReservation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE  `medicalReservations` CHANGE  `idGoogleEvent`  `idGoogleEvent` VARCHAR( 255 ) NOT NULL ,
CHANGE  `visitorName`  `visitorName` VARCHAR( 255 ) NOT NULL ,
CHANGE  `visitorPhone`  `visitorPhone` VARCHAR( 255 ) NOT NULL ,
CHANGE  `visitorNotes`  `visitorNotes` VARCHAR( 255 ) NOT NULL ;

INSERT INTO routing (idRoute, type, name, module, controller, action, url, defaultParams, routeStatus) VALUES
(151, 1, 'admin-medical-reservation-list', 'admin', 'medical_reservation', 'list', '/admin/medical/reservations/:page', '{"page":1}', 1),
(151+1, 1, 'admin-medical-reservation-edit', 'admin', 'medical_reservation', 'edit', '/admin/medical/reservation/:page/edit/:id', '{}', 1),
(151+2, 1, 'admin-medical-reservation-ajax', 'admin', 'medical_reservation', 'ajax', '/admin/medical/reservation/ajax', '{}', 1);

ALTER TABLE  `medicalReservations` CHANGE  `timeVisit`  `timeVisitDesired` INT( 11 ) NOT NULL ;

ALTER TABLE  `medicalReservations` CHANGE  `timeFinalVisit`  `timeVisitFinal` INT( 11 ) NOT NULL ;

ALTER TABLE  `medicalReservations` CHANGE  `timeCreated`  `createTime` INT( 11 ) NOT NULL ,
CHANGE  `timeVisitDesired`  `desiredVisitTime` INT( 11 ) NOT NULL ,
CHANGE  `timeVisitFinal`  `finalVisitTime` INT( 11 ) NOT NULL ,
CHANGE  `timeLastSaved`  `visitEndTime` INT( 11 ) NOT NULL ,
CHANGE  `timeLastSynced`  `lastSyncTime` INT( 11 ) NOT NULL ;
ALTER TABLE  `medicalReservations` ADD  `lastSaveTime` INT NOT NULL AFTER  `lastSyncTime` ;

-- 04.02.2014

ALTER TABLE  `medicalReservations` DROP  `visitorNotes` ;