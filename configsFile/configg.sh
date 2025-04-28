#!/bin/bash

apt install php8.1-sqlite3
service nginx restart
service php8.1-fpm restart
service freeradius restart
service mysql restart
service freeradius start
service cron start

# sed -i 's/user = www-data/user = root/g' /etc/php/8.1/fpm/pool.d/www.conf
# sed -i 's/group = www-data/group = root/g' /etc/php/8.1/fpm/pool.d/www.conf
/bin/bash