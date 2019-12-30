# Generate the ssh key

# Print the ssh key to be used in the espectra application
SSH_KEY_NAME='espectra'

ssh-keygen -o -a 100 -t ed25519 -P '' -f ~/.ssh/$SSH_KEY_NAME -C worker@espectra.com

cat ~/.ssh/$SSH_KEY_NAME.pub
