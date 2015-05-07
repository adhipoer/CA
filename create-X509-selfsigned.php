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
      if(empty($_POST['cn'])) 
      { die("Please enter a common name/hostname."); }
      if(empty($_POST['csr'])) 
      { die("Please enter a csr."); }
      if(empty($_POST['publickey'])) 
      { die("Please enter a publickey."); }
      $query = " 
              SELECT 
                  request_id,
                  request_cm,
                  user_id,
                  request_serial
              FROM request
              WHERE 
          request_cm = :cn 
          "; 
      $query_params = array( 
        ':cn' => $_POST['cn'] 
      ); 
      try{ 
          $stmt = $db->prepare($query); 
          $result = $stmt->execute($query_params); 
      } 
      catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); } 
      $reqCa = $stmt->fetch();

      if($reqCa)
      {
        if($reqCa['request_serial']!=NULL){
          alert("CA anda sudah ditanda tangani");
        }
        else{
          $query3 = " 
                  SELECT 
                      private_key,
                      public_key,
                      ca_root
                  FROM root  
              ";
        try{ 
              $stmt = $db->prepare($query3); 
              $result = $stmt->execute(); 
          } 
          catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); }
          $row = $stmt->fetch();
          
        $CAPrivKey = new Crypt_RSA();
        $CAPrivKey->loadKey($row['private_key']);

        $userPubKey = $_POST['publickey'];
        $pubKey = new Crypt_RSA();
        $pubKey->loadKey($userPubKey);
        $pubKey->setPublicKey();
        
        $userCSR = $_POST['csr'];
        $subject = new File_X509();
        $subject->loadCSR($userCSR);
        $subject->setPublicKey($pubKey);
        
        $issuer = new File_X509();
        $issuer->setPrivateKey($CAPrivKey);
        $ca = $issuer->loadX509($row['ca_root']);
        
        $serialNum = $reqCa['user_id'].$reqCa['request_id'];
        $x509 = new File_X509();
        $x509->setSerialNumber(chr($serialNum));
        $x509->makeCA();
        $query2 = " 
                UPDATE 
                  request 
                set 
                  request_serial = :request_serial
                WHERE
                  request_cm = :cn

            ";
          $query_params2 = array(
                ':request_serial' => $serialNum,
                ':cn' => $_POST['cn']
            ); 
          try {  
            $stmt = $db->prepare($query2); 
              $result = $stmt->execute($query_params2); 
          } 
          catch(PDOException $ex){ die("Failed to run query2: " . $ex->getMessage()); }

        $result = $x509->sign($issuer, $subject);
        $filename = $_POST['cn']."-ca.pem";
          header("Cache-Control: public");
          header("Content-Description: File Transfer");
          header("Content-Length: ".strlen($x509->saveX509($result)));
          header("Content-Disposition: attachment; filename=$filename");
          header("Content-Type: application/octet-stream; "); 
          header("Content-Transfer-Encoding: binary");
          echo $x509->saveX509($result);
          exit();
  }
  //echo "the CA cert to be imported into the browser is as follows:\r\n\r\n";
  //echo $x509->saveX509($result);
  //echo "\r\n\r\n";
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
                    <form class="" role="form" method="POST" action="create-X509-CAsigned.php" id="casign">
                      <div class="form-group form-group-default required">
                        <label>Common Name (hostname)</label>
                        <input name="cn"type="input" class="form-control" placeholder="ex: example.com" required>
                      </div>
                      <div class="form-group form-group-default required" >
                        <label>CSR</label>
                        <textarea class="form-control" rows="5" id="csr" name="csr" required>
                        </textarea>
                      </div>
                      <div class="form-group form-group-default required" >
                        <label>Public Key</label>
                        <textarea class="form-control" rows="5" id="publickey" name="publickey" required>
                        </textarea>
                      </div>
                      <button class="btn btn-complete btn-cons col-md-12" height="50px">Self Sign Certificate</button>
                    </form>
                  </div>
                </div>
        </div>
</div>
</body>
</html>