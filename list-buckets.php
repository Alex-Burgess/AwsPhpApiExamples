<?php

require 'vendor/autoload.php';

use Aws\Sts\StsClient;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Aws\Credentials\AssumeRoleCredentialProvider;

$ini = parse_ini_file('config/app.ini', true);

// An environment variable is set in elastic beanstalk to designate the application environment
// The app.ini files contains the environment specific configuration, for example role arn.
if ($_SERVER['PHP_APP_ENV'] == 'DEV') {
   $env_array = $ini['dev'];
} elseif ($_SERVER['PHP_APP_ENV'] == 'TEST') {
   $env_array = $ini['test'];
} else {
   $env_array = $ini['test'];
}


try {
  $assumeRoleCredentials = new AssumeRoleCredentialProvider([
    'client' => new StsClient([
        'region' => 'eu-west-1',
        'version' => '2011-06-15'
    ]),
    'assume_role_params' => [
        'RoleArn' => $env_array['roleArn'], // REQUIRED
        'RoleSessionName' => $env_array['roleSessionName'], // REQUIRED
    ]
  ]);

  $s3 = new S3Client([
      'region' => 'us-east-1',
      'version' => 'latest',
      'credentials' => $assumeRoleCredentials
  ]);

  // Retrieve the list of buckets.
  $buckets = $s3->listBuckets();
} catch (Exception $e) {
  $error_message = $e->getMessage();
  $error = $e;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>List Buckets</title>
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Lobster+Two" type="text/css">
    <link rel="icon" href="https://awsmedia.s3.amazonaws.com/favicon.ico" type="image/ico" >
    <link rel="shortcut icon" href="https://awsmedia.s3.amazonaws.com/favicon.ico" type="image/ico" >
    <link rel="stylesheet" href="/styles.css" type="text/css">
</head>
<body>
    <section class="congratulations">
        <h1>List S3 Buckets</h1>
        <p>Code samples demonstrating how to get a list S3 buckets.</p>
    </section>

    <section class="instructions">
        <h2>S3 Buckets</h2>
        <ul>
          <?php
            if ($error) {
              echo "<ul><li>" . $error_message . "</li>";
              echo "<li>" . $error . "</li></ul>";
            } else {
              foreach ($buckets['Buckets'] as $bucket){
                echo "<li>" . $bucket['Name'] . "</li>";
              }
            }
          ?>
        </ul>
    </section>
</body>
</html>
