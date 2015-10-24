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

        // create symlink under wp/wp-content dir.
        $path = array(
            'target' => "../../wp-content/themes",
            "link" => "{$projectRoot}/wp/wp-content/my-themes",
        );
        if (!file_exists($path['link'])) {
            symlink($path['target'], $path['link']);
        }
    }
}
