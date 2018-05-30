<?php

require 'vendor/autoload.php';

use Aws\Sts\StsClient;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Aws\Credentials\AssumeRoleCredentialProvider;

try {
  $assumeRoleCredentials = new AssumeRoleCredentialProvider([
    'client' => new StsClient([
        'region' => 'eu-west-1',
        'version' => '2011-06-15'
    ]),
    'assume_role_params' => [
        'RoleArn' => 'arn:aws:iam::369331073513:role/PhpSamplesRole', // REQUIRED
        'RoleSessionName' => 'test', // REQUIRED
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
    <!--[if IE]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    <link rel="stylesheet" href="/styles.css" type="text/css">
</head>
<body>
    <section class="congratulations">
        <h1>PHP List S3 Buckets</h1>
        <p>Some snippets of code demonstrating how to get S3 buckets.</p>
    </section>

    <section class="instructions">
        <h2>S3 Buckets</h2>
        <ul>
          <?php
            if ($error) {
              echo "<li>" . $error . "</li>";
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
