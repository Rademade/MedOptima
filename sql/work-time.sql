-- 24.01.14

CREATE TABLE IF NOT EXISTS `medicalDoctorWorkTime` (
  `idWorkTime` int(11) NOT NULL AUTO_INCREMENT,
  `weekDay` int(11) NOT NULL,
  `timeBegin` time NOT NULL,
  `timeEnd` time NOT NULL,
  `workTimeStatus` int(11) NOT NULL,
  PRIMARY KEY (`idWorkTime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE  `medicalDoctorWorkTime` ADD  `idDoctor` INT NOT NULL AFTER  `idWorkTime` ;