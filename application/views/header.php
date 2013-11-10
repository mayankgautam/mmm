<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="<?php echo $baseurl ?>bootstrap/dist/css/bootstrap.min.css" />
        <link rel="stylesheet" href="<?php echo $baseurl ?>style.css" />
        <script src="<?php echo $baseurl ?>jquery.js"></script>
        <script src="<?php echo $baseurl ?>bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="<?php echo $baseurl ?>bootstrap/js/carousel.js"></script>
        <script src="<?php echo $baseurl ?>jquery.validate.min.js"></script>
        <script src="<?php echo $baseurl ?>working.js"></script>
    </head>
    <body>
        <nav class="navbar navbar-default" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo "$baseurl/$index" ?>">MMM</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav">
                    <li><a href="<?php echo "$baseurl/$index" ?>/mmm/submitdata">Submit Data</a></li>
                    <li><a href="<?php echo "$baseurl/$index" ?>/mmm/showall">Show All</a></li>
                </ul>
                <form class="navbar-form navbar-right" role="search">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Search">
                    </div>
                    <button type="submit" class="btn btn-default">Submit</button>
                </form>

            </div>
        </nav>