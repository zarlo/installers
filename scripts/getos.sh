#!/bin/bash

if [ ! -x /usr/bin/wget ] ; then

    if [ -f /etc/lsb-release ]; then
        apt install -y wget
    fi

    if [ -f /etc/redhat-release ]; then
        yum intall wget
    fi

fi

function getInstaller()
{
    wget {{ domain }}$1 | sh
    exit 0
}



{% if get.deb %}

if [ -f /etc/lsb-release ]; then
    getInstaller {{ get.deb }}
fi

{% endif %}

{% if get.rpm %}

if [ -f /etc/redhat-release ]; then
    getInstaller {{ get.rpm }}
fi

{% endif %}