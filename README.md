UniversiBO
==========
## What is UniversiBO?
UniversiBO is a Web Community joined by Students, Professors and Staff in Alma Mater Studiorum - University of Bologna, Italy since 2002.
* Version 1 was a PHP-PostgreSQL "old style" application
* Since version 2 we migrated to eocene framework  (http://sourceforge.net/projects/eocene-php/)
* We're working on version 3 which is a porting to Symfony 2.3 framework (http://symfony.com/), we still have a lot of legacy code :(

## Running UniversiBO
You can get a working copy of UniversiBO in 6 easy steps:

1. Install virtualbox https://www.virtualbox.org/
2. Install Vagrant http://vagrantup.com/
3. Run ```vagrant plugin install vagrant-vbguest```
4. Fork this repository and clone it
5. Run ```cd vagrant && vagrant up``` and wait for Vagrant doing all the magic
6. Open your browser at http://localhost:8888/app_dev.php 

## Contributing
Feel free to [fork this repository](https://help.github.com/articles/fork-a-repo) and send us pull request, 
contributed code *must* follow PSR-2 standards, you can use [PHP Coding Standards Fixer](http://cs.sensiolabs.org/) to check your code.
You can automate that task just copying hooks/pre-commit file to your .git/hooks directory: this will prevent you from committing non-compliant code (not working on Windows).
This project is currently licensed under GPLv2, since we're considering a license switch, by contributing you accept that code might be licensed under GPLv3 or Affero GPL later.

## Past contributors
Unfortunately we couldn't migrate the whole history from our [Subversion Repository](https://sourceforge.net/p/universibo/svn/)
A special thank to [iliasbartolini](https://github.com/iliasbartolini) who founded and led the project in its first years of development (560 commits) together with
Andrea and Matteo who worked hard on management side and to the other (around) 200 people involved in this project over the years.

### Contributors from Subversion history (SourceForge username)
* [iliasbartolini](https://github.com/iliasbartolini) (560 commits)
* evaimitico / evaimitico-good (394 commits)
* lasthope83 (137 commits)
* dvbellet / [dbellettini](https://github.com/dbellettini) (100 commits)
* roby_46 (24 commits)
* mel82 (4 commits)
* tntimo (4 commits)
* greatkris (3 commits)
* giorgitus (1 commit)
* iceblack (1 commit)

## Continuous integration
UniversiBO uses Travis and Scrutinizer
[![Build Status](https://travis-ci.org/UniversiBO/UniversiBO.png?branch=master)](https://travis-ci.org/UniversiBO/UniversiBO)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/UniversiBO/UniversiBO/badges/quality-score.png?s=845b48fffede63a081c6cf03bba85ef3e7fede59)](https://scrutinizer-ci.com/g/UniversiBO/UniversiBO/)
[![Code Coverage](https://scrutinizer-ci.com/g/UniversiBO/UniversiBO/badges/coverage.png?s=6ce13ce595f42cfb5a92f70b070e9c19689e03f3)](https://scrutinizer-ci.com/g/UniversiBO/UniversiBO/)

## License
Copyright (C) \<2002-2013\>  Associazione UniversiBO

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


[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/UniversiBO/universibo/trend.png)](https://bitdeli.com/free "Bitdeli Badge")

