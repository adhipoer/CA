<?php

/*
Class: OpenSSL

Description:
A wrapper class for a simple subset of the PHP OpenSSL functions:

This code originates from site:
http://www.karenandalex.com/php_stuff/classes/Openssl.php
and is based from many contributors to the PHP.net manual

Mobilefish.com has made several changes.
This code has been tested on PHP5.2.8 + Apache 2.2.11
This code is free for any use including commercial, but you use it at your own risk. 
No warranty is given or implied as to its fitness for any purpose.

*/

DEFINE("OPEN_SSL_CONF_PATH", "C:/tools/php-5.2.8-Win32/extras/openssl/openssl.cnf");        // Point to your config file
DEFINE("OPEN_SSL_CERT_DAYS_VALID", 365);                                                    // Number of days how long the certificate is valid
DEFINE("FILE_LOCATION", $_SERVER["DOCUMENT_ROOT"]."/customer/tmp/openssl/");                // Location where to store the pem files.
DEFINE("HTML_LOCATION", "http://".$_SERVER["SERVER_NAME"]."/customer/tmp/openssl/");        // Location where to store the pem files.
DEFINE("DEBUG", 1);                                                                         // Show debug messages

class OpenSSL{

    var $certificate_resource_file; //the certificate in a file
    var $csr_resource_file;         //the csr in a file
    var $privatekey_resource_file;  //the private key in a file
    
    var $certificate_resource;      //the generated certificate
    var $csr_resource;              //the certificate signing request
    var $privatekey_resource;       //the private key

    var $certificate;               //the certificate
    var $crypttext;                 //the encrypted (= secure) text
    var $csr;                       //the csr
    var $dn;                        //the DN
    var $plaintext;                 //the decrypted (= unsecure) text
    var $ppkeypair;                 //the private and public key pair
    var $signature;                 //the signature

    var $config;                    //openssl config settings
    var $ekey;                      //ekey aka envelope key is set by encryption, required by decryption
                                    //randomly generated secret key and encrypted by public key
    var $privkeypass;               //password for private key
    var $random_filename;           //randomly generated filename

    function OpenSSL($isFile=0){
        $this->clear_debug_buffer();
        if($isFile) {
            $this->config = array("config" => OPEN_SSL_CONF_PATH);
        } else {
            // Configuration overrides.
            $this->config = array(
                "digest_alg" => "md5",
                "x509_extensions" => "v3_ca",
                "req_extensions" => "usr_cert",
                "private_key_bits" => 1024,
                "private_key_type" => OPENSSL_KEYTYPE_RSA,
                "encrypt_key" => true
            );
        }
        $this->debug("openssl");
    }

    function check_certificate_purpose($purpose) {
        //$this->clear_debug_buffer();
        $ok = openssl_x509_checkpurpose( $this->certificate_resource, $purpose);
        //$this->debug("check_certificate_purpose");
        return $ok;
    }
    
    function check_privatekey_match_certificate() {
        $this->clear_debug_buffer();
        $ok = openssl_x509_check_private_key ( $this->certificate_resource, $this->privatekey_resource );
        $this->debug("check_privatekey_match_certificate");
        return $ok;
    }
    
    function check_signature($plain=""){
        $this->clear_debug_buffer();
        if ($plain) $this->plaintext=$plain;
        $ok = openssl_verify($this->plaintext, $this->signature, $this->certificate_resource);
        $this->debug("check_signature");
        return $ok;
    }
    
    function clear_debug_buffer() {
        if(DEBUG) {
            while ($e = openssl_error_string());
        }
    }   

    // Create a certificate signing request (CSR)
    function create_csr() {
        //$this->clear_debug_buffer();
        $this->csr = openssl_csr_new($this->dn, $this->ppkeypair, $this->config);
        //$this->debug("create_csr");
    }
    
    // Create a new private and public key pair
    function create_key_pair() {
        //$this->clear_debug_buffer();
        $this->ppkeypair = openssl_pkey_new($this->config);
        //$this->debug("create_key_pair");
    }
    
    // Create self-signed signed certificate. The certificate is valid for N days
    function create_self_signed_certificate($days=OPEN_SSL_CERT_DAYS_VALID) {
        //$this->clear_debug_buffer();
        $this->certificate = openssl_csr_sign($this->csr, null, $this->ppkeypair, $days, $this->config);
        //$this->debug("create_self_signed_certificate");
    }

    function create_signature($plain=""){
        $this->clear_debug_buffer();
        if ($plain) $this->plaintext=$plain;
        openssl_sign($this->plaintext, $this->signature, $this->privatekey_resource);
        $this->debug("create_signature");
    }

    function debug($location) {
        if(DEBUG) {
            // Show any errors that occurred here
            while (($e = openssl_error_string()) !== false) {
                echo $location . " -- ". $e . "<br />";
            }
        }
    }

    // Decrypt text for only 1 recipient
    function decrypt($crypt="", $ekey=""){
        $this->clear_debug_buffer();
        if ($crypt)$this->crypttext=$crypt;
        if ($ekey)$this->ekey=$ekey;
        openssl_open($this->crypttext, $this->plaintext, $this->ekey, $this->privatekey_resource);
        $this->debug("decrypt");
    }    
    
    // Decrypt text using private key
    function decrypt_private($crypt=""){
        $this->clear_debug_buffer();
        if ($crypt)$this->crypttext=$crypt;
        openssl_private_decrypt ($this->crypttext, $this->plaintext, $this->privatekey_resource);
        $this->debug("decrypt_private");
    }   
    
    // Decrypt text using public key    
    function decrypt_public($crypt=""){
        $this->clear_debug_buffer();
        if ($crypt)$this->crypttext=$crypt;
        openssl_public_decrypt ($this->crypttext, $this->plaintext, $this->certificate_resource);
        $this->debug("decrypt_public");
    }       

    function display_certificate_information($shortnames){
        $this->clear_debug_buffer();
        $arr = openssl_x509_parse ( $this->certificate_resource, $shortnames);
        $this->debug("display_certificate_information");
        return $arr;
    }
    
    // Encrypt text for only 1 recipient
    function encrypt($plain=""){
        $this->clear_debug_buffer();
        if ($plain) $this->plaintext=$plain;
        openssl_seal($this->plaintext, $this->crypttext, $ekey, array($this->certificate_resource));
        $this->ekey=$ekey[0];
        $this->debug("encrypt");
    }
    
    // Encrypt text using public key
    // The function openssl_public_encrypt is not intended for general encryption and decryption. 
    // For that, you want openssl_seal() and openssl_open()
    // The maximum limit on the size of the string to be encrypted is 117 characters.
    function encrypt_public($plain=""){
        $this->clear_debug_buffer();
        if ($plain) $this->plaintext=$plain;
        openssl_public_encrypt ($this->plaintext, $this->crypttext, $this->certificate_resource);
        $this->debug("encrypt_public");
    }   

    // Encrypt text using private key
    // The function openssl_private_encrypt is not intended for general encryption and decryption. 
    // For that, you want openssl_seal() and openssl_open()
    // The maximum limit on the size of the string to be encrypted is 117 characters.
    function encrypt_private($plain=""){
        $this->clear_debug_buffer();
        if ($plain) $this->plaintext=$plain;
        openssl_private_encrypt ($this->plaintext, $this->crypttext, $this->privatekey_resource);
        $this->debug("encrypt_private");
    }       

    // Export the certificate as a file (PEM encoded format)
    function export_certificate_to_file(){
        $this->clear_debug_buffer();
        // Create empty certificate file;
        $this->set_certificate_file();
        openssl_x509_export_to_file($this->certificate, FILE_LOCATION.$this->certificate_resource_file);    
        $this->debug("export_certificate_to_file");
    }   

    // Export the certificate as a string (PEM encoded format)
    function export_certificate_to_string(){
        $this->clear_debug_buffer();
        openssl_x509_export($this->certificate, $this->certificate_resource);
        $this->debug("export_certificate_to_string");
    }
    
    // Export the CSR as a file
    function export_csr_to_file(){
        $this->clear_debug_buffer();    
        // Create empty csr file;
        $this->set_csr_file();
        openssl_csr_export_to_file($this->csr, FILE_LOCATION.$this->csr_resource_file);
        $this->debug("export_csr_to_file");
    }

    // Export the CSR as a string
    function export_csr_to_string(){    
        $this->clear_debug_buffer();    
        openssl_csr_export($this->csr, $this->csr_resource);
        $this->debug("export_csr_to_string");
    }   
        
    // Export the private key certificate as a file (PEM encoded format)
    function export_privatekey_to_file(){       
        //$this->clear_debug_buffer();
        // Create empty private key file;
        $this->set_privatekey_file();
        openssl_pkey_export_to_file($this->ppkeypair, FILE_LOCATION.$this->privatekey_resource_file);
        //$this->debug("export_privatekey_to_file");
    }

    // Export the private key certificate as a string (PEM encoded format)
    function export_privatekey_to_string(){ 
        //$this->clear_debug_buffer();
        openssl_pkey_export($this->ppkeypair, $this->privatekey_resource);
        //$this->debug("export_privatekey_to_string");
    }

    // Create random characters
    function generateRandomString($size) {
      srand( ( (double) microtime() ) * 1000000 );
      $string = '';
      $signs = 'abcdefghijklmnopqrstuvwxyz';
      $signs .= 'ABCDEFGHIJKLMNOPQRSTUWXYZ';
      $signs .= '01234567890123456789';
      for( $i = 0; $i < $size; $i++ ){
        $string .= $signs{ rand( 0, ( strlen( $signs ) - 1 ) ) };
      }
      $this->random_filename = $string;
    }       
    
    function get_certificate(){
        return $this->certificate_resource;
    }
    
    function get_certificate_file(){
        return $this->certificate_resource_file;
    }   
        
    function get_crypt(){
        return $this->crypttext;
    }
    
    function get_csr(){
        return $this->csr_resource;
    }
        
    function get_csr_file(){
        return $this->csr_resource_file;
    }   
            
    function get_ekey(){
        return $this->ekey;
    }
    
    function get_plain(){
        return $this->plaintext;
    }
        
    function get_privatekey(){
        return $this->privatekey_resource;
    }
    
    function get_privatekey_file(){
        return $this->privatekey_resource_file;
    }

    function get_privkeypass(){
        return $this->privkeypass;
    }

    function get_signature(){
        return $this->signature;
    }   

    function load_certificate($cert) {
        $this->clear_debug_buffer();
        if(DEBUG) echo "Certificate loaded from =" .$cert . "<br />";
        if($this->certificate_resource = openssl_x509_read ($cert)){
            if(DEBUG) echo "Certificate loaded<br /><br />";
        } else {
            if(DEBUG) echo "Certificate not loaded <br /><br />";
        }
        $this->debug("load_certificate");
    }

    function load_privatekey($arr) {
        $this->clear_debug_buffer();
        if(DEBUG) echo "Source loaded from =" .$arr[0] . "<br />";
        if($this->privatekey_resource = openssl_pkey_get_private($arr)){
            if(DEBUG) echo "Private key loaded<br /><br />";
        } else {
            if(DEBUG) echo "Private key not loaded <br /><br />";
        }
        $this->debug("load_privatekey");
    }

    function readf($path){
        //return file contents
        $fp=fopen($path,"r");
        $ret=fread($fp,8192);
        fclose($fp);
        return $ret;
    }

    function set_certificate($cert){
        $this->certificate_resource=$cert;
    }
    
    // Certificate stored in file
    function set_certificate_file(){
        $this->certificate_resource_file="certificate_".$this->random_filename.".pem";
    }
        
    function set_crypttext($txt){
        $this->crypttext=$txt;
    }
            
    // CSR stored in file
    function set_csr_file(){
        $this->csr_resource_file="csr_".$this->random_filename.".pem";
    }

    function set_dn($countryName = "NL",
                    $stateOrProvinceName = "Noord-Holland",
                    $localityName = "Zaandam",
                    $organizationName = "Mobilefish.com",
                    $organizationalUnitName = "Certification Services",
                    $commonName = "Mobilefish.com CA",
                    $emailAddress = "contact@mobilefish.com"){
                    
        $this->dn=Array(
            "countryName" => $countryName,
            "stateOrProvinceName" => $stateOrProvinceName,
            "localityName" => $localityName,
            "organizationName" => $organizationName,
            "organizationalUnitName" => $organizationalUnitName,
            "commonName" => $commonName,
            "emailAddress" => $emailAddress );
    }               

    function set_ekey($ekey){
        $this->ekey=$ekey;
    }

    function set_plain($txt){
        $this->plaintext=$txt;
    }

    // Privatekey can be text or file path
    function set_privatekey($privatekey, $isFile=0, $key_password=""){
        $this->clear_debug_buffer();
        if ($key_password) $this->privkeypass=$key_password;
        if ($isFile)$privatekey=$this->readf($privatekey);
        $this->privatekey_resource=openssl_get_privatekey($privatekey, $this->privkeypass);
        $this->debug("set_privatekey");
    }
    
    // Privatekey stored in file
    function set_privatekey_file(){
        $this->privatekey_resource_file="privatekey_".$this->random_filename.".pem";
    }

    // Set password for private key
    function set_privkeypass($pass){
        $this->privkeypass=$pass;
    }

    function set_signature($signature){
        $this->signature=$signature;
    }       
}

//=============== START USING THE CLASS =========

//=============== Initial setup ==================
echo "<h2><u>1. Initial setup</u></h2>\n";
$ossl = new OpenSSL(1);

// Set password
$pass="zPUp9mCzIrM7xQOEnPJZiDkBwPBV9UlITY0Xd3v4bfIwzJ12yPQCAkcR5BsePGVw
RK6GS5RwXSLrJu9Qj8+fk0wPj6IPY5HvA9Dgwh+dptPlXppeBm3JZJ+92l0DqR2M
ccL43V3Z4JN9OXRAfGWXyrBJNmwURkq7a2EyFElBBWK03OLYVMevQyRJcMKY0ai+
tmnFUSkH2zwnkXQfPUxg9aV7TmGQv/3TkK1SziyDyNm7GwtyIlfcigCCRz3uc77U
Izcez5wgmkpNElg/D7/VCd9E+grTfPYNmuTVccGOes+n8ISJJdW0vYX1xwWv5l
bK22CwD/l7SMBOz4M9XH0Jb0OhNxLza4XMDu0ANMIpnkn1KOcmQ4gB8fmAbBt";

$ossl->set_privkeypass($pass);
$ossl->generateRandomString(5);

$ossl->create_key_pair();

$ossl->set_dn();
$ossl->create_csr();
$ossl->export_csr_to_string();
echo "The Certificate Signing Request (CSR):<br />\n";
echo "<textarea rows='15' cols='65'>".HTMLENTITIES($ossl->get_csr())."</textarea><br />\n";
$ossl->export_csr_to_file();
echo "Certificate Signing Request as a file: <a href='".HTML_LOCATION.$ossl->get_csr_file()."' >".$ossl->get_csr_file()."</a><br /><br />\n";

$ossl->create_self_signed_certificate();
$ossl->export_certificate_to_string();
echo "The Certificate:<br />\n";
echo "<textarea rows='20' cols='65'>".HTMLENTITIES($ossl->get_certificate())."</textarea><br />\n";
$ossl->export_certificate_to_file();
echo "Certificate as a file: <a href='".HTML_LOCATION.$ossl->get_certificate_file()."' >".$ossl->get_certificate_file()."</a><br /><br />\n";

$ossl->export_privatekey_to_string();
echo "The Private Key:<br />\n";
echo "<textarea rows='15' cols='65'>".HTMLENTITIES($ossl->get_privatekey())."</textarea><br />\n";
$ossl->export_privatekey_to_file();
echo "Private Key as a file: <a href='".HTML_LOCATION.$ossl->get_privatekey_file()."' >".$ossl->get_privatekey_file()."</a><br /><br />\n";


// Store the private key and certificate in a variable. No need to create them each time.
$privatekey=$ossl->get_privatekey();
$privatekey_file=$ossl->get_privatekey_file();
$certificate=$ossl->get_certificate();
$certificate_file=$ossl->get_certificate_file();

//=============== Method A ==================
echo "<h2><u>2. Encrypt and Decrypt text (Method A)</u></h2>\n";
echo "The following function is used:<br />\n";
echo "openssl_seal() <br />\n";
echo "The function openssl_seal is intended for general encryption and decryption.<br />\n";
echo "There is no limit on the size of the string to be encrypted.\n";


echo "<h3><u>2.1. Encrypt text</u></h3>\n";
// Wipe clean and start again
unset($ossl);
$ossl = new OpenSSL();
// Get the certificate
$ossl->set_certificate($certificate);

$testtext="It is the policy of the United States to deter, defeat and respond vigorously to all terrorist attacks on our territory and against our citizens, or facilities, whether they occur domestically, in international waters or airspace or on foreign territory. The United States regards all such terrorism as a potential threat to national security as well as a criminal act and will apply all appropriate means to combat it. In doing so, the U.S. shall pursue vigorously efforts to deter and preempt, apprehend and prosecute, or assist other governments to prosecute, individuals who perpetrate or plan to perpetrate such attacks.\n";

echo "The following text will be encrypted:<br />\n";
echo "<textarea rows='10' cols='65'>".htmlentities($testtext)."</textarea><br /><br />\n";

// Encrypt the text
$ossl->encrypt($testtext);
// Get the encrypted text
$crypt=$ossl->get_crypt();
echo "The encrypted text looks like:<br />\n";
echo "<textarea rows='10' cols='65'>".htmlentities($crypt)."</textarea><br /><br />\n";
echo "The envelope key, returned during encryption, looks like:<br />\n";
// Get the envelope key also needed to decrypt the encrypted text


$ekey=$ossl->get_ekey();
echo "<textarea rows='5' cols='65'>".htmlentities($ekey)."</textarea><br /><br />\n";

echo "<h3><u>2.2. Decrypt text</u></h3>\n";
// Wipe clean and start again
unset($ossl);
$ossl = new OpenSSL();
// Get the private key
$ossl->set_privatekey($privatekey, false, $pass);
$ossl->decrypt($crypt, $ekey);
echo "The decrypted text looks like:<br />\n";
echo "<textarea rows='10' cols='65'>".htmlentities($ossl->get_plain())."</textarea><br /><br />\n";


//=============== Method B ==================
echo "<h2><u>3. Encrypt and Decrypt text (Method B)</u></h2>\n";
echo "The following functions are used:<br />\n";
echo "openssl_public_encrypt() <br />\n";
echo "openssl_private_decrypt() <br />\n";
echo "Both functions are not intended for general encryption and decryption.<br />\n";
echo "For that, you must use openssl_seal() and openssl_open().<br />\n";
echo "A maximum limit on the size of the string to be encrypted is 117 characters.\n";

echo "<h3><u>3.1. Encrypt text</u></h3>\n";
// Wipe clean and start again
unset($ossl);
$ossl = new OpenSSL(1);
// Get the certificate
$ossl->set_certificate($certificate);

$testtext="123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567";

echo "The following text will be encrypted:<br />\n";
echo "<textarea rows='5' cols='65'>".htmlentities($testtext)."</textarea><br /><br />\n";

// Encrypt the text
$ossl->encrypt_public($testtext);

// Get the encrypted text
$crypt=$ossl->get_crypt();
echo "The encrypted text looks like:<br />\n";
echo "<textarea rows='5' cols='65'>".htmlentities($crypt)."</textarea><br /><br />\n";

echo "<h3><u>3.2. Decrypt text</u></h3>\n";
// Wipe clean and start again
unset($ossl);
$ossl = new OpenSSL();
// Get just the certificate
$ossl->set_privatekey($privatekey);
$ossl->decrypt_private($crypt);
echo "The decrypted text looks like:<br />\n";
echo "<textarea rows='5' cols='65'>".htmlentities($ossl->get_plain())."</textarea><br /><br />\n";

//=============== Method C ==================
echo "<h2><u>4. Encrypt and Decrypt text (Method C)</u></h2>\n";
echo "The following functions are used:<br />\n";
echo "openssl_private_encrypt() <br />\n";
echo "openssl_public_decrypt() <br />\n";
echo "Both functions are not intended for general encryption and decryption.<br />\n";
echo "For that, you must use openssl_seal() and openssl_open().<br />\n";
echo "A maximum limit on the size of the string to be encrypted is 117 characters.\n";

echo "<h3><u>4.1. Encrypt text</u></h3>\n";
// Wipe clean and start again
unset($ossl);
$ossl = new OpenSSL();
// Get the private key
$ossl->set_privatekey($privatekey);

$testtext="123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567";

echo "The following text will be encrypted:<br />\n";
echo "<textarea rows='5' cols='65'>".htmlentities($testtext)."</textarea><br /><br />\n";

// Encrypt the text
$ossl->encrypt_private($testtext);

// Get the encrypted text
$crypt=$ossl->get_crypt();
echo "The encrypted text looks like:<br />\n";
echo "<textarea rows='5' cols='65'>".htmlentities($crypt)."</textarea><br /><br />\n";

echo "<h3><u>4.2. Decrypt text</u></h3>\n";
// Wipe clean and start again
unset($ossl);
$ossl = new OpenSSL();
// Get the certificate
$ossl->set_certificate($certificate);
$ossl->decrypt_public($crypt);
echo "The decrypted text looks like:<br />\n";
echo "<textarea rows='5' cols='65'>".htmlentities($ossl->get_plain())."</textarea><br /><br />\n";

//=============== Signature ==================

echo "<h2><u>5. Signature</u></h2>\n";

echo "<h3><u>5.1. Create signature</u></h3>\n";
// Wipe clean and start again
unset($ossl);
$ossl = new OpenSSL();
// Get the private key
$ossl->set_privatekey($privatekey);

$testtext="Hello World";

echo "The following text will be signed:<br />\n";
echo "<textarea rows='5' cols='65'>".htmlentities($testtext)."</textarea><br /><br />\n";

// Create signature 
$ossl->create_signature($testtext);

// Get the signature
$signature=$ossl->get_signature();
echo "The signature looks like:<br />\n";
echo "<textarea rows='5' cols='65'>".htmlentities($signature)."</textarea><br /><br />\n";


echo "<h3><u>5.2. Verify signature</u></h3>\n";
// Wipe clean and start again
unset($ossl);
$ossl = new OpenSSL();
// Get the certificate
$ossl->set_certificate($certificate);

$testtext="Hello World";

// Set signatute to be checked
$ossl->set_signature($signature);

// Check signature 
$ok = $ossl->check_signature($testtext);

// State whether signature is okay or not
if ($ok == 1) {
    echo "Signature is good.";
} elseif ($ok == 0) {
    echo "Signature is bad.";
} else {
    echo "There seems to be an error checking the signature.";
}

//=============== Miscellaneous ==================

echo "<h2><u>6. Miscellaneous</u></h2>\n";
unset($ossl);
$ossl = new OpenSSL();
// Get the private key
$ossl->set_privatekey($privatekey);
$ossl->set_certificate($certificate);

echo "<h3><u>6.1. Check if private key match the certificate</u></h3>\n";
$ok = $ossl->check_privatekey_match_certificate();

// State whether signature is okay or not
if ($ok == 1) {
    echo "Private key does match the certificate.";
} elseif ($ok == 0) {
    echo "Private key does not match the certificate.";
} else {
    echo "There seems to be an error when matching the private key and the certificate.";
}

echo "<h3><u>6.2. Check if a certificate can be used for a particular purpose</u></h3>\n";

$purpose = array();
$purpose[0]=X509_PURPOSE_SSL_CLIENT;    //Can the certificate be used for the client side of an SSL connection?
$purpose[1]=X509_PURPOSE_SSL_SERVER;    //Can the certificate be used for the server side of an SSL connection?
$purpose[2]=X509_PURPOSE_NS_SSL_SERVER; //Can the cert be used for Netscape SSL server?
$purpose[3]=X509_PURPOSE_SMIME_SIGN;    //Can the cert be used to sign S/MIME email?
$purpose[4]=X509_PURPOSE_SMIME_ENCRYPT; //Can the cert be used to encrypt S/MIME email?
$purpose[5]=X509_PURPOSE_CRL_SIGN;      //Can the cert be used to sign a certificate revocation list (CRL)?
$purpose[6]=X509_PURPOSE_ANY;           //Can the cert be used for Any/All purposes?

for($i=0;$i<sizeof($purpose);$i++) {
    $ok = $ossl->check_certificate_purpose($i);
    
    if ($ok == 1) {
        echo "Certificate can be used for purpose: ".$i."<br />\n";
    } elseif ($ok == 0) {
        echo "Certificate can not be used for purpose: ".$i."<br />\n";
    } else {
        echo "There seems to be an error when checking the certificate purpose.<br />\n";
    }
}

echo "<h3><u>6.3. Display certficate information</u></h3>\n";

$array = $ossl->display_certificate_information(false);

foreach ($array as $key => $value) {
    echo "<b>[".$key ."]</b><br />";
    if(is_array($array[$key])){
        foreach ($array[$key] as $key2 => $value2) {
            echo "<i>[".$key2 ."]</i><br />";
            if(is_array($array[$key][$key2])){
                foreach ($array[$key][$key2] as $key3 => $value3) {
                    echo $key3 ." - ".$value3. "<br />";
                }
            } else {
                echo $value2 ."<br />";
            }   
        }
    } else {
        echo $value ."<br />";
    }   
}

echo "<h3><u>6.4. Loading a private key</u></h3>\n";
echo "Load private key:<br />\n";
// You must add prefix "file://"
$private_data = array("file://".FILE_LOCATION.$privatekey_file, $pass);
$ossl->load_privatekey($private_data);

echo "<h3><u>6.5. Loading a certificate</u></h3>\n";
echo "Load certificate:<br />\n";
$ossl->load_certificate("file://".FILE_LOCATION.$certificate_file);


?> 