# Install MySQL

apt-get install -y mysql-server
USER='espectra'
MYSQL_ROOT_PASSWORD=$1

# Configure Password Expiration

# echo "default_password_lifetime = 0" >> /etc/mysql/mysql.conf.d/mysqld.cnf

# Configure Access Permissions For Root & User

# sed -i '/^bind-address/s/bind-address.*=.*/bind-address = */' /etc/mysql/mysql.conf.d/mysqld.cnf
mysql --user="root" --password="$MYSQL_ROOT_PASSWORD" -e "GRANT ALL ON *.* TO root@'localhost' IDENTIFIED BY '$MYSQL_ROOT_PASSWORD';"
mysql --user="root" --password="$MYSQL_ROOT_PASSWORD" -e "GRANT ALL ON *.* TO root@'%' IDENTIFIED BY '$MYSQL_ROOT_PASSWORD';"
service mysql restart

mysql --user="root" --password="$MYSQL_ROOT_PASSWORD" -e "CREATE USER '$USER'@'localhost' IDENTIFIED BY '$MYSQL_ROOT_PASSWORD';"
mysql --user="root" --password="$MYSQL_ROOT_PASSWORD" -e "GRANT ALL ON *.* TO '$USER'@'localhost' IDENTIFIED BY '$MYSQL_ROOT_PASSWORD' WITH GRANT OPTION;"
mysql --user="root" --password="$MYSQL_ROOT_PASSWORD" -e "GRANT ALL ON *.* TO '$USER'@'%' IDENTIFIED BY '$MYSQL_ROOT_PASSWORD' WITH GRANT OPTION;"
mysql --user="root" --password="$MYSQL_ROOT_PASSWORD" -e "FLUSH PRIVILEGES;"
