include postgresql::server

class {'apache': 
    default_vhost => false,
    mpm_module => 'prefork'
}

class { 'apache::mod::php': }
apache::mod { 'rpaf': }
apache::mod { 'rewrite': }

class lapp_config
{
    postgresql::server::db { 'universibo':
        user     => 'universibo',
        password => 'universibo'
    }

    postgresql::server::db { 'universibo_forum3':
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
    exec { 'varnish-restart': 
        command => 'service varnish restart',
        require => File['varnish-conf', 'varnish-default']
    }
    
#    file { 'apache-ports':
#        path   => '/etc/apache2/ports.conf',
#        ensure => present,
#        source => '/vagrant/vagrant/resources/app/etc/apache2/ports.conf'
#    }

#    exec { 'allow-all':
#        command => "sed 's/.*allow from 127.*/Allow from All/i' -i /etc/apache2/conf.d/phppgadmin"
#    }

    exec { 'ports.conf':
        command => "sed '/^NameVirtualHost/d' /etc/apache2/ports.conf"
    }

    exec { 'reload':
        command => 'apache2ctl graceful',
#        require => Exec['allow-all', 'ports.conf', 'enable-modules']
        require => Exec['ports.conf']
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
}
