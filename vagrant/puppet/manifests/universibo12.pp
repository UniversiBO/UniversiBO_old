$postgres_default_version = '9.1'

import 'lapp_packages.class.pp'
import 'lapp_config.class.pp'

group { "puppet":
  ensure => "present",
}

File { owner => 0, group => 0, mode => 0644 }

file { '/etc/motd':
  content => "Welcome to UniversiBO Development Kit
         Managed by Vagrant & Puppet.\n"
}

Exec { path => [ "/bin/", "/sbin/" , "/usr/bin/", "/usr/sbin/", "/usr/local/sbin", "/usr/local/bin", "/opt/vagrant_ruby/bin" ] }

Class['lapp_packages']->Class['lapp_config']

include lapp_packages
include lapp_config
