INSERT INTO routing (idRoute, type, name, module, controller, action, url, defaultParams, routeStatus) VALUES
(150, 1, 'admin-link-google-account', 'admin', 'google-account', 'link', '/admin/google/link', '{}', 1);

--31.01.14

CREATE TABLE IF NOT EXISTS `googleAccessTokens` (
  `idAccessToken` int(11) NOT NULL AUTO_INCREMENT,
  `idUser` int(11) NOT NULL,
  `accessToken` varchar(255) NOT NULL,
  `tokenType` varchar(255) NOT NULL,
  `refreshToken` varchar(255) NOT NULL,
  `timeCreated` int(11) NOT NULL,
  `timeExpires` int(11) NOT NULL,
  `accessTokenStatus` int(11) NOT NULL,
  PRIMARY KEY (`idAccessToken`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE  `googleAccessTokens` CHANGE  `idUser`  `idDoctor` INT( 11 ) NOT NULL ;
