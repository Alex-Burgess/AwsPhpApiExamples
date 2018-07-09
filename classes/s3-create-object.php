<?php
require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require 's3-connection.php';     // Provides S3 connection in variable $s3 or $s3_connect_error

$bucket = $_POST['bucketname'];
$filename = $_POST['filename'] . '.txt';
$keyname = 'uploads/' . $filename;

try {
   // Upload data.
   $put_result = $s3->putObject([
       'Bucket' => $bucket,
       'Key'    => $keyname,
       'Body'   => 'Hello, world!',    // This can come from ajax??
       'ACL'    => 'private'
   ]);

   $result = $s3->listObjects([     // TODO what is this for??
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
