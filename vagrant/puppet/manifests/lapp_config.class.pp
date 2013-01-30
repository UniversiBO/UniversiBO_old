include postgresql::server

class {'apache':  }

class lapp_config
{
    postgresql::db { 'universibo':
        user     => 'universibo',
        password => 'universibo'
    }

    postgresql::db { 'universibo_forum3':
        user     => 'universibo',
        password => 'universibo'
    }

    file { '/etc/apache2/conf.d/user':
        content => "User vagrant\nGroup vagrant"
    }

    exec { 'allow-all':
        command => "sed 's/.*allow from 127.*/Allow from All/i' -i /etc/apache2/conf.d/phppgadmin"
    }

    exec { 'ports.conf':
        command => "sed '/^NameVirtualHost/d' /etc/apache2/ports.conf"
    }

    exec { 'enable-modules':
        command => 'a2enmod rewrite rpaf' 
    }

    exec { 'reload':
        command => 'apache2ctl graceful',
        require => Exec['allow-all', 'ports.conf', 'enable-modules']
    }

    apache::vhost { 'default':
        priority        => '10',
        vhost_name      => '*',
        port            => '80',
        docroot         => '/vagrant/web',
        logroot         => '/vagrant/app/logs',
        serveradmin     => 'webmaster@example.com',
        override        => 'All'
    }
}
