# This script finds a free port on the server we can run the site on

# Get the local port range, highest and lowest

read LOWERPORT UPPERPORT < /proc/sys/net/ipv4/ip_local_port_range
\
# Find 
comm -23 <(seq $LOWERPORT $UPPERPORT | sort) <(ss -tan | awk '{print $4}' | cut -d':' -f2 | grep "[0-9]\{1,5\}" | sort -u) | shuf | head -n 2
