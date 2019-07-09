# Get the name of this site
SITE_NAME=$1
REPOSITORY_URL=$2

cd /home/espectra

git clone $REPOSITORY_URL $SITE_NAME
# git@github.com:shiftboard/member-js.git
