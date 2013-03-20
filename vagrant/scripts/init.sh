#!/bin/bash

PUPPET_MODULES=/etc/puppet/modules

sed 's/us\.archive/it\.archive/' -i /etc/apt/sources.list
apt-key add /vagrant/vagrant/resources/keyring/ondrej && cp /vagrant/vagrant/resources/app/etc/apt/sources.list.d/ondrej-php5.list /etc/apt/sources.list.d/
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
puppet_install 'attachmentgenie/locales'
