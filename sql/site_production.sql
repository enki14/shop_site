create table shop
(shop_id int(4), shop_name varchar(60), shop_url varchar(255), twitter_user_id varchar(30),
 api_scrape_kubun int(1), img_src varchar(255), primary key(shop_id));

create table sale_point
(sp_code int(10), shop_id int(4), store_id int(5), sp_kubun int(1), sp_title varchar(255),
 sp_subtitle varchar(255), sp_url varchar(255), event_start char(8), event_end char(8), 
 strength int(1), cash_kubun int(1), event_name_kubun int(1), shop_event_id int(4), primary key(sp_code, shop_id));

create table store
(shop_id int(4), store_id int(5), store_name varchar(90), zip varchar(8), store_address varchar(120),
 store_tel varchar(20), store_url varchar(1024), business_hours varchar(250),
 prefectures varchar(15), town varchar(30), ss_town varchar(30), station varchar(40), location geometry, 
 local_id int(4), primary key(shop_id, store_id));

-- ※shopテーブルの説明
-- api_scrape_kubun  ・・・・・・外部取得がapiかscrapeかの区分（使いみち要検討）

-- ※sale_pointの説明
-- sp_code  ・・・・・・sale_pointそのものの主キー
-- store_id  ・・・・・・ここに番号が振られているものは店舗ごとにイベントがあるということ
-- sp_kubun  ・・・・・・セールかポイントか、その両方かを0,1,2として判定する（使い道要検討）
-- sp_subtitle  ・・・・・・タイトルの説明
-- strength  ・・・・・・セールやポイントの度合いの強弱を表す（要らないかも）
-- cash_kubun  ・・・・・・現金、クレジット、電子マネー、QRコード、商品券などを番号で区分する
-- event_name_kubun  ・・・・・・使いみち失念。。。
-- shop_event_id  ・・・・・・会社のイベントととして+1振られるid

-- ※storeテーブルの説明
-- local_id  ・・・・・・localdataテーブルのlocal_idと紐づけている