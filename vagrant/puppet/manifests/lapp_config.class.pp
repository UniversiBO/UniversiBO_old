#include postgresql::server

class lapp_config
{
#    postgresql::db { 'universibo':
#        user     => 'universibo',
#        password => 'universibo'
#    }

    exec { 'allow-all':
        command => "sed 's/.*allow from 127.*/Allow from All/i' -i /etc/apache2/conf.d/phppgadmin"
    }

    exec { 'enable-modules':
        command => 'a2enmod rewrite rpaf' 
    }

    exec { 'reload':
        command => 'apache2ctl graceful',
        require => Exec['allow-all', 'enable-modules']
    }

    exec { 'init-db': 
        command => "su - postgres -c 'psql -f /vagrant/vagrant/sql/init.sql'"
    }
}
