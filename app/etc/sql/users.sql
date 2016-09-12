
   DROP TABLE IF EXISTS appuser_role;
   DROP TABLE IF EXISTS userrole;
   DROP TABLE IF EXISTS appuser_pref;
   DROP TABLE IF EXISTS appuser;


 CREATE TABLE appuser (
                `user_id`          INT(11)      UNSIGNED NOT NULL AUTO_INCREMENT
              , `user_name`        VARCHAR(60)  NOT NULL
              , `user_pass`        VARCHAR(255) NOT NULL
              , `user_email`       VARCHAR(80)  NOT NULL
              , `user_firstname`   VARCHAR(60)      NULL
              , `user_lastname`    VARCHAR(100)     NULL
              , `user_sysadmin`    TINYINT(1)   NOT NULL DEFAULT '0'
              , `user_active`      TINYINT(1)   NOT NULL DEFAULT '1'
              , `user_passexpired` TINYINT(1)   NOT NULL DEFAULT '0'
              , `user_passdate`    DATETIME         NULL DEFAULT NULL
              , `user_lastseen`    DATETIME         NULL DEFAULT NULL
              , `user_cdate`       TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'record creation date'

              , PRIMARY KEY (`user_id`)
              , UNIQUE INDEX `user_name`  (`user_name`)
              , UNIQUE INDEX `user_email` (`user_email`)
              , INDEX `user_active` (`user_active`)
              , INDEX `userpass`    (`user_name`, `user_pass`)
              , INDEX `emailpass`   (`user_email`, `user_pass`)
              )
              COMMENT='application user accounts'
              ENGINE=InnoDB;
              
 CREATE TABLE appuser_pref (
                `user_id`          INT(11)      UNSIGNED NOT NULL                  COMMENT 'PK, FK - appuser.user_id'
              , `user_theme`       VARCHAR(50)      NULL DEFAULT NULL
              , PRIMARY KEY (`user_id`)
              , FOREIGN KEY (`user_id`)         REFERENCES `appuser`  (`user_id`)  ON UPDATE CASCADE ON DELETE CASCADE
              )
              COMMENT='app user account settings'
              ENGINE=InnoDB;


 CREATE TABLE userrole (
                `role_id`          INT(11)      UNSIGNED NOT NULL AUTO_INCREMENT
              , `role_name`        VARCHAR(30)  NOT NULL
              , `role_desc`        VARCHAR(255)     NULL DEFAULT NULL
              , PRIMARY KEY (`role_id`)
              , UNIQUE INDEX `role_name` (`role_name`)
              )
              COMMENT='pool of avaible user roles, like groups'
              ENGINE=InnoDB;

 CREATE TABLE appuser_role (
                `userrole_id`      INT(11)      UNSIGNED NOT NULL AUTO_INCREMENT
              , `user_id`          INT(11)      UNSIGNED NOT NULL                  COMMENT 'FK - appuser.user_id'
              , `role_id`          INT(11)      UNSIGNED NOT NULL                  COMMENT 'FK - userrole.role_id'

              , PRIMARY KEY (`userrole_id`)
              , FOREIGN KEY (`user_id`)         REFERENCES `appuser`  (`user_id`)  ON UPDATE CASCADE ON DELETE CASCADE
              , FOREIGN KEY (`role_id`)         REFERENCES `userrole` (`role_id`)  ON UPDATE CASCADE ON DELETE CASCADE
              , UNIQUE INDEX `userrole` (`user_id`, `role_id`)
              )
              COMMENT='app user roles, kinda like groups users can belong to'
              ENGINE=InnoDB;



   DROP VIEW IF EXISTS appuser_data;
 CREATE VIEW appuser_data AS
      SELECT appuser.*
           , user_theme
        FROM appuser
   LEFT JOIN appuser_pref       USING(user_id);