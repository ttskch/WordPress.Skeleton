# WordPress.Skeleton

WordPress project skeleton to focus only your own source codes.

## Installation

```bash
$ composer create-project wordpress/skeleton {project-name}
$ cd {project-name}
$ cp local-config-sample.php local-config.php
$ vi local-config.php # tailor to your environment
```

You can use Japanese or English environment.

## Usage

WordPress will be installed in `/wp/` so you should set DocumentRoot to `/path/to/project/wp/`.

And some files and directories will be symlinked after `composer install/update`, as shown below:

* `/wp/wp-content/plugins` -> `/wp-content/plugins`
* `/wp/wp-content/themes` -> `/wp-content/themes`
* `/wp/wp-config.php` -> `/wp-config.php`
* `/wp/local-config.php` -> `/local-config.php`

Then, `/wp/` and `/wp-content/plugins/` are not managed on your repository so you can focus only your own source codes in `/wp-content/themes`.

Of course you can install plugins or themes via composer as described in the next chapter.

## Installing plugins via composer

### Using WordPress Packagist

You can use [WordPress Packagist](http://wpackagist.org) to install plugins or themes via composer like below:

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
                "name": "wp-ogp-customized",
                "type": "wordpress-plugin",
                "version": "dev-master",
                "source": {
                    "type": "git",
                    "url": "git@github.com:jyokyoku/wp-ogp-customized.git",
                    "reference": "master"
                }
            }
        }
    ],
    "require": {
        "wp-ogp-customized": "dev-master"
    }
}
```

## Backing up mysqldump

`/sql/` is for backing up myspldump. You can back up WordPress database if you need.

```bash
$ mysqldump -u[user] -p [database] > sql/dump.sql
```
