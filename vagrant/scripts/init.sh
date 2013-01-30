#!/bin/bash

PUPPET_MODULES=/etc/puppet/modules

sed 's/us\.archive/it\.archive/' -i /etc/apt/sources.list

if [ `aptitude search $pkg | grep ^i | wc -l` -eq 0 ]; then
    aptitude update
    aptitude install python-software-properties --assume-yes
fi

add-apt-repository ppa:ondrej/php5
aptitude update

if [ `which git | wc -l` -eq 0 ]; then
    aptitude install git --assume-yes
fi

if [ ! -d $PUPPET_MODULES ]; then
    mkdir -p $PUPPET_MODULES
fi

function puppet_install {
    clean=`echo "$1" | sed 's/\\//-/'`
    if [ `puppet module list | grep "$clean" | wc -l` -eq 0 ]; then
        puppet module install $1
    fi
}

puppet_install 'puppetlabs/postgresql'
puppet_install 'puppetlabs/apache'
