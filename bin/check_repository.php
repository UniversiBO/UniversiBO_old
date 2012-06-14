<?php

$repoPath = realpath(__DIR__.'/../src/Universibo/Bundle/LegacyBundle');

function find_entities_rec($path, array &$found)
{
    if (is_file($path)) {
        if(preg_match('/\\.php$/', $path)) {
            $found[]= $path;
        }
    } elseif (is_dir($path)) {
        $dir = dir($path);
        
        while(false !== ($file = $dir->read())) {
            if($file !== '.' && $file !== '..') { 
                find_entities_rec($path . DIRECTORY_SEPARATOR . $file, $found);
            }            
        }
        
        $dir->close();
    }
}

function find_entities($path)
{
    $found = array();
    find_entities_rec($path, $found);
    
    sort($found);
    
    return $found;
}

function check_entity($file)
{
    $dbCalls = `egrep -ni 'getDbConnection|DBRepository' $file`;
    if(strlen($dbCalls) > 0) {
        echo 'File ', $file, PHP_EOL, $dbCalls;
    } 
}

$found = find_entities($repoPath);
foreach($found as $entity) {
   check_entity($entity);
}
