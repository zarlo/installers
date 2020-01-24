#!/bin/bash

apt-get update
apt-get upgrade
apt-get install -y libxml-parser-perl libpath-class-perl perl-modules screen rsync sudo e2fsprogs unzip subversion libarchive-extract-perl pure-ftpd libarchive-zip-perl libc6 libgcc1 git curl
apt-get install -y libc6-i386
apt-get install -y libgcc1:i386
apt-get install -y lib32gcc1
apt-get install -y libhttp-daemon-perl

apt-get install -y pure-ftpd libarchive-zip-perl libc6 libgcc1 git curl
apt-get install -y libc6-i386 lib32gcc1

if [ "$(grep -Ei 'debian' /etc/*release)" ]; then
   apt-get install libarchive-extract-perl
fi

wget -N "https://github.com/OpenGamePanel/Easy-Installers/raw/master/Linux/Debian-Ubuntu/ogp-agent-latest.deb" -O "ogp-agent-latest.deb"
dpkg -i "ogp-agent-latest.deb"

