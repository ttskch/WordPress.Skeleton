<?php
namespace Skeleton\Scripts;

use Composer\Script\Event;

class Installer
{
    public static function postInstall(Event $event = null)
    {
        $projectRoot = dirname(__DIR__);
        $projectName = (new \SplFileInfo($projectRoot))->getFilename();

        // composer.json.
        $src = "{$projectRoot}/scripts/composer.json";
        $dst = "{$projectRoot}/composer.json";
        $content = file_get_contents($src);
        $content = str_replace('{project-name}', strtolower($projectName), $content);
        file_put_contents($src, $content);
        unlink($dst);
        rename($src, $dst);

        // delete self.
        unlink(__FILE__);
    }
}
