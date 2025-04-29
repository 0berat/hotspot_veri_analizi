#!/bin/sh

set -e

cd "/var/www/html/mailer"
[ ! -d "vendor" ] && composer install
php "src/cron.php"
