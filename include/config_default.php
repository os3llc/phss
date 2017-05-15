/**
 * File that sets up necessary configuration stuff.
 */

$config = Array();

// Database configuration defaults
$config['db'] = Array();
$config['db']['host'] = 'localhost';
$config['db']['dbname'] = 'phss';
$config['db']['user'] = 'phss';
$config['db']['password'] = 'phss';
$config['db']['port'] = 5432;
// Supported values for type: pgsql, mysql
$config['db']['type'] = 'pgsql';
$config['db']['ssl'] = false;

// Authentication defaults
// Supported values for source: database, ldap
$config['auth']['source'] = 'database';
// Supported values for type: pgsql, mysql, ldap
$config['auth']['type'] = 'pgsql';
$config['auth']['host'] = 'localhost';
$config['auth']['port'] = 5432;
$config['auth']['dbname'] = 'phss';
$config['auth']['user'] = 'phss';
$config['auth']['password'] = 'phss';
$config['auth']['ssl'] = false;

// API Configuration
$config['api']['uri'] = '/phss';
$config['api']['auth'] = '';

// Branding
$config['branding']['logo'] = 'img/phss.png';
$config['branding']['title'] = 'Psalms, Hymns, and Spiritual Songs';
$config['branding']['theme'] = 'phss';
$config['branding']['styles'] = $config['branding']['theme'] . '/css/theme.css';
