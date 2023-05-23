-- use usar;
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

Select from_unixtime(1684187533);