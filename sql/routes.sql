-- 25.01.14

INSERT INTO `routing` (`idRoute`, `type`, `name`, `module`, `controller`, `action`, `url`, `defaultParams`, `routeStatus`) VALUES
(1001, 1, 'clinic', 'public', 'clinic', 'index', '/clinic', '{}', 1),
(1002, 1, 'contacts', 'public', 'index', 'contacts', '/contacts', '{}', 1),
(1003, 1, 'advices', 'public', 'advice', 'index', '/advices', '{}', 1);
