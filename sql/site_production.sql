create table shop
(shop_id int(4), shop_name varchar(60), shop_url varchar(255), twitter_user_id varchar(30),
 img_src varchar(255), series varchar(80), primary key(shop_id));

create table sale_point
(sp_code int(10), shop_id int(4), store_id int(5), sp_title varchar(255),
 sp_subtitle varchar(1000), sp_url varchar(255), event_start char(8), event_end char(8), 
 cash_kubun varchar(250), keyword varchar(50), register_day char(8), card_true boolean, 
 primary key(sp_code));

create table store
(shop_id int(4), store_id int(5), store_name varchar(90), zip varchar(8), store_address varchar(120),
 store_tel varchar(20), store_url varchar(1024), business_hours varchar(250),
 prefectures varchar(15), town varchar(30), ss_town varchar(30), location geometry, 
 bfr_aftr int(1), primary key(shop_id, store_id));

-- ※shopテーブルの説明
-- series  ・・・・・・・・・・店の系列

-- ※sale_pointの説明
-- sp_code  ・・・・・・sale_pointそのものの主キー
-- shop_id  ・・・・・・会社のイベントとして振られるid。それ以外の時は値を入れない
-- store_id  ・・・・・・店舗のイベントとして振られるid。それ以外の時は値を入れない
-- sp_subtitle  ・・・・・・タイトルの説明
-- cash_kubun  ・・・・・・現金、クレジット、電子マネー、QRコード、商品券などを番号で区分する
-- keyword  ・・・・・キーワード検索に使えそうなキーワードを文字列として入れていく。イベントに適当な値が無ければ何も入れない
-- register_day  ・・・・・・イベントをインサートした登録日
-- card_true  ・・・・・・・特定のカードに限った支払いは[true 1]、特定のカードに限らない支払い全般は[false 0]



-- ※storeテーブルの説明
-- bfr_aftr  ・・・・・・・同じイベントの前実施と後実施の店舗を振り分ける。0 が前実施の店舗、1 が後実施の店舗