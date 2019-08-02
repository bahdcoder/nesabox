SSH_USER=$1
SITE_NAME=$2
GHOST_CONFIG = $3

echo '$GHOST_CONFIG' > /home/$SSH_USER/$SITE_NAME/config-2.production.json

# Reload pm2 site - no downtime
pm2 reload $SITE_NAME --update-env
