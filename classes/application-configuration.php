<?php
// This script is added to the top of any scripts using:
// require 'application-configuration.php';

// app.ini contains configuration variants for local (TEST) and elastic beanstalk (DEV) environments
// PHP_APP_ENV is an environment variable which is set on the elastic beanstalk instance
$ini = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/config/app.ini', true);

if ($_SERVER['PHP_APP_ENV']){
   $app_configuration = $ini[$_SERVER['PHP_APP_ENV']];
} else {
   $app_configuration = $ini['TEST'];
}
?>
