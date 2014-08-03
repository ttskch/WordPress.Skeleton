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
                'target' => "{$projectRoot}/wp-content/plugins",
                "link" => "{$projectRoot}/wp/wp-content/my-plugins",
            ],
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
    }
}
