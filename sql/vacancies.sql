-- --------
-- 25.06.2014
-- --------

INSERT INTO routing (idRoute, type, name, module, controller, action, url, defaultParams, routeStatus) VALUES
(158, 1, 'admin-vacancy-list', 'admin', 'vacancy', 'list', '/admin/vacancys/:page', '{"page":1}', 1),
(158+1, 1, 'admin-vacancy-add', 'admin', 'vacancy', 'add', '/admin/vacancy/:page/add', '{}', 1),
(158+2, 1, 'admin-vacancy-edit', 'admin', 'vacancy', 'edit', '/admin/vacancy/:page/edit/:id', '{}', 1),
(158+3, 1, 'admin-vacancy-ajax', 'admin', 'vacancy', 'ajax', '/admin/vacancy/ajax', '{}', 1);

INSERT INTO routing (idRoute, type, name, module, controller, action, url, defaultParams, routeStatus) VALUES
( 1011, 1, 'clinic-vacancies', 'public', 'clinic', 'vacancies', '/vacancies', '{}', 1 );

CREATE TABLE IF NOT EXISTS `vacancies` (
  `idVacancy` INT(11) NOT NULL AUTO_INCREMENT,
  `idContent` INT(11) NOT NULL,
  `vacancyStatus` INT(11) NOT NULL,
  PRIMARY KEY (`idVacancy`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =utf8
  AUTO_INCREMENT =1;