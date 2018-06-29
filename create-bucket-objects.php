<?php

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
               $('#result').append('<li>' + data + '</li>');
            }
         });
      });
    });
    </script>
</head>
<body>
    <section class="congratulations">
        <h1>Create S3 Bucket Objects</h1>
        <p>Sample code demonstrating how to create S3 bucket objects.</p>
        <p>Also demonstrates use of AJAX to dynamically call another php script and update the page with results.</p>
    </section>

    <section class="instructions">
      <h2>Create a Bucket Object</h2>
         <p>The following form can be used to create a text file in the <b> <?php echo $GLOBALS['bucket'] ?> </b> bucket.</p>
         <form id="create_form">
           Name: <input type="text" name="filename" id="filename"><br/>
           Content: <textarea name="content" rows="5" cols="40"></textarea><br/>
           <input name="submit" type="submit" value="Create File">
         </form>
         <div class="results">
            <p>Files created:</p>
            <ul id="result">
            </ul>
         </div>
    </section>
</body>
</html>
