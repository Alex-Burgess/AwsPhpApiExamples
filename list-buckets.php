<?php
require 'vendor/autoload.php';
require 'classes/s3-connection.php';     // Provides S3 connection in variable $s3 or $s3_connect_error
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
    <link rel="stylesheet" href="/css/styles.css" type="text/css">
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
            if ($s3_connect_error) {
              echo "<ul><li>" . $s3_connect_error_message . "</li>";
              echo "<li>" . $s3_connect_error . "</li></ul>";
            } else {
               // Retrieve the list of buckets.
               $buckets = $s3->listBuckets();

               foreach ($buckets['Buckets'] as $bucket){
                  echo "<li>" . $bucket['Name'] . "</li>";
               }
            }
          ?>
        </ul>
    </section>
</body>
</html>
