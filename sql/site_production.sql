create table shop
(shop_id int(4), shop_name varchar(60), shop_url varchar(255), twitter_user_id varchar(30),
 api_scrape_kubun int(1), img_src varchar(255), primary key(shop_id));

create table sale_point
(shop_id int(4), sp_code int(10), sp_kubun int(1), sp_title varchar(255),
 sp_subtitle varchar(255), sp_url varchar(255), event_start char(8), event_end char(8), 
 strength int(1), cash_kubun int(1), event_name_kubun int(1), primary key(shop_id, sp_code));

create table store
(shop_id int(4), store_id int(5), store_name varchar(90), store_address varchar(120),
 store_tel int(13), store_url varchar(255), business_hours varchar(100),
 prefectures varchar(15), town varchar(30), ss_town varchar(30), station varchar(40), location geometry, 
 local_id int(4), primary key(shop_id, store_id));

