<?php
namespace Skeleton\Scripts;

use Composer\Script\Event;

class Linker
{
    public static function symlink(Event $event = null)
    {
        $projectRoot = dirname(__DIR__);

        $paths = [
            [
                'target' => "{$projectRoot}/wp-config.php",
                "link" => "{$projectRoot}/wp/wp-config.php",
            ],
            [
                'target' => "{$projectRoot}/local-config.php",
                "link" => "{$projectRoot}/wp/local-config.php",
            ],
            [
                'target' => "{$projectRoot}/wp-content/plugins",
                "link" => "{$projectRoot}/wp/wp-content/plugins",
            ],
            [
                'target' => "{$projectRoot}/wp-content/themes",
                "link" => "{$projectRoot}/wp/wp-content/themes",
            ],
        ];

        foreach ($paths as $path) {
            if (!is_link($path['link']) && is_dir($path['link'])) {
                rename($path['link'], $path['link'] . '.org');
            } elseif (file_exists($path['link'])) {
                unlink($path['link']);
            }
            symlink($path['target'], $path['link']);
        }
    }
}
