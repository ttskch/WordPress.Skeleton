<?php
use Composer\Script\Event;

class Installer
{
    public static function postPackageInstall(Event $event = null)
    {
        if ($event->getOperation()->getPackage()->getName() === 'wordpress') {
            self::initWordPress();
        }
    }

    public static function postPackageUpdate(Event $event = null)
    {
        if ($event->getOperation()->getInitialPackage()->getName() === 'wordpress') {
            self::initWordPress();
        }
    }

    private static function initWordPress()
    {
        $projectRoot = dirname(__DIR__);

        // create symlinks under wp/wp-content dir.
        $paths = array(
            array(
                'target' => "../../wp-content/mu-plugins",
                "link" => "{$projectRoot}/wp/wp-content/my-mu-plugins",
            ),
            array(
                'target' => "../../wp-content/themes",
                "link" => "{$projectRoot}/wp/wp-content/my-themes",
            ),
        );
        foreach ($paths as $path) {
            if (!file_exists($path['link'])) {
                symlink($path['target'], $path['link']);
            }
        }

        // delete pre-installed plugins.
        $pluginsDir = "{$projectRoot}/wp/wp-content/plugins";
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($pluginsDir, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($files as $file) {
            if ($file->getPathname() === "{$pluginsDir}/index.php") {
                continue;
            } elseif ($file->isDir()) {
                rmdir($file->getPathname());
            } else {
                unlink($file->getPathname());
            }
        }
    }
}
