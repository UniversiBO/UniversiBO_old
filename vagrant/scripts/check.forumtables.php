#!/usr/bin/env php
<?php

$conn = new PDO('pgsql:dbname=universibo_forum3;user=universibo;password=universibo');
$result = $conn->query('SELECT COUNT(*) FROM pg_tables WHERE schemaname = \'public\' AND tablename = \'phpbb_forums\'');

echo $result->fetchColumn(), PHP_EOL;
