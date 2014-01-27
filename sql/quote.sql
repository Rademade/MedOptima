-- 25.01.14

CREATE TABLE IF NOT EXISTS `quotes` (
  `idQuote` int(11) NOT NULL AUTO_INCREMENT,
  `idContent` int(11) NOT NULL,
  `quoteStatus` int(11) NOT NULL,
  PRIMARY KEY (`idQuote`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO routing (idRoute, type, name, module, controller, action, url, defaultParams, routeStatus) VALUES
(146, 1, 'admin-quote-list', 'admin', 'quote', 'list', '/admin/quotes/:page', '{"page":1}', 1),
(146+1, 1, 'admin-quote-add', 'admin', 'quote', 'add', '/admin/quotes/:page/add', '{}', 1),
(146+2, 1, 'admin-quote-edit', 'admin', 'quote', 'edit', '/admin/quote/:page/edit/:id', '{}', 1),
(146+3, 1, 'admin-quote-ajax', 'admin', 'quote', 'ajax', '/admin/quote/ajax', '{}', 1);

ALTER TABLE  `quotes` ADD  `showOnClinic` INT NOT NULL AFTER  `idContent` ;