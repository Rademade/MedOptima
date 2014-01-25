-- 25.01.14

DROP TABLE contentBlocks;
DROP TABLE blocks;

CREATE TABLE IF NOT EXISTS `textBlocks` (
  `idBlock` int(11) NOT NULL AUTO_INCREMENT,
  `idContent` int(11) NOT NULL,
  `blockAlias` varchar(255) NOT NULL,
  `blockStatus` int(11) NOT NULL,
  PRIMARY KEY (`idBlock`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO routing (idRoute, type, name, module, controller, action, url, defaultParams, routeStatus) VALUES
(142, 1, 'admin-text-block-list', 'admin', 'text-block', 'list', '/admin/text-blocks/:page', '{"page":1}', 1),
(142+1, 1, 'admin-text-block-add', 'admin', 'text-block', 'add', '/admin/text-blocks/:page/add', '{}', 1),
(142+2, 1, 'admin-text-block-edit', 'admin', 'text-block', 'edit', '/admin/text-block/:page/edit/:id', '{}', 1),
(142+3, 1, 'admin-text-block-ajax', 'admin', 'text-block', 'ajax', '/admin/text-block/ajax', '{}', 1);