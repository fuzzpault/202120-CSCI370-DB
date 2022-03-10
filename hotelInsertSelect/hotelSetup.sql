CREATE DATABASE IF NOT EXISTS hotels;
USE hotels;

DROP TABLE IF EXISTS room;
DROP TABLE IF EXISTS hotel;

CREATE TABLE hotel
(hotelNo SMALLINT(5) NOT NULL DEFAULT 0,
 hotelName varchar(35),
 city varchar(20),
 PRIMARY KEY (hotelNo)
);



CREATE TABLE room
(roomNo SMALLINT(5) NOT NULL DEFAULT 0,
 hotelNo SMALLINT(5) NOT NULL DEFAULT 0,
 roomtype varchar(1),
 price float,
 PRIMARY KEY (roomNo, hotelNo),
 FOREIGN KEY room(hotelNo) REFERENCES hotel(hotelNo)
  ON DELETE CASCADE
);


INSERT INTO hotel VALUES('1','Paradise','Las Vegas');
INSERT INTO hotel VALUES('2','Flamingo','Reno');
INSERT INTO hotel VALUES('3','Marriott','Indianapolis');
INSERT INTO hotel VALUES('4','Hamilton','Washington');
INSERT INTO hotel VALUES('5','Hive','Washington');
INSERT INTO hotel VALUES('6','Flamingo','Reno');
INSERT INTO hotel VALUES('7','Westin','Indianapolis');
INSERT INTO hotel VALUES('8','Hilton','Cleveland');
INSERT INTO hotel VALUES('9','Freehand','Los Angeles');
INSERT INTO hotel VALUES('10','Mayfair','Los Angeles');

