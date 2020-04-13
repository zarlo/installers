#!/bin/bash

echo "[Webmin]
name=Webmin Distribution Neutral
#baseurl=https://download.webmin.com/download/yum
mirrorlist=https://download.webmin.com/download/yum/mirrorlist
enabled=1" > /etc/yum.repos.d/webmin.repo

wget http://www.webmin.com/jcameron-key.asc
rpm --import jcameron-key.asc
yum install webmin