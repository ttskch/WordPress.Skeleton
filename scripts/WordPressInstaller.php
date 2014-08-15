<?php
use Composer\Script\Event;

class WordPressInstaller
{
    public static function postPackageInstall(Event $event = null)
    {
        $installedPackageName = $event->getOperation()->getPackage()->getName();
        if ($installedPackageName !== 'wordpress') {
            return;
        }

        $projectRoot = dirname(__DIR__);

        // create symlinks under wp/wp-content dir.
        $paths = [
            [
                'target' => "{$projectRoot}/wp-content/mu-plugins",
                "link" => "{$projectRoot}/wp/wp-content/my-mu-plugins",
            ],
            [
                'target' => "{$projectRoot}/wp-content/themes",
                "link" => "{$projectRoot}/wp/wp-content/my-themes",
            ],
        ];
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
