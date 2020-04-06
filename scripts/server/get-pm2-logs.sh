SITE_NAME=$1

pm2 logs $SITE_NAME
tail 500 /home/nesa/.pm2/logs/$SITE_NAME
