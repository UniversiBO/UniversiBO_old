include postgresql::server

class {'apache': }

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

    service { 'varnish':
        ensure  => running,
        enable => true,
        require => Package['varnish']
    }

    file { '/etc/apache2/conf.d/user':
        content => "User vagrant\nGroup vagrant"
    }

    file { 'varnish-conf':
        path   => '/etc/varnish/default.vcl',
        ensure => present,
        source => '/vagrant/vagrant/resources/app/etc/varnish/default.vcl',
    }
    
    file { 'varnish-default':
        path   => '/etc/default/varnish',
        ensure => present,
        source => '/vagrant/vagrant/resources/app/etc/default/varnish',
    }

    # Notify is not enough
    exec {'varnish-restart':
        command => 'service varnish restart',
        require => File['varnish-conf', 'varnish-default']
    }
    
    file {'apache-ports':
        path   => '/etc/apache2/ports.conf',
        ensure => present,
        source => '/vagrant/vagrant/resources/app/etc/apache2/ports.conf'
    }

    exec {'allow-all':
        command => "sed 's/.*allow from 127.*/Allow from All/i' -i /etc/apache2/conf.d/phppgadmin"
    }

    exec {'ports.conf':
        command => "sed '/^NameVirtualHost/d' -i /etc/apache2/ports.conf",
        unless => "test `grep ^NameVirtualHost /etc/apache2/ports.conf | wc -l` -eq 0",
        notify  => Service["apache2"]
    }

    exec {'enable-modules':
        command => 'a2enmod rewrite rpaf' 
    }

    exec {'reload':
        command => 'apache2ctl graceful',
        require => Exec['allow-all', 'ports.conf', 'enable-modules']
    }

    apache::vhost { 'default':
        priority        => '10',
        vhost_name      => '*',
        port            => '8000',
        docroot         => '/vagrant/web',
        docroot_owner   => 'vagrant',
        docroot_group   => 'vagrant',
        logroot         => '/var/log',
        override        => 'All'
    }

    exec {'timezone':
        command => "sed 's/;date.timezone.*/date.timezone = Europe\\/Rome/' -i /etc/php5/*/php.ini",
        unless => "test `grep timezone /etc/php5/*/php.ini | grep Rome | wc -l` -gt 0",
        notify  => Service["apache2"],
    }

    exec {'short_open_tag':
        command => "sed 's/short_open_tag = On/short_open_tag = Off/' -i /etc/php5/*/php.ini",
        unless => "test `grep short_open_tag /etc/php5/*/php.ini | grep On | wc -l` -eq 0",
        notify  => Service["apache2"],
    }

    exec {'xdebug-nesting':
        cwd => '/etc/php5/mods-available/',
        command => "echo 'xdebug.max_nesting_level = 250' | tee -a xdebug.ini",
        unless => "test `grep nesting xdebug.ini | wc -l` -gt 0",
        notify  => Service["apache2"],
    }
}
