#!/bin/sh

set -e

cd "/usr/share/mailer"
[ ! -d "vendor" ] && composer install
php "src/cron.php"
