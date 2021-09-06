create table shop_info
(shop_id int(5), shop_name varchar(70), shop_address varchar(130), shop_tel varchar(13), shop_url varchar(50), open_hour varchar(8), close_hour varchar(8), primary key(shop_id));

create table sale_point
(salepoint_code int(4), shop_id int(5), sale_name varchar(90), sale_day varchar(30), point_up_name varchar(90), point_up_day varchar(30), primary key(salepoint_code, shop_id));


-- shop_infoデフォルトvalue
insert into shop_info values(00010, 'イオン乃木坂店', '東京都港区赤坂', '0354335555', 'https://****.or.jp', '10:00', '23:00');
insert into shop_info values(00020, 'SEIYOU高輪台店', '東京都港区高輪台', '0353004321', 'https://**+++.co.jp', '9:30', '23:30');
insert into shop_info values(00030, 'ライフ中村橋店', '東京都練馬区中村橋', '0336662222', 'https://....or.jp', '9:30', '22:30');
insert into shop_info values(00040, 'ヨークマート笹塚店', '東京都渋谷区笹塚', '0345556789', 'https://>>>>.co.jp', '9:30', '24:00');

-- sale_pointデフォルトvalue
insert into sale_point values(1000, 00010, '10周年還元祭', '2021/06/10', '土日５倍デー', '次回20日21日');
insert into sale_point values(2000, 00020, '納涼祭', '07/10 ~ 7/20', '*****', '******');
insert into sale_point values(3000, 00030, '衣料品全店２割引き', '2021/07/15', 'チャージデー', '5/25 ~ 5/28');
insert into sale_point values(4000, 00040, '生鮮トクバイ', '2021/05/10', 'ポイントカード入会キャンペーン', '5/23 ~ 5/30');

