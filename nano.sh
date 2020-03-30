#!/bin/bash env

test -r /app/ || echo "Not in heroku remote" && exit 0
mkdir /app/nano
curl https://github.com/Ehryk/heroku-nano/raw/master/heroku-nano-2.5.1/nano.tar.gz --location --silent | tar xz -C /app/nano
export PATH=$PATH:/app/nano
