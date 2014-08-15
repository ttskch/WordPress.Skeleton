<?php
namespace Skeleton\Scripts;

use Composer\Script\Event;

class Linker
{
    public static function postInstallWordPress(Event $event = null)
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

        // create wp/wp-content/my-plugins dir.
        $myPluginsDirPath = "{$projectRoot}/wp/wp-content/my-plugins";
        if (!file_exists($myPluginsDirPath)) {
            mkdir($myPluginsDirPath);
            touch("{$myPluginsDirPath}/index.php");
        }
    }
}
