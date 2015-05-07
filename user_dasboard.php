<?php
    require("config.php");
    if(empty($_SESSION['email'])) 
    {
        header("Location: index.php");
        die("Redirecting to index.php"); 
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
      <!--<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">-->
      <link rel="stylesheet" href="https://bootswatch.com/sandstone/bootstrap.min.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
      <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <title>KIJ-CA</title>
</head>
<body>

<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand">KIJ-CA</a>
    </div>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
            <li><a href="create-csr.php">CSR</a></li>
			      <li><a href="create-X509-CAsigned.php">CA SIGN</a></li>
			      <li><a href="create-x509-CAsigned.php">Self Sign</a></li>
			      <li><a href="#">Revoke</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
		    <li><a> Hello, <?php echo htmlentities($_SESSION['email']['user_organization'], ENT_QUOTES, 'UTF-8'); ?></a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
  </div>
</nav>

</body>
</html>