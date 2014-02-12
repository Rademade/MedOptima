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

INSERT INTO  `routing` (
`idRoute` ,
`type` ,
`name` ,
`module` ,
`controller` ,
`action` ,
`url` ,
`defaultParams` ,
`routeStatus`
)
VALUES (
NULL ,  '1',  'remove-reservation-ajax',  'public',  'reservation-ajax',  'remove',  '/reservation/remove/ajax',  '{}',  '1'
);

UPDATE  `routing` SET  `name` =  'save-reservation-ajax',
`action` =  'save',
`url` =  '/reservation/save/ajax' WHERE  `routing`.`idRoute` =1008;

-- 12.02.2014

UPDATE  `routing` SET  `name` =  'create-reservation-ajax' WHERE  `routing`.`idRoute` =1008;

UPDATE  `routing` SET  `name` =  'delete-reservation-ajax' WHERE  `routing`.`idRoute` =1009;

UPDATE  `routing` SET  `url` =  '/reservation/create/ajax' WHERE  `routing`.`idRoute` =1008;

UPDATE  `routing` SET  `action` =  'create' WHERE  `routing`.`idRoute` =1008;

UPDATE  `routing` SET  `action` =  'delete' WHERE  `routing`.`idRoute` =1009;

UPDATE  `routing` SET  `url` =  '/reservation/delete/ajax' WHERE  `routing`.`idRoute` =1009;

INSERT INTO  `routing` (
`idRoute` ,
`type` ,
`name` ,
`module` ,
`controller` ,
`action` ,
`url` ,
`defaultParams` ,
`routeStatus`
)
VALUES (
NULL ,  '1',  'update-reservation-ajax',  'public',  'reservation-ajax',  'update',  '/reservation/update/ajax',  '{}',  '1'
);

-- RestFull

DELETE FROM routing WHERE idRoute IN (1007, 1008, 1009, 1010);

INSERT INTO
  `routing` (`idRoute`, `type`, `name`, `module`, `controller`, `action`, `url`, `defaultParams`, `routeStatus`)
VALUES
  (1007, 1, 'api-doctor-resources', 'public', 'api_doctor', 'index', '/api/doctor/', '{}', 1),
  (1008, 1, 'api-doctor-resource',  'public', 'api_doctor', 'index', '/api/doctor/:id', '{}', 1),
  (1009, 1, 'api-reservation-resources', 'public', 'api_reservation', 'index', '/api/reservation/', '{}', 1),
  (1010, 1, 'api-reservation-resource',  'public', 'api_reservation', 'index', '/api/reservation/:id', '{}', 1);
