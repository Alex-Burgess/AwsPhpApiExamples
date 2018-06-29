<?php

require 'vendor/autoload.php';

use Aws\Sts\StsClient;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Aws\Credentials\AssumeRoleCredentialProvider;

$GLOBALS['bucket'] = 'froome-dog';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Create Bucket Objects</title>
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Lobster+Two" type="text/css">
    <link rel="icon" href="https://awsmedia.s3.amazonaws.com/favicon.ico" type="image/ico" >
    <link rel="shortcut icon" href="https://awsmedia.s3.amazonaws.com/favicon.ico" type="image/ico" >
    <link rel="stylesheet" href="/styles.css" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
    $(function () {
      $('#create_form').on('submit', function (e) {
         e.preventDefault();

         $.ajax({
            type: 'post',
            url: 'create-object.php',
            data: $('form').serialize(),
            success: function (data) {
               //alert('form was submitted');
               $('#result').append(data);
            }
         });
      });
    });
    </script>
</head>
<body>
    <section class="congratulations">
        <h1>PHP Create S3 Bucket Object</h1>
        <p>Some snippets of code demonstrating how to Create a S3 bucket object.</p>
    </section>

    <section class="instructions">
         <h2>S3 Buckets</h2>
         <p>The following form can be used to create a new object in S3.</p>
        <form id="create_form">
          Name: <input type="text" name="filename" id="filename"><br/>
          Content: <textarea name="content" rows="5" cols="40"></textarea><br/>
          <input name="submit" type="submit" value="Submit">
        </form>
        <p>Results: <span id="result"></span></p>
    </section>
</body>
</html>
