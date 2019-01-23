<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Home extends REST_Controller
{

    public function index_get()
    {
        // $this->load->library('email');
        // $this->email->initialize();
        // $this->email->from('yamildiego91@gmail.com', 'Yamil Diego');
        // $this->email->to('yamildiego@gmail.com');
        // $this->email->subject('Email Test');
        // $this->email->message('Testing the email class.');
        // $a = $this->email->send();

        $source_img = 'source.jpg';

        $this->compress('./assets/php/files/7da96dcd4acf0e1f87cb7256156dc15a.png', './assets/php/files/nq/ss.jpg', 95);

        // $destination_img = 'destination.jpg';

        // $d = compress($source_img, $destination_img, 90);

        // $doc = new Doctrine();
        // $doc->generate_classes();
        // $this->response("OK", REST_Controller::HTTP_OK); // OK (200)
    }

    public function compress($source, $destination, $quality)
    {

        $info = getimagesize($source);

        if ($info['mime'] == 'image/jpeg') {
            $image = imagecreatefromjpeg($source);
        } elseif ($info['mime'] == 'image/gif') {
            $image = imagecreatefromgif($source);
        } elseif ($info['mime'] == 'image/png') {
            $image = imagecreatefrompng($source);
        }

        imagejpeg($image, $destination, $quality);

        return $destination;
    }

    public function sendMsg_post()
    {
        $obj = (object) array('name' => $this->post('name'), 'email' => $this->post('email'), 'message' => $this->post('query'));

        $errors = array();

        if ($obj->name == '' || $obj->name == null) {
            $errors[] = "El campo nombre es obligatorio.";
        }

        if ($obj->email == '' || $obj->email == null) {
            $errors[] = "El campo email es obligatorio.";
        } elseif (!filter_var($obj->email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "El campo correo electrónico debe contener una dirección de correo válida.";
        }

        if ($obj->message == '' || $obj->message == null) {
            $errors[] = "El campo consulta es obligatorio.";
        }

        if (count($errors) > 0) {
            $data = array('status' => 'errors_exists', 'errors' => $errors);
            $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
        } else {
            $mensaje = $this->load->view('email_contact_view', array('obj' => $obj), true);
            $status_email = $this->_send_email('info@unallama.com.ar', 'yamildiego@gmail.com', $mensaje, 'Consulta desde la Web: ' . 'Consulta WEB Unallama');

            if ($status_email) {
                $data = array('status' => "OK");
                $this->response($data, REST_Controller::HTTP_OK); // OK (200)
            } else {
                $this->response(array('status' => 'unexpected_error'), REST_Controller::HTTP_FOUND); // FOUND (302)
            }
        }
    }

    public function _send_email($p_email_from, $p_email_to, $p_message, $p_subject)
    {
        $p_email_from = 'yamildiego91@gmail.com';
        $this->load->library('email');
        $this->email->initialize();
        $this->email->from($p_email_from, 'Unallama');
        $this->email->to($p_email_to);
        $this->email->subject($p_subject);
        $this->email->message($p_message);
        $a = $this->email->send();

        if (!$a) {
            echo $this->email->print_debugger();
        }
        return $a;
    }

}
