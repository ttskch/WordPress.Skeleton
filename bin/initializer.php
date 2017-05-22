<?php
$projectRoot = dirname(__DIR__);
$splFile = new \SplFileInfo($projectRoot);
$projectName = $splFile->getFilename();

// tweak composer.json.
$jsonPath = "{$projectRoot}/composer.json";
$jsonArray = json_decode(file_get_contents($jsonPath), true);
$jsonArray['name'] = "vendor/{$projectName}";
$jsonArray['description'] = '';
$jsonArray['keywords'] = array();
unset($jsonArray['homepage']);
unset($jsonArray['authors']);
unset($jsonArray['require-dev']);
unset($jsonArray['scripts']['post-create-project-cmd']);
$newJsonString = json_encode($jsonArray, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
file_put_contents($jsonPath, $newJsonString);

// clean up.
unlink("{$projectRoot}/composer.lock");
unlink(__FILE__);
rmdir(__DIR__);
