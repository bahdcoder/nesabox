FILE_PATH=$1

cat $FILE_PATH

if [ -f '$FILE_PATH' ]
then
    cat $FILE_PATH
else
    echo ''
fi
