#!/usr/bin/env php
<?php

$conn = new PDO('pgsql:dbname=universibo;user=universibo;password=universibo');
$result = $conn->query('SELECT COUNT(*) FROM pg_tables WHERE schemaname = \'public\' AND tablename = \'fos_user\'');

echo $result->fetchColumn(), PHP_EOL;
