#!/bin/bash
mkdir -p /srv/daemon /srv/daemon-data
cd /srv/daemon

curl -L https://github.com/pterodactyl/daemon/releases/download/v0.6.12/daemon.tar.gz | tar --strip-components=1 -xzv

npm install --only=production

