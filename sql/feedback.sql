DROP TABLE comments;
DROP TABLE commentVotes;
DROP TABLE authors;
DROP TABLE bannerAreas;
DROP TABLE bannerLocaleData;

CREATE TABLE IF NOT EXISTS `feedbacks` (
  `idFeedback` int(11) NOT NULL AUTO_INCREMENT,
  `visitorName` varchar(255) NOT NULL,
  `visitorPhone` varchar(255) NOT NULL,
  `feedbackContent` text NOT NULL,
  `datePosted` date NOT NULL,
  `postStatus` int(11) NOT NULL,
  PRIMARY KEY (`idFeedback`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE  `feedbacks` CHANGE  `postStatus`  `feedbackStatus` INT( 11 ) NOT NULL ;

INSERT INTO routing (idRoute, type, name, module, controller, action, url, defaultParams, routeStatus) VALUES
(135, 1, 'admin-feedback-list', 'admin', 'feedback', 'list', '/admin/feedbacks/:page', '{"page":1}', 1),
(135+1, 1, 'admin-feedback-ajax', 'admin', 'feedback', 'ajax', '/admin/feedback/ajax', '{}', 1),
(135+2, 1, 'admin-feedback-edit', 'admin', 'feedback', 'edit', '/admin/feedback/edit/:id', '{}', 1);

ALTER TABLE  `feedbacks` ADD  `showOnMain` INT NOT NULL AFTER  `datePosted` ;

-- 12.02.2014

ALTER TABLE  `feedbacks` ADD  `isProcessed` INT NOT NULL AFTER  `showOnMain` ;