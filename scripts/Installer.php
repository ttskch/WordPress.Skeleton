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

        // create symlinks under wp/wp-content dir.
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
                'target' => '../../backup/uploads',
                'link' => "{$projectRoot}/wp/wp-content/uploads",
            )
        );
        foreach ($paths as $path) {
            if (!file_exists($path['link'])) {
                symlink($path['target'], $path['link']);
            }
        }
    }
}
