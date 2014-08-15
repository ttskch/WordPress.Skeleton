<?php
namespace Skeleton\Scripts;

use Composer\Script\Event;

class Installer
{
    public static function postInstall(Event $event = null)
    {
        $projectRoot = dirname(__DIR__);
        $projectName = (new \SplFileInfo($projectRoot))->getFilename();

        // delete old composer.json and composer.lock.
        unlink("{$projectRoot}/composer.json");
        unlink("{$projectRoot}/composer.lock");

        // put new composer.json.
        $newJson = "{$projectRoot}/scripts/composer.json";
        $content = file_get_contents($newJson);
        $content = str_replace('{project-name}', strtolower($projectName), $content);
        file_put_contents($newJson, $content);
        rename($newJson, "{$projectRoot}/composer.json");

        // delete self.
        unlink(__FILE__);
    }
}
