#!/bin/bash

if [[ ! -r /app ]]; then
   echo -n "Not in Heroku remote"
   echo -n "Skipping nano install"
   exit 0
fi

mkdir /app/nano
echo -n "Created dir /app/nano"
curl https://github.com/Ehryk/heroku-nano/raw/master/heroku-nano-2.5.1/nano.tar.gz --location --silent | tar xz -C /app/nano
echo -n "Downloaded heroku-nano to /app/nano"
export PATH=$PATH:/app/nano
echo -n "Added nano editor to PATH"
exit 0
