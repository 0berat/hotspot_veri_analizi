#!/bin/sh

[ -f ".env" ] && . ".env"

set -e

service mysql start

mysql -u "${MAILER_USER:-root}" -p"${MAILER_PASS:-mypass1}" -e "CREATE DATABASE mailer;"

mysqlcomm() {
    mysql -u "${MAILER_USER:-root}" -p"${MAILER_PASS:-mypass1}" -D "mailer" -e "${1}"    
}

mysqlcomm "CREATE TABLE IF NOT EXISTS mailer (
    id INT AUTO_INCREMENT PRIMARY KEY,
    toName TEXT,
    toMail TEXT,
    subject TEXT,
    body TEXT,
    altBody TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);"
