# WordPress.Skeleton

[![Latest Stable Version](https://poser.pugx.org/wordpress/skeleton/v/stable.svg)](https://packagist.org/packages/wordpress/skeleton)
[![Total Downloads](https://poser.pugx.org/wordpress/skeleton/downloads.svg)](https://packagist.org/packages/wordpress/skeleton)

WordPress project skeleton to focus only your own source codes. Inspired by [markjaquith/WordPress-Skeleton](https://github.com/markjaquith/WordPress-Skeleton).

Advantages compared to WordPress-Skeleton:

* You can install via `composer create-project`.
* You don't need to do `git submodule init/update`. (so installing is very fast)
* You can set `/wp/` as DocumentRoot. (in other words, **you can hide "/wp/" from url**)
* It works even on descendant directory of DocumentRoot. (you can get it to work casually ,without vhost settings, for local development)

## Requirements

* PHP 5.3+
* Linux or OSX only

## Installation

```bash
$ composer create-project wordpress/skeleton {project-name}
$ cd {project-name}
$ cp local-config-sample.php local-config.php
$ vi local-config.php # tailor to your environment
```

You can use Japanese or English environment as you like.

## Usage

WordPress core will be installed in `/wp/` so root directory of your website will be `/wp/`. (e.g. "http://example.com/project-name/wp/")

If you want to hide `/wp/` from URL you should set DocumentRoot to `/path/to/project/wp/`.

Now you can create your own theme in `/wp-content/themes/` and install some plugins into `/wp/wp-content/plugins/` via composer (as described in the next chapter).
And your git repository doesn't manage `/wp/` so **you can focus only your own source codes** in `/wp-content/themes`.

## Installing plugins via composer

### Using WordPress Packagist

You can use [WordPress Packagist](http://wpackagist.org) to install plugins (or themes) via composer like below:

```json
{
    "require": {
        "wpackagist-plugin/akismet": "dev-trunk",
        "wpackagist-plugin/captcha": ">=3.9",
        "wpackagist-theme/hueman": "*"
    }
}
```

### Installing plugins form GitHub or zip file

You can also install some plugins (which isn't on WordPress.org) from GitHub repository, zip file, and so on.
To do that you should add package with `"type": "wordpress-plugin"` and require it like below:

```json
{
    "repositories": [
        {
            "type": "package",
            "package": {
                "name": "something-on-github",
                "type": "wordpress-plugin",
                "version": "dev-master",
                "source": {
                    "type": "git",
                    "url": "git@github.com:someone/something.git",
                    "reference": "master"
                }
            }
        },
        {
            "type": "package",
            "package": {
                "name": "something-of-zip",
                "type": "wordpress-plugin",
                "version": "1.0",
                "dist": {
                    "type": "zip",
                    "url": "http://something.com/download/1.0.zip"
                }
            }
        }
    ],
    "require": {
        "something-on-github": "dev-master",
        "something-of-zip": "1.0"
    }
}
```

## Backing up database and uploaded files

`/backup/` directory is just for saving (and version-managing) database and uploaded files. If you need, you can save them here like below:

```bash
$ mysqldump -u[user] -p [database] > backup/dump.sql
```

```bash
$ zip -r backup/uploads.zip wp/wp-content/uploads
```

## Mechanism, FYI

After install/update "wordpress" package, two symlinks will be created in `/wp/` environment as shown below:

* `/wp/wp-content/my-mu-plugins` -> `/wp-content/mu-plugins`
* `/wp/wp-content/my-themes` -> `/wp-content/themes`

And `/wp/wp-content/my-mu-plugins` and `/wp/wp-content/my-themes` will be used automatically because of customizing constant of `WPMU_PLUGIN_DIR` and executing `register_theme_directory`.

Just to tell you, `/wp-config.php` (and `/local-config.php`) need not be symlinked into `/wp/` because they will loaded from `/wp/wp-load.php` during WordPress' normal booting process.
