-- 25.01.14

CREATE TABLE IF NOT EXISTS `options` (
  `idOption` int(11) NOT NULL AUTO_INCREMENT,
  `idContent` int(11) NOT NULL,
  `optionKey` varchar(255) NOT NULL,
  PRIMARY KEY (`idOption`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

INSERT INTO routing (idRoute, type, name, module, controller, action, url, defaultParams, routeStatus) VALUES
(138, 1, 'admin-option-list', 'admin', 'option', 'list', '/admin/options/:page', '{"page":1}', 1),
(138+1, 1, 'admin-option-add', 'admin', 'option', 'add', '/admin/options/:page/add', '{}', 1),
(138+2, 1, 'admin-option-edit', 'admin', 'option', 'edit', '/admin/option/:page/edit/:id', '{}', 1),
(138+3, 1, 'admin-option-ajax', 'admin', 'option', 'ajax', '/admin/option/ajax', '{}', 1);