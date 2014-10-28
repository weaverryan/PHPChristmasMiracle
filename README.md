PHP Camp (or the Great Framework Kumbaya)
=========================================

This application was created to accompany a presentation called "The Great Framework Kumbaya"

The purpose is two-fold:

1. To highlight how similar frameworks are and how they can be used interchangeably

2. To show how an app evolves from a flat PHP app into a responsibly-organized
    mini framework

Start and End Code
------------------

The starting code is on the `master` branch. Hmm, simple enough.

The finished code is on the `finished` branch. Again, that makes good sense :).

Usage
-----

To use the app, clone it, check out to whichever branch you want, and do
the following:

* Clone this repository

* Run Composer

```bash
composer install
```

* Initialize the database via `php data/setupDb.php`

* Run the build-in web server:

```bash
php -S localhost:8000
```

Now put that address in your browser!

If you have any permissions problems, run the following:

```bash
chmod 777 data/database.sqlite
chmod 777 data/web.log
```

Enjoy!
