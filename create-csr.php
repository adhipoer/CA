<?php
  require("config.php");
	include('File/X509.php');
	include('Crypt/RSA.php');

	if(empty($_SESSION['email'])) 
  {
        header("Location: index.php");
        die("Redirecting to index.php"); 
  }
  if(!empty($_POST)){
    if(empty($_POST['organizationalunit'])) 
    { die("Please enter a organizational unit."); }
    if(empty($_POST['cn'])) 
    { die("Please enter a common name/hostname."); }
    $query = " 
            INSERT INTO request ( 
                request_ou,
                request_cm,
                user_id
                
            ) VALUES ( 
                :ou,
                :cn,
                :user_id
            ) 
        ";
    $query_params = array(
            ':ou' => $_POST['organizationalunit'],
            ':cn' => $_POST['cn'],
            ':user_id' => $_SESSION['email']['user_id'] 
        ); 
    try {  
      $stmt = $db->prepare($query); 
      $result = $stmt->execute($query_params); 
    } 
    catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); }
    //membuat csr dan memberikan kepada user
    $query = " 
            SELECT 
                private_key
            FROM root  
        ";
    try{ 
        $stmt = $db->prepare($query); 
        $result = $stmt->execute(); 
    } 
    catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); }
    $row = $stmt->fetch();

    $privKey = new Crypt_RSA();
  	$privKey->loadKey($row['private_key']);

  	$x509 = new File_X509();
  	$x509->setPrivateKey($privKey);

  	$x509->setDNProp('id-at-organizationName', $_SESSION['email']['user_organization']);
  	$x509->setDNProp('id-at-organizationalUnitName', $_POST['organizationalunit']);
  	$x509->setDNProp('id-at-countryName', $_SESSION['email']['user_country']);
  	$x509->setDNProp('id-at-stateOrProvinceName', $_SESSION['email']['user_state']);
  	$x509->setDNProp('id-at-localityName', $_SESSION['email']['user_locality']);
  	$x509->setDNProp('id-at-commonName', $_POST['cn']);
  	$x509->setDNProp('id-emailAddress', $_SESSION['email']['user_email']);

  	$csr = $x509->signCSR();

    $filename = $_POST['organizationalunit']."-csr.pem";
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-Length: ".strlen($x509->saveCSR($csr)));
    header("Content-Disposition: attachment; filename=$filename");
    header("Content-Type: application/octet-stream; "); 
    header("Content-Transfer-Encoding: binary");
    echo $x509->saveCSR($csr);
    exit();
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
            <li><a href="create-X509-selfsigned.php">Self Sign</a></li>
            <li><a href="#">Revoke</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
        <li><a> Hello, <?php echo htmlentities($_SESSION['email']['user_organization'], ENT_QUOTES, 'UTF-8'); ?></a></li>
            <li><a href="logout.php">Logout</a></li>
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
                    <form class="" role="form" method="POST" action="create-csr.php" >

                      <div class="form-group form-group-default required">
                        <label>Organization Name</label>
                        </br>
                        <?php echo $_SESSION['email']['user_organization']?>
                      </div>

                      <div class="form-group form-group-default required">
                        <label>Organizational Unit</label>
                        <input name="organizationalunit"type="input" class="form-control" placeholder="ex: LPTSI" required>
                      </div>

                      <div class="form-group form-group-default required">
                        <label>Country (2 letter code)</label>
                        </br>
                        <?php echo $_SESSION['email']['user_country']?>
                      </div>

                      <div class="row">
                        <div class="col-sm-6">
                          <div class="form-group form-group-default required">
                            <label>State or Province</label>
                            </br>
                            <?php echo $_SESSION['email']['user_state']?>
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-group form-group-default">
                            <label>Locality or City</label>
                            </br>
                            <?php echo $_SESSION['email']['user_locality']?>
                          </div>
                        </div>
                      </div>

                      <div class="form-group form-group-default required">
                        <label>Common Name (hostname)</label>
                        <input name="cn"type="input" class="form-control" placeholder="ex: example.com" required>
                      </div>

                      <div class="form-group form-group-default required ">
                        <label>Email Address</label>
                        </br> 
                        <?php echo $_SESSION['email']['user_email']?>
                      </div>
                      <button class="btn btn-complete btn-cons col-md-12" height="50px">Request Certificate</button>
                    </form>
                  </div>
                </div>
        </div>
</div>
</body>
</html>