
abort() {
  echo
  echo "  $@" 1>&2
  echo
  exit 1
}

nginx -V
test $? -eq 0 || abort Verification Failed. Server not ready.

redis-cli --version
test $? -eq 0 || abort Verification Failed. Server not ready.
