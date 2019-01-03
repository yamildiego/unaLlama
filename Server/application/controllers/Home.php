<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Home extends REST_Controller
{

    public function index_get()
    {
        var_dump("AA");
        // $doc = new Doctrine();
        // $doc->generate_classes();
        // $this->response("OK", REST_Controller::HTTP_OK); // OK (200)
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
        $this->load->library('email');
        $this->email->set_mailtype("html");
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