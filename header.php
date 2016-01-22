<?php
global $locale_lang;
?>
<!DOCTYPE html>
<html>
    <head>
        <title>SCADA</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" href="alertifyjs/css/alertify.css" />
        <link rel="stylesheet" href="alertifyjs/css/themes/semantic.css" />
        <link rel="stylesheet" href="css/style.css" />

        <script type="text/javascript" src="js/jquery-2.1.4.js"></script>
        <script type="text/javascript" src="alertifyjs/alertify.min.js"></script>
    </head>
    <body>
        <div class="navigation-area">
            <div class="collapse navbar-collapse">
                <ul class="nav nav-pages">
                    <li>MODE
                        <ul class="nav sub-nav">
                            <li data="0"><a href="/">Virtual</a></li>
                            <li data="1"><a href="?m=r">Real</a></li>
                        </ul>
                    </li>
                    <li>EXERCISES
                        <ul class="nav sub-nav">
                            <li><a href="?p=ex&m=1">Exercise 1</a></li>
                            <li><a href="?p=ex&m=2">Exercise 2</a></li>
                            <li><a href="?p=ex&m=3">Exercise 3</a></li>
                            <li><a href="?p=ex&m=4">Exercise 4</a></li>
                        </ul>
                    </li>
                    <li>ABOUT
                        <ul class="nav sub-nav">
                            <li><a href="?p=doc">About the System</a></li>
                            <li><a href="?p=cr">Credits</a></li>
                        </ul>
                    </li>
                </ul>
            </div><!-- /.nav-collapse -->
        </div>
