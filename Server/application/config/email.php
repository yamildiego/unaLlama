<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

$config['protocol'] = 'smtp';
$config['smtp_host'] = 'ssl://smtp.gmail.com';
$config['smtp_port'] = 465;
$config['smtp_user'] = 'yamildiego91@gmail.com';
$config['smtp_pass'] = 'uouam6tqvp';
//$config['smtp_timeout'] = '7';
//$config['charset'] = 'utf-8';
$config['newline'] = "\r\n";
$config['mailtype'] = 'html'; // or html
//$config['validation'] = TRUE; // bool whether to validate email or not

//$config = array(
//    'protocol' => 'smtp',
//    'smtp_host' => 'ssl://smtp.gmail.com',
////            'smtp_host' => 'smtp.gmail.com',
//    'smtp_port' => 465,
//    'mailtype' => 'html',
//    'smtp_user' => 'yamildiego@gmail.com',
//    'smtp_pass' => 'uouam6tqvp',
//    'newline' => "\r\n",
//);
