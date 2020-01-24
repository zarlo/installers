#!/bin/bash

. /etc/os-release

cd /usr/local/src

if [$VERSION_ID 6]; then

wget http://centos-webpanel.com/cwp-latest -o cwp-installer 

else

wget http://centos-webpanel.com/cwp-el7-latest -o cwp-installer 

fi

sh ./cwp-installer