#!/bin/bash

# Note this needs VPN

if [ "$1" == "" ] || [ $# -gt 1 ]; then
  echo "Host parameter is missing"
  echo "e.g. channel.hammer.ucla.edu"
  exit
fi

# Connect to remote server
ssh hammer.channel << EOF
  sudo su deploy
  cd "/var/www/${1}/current"
  php artisan responsecache:clear
  exit
EOF