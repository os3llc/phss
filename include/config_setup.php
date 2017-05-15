require_once('include/config.php');

$DB_STRING = $config['db']['type'] . ":";
$DB_STRING .= "host=" . $config['db']['host'] . ";";
$DB_STRING .= "port=" . $config['db']['port'] . ";";
$DB_STRING .= "dbname=" . $config['db']['dbname'] . ";";
$DB_STRING .= "user=" . $config['db']['user'] . ";";
$DB_STRING .= "password=" . $config['db']['password'] . ";";
