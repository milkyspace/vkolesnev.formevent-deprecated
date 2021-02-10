CREATE TABLE IF NOT EXISTS `b_vkolesnev_formevent_event_by_user` (

  `ID` int(11) NOT NULL auto_increment,
  `USER_ID` varchar(250)  NOT NULL default '',
  `EVENT_TYPE`  varchar(100) NOT NULL default '',
  `CREATED_AT` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
   PRIMARY KEY  (`id`)

);