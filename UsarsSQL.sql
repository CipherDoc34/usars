use usar;
-- CREATE TABLE videos(
-- 	id varchar(20) NOT NULL primary key,
--     date date NOT NULL,
--     location varchar(255),
--     filePath varchar(255)
-- );

-- delete from videos where id="test";
-- select * from videos; 
-- INSERT INTO videos(id, date, location, filePath) VALUES('test','1990-01-01','Princess Street','test');
-- select * from videos;

-- alter table videos change id id varchar(255);

-- alter table videos
-- add jsonMeta nvarchar(4000);

-- Select * from OpenJson(@videos);
-- alter table videos
-- 	add name varchar(225),
--     add ext varchar(225)
--     ;
-- alter table videos change date date timestamp;

-- SELECT date, CAST(date at TIME ZONE INTERVAL 'est' AS DATETIME) AS ut 
-- FROM videos ORDER BY id;

-- Select * from videos
-- where "366ece0d5ea9d24eb88dfc0332e0ba6f7416872943fdc88e4c86ce222a04c31d" = videos.id;

-- Select * from videos
-- where "ddddd" = videos.id;

-- delete from videos where id = "0516774b8a38d8b4cf7cc2727a3634e5ef6c5e9c97dbc8df160a002fea1e33c9";

-- alter table videos modify date timestamp null;

-- CREATE TABLE `videos` (
--   `id` varchar(255) NOT NULL,
--   `date` timestamp NULL DEFAULT NULL,
--   `location` varchar(255) DEFAULT NULL,
--   `filePath` varchar(255) NOT NULL,
--   `jsonMeta` varchar(4000) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
--   `name` varchar(225) DEFAULT NULL,
--   `ext` varchar(225) DEFAULT NULL,
--   PRIMARY KEY (`id`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- alter table videos modify date bigint default(UNIX_TIMESTAMP());

-- alter table videos add videoMeta varchar(4000);

-- Select from_unixtime(1684187533);

-- CREATE USER 'box'@'localhost' IDENTIFIED BY '69420';
-- GRANT ALL PRIVILEGES ON *.* TO 'box'@'localhost' WITH GRANT OPTION;
-- CREATE USER 'box'@'%' IDENTIFIED BY '69420';
-- GRANT ALL PRIVILEGES ON *.* TO 'box'@'%' WITH GRANT OPTION;
-- FLUSH PRIVILEGES;

 -- alter table videos rename column location to locationLO;
-- ALTER TABLE videos
-- MODIFY locationLO float;

-- ALTER TABLE videos
-- add locationLA float;

-- Select * from videos 
-- where locationLA < 80 and locationLA > 0 
-- and locationLO < 70 and locationLO > 0;

-- alter table videos rename column locationLO to longitude;
-- alter table videos rename column locationLA to latitude;

-- alter table videos modify column longitude double;
-- alter table videos modify column latitude double;
-- CREATE TABLE `videos` (
--   `id` varchar(255) NOT NULL,
--   `date` bigint DEFAULT (unix_timestamp()),
--   `longitude` double DEFAULT NULL,
-- 	`latitude` double DEFAULT NULL,
--   `filePath` varchar(255) NOT NULL,
--   `jsonMeta` varchar(4000) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
--   `name` varchar(225) DEFAULT NULL,
--   `ext` varchar(225) DEFAULT NULL,
--   `videoMeta` varchar(4000) DEFAULT NULL,
--   `creator` varchar(255) NOT NULL,
--   PRIMARY KEY (`id`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Create trigger Make_GeoTable After insert on videos
-- for each row create table NEW.id (
-- `longitude` double DEFAULT NULL, 
-- `latitude` double DEFAULT NULL, 
-- `date` bigint DEFAULT (unix_timestamp())
-- );

-- create table `Geo_location` (
-- 	videoID varchar(255) NOT NULL,
--     timestamp bigint,
--     longitude double,
--     latitude double,
--     foreign key (videoID) references videos (id),
--     Primary Key (videoID, timestamp)
-- );
 -- alter table geo_location add foreign key (videoID) references videos (id) on delete cascade;
-- alter table geo_location drop foreign key videoID;

-- show create table geo_location;

 -- alter table geo_location drop foreign key `geo_location_ibfk_1`;
 
-- Declare @GeoJsonData varchar(4000) = N'{};

-- alter table geo_location modify column timestamp double;

 -- delete from videos;

-- create table `API_KEYS` (
-- 	`service` varchar(255) PRIMARY KEY,
--     `key` varchar(1000)
-- );

select * from videos v right join geo_location g
on v.id = g.videoID
where v.date = 1685636957138 and
g.latitude <= 44.9 and
g.latitude >= 44 and 
g.longitude <= -76 and
g.longitude >= -76.9
;

-- x1 = 44.217176
-- x2 = 44.248845
-- y1 = -76.528374
-- y2 = -76.481384 
-- (x1,y1) (x2,y1) (x1,y2) (x2, y2)
--   44.9, 44, -76, -76.9
--   triangleCoords = [{
--     "lat": 44.9,
--     "lng": -76.9
--   }, {
--     "lat": 44,
--     "lng": -76.9
--   }, {
--     "lat": 44,
--     "lng": -76
--   }, {
--     "lat": 44.9,
--     "lng": -76
--   }];

CREATE DATABASE `usar` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

CREATE TABLE `api_keys` (
  `service` varchar(255) NOT NULL,
  `key` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`service`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
CREATE TABLE `videos` (
  `id` varchar(255) NOT NULL,
  `date` bigint DEFAULT (unix_timestamp()),
  `longitude` double DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `filePath` varchar(255) NOT NULL,
  `jsonMeta` varchar(4000) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `name` varchar(225) DEFAULT NULL,
  `ext` varchar(225) DEFAULT NULL,
  `videoMeta` varchar(4000) DEFAULT NULL,
  `creator` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
CREATE TABLE `geo_location` (
  `videoID` varchar(255) NOT NULL,
  `timestamp` double NOT NULL,
  `longitude` double DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  PRIMARY KEY (`videoID`,`timestamp`),
  CONSTRAINT `geo_location_ibfk_2` FOREIGN KEY (`videoID`) REFERENCES `videos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



