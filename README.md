PHPChristmasMiracle
===================

This application was created to accompany a presentation at
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

        git checkout -b start origin/start

* `stage2` - Introduction of autoloader and Request and Response classes

        git checkout -b stage2-http-foundation origin/stage2-http-foundation

* `stage3` - Introduction of a router

        git checkout -b stage3-routing origin/stage3-routing

* `stage4` - Introduction of a dependency injection container

        git checkout -b stage4-container origin/stage4-container

* `stage5` - Introduction of ZF2 logger

        git checkout -b stage5-zf2-logger origin/stage5-zf2-logger

* `stage6` - Reorganization of the project's structure

        git checkout -b stage6-dir-structure origin/stage6-dir-structure

Usage
-----

To use the app, clone it, check out to whichever branch you want, and do
the following:

* Clone the repository

    git clone git://github.com/weaverryan/PHPChristmasMiracle.git

* Initialize the database via `php data/setupDb.php`

* Run Composer

    composer install

If you have any permissions problems, run the following:

    chmod 777 data/database.sqlite
    chmod 777 data/web.log

Enjoy!
