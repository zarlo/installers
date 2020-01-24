#!/bin/bash

cd /root
wget http://www.webmin.com/jcameron-key.asc
apt-key add jcameron-key.asc

echo "deb https://download.webmin.com/download/repository sarge contrib" > /etc/apt/sources.list.d/webmin.list

apt-get install apt-transport-https
apt-get update
apt-get install webmin
