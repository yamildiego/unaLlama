<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

$config = array();
$config['protocol'] = 'ssmtp';
$config['smtp_host'] = 'cloud72.hostgator.com';
$config['smtp_port'] = '465';
$config['smtp_timeout'] = '7';
$config['smtp_user'] = 'info@unallama.com.ar';
$config['smtp_pass'] = 'XXXXXXXX';
$config['charset'] = 'utf-8';
$config['newline'] = "\r\n";
$config['mailtype'] = 'html'; // or html
$config['validation'] = true; // bool whether to validate email or not