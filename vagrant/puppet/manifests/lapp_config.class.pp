class {'apache':  }

class lapp_config
{
#    postgresql::db { 'universibo':
#        user     => 'universibo',
#        password => 'universibo'
#    }

    file { '/etc/apache2/conf.d/user':
        content => "User vagrant\nGroup vagrant"
    }

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

    apache::vhost { 'default':
        priority        => '10',
        vhost_name      => '192.168.33.10',
        port            => '80',
        docroot         => '/vagrant/web',
        logroot         => '/vagrant/app/logs',
        serveradmin     => 'webmaster@example.com',
        override        => 'All'
    }
}
