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

$x509->setSerialNumber(49);

$result = $x509->sign($issuer, $subject);
//echo "the stunnel.pem contents are as follows:\r\n\r\n";
echo $privKey->getPrivateKey();
echo "\r\n";
echo $x509->saveX509($result);
echo "\r\n";
?>