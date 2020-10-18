apt-get install -y mysql-server
USER="{$database->databaseUser->name}"
MYSQL_ROOT_PASSWORD="{$database->databaseUser->password}"
mysql --user="root" --password="\$MYSQL_ROOT_PASSWORD" -e "GRANT ALL ON *.* TO root@'localhost' IDENTIFIED BY '\$MYSQL_ROOT_PASSWORD';"
mysql --user="root" --password="\$MYSQL_ROOT_PASSWORD" -e "GRANT ALL ON *.* TO root@'%' IDENTIFIED BY '\$MYSQL_ROOT_PASSWORD';"
service mysql restart
mysql --user="root" --password="\$MYSQL_ROOT_PASSWORD" -e "CREATE USER '\$USER'@'localhost' IDENTIFIED BY '\$MYSQL_ROOT_PASSWORD';"
mysql --user="root" --password="\$MYSQL_ROOT_PASSWORD" -e "GRANT ALL ON *.* TO '\$USER'@'localhost' IDENTIFIED BY '\$MYSQL_ROOT_PASSWORD' WITH GRANT OPTION;"
mysql --user="root" --password="\$MYSQL_ROOT_PASSWORD" -e "GRANT ALL ON *.* TO '\$USER'@'%' IDENTIFIED BY '\$MYSQL_ROOT_PASSWORD' WITH GRANT OPTION;"
mysql --user="root" --password="\$MYSQL_ROOT_PASSWORD" -e "FLUSH PRIVILEGES;"
mysql --user="root" --password="\$MYSQL_ROOT_PASSWORD" -e "CREATE DATABASE {$database->name}";
