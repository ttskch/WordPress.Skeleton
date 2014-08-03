# WordPress.Skeleton

WordPress project skeleton to focus only your own source codes.

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

Now you can create your own theme in `/wp-content/themes/` and install some plugins into `/wp-content/plugins/`.
And your git repository doesn't manage `/wp/` and `/wp-content/plugins/` so **you can focus only your own source codes** in `/wp-content/themes`.

Of course you can install plugins (or themes) via composer as described in the next chapter.

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

## Backing up mysqldump

`/sql/` is for backing up myspldump. You can back up WordPress database if you need.

```bash
$ mysqldump -u[user] -p [database] > sql/dump.sql
```

## Mechanism, FYI

Your own contents will be symlinked after `composer install/update`, as shown below:

* `/wp/wp-content/my-mu-plugins` -> `/wp-content/mu-plugins`
* `/wp/wp-content/my-plugins` -> `/wp-content/plugins`
* `/wp/wp-content/my-themes` -> `/wp-content/themes`

`/wp/wp-content/my-mu-plugins`, `/wp/wp-content/my-plugins` and `/wp/wp-content/my-themes` will be used automatically because of customizing constants of `WPMU_PLUGIN_DIR` and `WP_PLUGIN_DIR` and executing `register_theme_directory`.

Just to tell you, `/wp-config.php` (and `/local-config.php`) need not be symlinked into `/wp/` because they will loaded from `/wp/wp-load.php` during WordPress' normal booting process.
