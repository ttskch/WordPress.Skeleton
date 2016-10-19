<?php
use Composer\DependencyResolver\Operation\InstallOperation;
use Composer\DependencyResolver\Operation\UpdateOperation;
use Composer\Installer\PackageEvent;

class Installer
{
    public static function postPackageInstall(PackageEvent $event = null)
    {
        /** @var InstallOperation $operation */
        $operation = $event->getOperation();

        if ($operation->getPackage()->getName() === 'johnpbloch/wordpress') {
            self::initWordPress();
        }
    }

    public static function postPackageUpdate(PackageEvent $event = null)
    {
        /** @var UpdateOperation $operation */
        $operation = $event->getOperation();

        if ($operation->getInitialPackage()->getName() === 'johnpbloch/wordpress') {
            self::initWordPress();
        }
    }

    private static function initWordPress()
    {
        $projectRoot = dirname(__DIR__);

        // delete original plugins dir.
        $pluginsDir = "{$projectRoot}/wp/wp-content/plugins";
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

        // define symlinks under wp/wp-content dir.
        $paths = array(
            array(
                'target' => '../../wp-content/themes',
                'link' => "{$projectRoot}/wp/wp-content/my-themes",
            ),
            array(
                'target' => '../../wp-content/plugins',
                'link' => "{$projectRoot}/wp/wp-content/plugins",
            ),
            array(
                'target' => '../../wp-content/uploads',
                'link' => "{$projectRoot}/wp/wp-content/uploads",
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
