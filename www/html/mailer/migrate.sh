#!/bin/sh

set -e

export SQLITE="${SQLITE:-sqlite3}" DATABASE="${1:-database.db}"

command -vV "${SQLITE}"

sqlitegen() {
	[ -n "${1}" ] && "${SQLITE}" "${DATABASE}" "${1}"
}

# rapor_gonderen
sqlitegen "CREATE TABLE IF NOT EXISTS sender (
    id INTEGER PRIMARY KEY,
	mail TEXT NOT NULL,
	pass TEXT NOT NULL,
	name TEXT NOT NULL	
);"

# rapor_alici
sqlitegen "CREATE TABLE IF NOT EXISTS receiver (
    id INTEGER PRIMARY KEY,
	mail TEXT NOT NULL,
	name TEXT NOT NULL			
);"

echo "${0##*/}: veri tabanı oluşturuldu."
