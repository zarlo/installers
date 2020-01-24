#!/bin/bash
apt-get install software-properties-common python-software-properties
add-apt-repository ppa:certbot/certbot
apt-get update
apt-get install python-certbot-apache