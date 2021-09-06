create table localdata
(local_id int(4), prefectures_name varchar(15), town_name varchar(30), ss_town_name varchar(30), L_location geometry, primary key(local_id));

create table scrape
(id int(11), target_name varchar(60), url varchar(255), element_path varchar(255), primary key(id));
