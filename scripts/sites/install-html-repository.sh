# Get the name of this site
SITE_NAME=$1
BRANCH=$2
REPOSITORY_URL=$3
SSH_USER=$4

git clone --single-branch --branch $BRANCH $REPOSITORY_URL /home/$SSH_USER/$SITE_NAME
