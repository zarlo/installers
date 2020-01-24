#!/bin/bash
mkdir -p /srv && cd /srv
curl -L -o pufferpanel.tar.gz https://git.io/fNZYg
tar -xf pufferpanel.tar.gz
cd pufferpanel 
chmod +x pufferpanel
./pufferpanel install