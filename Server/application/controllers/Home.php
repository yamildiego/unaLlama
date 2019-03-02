<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Home extends REST_Controller
{

    public function index_get()
    {
        // $this->cleanPhotos();
        // $doc = new Doctrine();
        // $doc->generate_classes();
        // $this->response("OK", REST_Controller::HTTP_OK); // OK (200)
    }

    private function cleanPhotos()
    {
        $this->load->model('Photo_model');
        $ruta = "./assets/php/files";
        $directorio = opendir($ruta); //ruta actual
        while ($file = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
        {
            if (is_dir($file)) //verificamos si es o no un directorio
            {

            } else {
                if ($file != "user.png" && $file != "lowq" && $file != "thumbnail") {
                    $photo = $this->Photo_model->getPhotoByName($file);
                    echo $file . (($photo == null) ? ' no ' : ' si ');

                    if ($photo == null) {
                        if (file_exists($ruta . "/" . $file)) {
                            unlink($ruta . "/" . $file);
                            echo ' T ';
                        }
                        if (file_exists($ruta . "/lowq/" . $file)) {
                            unlink($ruta . "/lowq/" . $file);
                            echo ' T ';
                        }
                        if (file_exists($ruta . "/thumbnail/" . $file)) {
                            unlink($ruta . "/thumbnail/" . $file);
                            echo ' T ';
                        }
                    }

                    echo "<br />";
                }
            }
        }
    }

    public function getCategories_get()
    {
        $data = array('status' => null);
        $categories = $this->Category_model->get_categories();
        $categoriesData = array();

        foreach ($categories as $category) {
            $categoriesData[$category->getId()] = array('id' => $category->getId(), 'name' => $category->getName(), 'icon' => $category->getIcon(), 'quantity_products' => $this->Article_model->getQuantityProducts($category->getId()));
        }

        $data['status'] = 'OK';
        $data['data'] = $categoriesData;

        $this->response($data, REST_Controller::HTTP_OK); // OK (200)
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

    private function _send_email($p_email_from, $p_email_to, $p_message, $p_subject)
    {
        $p_email_from = 'info@unallama.com.ar';
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
