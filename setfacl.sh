#!/bin/bash
sudo setfacl -R -m u:www-data:rwx -m u:`whoami`:rwx app/cache app/logs universibo/templates_compile/unibo/
sudo setfacl -dR -m u:www-data:rwx -m u:`whoami`:rwx app/cache app/logs universibo/templates_compile/unibo/
