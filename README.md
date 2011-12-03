PHPChristmasMiracle
===================

This application was created to accompany a presentation a
[CodeWorks](http://codeworks.phparch.com/).

The purpose is two-fold:

1. To highlight how similar frameworks are and how they can be used interchangeably

2. To show how an app evolves from a flat PHP app into a reponsibly-organized
    mini framework

Stages
------

This project is organized into six different stages, each representing one
stage of the evolution of the app. You can checkout to different stages to
see how things looked:

* `start` - A flat, PHP4-style application

* `stage2` - Introduction of autoloader and Request and Response classes

* `stage3` - Introduction of a router

* `stage4` - Introduction of a dependency injection container

* `stage5` - Introduction of ZF2 logger

* `stage6` - Reorganization of the project's structure

Usage
-----

To use the app, clone it, check out to whichever branch you want, and do
the following:

* Initialize the database via `php data/setupDb.php`

* Pull down the submodules:

    git submodule init
    git submodule update

If you have any permissions problems, run the following:

    chmod 777 data/database.sqlite
    chmod 777 data/web.log

Enjoy!