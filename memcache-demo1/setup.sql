-- admin  P8MHNpCJiICT
CREATE DATABASE IF NOT EXISTS yurt_reservations;
USE yurt_reservations;

DROP TABLE IF EXISTS reservations, yurts;

CREATE TABLE yurts
(id SMALLINT(5) NOT NULL,
 name varchar(35),
 max int,
 cost float,
 PRIMARY KEY (id)
);

INSERT INTO yurts VALUES(0,'Yurt Mini', 2, 55.0);
INSERT INTO yurts VALUES(1,'Yurt View', 4, 45.0);
INSERT INTO yurts VALUES(2,'Yurt Party', 10, 38.0);
INSERT INTO yurts VALUES(3,'Yurt Swim', 2, 62.0);

CREATE TABLE reservations
(id int NOT NULL AUTO_INCREMENT,
	yurt_id SMALLINT(5),
	begin_day int NOT NULL,
	end_day int NOT NULL,
	num_guests int,
	guest_name varchar(50),
	guest_phone varchar(12),
	secret varchar(5),
	PRIMARY KEY (id),
	FOREIGN KEY (yurt_id) REFERENCES yurts(id)
	ON DELETE CASCADE
	ON UPDATE CASCADE
);

INSERT INTO reservations VALUES(0,0,5,9,2,"Bob0 Billy", "555-345-3431",'12345');
INSERT INTO reservations VALUES(0,1,3,5,2,"Bob1 Billy", "555-345-3431",'12345');
INSERT INTO reservations VALUES(0,2,1,2,2,"Bob2 Billy", "555-345-3431",'12345');
INSERT INTO reservations VALUES(0,3,10,20,2,"Bob3 Billy", "555-345-3431",'12345');