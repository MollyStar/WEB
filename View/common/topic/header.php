<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0">
    <title><?php echo isset($title) ? $title : '' ?></title>
    <link href="/asset/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="/asset/css/bootstrap-theme-flatly.min.css" rel="stylesheet" type="text/css">
    <link href="/asset/css/part/common.min.css" rel="stylesheet" type="text/css">
    <link href="/asset/css/part/topic.min.css" rel="stylesheet" type="text/css">
    <script src="/asset/js/jquery.min.js"></script>
    <script src="/asset/js/popper.min.js"></script>
    <script src="/asset/js/bootstrap.min.js"></script>
    <script src="/asset/js/part/common.min.js"></script>
    <!--[if lt IE 9]>
    <script src="/asset/js/html5shiv.min.js"></script>
    <script src="/asset/js/respond.min.js"></script>
    <![endif]-->
    <script src="/asset/js/fingerprint2.min.js"></script>
    <script>
        new Fingerprint2().get(function (result, components) {
            console.log(result); //a hash, representing your device fingerprint
            console.log(components); // an array of FP components
        });
    </script>
</head>
<body>
<!-- /header -->
