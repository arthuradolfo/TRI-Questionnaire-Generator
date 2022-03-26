#!/bin/bash
apt-get update
apt-get install -y gnupg
apt-get install -y odbcinst
apt-get install -y wget
apt install -y unixodbc-dev libxml2-dev libcurl4-openssl-dev libssl-dev libcurl4
apt-key adv --keyserver hkp://keys.gnupg.net:80 --recv-key 'E19F5F87128899B192B1A2C2AD5F960A256A04AF'
apt-get install -y software-properties-common
add-apt-repository 'deb https://cloud.r-project.org/bin/linux/debian buster-cran40/'
apt update
apt upgrade
apt install -y r-base
wget https://dlm.mariadb.com/1489921/Connectors/odbc/connector-odbc-3.1.11/mariadb-connector-odbc-3.1.11-debian-buster-amd64.tar.gz
tar -xvzf mariadb-connector-odbc-3.1.11-debian-buster-amd64.tar.gz
cd mariadb-connector-odbc-3.1.11-debian-buster-amd64
install lib/mariadb/libmaodbc.so /usr/lib/
install -d /usr/lib/mariadb/
install -d /usr/lib/mariadb/plugin/
install lib/mariadb/plugin/auth_gssapi_client.so /usr/lib/mariadb/plugin/
install lib/mariadb/plugin/caching_sha2_password.so /usr/lib/mariadb/plugin/
install lib/mariadb/plugin/client_ed25519.so /usr/lib/mariadb/plugin/
install lib/mariadb/plugin/dialog.so /usr/lib/mariadb/plugin/
install lib/mariadb/plugin/mysql_clear_password.so /usr/lib/mariadb/plugin/
install lib/mariadb/plugin/sha256_password.so /usr/lib/mariadb/plugin/
echo "[MariaDB ODBC 3.0 Driver]
Description = MariaDB Connector/ODBC v.3.0
Driver = /usr/lib/libmaodbc.so" > MariaDB_odbc_driver_template.ini
odbcinst -i -d -f MariaDB_odbc_driver_template.ini
