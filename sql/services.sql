-- 23.01.14

INSERT INTO routing (idRoute, type, name, module, controller, action, url, defaultParams, routeStatus) VALUES
(119, 1, 'admin-medical-service-list', 'admin', 'medical_service', 'list', '/admin/medical/services/:page', '{"page":1}', 1),
(119+1, 1, 'admin-medical-service-add', 'admin', 'medical_service', 'add', '/admin/medical/services/:page/add', '{}', 1),
(119+2, 1, 'admin-medical-service-edit', 'admin', 'medical_service', 'edit', '/admin/medical/service/:page/edit/:id', '{}', 1),
(119+3, 1, 'admin-medical-service-ajax', 'admin', 'medical_service', 'ajax', '/admin/medical/service/ajax', '{}', 1);

CREATE TABLE IF NOT EXISTS `medicalServices` (
  `idService` int(11) NOT NULL AUTO_INCREMENT,
  `idContent` int(11) NOT NULL,
  `serviceStatus` int(11) NOT NULL,
  PRIMARY KEY (`idService`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `medicalDoctorServices` (
  `idDoctorService` int(11) NOT NULL AUTO_INCREMENT,
  `idDoctor` int(11) NOT NULL,
  `idService` int(11) NOT NULL,
  `doctorServiceStatus` int(11) NOT NULL,
  PRIMARY KEY (`idDoctorService`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;