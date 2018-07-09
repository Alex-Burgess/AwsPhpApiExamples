<?php

require 'vendor/autoload.php';
require 's3-connection.php';     // Provides S3 connection in variable $s3 or $s3_connect_error

$GLOBALS['bucket'] = 'froome-dog-sse';
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
        <h1>Get Bucket Objects with SSE</h1>
        <p>Code samples demonstrating how to get objects from an S3 bucket, where the bucket and contents are encrypted with AWS KMS key.</p>
    </section>

    <section class="instructions">
        <h2><?php echo $GLOBALS['bucket'] ?></h2>
          <?php
             if ($s3_connect_error) {
               echo "<ul><li>" . $s3_connect_error_message . "</li>";
               echo "<li>" . $s3_connect_error . "</li></ul>";
               } else {
                  $result = $s3->listObjects([
                     'Bucket' => $GLOBALS['bucket']
               ]);

               foreach ($result['Contents'] as $object) {
                  if (preg_match('/320x240.JPG/', $object['Key'])) {
                     //Creating a presigned URL
                     $cmd = $s3->getCommand('GetObject', [
                        'Bucket' => $GLOBALS['bucket'],
                        'Key'    => $object['Key']
                     ]);

                     $request = $s3->createPresignedRequest($cmd, '+20 minutes');

                     // Get the actual presigned-url
                     $presignedUrl = (string) $request->getUri();

                     echo "<br><img src=\"" . $presignedUrl . "\"</br>";
                  }
               }
            }
          ?>
    </section>
</body>
</html>
