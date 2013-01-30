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

    exec { 'pgsql-md5-auth':
        command => "sed 's/^local.*all.*all.*ident/local   all         all                               md5/' -i /etc/postgresql/9.1/main/pg_hba.conf",
        notify => Service['postgresqld']
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
        docroot_owner   => 'vagrant',
        docroot_group   => 'vagrant',
        logroot         => '/var/log',
        override        => 'All'
    }
}
