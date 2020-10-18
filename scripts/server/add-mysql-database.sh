# Add a mysql database

DATABASE_NAME=$1
DATABASE_USER=$2
DATABASE_PASSWORD=$3

# Create new user
mysql -e "CREATE USER '$DATABASE_USER'@'localhost' IDENTIFIED BY '$DATABASE_PASSWORD';"

# Create database
mysql -e "CREATE DATABASE $DATABASE_NAME"

# Grant permissions on this database to this user
mysql -e "GRANT ALL PRIVILEGES ON $DATABASE_NAME.* TO '$DATABASE_USER'@'localhost'"

# Flush privileges
mysql -e "FLUSH PRIVILEGES"
