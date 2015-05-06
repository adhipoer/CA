<?php 
    require("config.php");  
    $submitted_email = ''; 
    if(!empty($_POST)){ 
        $query = " 
            SELECT 
                user_id,
                user_organization,
                user_email,  
                user_password, 
                user_salt  
            FROM user 
            WHERE 
                user_email = :email 
        "; 
        $query_params = array( 
            ':email' => $_POST['email'] 
        ); 

        try{ 
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); } 
        $login_ok = false; 
        $row = $stmt->fetch();
        if($row){ 

            $check_password = hash('sha256', $_POST['password'] . $row['user_salt']);
            for($round = 0; $round < 65536; $round++){
                $check_password = hash('sha256', $check_password . $row['user_salt']);
            }
            if($check_password === $row['user_password']){
                $login_ok = true;
            } 
        } 

        if($login_ok){ 
            unset($row['user_salt']); 
            unset($row['user_password']); 
            $_SESSION['email'] = $row;  
            header("Location: user_dasboard.php"); 
            die("Redirecting to: user_dasboard.php"); 
        } 
        else{ 
            print("Login Failed."); 
            $submitted_username = htmlentities($_POST['email'], ENT_QUOTES, 'UTF-8'); 
        } 
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
      <a class="navbar-brand" href="index.php">KIJ-CA</a>
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
                      Use your Email and Password to Login
                    </div>
                  </div>
                  <div class="panel-body">                   
                    <form class="" role="form" method="POST" action="index.php">

                      <div class="form-group form-group-default required ">
                        <label>Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="ex: some@example.com" value="<?php echo $submitted_email; ?>" required>
                      </div>
                      <div class="form-group form-group-default required ">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" placeholder="*********************" required>
                      </div>

                      <button class="btn btn-complete btn-cons col-md-12" height="50px">Login</button>
                    </form>
                  </div>
                </div>
</div>
</body>
</html>