<?php
include('File/X509.php');
include('Crypt/RSA.php');

$privKey = new Crypt_RSA();
extract($privKey->createKey());
$privKey->loadKey($privatekey);

$x509 = new File_X509();
$x509->setPrivateKey($privKey);

$x509->setDNProp('id-at-organizationName', 'ITS');
$x509->setDNProp('id-at-organizationalUnitName', 'LPTSI');
$x509->setDNProp('id-at-countryName', 'ID');
$x509->setDNProp('id-at-stateOrProvinceName', 'East Java');
$x509->setDNProp('id-at-localityName', 'Surabaya');
$x509->setDNProp('id-at-commonName', 'its.ac.id');
$x509->setDNProp('id-emailAddress', 'example@its.ac.id');

$csr = $x509->signCSR();

echo $x509->saveCSR($csr);
?>