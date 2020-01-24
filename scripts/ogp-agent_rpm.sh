#!/bin/bash
. /etc/os-release

yum -y update
yum -y install epel-release wget subversion git proftpd proftpd-utils perl-ExtUtils-MakeMaker glibc.i686 glibc libgcc_s.so.1 perl-IO-Compress-Bzip2

if [$VERSION_ID 6]; then

    yum install -y perl-libwww-perl proftpd
    sed -i "s/^LoadModule\( \)*mod_auth_file.c/#LoadModule mod_auth_file.c/g" "/etc/proftpd.conf"
    service proftpd restart

else

    yum install -y perl-HTTP-Daemon perl-LWP-Protocol-http10 

fi

wget -N "https://github.com/OpenGamePanel/Easy-Installers/raw/master/Linux/CentOS/ogp_agent_rpm-1.0.0-1.noarch.rpm" -O "ogp_agent.rpm"
yum install -y "ogp_agent.rpm"
