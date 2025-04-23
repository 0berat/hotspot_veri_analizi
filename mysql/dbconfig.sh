#!/bin/bash


sed -i 's/\/etc\/php\/8.1\/fpm\/php-fpm.conf/\/etc\/php\/8.1\/fpm\/php-fpm.conf -R/g' /lib/systemd/system/php8.1-fpm.service

service mysql start

mysql -u root -e "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password by 'mypass1';"
mysql -u root -pmypass1 -e "FLUSH PRIVILEGES;"
mysql -u root -pmypass1 -e "CREATE DATABASE radius;"
mysql -u root -pmypass1 radius < /etc/freeradius/3.0/mods-config/sql/main/mysql/schema.sql
mysql -u root -pmypass1 -D radius -e "INSERT INTO radcheck (username, attribute, op, value) VALUES ('berat', 'Cleartext-Password', ':=', '123456');"
mysql -u root -pmypass1 -D radius -e "INSERT INTO  nas VALUES (NULL ,  '172.17.0.2',  'nas client name',  'other', NULL ,  'passwordnas', NULL , NULL ,  'RADIUS Client nas1');"
#tabloya kayit tarihi ekliyoruz
mysql -u root -pmypass1 -D radius -e "ALTER TABLE radcheck ADD kayit_tarihi TIMESTAMP DEFAULT CURRENT_TIMESTAMP;"

#admin icin sadece

mysql -u root -pmypass1 -D radius -e "CREATE TABLE a_users (id INT PRIMARY KEY AUTO_INCREMENT,username VARCHAR(255) NOT NULL,password VARCHAR(255) NOT NULL);"
mysql -u root -pmypass1 -D radius -e "INSERT INTO a_users (username, password) VALUES ('admin', '\$2y\$10\$yGvYzy35SMWdhtAAlOLjFOE98J9yMqBzbmvm5xxLjSq/0uUf51DZ.');"
#admin admin admin_login.php sayfasi girisi
# mysql -u root -e "CREATE DATABASE radius;"
# mysql -u root radius < /etc/freeradius/3.0/mods-config/sql/main/mysql/schema.sql
# mysql -u root -D radius -e "INSERT INTO radcheck (username, attribute, op, value) VALUES ('berat', 'Cleartext-Password', ':=', '123456');"
# mysql -u root -D radius -e "INSERT INTO  nas VALUES (NULL ,  '172.17.0.2',  'nas client name',  'other', NULL ,  'passwordnas', NULL , NULL ,  'RADIUS Client nas1');"
