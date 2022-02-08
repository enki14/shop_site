create table localdata
(local_id int(4), prefectures_name varchar(15), town_name varchar(30), ss_town_name varchar(30), L_location geometry, primary key(local_id));

create table scrape
(id int(11), target_name varchar(60), url varchar(1024), element_path varchar(255), primary key(id));

create table pointsite.card
(card_id int(4), shop_id int(4), card_name varchar(50), link varchar(500), e_money int(1), 
credit int(1), P_or_D int(1), primary key(card_id))

create table event_list
(el_id int(3), el_name varchar(80), link varchar(700), el_title varchar(500), 
el_subtitle varchar(1000), ocr_text varchar(1000), register_day char(8), primary key(el_id))


-- localdataテーブルの説明　(現在は未使用)
-- このテーブルのデータ投入はHomeController@localDataで実施している

-- scrapeテーブルの説明
-- element_path  ・・・・・・スクレイプするhtmlの階層


-- cardテーブルの説明
-- 概要：　１レコード１種類のカードとは限らない。１ショップに存在するうちの一つのカードが１レコード分になる。ゆえにshop_idが必要
-- card_name  ・・・・・・・上に同じく、ショップごとに複数回登場することがある
-- link  ・・・・・・・・該当カード情報のリンク先
-- e_money　・・・・・・・電子マネー対応か否かのカラム　0 : 非対応, 1 : 対応, 2 : 条件による, 3 : 不確定,  4 : クレジットカードそのもの
-- credit  ・・・・・・・クレジットカード型が存在するか　0 : しない, 1 : する, 2 : クレジットカードのみ, 3 : 未確定,
-- P_or_D  ・・・・・・・ポイントか割引か　0: ポイントが貯まるカード, 1: 割引が効くカード, 2:ポイント貯まって、割引も効く


-- event_listテーブル 
-- イベントリストの仮出力用テーブル。テキストの出力が可能なもののみが有効。
-- exelの「 スクレイピング対象リスト 」のurl先からデータを取得insertしたものを直後のselect文で出力させる
-- el_name  ・・・・・・・・どこの店のイベントかを明記
-- link  ･･･・・・・・・一つ一つのイベントのリンク先
-- el_title, el_subtitle  ・・・・・・・OCRではない通常のテキスト
-- ocr_text  ・・・・・・・識字のテキスト。今はまだデータを投入することができず

