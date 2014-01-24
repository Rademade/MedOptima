-- 25.01.14

INSERT INTO routing (idRoute, type, name, module, controller, action, url, defaultParams, routeStatus) VALUES
(131, 1, 'admin-medical-advice-list', 'admin', 'medical_advice', 'list', '/admin/medical/advices/:page', '{"page":1}', 1),
(131+1, 1, 'admin-medical-advice-add', 'admin', 'medical_advice', 'add', '/admin/medical/advices/:page/add', '{}', 1),
(131+2, 1, 'admin-medical-advice-edit', 'admin', 'medical_advice', 'edit', '/admin/medical/advice/:page/edit/:id', '{}', 1),
(131+3, 1, 'admin-medical-advice-ajax', 'admin', 'medical_advice', 'ajax', '/admin/medical/advice/ajax', '{}', 1);

CREATE TABLE IF NOT EXISTS `medicalAdvices` (
  `idAdvice` int(11) NOT NULL AUTO_INCREMENT,
  `idDoctor` int(11) NOT NULL,
  `visitorQuestion` text NOT NULL,
  `doctorResponse` text NOT NULL,
  `adviceStatus` int(11) NOT NULL,
  PRIMARY KEY (`idAdvice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;