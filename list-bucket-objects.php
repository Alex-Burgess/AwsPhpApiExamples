<?php

require 'vendor/autoload.php';

use Aws\Sts\StsClient;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Aws\Credentials\AssumeRoleCredentialProvider;

$bucket = 'froome-dog';

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
      'region' => 'eu-west-1',
      'version' => 'latest',
      'credentials' => $assumeRoleCredentials
  ]);

  $result = $s3->listObjects([
      'Bucket' => $bucket
  ]);

} catch (Exception $e) {
  $error = $e;
  $error_message = $e->getMessage();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>List Bucket Objects</title>
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Lobster+Two" type="text/css">
    <link rel="icon" href="https://awsmedia.s3.amazonaws.com/favicon.ico" type="image/ico" >
    <link rel="shortcut icon" href="https://awsmedia.s3.amazonaws.com/favicon.ico" type="image/ico" >
    <link rel="stylesheet" href="/styles.css" type="text/css">
</head>
<body>
    <section class="congratulations">
        <h1>Get Bucket Objects</h1>
        <p>Code samples demonstrating how to get objects from an S3 bucket.</p>
    </section>

    <section class="instructions">
        <h2><?php echo $bucket ?></h2>
          <?php
            if ($error) {
              echo "<ul><li>" . $error_message . "</li>";
              echo "<li>" . $error . "</li></ul>";
            } else {
              foreach ($result['Contents'] as $object) {
                if (preg_match('/320x240.JPG/', $object['Key'])) {
                  //Creating a presigned URL
                  $cmd = $s3->getCommand('GetObject', [
                      'Bucket' => $bucket,
                      'Key'    => $object['Key']
                  ]);

                  $request = $s3->createPresignedRequest($cmd, '+20 minutes');

                  // Get the actual presigned-url
                  $presignedUrl = (string) $request->getUri();

                  echo "<br><img src=\"" . $presignedUrl . "\"></br>";
                }
              }
            }
          ?>
    </section>
</body>
</html>
