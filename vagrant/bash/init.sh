#!/bin/bash

export LANGUAGE=it_IT.UTF-8
export LANG=it_IT.UTF-8 
export LC_TYPE=it_IT.UTF-8 
export LC_ALL=it_IT.UTF-8

export PATH="/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin"

/usr/sbin/locale-gen it_IT.UTF-8
/usr/sbin/update-locale LANG=it_IT.UTF-8
/usr/sbin/dpkg-reconfigure locales

aptitude update

if [ `which git | wc -l` -eq 0 ]; then
    aptitude install git
fi

function puppet_install {
    clean=`echo "$1" | sed 's/\\//-/'`
    if [ `puppet module list | grep "$clean" | wc -l` -eq 0 ]; then
        puppet module install $1
    fi
}

puppet_install 'puppetlabs/postgresql'
puppet_install 'puppetlabs/apache'