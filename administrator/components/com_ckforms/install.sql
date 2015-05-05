drop table if exists #__ckforms;

create table #__ckforms
(
   id                           int(11) not null AUTO_INCREMENT,
   name                         text,
   title                        text,
   description                  longtext,
   emailfrom                    text,
   emailto                      text,
   emailcc                      text,
   emailbcc                     text,
   subject						text,	
   created                      datetime,
   created_by                   int(11),
   hits                         int(11),
   published                    tinyint,
   saveresult                   tinyint,
   emailresult                  tinyint,
   textresult                   longtext,
   redirecturl					text,
   captcha                    	tinyint,
   customjs						text,	
   uploadpath					text,
   maxfilesize					int,
   poweredby                   	tinyint,
   
   primary key (id)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

drop table if exists #__ckfields;

create table #__ckfields
(
   id                             int(11) not null AUTO_INCREMENT,
   fid                            int(11),
   name                           text,
   label                  	      text,
   typefield                      text,
   defaultvalue					  text,
   mandatory                      tinyint,
   test_validity                  tinyint,
   published                      tinyint,
   ordering                       int(11) not null DEFAULT 0, 
   custominfo					  text,
   customerror					  text,
   customvalidation				  text,
   primary key (id)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

