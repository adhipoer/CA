<?php
	require_once 'config.php';
	require_once 'Crypt/RSA.php';
	require_once 'File/X509.php';

// Load the CA and its private key.
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

	$pemcakey = $row['private_key'];
	$cakey = new Crypt_RSA();
	$cakey->loadKey($pemcakey);
	$pemca = $row['ca_root'];
	$ca = new File_X509();
	$ca->loadX509($pemca);
	$ca->setPrivateKey($cakey);

	// Load the CRL.
	$crl = new File_X509();
	$crl->loadCA($pemca); // For later signature check.
	$pemcrl = file_get_contents('mycrl.crl');
	$crl->loadCRL($pemcrl);

	// Validate the CRL.
	if ($crl->validateSignature() !== 1) {
	    exit("CRL signature is invalid\n");
	}

	// Update the revocation list.
	$crl->setRevokedCertificateExtension('5522', 'id-ce-cRLReasons', 'privilegeWithdrawn');

	// Generate the new CRL.
	$crl->setEndDate('+3 months');
	$newcrl = $crl->signCRL($ca, $crl);

	// Output it.
	$fileRoot = $crl->saveCRL($newcrl);
	$myfileroot = fopen("mycrl.crl","w") or die("Unable to open file!");
	fwrite($myRoot, $fileRoot);
	fclose($myRoot);
?>