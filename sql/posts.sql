-- 23.01.14

INSERT INTO routing (idRoute, type, name, module, controller, action, url, defaultParams, routeStatus) VALUES
(123, 1, 'admin-medical-post-list', 'admin', 'medical_post', 'list', '/admin/medical/posts/:page', '{"page":1}', 1),
(123+1, 1, 'admin-medical-post-add', 'admin', 'medical_post', 'add', '/admin/medical/posts/:page/add', '{}', 1),
(123+2, 1, 'admin-medical-post-edit', 'admin', 'medical_post', 'edit', '/admin/medical/post/:page/edit/:id', '{}', 1),
(123+3, 1, 'admin-medical-post-ajax', 'admin', 'medical_post', 'ajax', '/admin/medical/post/ajax', '{}', 1);

CREATE TABLE IF NOT EXISTS `medicalPosts` (
  `idPost` int(11) NOT NULL AUTO_INCREMENT,
  `idContent` int(11) NOT NULL,
  `postStatus` int(11) NOT NULL,
  PRIMARY KEY (`idPost`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `medicalDoctorPosts` (
  `idDoctorPost` int(11) NOT NULL AUTO_INCREMENT,
  `idDoctor` int(11) NOT NULL,
  `idPost` int(11) NOT NULL,
  `doctorPostStatus` int(11) NOT NULL,
  PRIMARY KEY (`idDoctorPost`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;