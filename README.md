UniversiBO
==========
## What is UniversiBO?
UniversiBO is a Web Community joined by Students, Professors and Staff in Alma Mater Studiorum - University of Bologna, Italy since 2002.
* Version 1 was a PHP-PostgreSQL "old style" application
* Since version 2 we migrated to eocene framework  (http://sourceforge.net/projects/eocene-php/)
* Version 3 will be a porting to Symfony 2.1 framework (http://symfony.com/)

## Running UniversiBO
You can get a working copy of UniversiBO in 6 easy steps:

1. Install virtualbox https://www.virtualbox.org/
2. Install Vagrant http://vagrantup.com/
3. Run ```vagrant plugin install vagrant-vbguest```
4. Fork this repository and clone it
5. Run ```cd vagrant && vagrant up``` and wait for Vagrant doing all the magic
6. Open your browser at http://localhost:8888/app_dev.php 

## Contributing
Feel free to fork us and send a pull request, contributed code *must* follow
PSR-2 standards, you can achieve that using this tool http://cs.sensiolabs.org/

## Continuous integration
UniversiBO uses Travis CI [![Build Status](https://travis-ci.org/UniversiBO/UniversiBO.png?branch=master)](https://travis-ci.org/UniversiBO/UniversiBO)

## License
Copyright (C) \<2002-2012\>  Associazione UniversiBO


This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
