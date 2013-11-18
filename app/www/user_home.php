<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="viewport" content="user-scalable=no, initial-scale=1, 
      maximum-scale=1, minimum-scale=1, width=device-width, 
      height=device-height, target-densitydpi=device-dpi" />

    <!-- jquery mobile -->
    <link href="css/jquery.mobile-1.3.2.min.css" rel="stylesheet">

    <title>Room 703</title>
  </head>
  <body>
    <div data-role="page" data-theme="b" id="user_home">
      <div data-theme="a" data-role="header">
        <h3>Room 703</h3>
      </div>
      <div data-role="content">
        <!-- put page content here -->
        <?php
          echo "Hello World!";
        ?>
      </div>
    </div>

    <!-- util functions -->
    <script type="text/javascript" src="js/util.js"></script>

    <!-- server interface wrapper -->
    <script type="text/javascript" src="js/server-interface.js"></script>

    <!-- jquery -->
    <script src="js/jquery-2.0.3.min.js"></script>
    <script src="js/jquery.mobile-1.3.2.min.js"></script>
  </body> 
</html>
