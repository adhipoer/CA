<?php
require_once 'Crypt/RSA.php';
require_once 'File/X509.php';

// Load the CA and its private key.
$pemcakey = file_get_contents('privKey.crt');
$cakey = new Crypt_RSA();
$cakey->loadKey($pemcakey);
$pemca = file_get_contents('CA.crt');
$ca = new File_X509();
$ca->loadX509($pemca);
$ca->setPrivateKey($cakey);

// Build the (empty) certificate revocation list.
$crl = new File_X509();
$crl->loadCRL($crl->saveCRL($crl->signCRL($ca, $crl)));

// Revoke a certificate.
$crl->setRevokedCertificateExtension('12', 'id-ce-cRLReasons', 'privilegeWithdrawn');

// Sign the CRL.
$crl->setSerialNumber(1, 10);
$crl->setEndDate('+3 months');
$newcrl = $crl->signCRL($ca, $crl);

// Output it.
$fileRoot = $crl->saveCRL($newcrl);
$myfileroot = fopen("mycrl.crl","w") or die("Unable to open file!");
fwrite($myfileroot, $fileRoot);
fclose($myfileroot);
?>