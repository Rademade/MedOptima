-- 25.01.14

INSERT INTO `routing` (`idRoute`, `type`, `name`, `module`, `controller`, `action`, `url`, `defaultParams`, `routeStatus`) VALUES
(1001, 1, 'clinic', 'public', 'clinic', 'index', '/clinic', '{}', 1),
(1002, 1, 'contacts', 'public', 'index', 'contacts', '/contacts', '{}', 1),
(1003, 1, 'advices', 'public', 'advice', 'index', '/advices', '{}', 1);

INSERT INTO `routing` (`idRoute`, `type`, `name`, `module`, `controller`, `action`, `url`, `defaultParams`, `routeStatus`) VALUES
(1004, 1, 'translation', 'public', 'static', 'translation-js', '/static/translation', '{}', 1);

INSERT INTO `routing` (`idRoute`, `type`, `name`, `module`, `controller`, `action`, `url`, `defaultParams`, `routeStatus`) VALUES
(1005, 1, 'ask-question-ajax', 'public', 'advice-ajax', 'ask-question', '/question/ask/ajax', '{}', 1);

INSERT INTO `routing` (`idRoute`, `type`, `name`, `module`, `controller`, `action`, `url`, `defaultParams`, `routeStatus`) VALUES
(1006, 1, 'post-feedback-ajax', 'public', 'clinic-ajax', 'post-feedback', '/clinic/post/feedback/ajax', '{}', 1);

-- 05.02.2014

INSERT INTO `routing` (`idRoute`, `type`, `name`, `module`, `controller`, `action`, `url`, `defaultParams`, `routeStatus`) VALUES
(1007, 1, 'doctor-list-ajax', 'public', 'reservation-ajax', 'doctor-list', '/doctor/list/ajax', '{}', 1);

-- 06.02.2014

INSERT INTO `routing` (`idRoute`, `type`, `name`, `module`, `controller`, `action`, `url`, `defaultParams`, `routeStatus`) VALUES
(1008, 1, 'create-reservation-ajax', 'public', 'reservation-ajax', 'create', '/reservation/create/ajax', '{}', 1);