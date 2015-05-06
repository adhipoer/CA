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

echo $privatekey;
echo "\r\n\r\n";

echo $publickey;
echo "\r\n\r\n";

?>