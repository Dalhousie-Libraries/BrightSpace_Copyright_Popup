CREATE TABLE notifications
    (
        d2lusername VARCHAR(50) NOT NULL,
        dateapproved DATETIME NOT NULL,
        noticetype INT(2) NOT NULL,
        id bigint NOT NULL AUTO_INCREMENT,
        expire INT(1) DEFAULT 0,
        PRIMARY KEY (id)
    )
    ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE  ExpireTime
    (
        id int NOT NULL AUTO_INCREMENT,
        expiretime int,
        PRIMARY KEY (id)
    )
    ENGINE=MyISAM DEFAULT CHARSET=latin1;
	
INSERT INTO ExpireTime VALUES (1,120);