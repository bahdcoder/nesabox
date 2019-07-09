# Generate the ssh key

# Print the ssh key to be used in the espectra application
SSH_KEY_NAME='espectra'

ssh-keygen -f ~/.ssh/$SSH_KEY_NAME -t rsa -b 4096 -P '' -C worker@espectra.com

cat ~/.ssh/$SSH_KEY_NAME.pub
