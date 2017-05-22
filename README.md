# WordPress.Skeleton

![image](https://cloud.githubusercontent.com/assets/4360663/15803328/f4f2f660-2b12-11e6-8d4e-59461e223640.png)

[![Latest Stable Version](https://poser.pugx.org/wordpress/skeleton/v/stable.svg)](https://packagist.org/packages/wordpress/skeleton)
[![Total Downloads](https://poser.pugx.org/wordpress/skeleton/downloads.svg)](https://packagist.org/packages/wordpress/skeleton)

WordPress project skeleton to focus only your own source codes because of composer-friendly design. Inspired by [markjaquith/WordPress-Skeleton](https://github.com/markjaquith/WordPress-Skeleton).

Advantages compared to WordPress-Skeleton:

* You can install via `composer create-project`.
* You can add plugins via `composer require/install`.
* You don't need to do `git submodule init/update`. (so installing is very fast)
* You can set `/wp/` as DocumentRoot. (in other words, **you can hide "/wp/" from url**)
* It works even on descendant directory of DocumentRoot. (you can get it to work casually ,without vhost settings, for local development)
* All languages will be installed by default.

## Requirements

* PHP 5.3+

## Installation

```sh
$ composer create-project wordpress/skeleton {project-name}
$ cd {project-name}
$ cp local-config-sample.php local-config.php
$ vi local-config.php # tailor to your environment
```

You can use Japanese or English environment as you like.

### Note: For Windows

On Windows environment, maybe you need to use console (like cmd.exe) as an administrator user for creating symlink.

If you still have any symlink related problem, please create-project in following way :bow:

```sh
$ composer create-project wordpress/skeleton {project-name} --no-scripts
$ cd {project-name}
$ mklink /D wp\wp-content\my-themes ..\..\wp-content\themes # or create symlink in some way
$ mklink /D wp\wp-content\uploads ..\..\wp-content\uploads # or create symlink in some way
$ rm -rf wp/wp-content/plugins
$ mklink /D wp\wp-content\plugins ..\..\wp-content\plugins # or create symlink in some way
$ cp local-config-sample.php local-config.php
$ vi local-config.php # tailor to your environment
```

## Usage

WordPress core will be installed in `/wp/` so root directory of your website will be `/wp/`. (e.g. "http://example.com/project-name/wp/")

If you want to hide `/wp/` from URL you should set DocumentRoot to `/path/to/project/wp/`.

Now you can create your own theme in `/wp-content/themes/` and install some plugins into `/wp-content/plugins/` via composer (as described in the next chapter).
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

```sh
$ mysqldump -u[user] -p [database] > backup/dump.sql
```

```sh
$ zip -r backup/uploads.zip wp/wp-content/uploads
```

## Mechanism, FYI

After install/update "wordpress" package, a symlink will be created in `/wp/` environment as shown below:

* `/wp/wp-content/my-themes` -> `/wp-content/themes`

And on WordPress's booting process, `/wp/wp-content/my-theme` will be enabled as an additional theme directory by following process:

1. `WPMU_PLUGIN_DIR` points `/wp-content/mu-plugins` because of customizing in `/wp-config.php`.
2. In `/wp-content/mu-plugins/add-skeleton-theme-directory.php`, theme directory is added with `register_theme_directory()` function.

Just to tell you, `/wp-config.php` (and `/local-config.php`) need not be symlinked into `/wp/` because they will loaded from `/wp/wp-load.php` during WordPress' normal booting process.

## Commonly-used plugins

* [TinyMCE Advanced](https://wordpress.org/plugins/tinymce-advanced/)
* [Google XML Sitemaps](https://wordpress.org/plugins/google-sitemap-generator/)
* [Acunetix Secure WordPress](https://wordpress.org/plugins/secure-wordpress/)
* [Simple Local Avatars](https://wordpress.org/plugins/simple-local-avatars/)
* [User Role Editor](https://wordpress.org/plugins/user-role-editor/)
* ~~[jyokyoku/wp-ogp-customized](https://github.com/jyokyoku/wp-ogp-customized)~~
    * Doesn't work on PHP 7. Use [ttskch/wp-ogp-customized](https://github.com/ttskch/wp-ogp-customized) instead.
