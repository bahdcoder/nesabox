# this script adds an ssh key to espectra user.
# SSH access via password will be disabled. Use keys instead.
USER=$1
PUBLIC_SSH_KEYS=$2

cat >> /home/${USER}/.ssh/authorized_keys << EOF
$PUBLIC_SSH_KEYS
EOF

# Restart SSH

ssh-keygen -A
service ssh restart
