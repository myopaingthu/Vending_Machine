<?php

http_response_code($response_code);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error <?php echo $response_code; ?></title>
    <link href="/css/bootstrap.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 50px;
        }

        .error-page {
            text-align: center;
        }

        .error-page h1 {
            font-size: 100px;
            font-weight: bold;
            color: #dc3545;
        }

        .error-page p {
            font-size: 20px;
        }
    </style>
</head>

<body>
    <div class="container error-page">
        <h1><?php echo $response_code; ?></h1>
        <p class="lead"><?php echo $message; ?></p>
        <a href="/" class="btn btn-primary">Back to Home</a>
    </div>

    <script src="/js/jquery.min.js"></script>
    <script src="/js/bootstrap.js"></script>
</body>

</html>