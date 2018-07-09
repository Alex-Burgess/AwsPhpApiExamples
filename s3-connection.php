<?php
require 'application-configuration.php';

use Aws\Sts\StsClient;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Aws\Credentials\AssumeRoleCredentialProvider;

try {
  $assumeRoleCredentials = new AssumeRoleCredentialProvider([
    'client' => new StsClient([
        'region' => $app_configuration['region'],
        'version' => '2011-06-15'
    ]),
    'assume_role_params' => [
        'RoleArn' => $app_configuration['roleArn'], // $app_configuration comes from application-configuration.php
        'RoleSessionName' => $app_configuration['roleSessionName'], // $app_configuration comes from application-configuration.php
    ]
  ]);

  $s3 = new S3Client([
      'region' => $app_configuration['region'],
      'version' => 'latest',
      'credentials' => $assumeRoleCredentials
  ]);
} catch (Exception $e) {
  $s3_connect_error_message = $e->getMessage();
  $s3_connect_error = $e;
}
?>
