<?php
require 'vendor/autoload.php';
use Aws\Sts\StsClient;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Aws\Credentials\AssumeRoleCredentialProvider;

$bucket = $_POST['bucketname'];
$filename = $_POST['filename'] . '.txt';
$keyname = 'uploads/' . $filename;

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

  // Upload data.
  $put_result = $s3->putObject([
      'Bucket' => $bucket,
      'Key'    => $keyname,
      'Body'   => 'Hello, world!',
      'ACL'    => 'private'
  ]);

  $result = $s3->listObjects([
      'Bucket' => $bucket
  ]);

  // echo $result;      // TODO can this be parsed to get a success message??
  echo $filename . " with path (uploads/) created in " . $bucket . " bucket";
} catch (Exception $e) {
  $error = $e;
  $error_message = $e->getMessage();
  echo $error_message;
}
?>
