SITE_NAME=$1

abort() {
  echo
  echo "  $@" 1>&2
  echo
  exit 1
}

certbot --agree-tos -n --nginx --redirect -d $SITE_NAME -m worker@espectra.com

test $? -eq 0 || abort Failed generating certificate.
