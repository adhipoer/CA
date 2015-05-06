<?php
    require("config.php");
    if(empty($_SESSION['email'])) 
    {
        header("Location: index.php");
        die("Redirecting to index.php"); 
    }
    if(isset($_POST['certificate']))
    {
        header('Content-disposition: attachment; filename=test.cer');
        header('Content-type: application/txt');
        echo $_POST['certificate'];
        exit; //stop writing
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
      <a class="navbar-brand" href="#">SPUFF-CA</a>
    </div>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
            <li><a href="create-csr.php">CSR <span class="sr-only"></span></a></li>
			<li><a href="#">CA SIGN</a></li>
			<li class="active"><a href="create-x509-CAsigned.php">Self Sign<span class="sr-only">(current)</span></a></li>
			<li><a href="#">Revoke</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
		    <li><a href="user_dashboard.php"> Hello, <?php echo htmlentities($_SESSION['email']['user_organization'], ENT_QUOTES, 'UTF-8'); ?></a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
  </div>
</nav>

<?php
include('File/X509.php');
include('Crypt/RSA.php');

// create private key / x.509 cert for stunnel / website
$privKey = new Crypt_RSA();
extract($privKey->createKey());
$privKey->loadKey($privatekey);

$pubKey = new Crypt_RSA();
$pubKey->loadKey($publickey);
$pubKey->setPublicKey();

$subject = new File_X509();

$subject->setDNProp('id-at-organizationName', 'ITS');
$subject->setDNProp('id-at-organizationalUnitName', 'LPTSI');
$subject->setDNProp('id-at-countryName', 'ID');
$subject->setDNProp('id-at-stateOrProvinceName', 'East Java');
$subject->setDNProp('id-at-localityName', 'Surabaya');
$subject->setDNProp('id-at-commonName', 'its.ac.id');
$subject->setDNProp('id-emailAddress', 'example@its.ac.id');

$subject->setPublicKey($pubKey);

$issuer = new File_X509();
$issuer->setPrivateKey($privKey);
$issuer->setDN($subject->getDN());

$x509 = new File_X509();

$x509->setSerialNumber(50);

$result = $x509->sign($issuer, $subject);
//echo "the stunnel.pem contents are as follows:\r\n\r\n";
echo $privKey->getPrivateKey();
echo "\r\n";
//echo $x509->saveX509($result);
//echo "\r\n";
?>

<div class="default-page">

              <div class="col-md-4">
              </div>
              <div class="col-md-5">
                <!-- START PANEL -->
                <h1></h1>
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <div class="panel-title">
                      CERTIFICATE X509 SELF-SIGNED
                    </div>
                  </div>
                  <div class="panel-body">                   
                    <form class="" role="form" method="POST">

                      <div class="form-group form-group-default required" >
                      	<label>Certificate</label>
                        <textarea class="form-control" rows="5" id="certificate" name="certificate">
                        	<?php
                        		echo $x509->saveX509($result);
								echo "\r\n";
							?>
                        </textarea>
                      </div>

                      <a href="x509.txt" download>
                      	<button class="btn btn-complete btn-cons col-md-12" height="50px">Download</button>
                      </a>
                    </form>
                  </div>
                </div>
</div>

</body>