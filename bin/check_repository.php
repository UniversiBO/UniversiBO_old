<?php

$repoPath = realpath(__DIR__.'/../src/Universibo/Bundle/LegacyBundle/Entity');

function find_entities_rec($path, array &$found)
{
    if (is_file($path)) {
        if(preg_match('/php$/', $path)) {
            $type = preg_match('/DB.*Repository/', $path) ? 'repository' : 'entity';
            $found[$type][] = $path;
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
    
    sort($found['entity']);
    sort($found['repository']);
    
    return $found;
}

function check_entity($file)
{
    $dbCalls = `grep -ni getDbConnection $file`;
    if(strlen($dbCalls) > 0) {
        echo 'File ', $file, PHP_EOL, $dbCalls;
    } 
}

$found = find_entities($repoPath);
foreach($found['entity'] as $entity) {
   check_entity($entity);
}

foreach($found['repository'] as $repository) {
   check_repository($repository);
}


function check_repository($file)
{
    if(preg_match('/DB.*Repository/', $file, $matches)) { 
        $repoName = $matches[0];
        $otherRepo = `grep -n Repository $file | grep new | grep -v $repoName`;

        if(strlen($otherRepo) > 0) {
            echo 'File ', $file, PHP_EOL, $otherRepo;
        }
    }
}
