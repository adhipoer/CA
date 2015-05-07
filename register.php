<?php 
    require("config.php");
    if(!empty($_POST)) 
    { 
        // Ensure that the user fills out fields 
        if(empty($_POST['organization'])) 
        { die("Please enter a organization."); }  
        //if(empty($_POST['organizationalunit'])) 
        //{ die("Please enter a organizational unit."); }
        if(empty($_POST['country'])) 
        { die("Please enter a country."); }
        if(empty($_POST['state'])) 
        { die("Please enter a state/province."); }
        if(empty($_POST['locality'])) 
        { die("Please enter a locality/City."); } 
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) 
        { die("Invalid E-Mail Address"); }
        if(empty($_POST['password'])) 
        { die("Please enter a password."); }

         
        // Check if the username is already taken
        $query = " 
            SELECT 
                1 
            FROM user
            WHERE 
                user_organization = :organization 
        "; 
        $query_params = array( ':organization' => $_POST['organization'] ); 
        try { 
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); } 
        $row = $stmt->fetch(); 
        if($row){ die("This organization is already in use"); } 
        $query = " 
            SELECT 
                1 
            FROM user
            WHERE 
                user_email = :email 
        "; 
        $query_params = array( 
            ':email' => $_POST['email'] 
        ); 
        try { 
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage());} 
        $row = $stmt->fetch(); 
        if($row){ die("This email address is already registered"); } 
         
        // Add row to database 
        $query = " 
            INSERT INTO user ( 
                user_organization,
                user_country,
                user_state,
                user_locality,
                user_email,  
                user_password, 
                user_salt 
                
            ) VALUES ( 
                :organization, 
                :country,
                :state,
                :locality,
                :email, 
                :password, 
                :salt
            ) 
        "; 
         
        // Security measures
        $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647)); 
        $password = hash('sha256', $_POST['password'] . $salt); 
        for($round = 0; $round < 65536; $round++){ $password = hash('sha256', $password . $salt); } 
        $query_params = array(
            ':organization' => $_POST['organization'],
            ':country' => $_POST['country'],
            ':state' => $_POST['state'],
            ':locality' => $_POST['locality'], 
            ':email' => $_POST['email'],
            ':password' => $password, 
            ':salt' => $salt 
        ); 
        try {  
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); } 
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
            <li class="active"><a href="#">Link <span class="sr-only">(current)</span></a></li>
            <li><a href="#">Link</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="register.php">Register</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="index.php">Login</a></li>
        </ul>
    </div>
  </div>
</nav>

<div class="default-page">

              <div class="col-md-4">
              </div>
              <div class="col-md-5">
                <!-- START PANEL -->
                <h1></h1>
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <div class="panel-title">
                      Complete the Form
                    </div>
                  </div>
                  <div class="panel-body">                   
                    <form class="" role="form" method="POST" action="register.php">

                      <div class="form-group form-group-default required">
                        <label>Organization Name</label>
                        <input name="organization"type="text" class="form-control" placeholder="ex: Institut Teknologi Sepuluh Nopember" required>
                      </div>

                      <!--<div class="form-group form-group-default required">
                        <label>Organizational Unit</label>
                        <input name="organizationalunit"type="text" class="form-control" placeholder="ex: LPTSI" required>
                      </div>-->

                      <div class="form-group form-group-default required">
                        <label>Country (2 letter code)</label>
                        <input name="country"type="text" class="form-control" placeholder="ex: ID for Indonesia" required>
                      </div>

                      <div class="row">
                        <div class="col-sm-6">
                          <div class="form-group form-group-default required">
                            <label>State or Privince</label>
                            <input name="state" type="text" class="form-control" placeholder="ex: Jawa Timur" required>
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-group form-group-default">
                            <label>Locality or City</label>
                            <input name="locality" type="text" class="form-control" placeholder="ex: Surabaya" required>
                          </div>
                        </div>
                      </div>

                      <!--<div class="form-group form-group-default required">
                        <label>Common Name (hostname)</label>
                        <input name="cn"type="text" class="form-control" placeholder="ex: example.com" required>
                      </div>-->

                      <div class="form-group form-group-default required ">
                        <label>Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="ex: some@example.com" required>
                      </div>

                      <!--<div class="form-group form-group-default required">
                        <label>Public Key</label>
                        <input name="publickey"type="file" class="form-control" placeholder="ex: example.com" required>
                      </div>-->
                      <div class="form-group form-group-default required ">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" placeholder="*********************" required>
                      </div>

                      <button class="btn btn-complete btn-cons col-md-12" height="50px">Register</button>
                    </form>
                  </div>
                </div>
</div>

</body>
</html>