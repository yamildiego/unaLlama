<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

$config = array();
$config['protocol'] = 'ssmtp';
$config['smtp_host'] = 'ssl://smtp.gmail.com';
$config['smtp_port'] = '465';
$config['smtp_timeout'] = '7';
$config['smtp_user'] = 'yamildiego91@gmail.com';
$config['smtp_pass'] = 'uouam6tqvp';
$config['charset'] = 'utf-8';
$config['newline'] = "\r\n";
$config['mailtype'] = 'html'; // or html
$config['validation'] = true; // bool whether to validate email or not
