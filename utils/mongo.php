<?php

require 'vendor/autoload.php';

$mongohost = "localhost";
$mongodb = "test";

$mongo = new MongoDB\Client("mongodb://" . $mongohost);
$db = $mongo->$mongodb;

$hymns = $db->hymns;

?>
