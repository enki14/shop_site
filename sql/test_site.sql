-- primary key　や　autoincrement の指定は括弧の最後の方に記す。
create table testsite
(shop_id int(4), shop_name varchar(20), 
title varchar(40), link varchar(8190), primary key autoincrement(shop_id)) default charset=utf8;

insert into testsite values(0001, 'イトーヨーカドー', 'かめはめ波', 'https://example.com');
insert into testsite values(0002, 'ライフ', 'マケマケ', 'https://life-example.com');
