# Get the name of this site
SITE_PATH=$1
BRANCH=$2
REPOSITORY_URL=$3

# git@github.com:shiftboard/member-js.git
git clone --single-branch --branch $BRANCH $REPOSITORY_URL $SITE_PATH
