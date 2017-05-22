<?php
use Composer\DependencyResolver\Operation\InstallOperation;
use Composer\DependencyResolver\Operation\UpdateOperation;
use Composer\Installer\PackageEvent;
use Composer\Script\Event;

class Installer
{
    public static function postPackageInstall(PackageEvent $event)
    {
        /** @var InstallOperation $operation */
        $operation = $event->getOperation();

        if ($operation->getPackage()->getName() === 'johnpbloch/wordpress') {
            self::initWordPress($event);
        }
    }

    public static function postPackageUpdate(PackageEvent $event)
    {
        /** @var UpdateOperation $operation */
        $operation = $event->getOperation();

        if ($operation->getInitialPackage()->getName() === 'johnpbloch/wordpress') {
            self::initWordPress($event);
        }
    }

    /**
     * @param $event PackageEvent|Event
     */
    public static function initWordPress($event)
    {
        $extra = $event->getComposer()->getPackage()->getExtra();

        $projectRoot = dirname(__DIR__);
        $wpDir = "{$projectRoot}/{$extra['wordpress-install-dir']}";

        // delete original plugins dir.
        $pluginsDir = "{$wpDir}/wp-content/plugins";
        if (is_dir($pluginsDir) && !is_link($pluginsDir)) {
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($pluginsDir, \FilesystemIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::CHILD_FIRST
            );
            foreach ($files as $file) {
                if ($file->isDir()) {
                    rmdir($file->getPathname());
                } else {
                    unlink($file->getPathname());
                }
            }
            rmdir($pluginsDir);
        }

        // define symlinks under wp/wp-content dir.
        $paths = array(
            array(
                'target' => '../../wp-content/themes',
                'link' => "{$wpDir}/wp-content/my-themes",
            ),
            array(
                'target' => '../../wp-content/plugins',
                'link' => "{$wpDir}/wp-content/plugins",
            ),
            array(
                'target' => '../../wp-content/uploads',
                'link' => "{$wpDir}/wp-content/uploads",
            ),
        );

        $isWin = DIRECTORY_SEPARATOR !== '/';

        // for WIN, replace directory separators.
        if ($isWin) {
            foreach ($paths as $i => $path) {
                foreach ($path as $key => $str) {
                    $paths[$i][$key] = str_replace('/', '\\', $str);
                }
            }
        }

        // create symlinks.
        foreach ($paths as $path) {
            if (!file_exists($path['link'])) {
                if ($isWin) {
                    self::symlinkForWin($path['target'], $path['link']);
                } else {
                    symlink($path['target'], $path['link']);
                }
            }
        }
    }

    private static function symlinkForWin($target, $link)
    {
        exec(sprintf('mklink /D %s %s', escapeshellarg($link), escapeshellarg($target)));
    }
}
