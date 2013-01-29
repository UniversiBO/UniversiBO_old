#!/bin/bash
aptitude -q update
gem update

PUPPET_DIR=/etc/puppet/

if [ ! -d $PUPPET_DIR ]; then
    mkdir $PUPPET_DIR
fi

if [ `which git | wc -l` -eq 0 ]; then
    aptitude install git
fi

cp /vagrant/puppet/Puppetfile $PUPPET_DIR

if [ `gem query --local | grep librarian-puppet | wc -l` -eq 0 ]; then
  gem install librarian-puppet
  cd $PUPPET_DIR && librarian-puppet install --clean
else
  cd $PUPPET_DIR && librarian-puppet update
fi
