# This script would be used to install and configure mongodb on the server

MONGO_DB_ADMIN_PASSWORD=$1

# Import public key used for package management system
sudo apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv 9DA31620334BD75D9DCB49F368818C72E52529D4

# Create a list file for mongodb
sudo echo "deb [ arch=amd64 ] https://repo.mongodb.org/apt/ubuntu bionic/mongodb-org/4.0 multiverse" | sudo tee /etc/apt/sources.list.d/mongodb-org-4.0.list
# sudo bash -c 'echo "deb [arch=amd64] http://repo.mongodb.org/apt/ubuntu bionic/mongodb-org/4.0 multiverse" > /etc/apt/sources.list.d/mongodb-org-4.0.list'

# Reload package database
sudo apt-get update

# Install mongodb 
sudo apt-get install -y mongodb-org

# Start mongodb
sudo service mongod start

# Start mongodb when server starts
sudo systemctl enable mongod

# Create a default espectra user

cat > app.js << EOF
db.createUser ({
    user: "espectra",
    pwd: "$MONGO_DB_ADMIN_PASSWORD",
    roles: [ { role: "userAdminAnyDatabase", db: "admin" }, "readWriteAnyDatabase" ]
  }
)
printjson(db.getUsers())
EOF

# Execute app.js script
mongo admin app.js

# Delete app.js script

rm app.js

# Enable security for mongodb v4
cat >> /etc/mongod.conf << EOF

security:
  authorization: enabled
EOF
