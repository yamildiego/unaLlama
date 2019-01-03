<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class UserController extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Article_model');
    }

    public function getLoginStatus_get()
    {
        $data = array('status' => null);

        if ($this->session->userdata('logout') === 'no-logged') {
            $data['status'] = 'session_expired';
            $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
        } else {
            $user = $this->_existsControlUser();
        }

        $data['data'] = array(
            'id' => $user->getId(),
            'name' => utf8_encode($user->getName()),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'photo' => ($user->getPhoto() == null) ? 'user.png' : $user->getPhoto());

        $data['status'] = 'OK';

        $this->response($data, REST_Controller::HTTP_OK); // OK (200)
    }

    public function getUserProfileData_get($id)
    {
        try {
            if ($id == null) {
                throw new Exception('unexpected_error');
            } else {
                $user = $this->User_model->load($id);
                if ($user == null) {
                    throw new Exception('unexpected_error');
                } else {
                    $data = array('status' => 'OK');
                    $data['data'] = array(
                        'id' => $user->getId(),
                        'name' => utf8_encode($user->getName()),
                        'username' => $user->getUsername(),
                        'email' => $user->getEmail(),
                        'total' => $this->Article_model->getTotalArticles($user->getId()),
                        'photo' => ($user->getPhoto() == null) ? 'user.png' : $user->getPhoto());

                    $this->response($data, REST_Controller::HTTP_OK); // OK (200)
                }
            }
        } catch (Exception $ex) {
            $data['status'] = 'unexpected_error';
            $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
        }
    }

    public function newUser_post()
    {
        $data = array('status' => null);
        $errors = array();

        $userNew = new User();
        $userNew->setName($this->post('name'));
        $userNew->setUsername($this->post('username'));
        $userNew->setEmail($this->post('email'));
        $userNew->setPassword($this->post('password'));

        if ($userNew->getName() == '' || $userNew->getName() == null) {
            $errors[] = "El campo nombre es obligatorio.";
        }

        if ($userNew->getUsername() == '' || $userNew->getUsername() == null) {
            $errors[] = "El campo usuario es obligatorio.";
        } elseif (strlen($userNew->getUsername()) < 5) {
            $errors[] = "El campo usuario debe contener al menos 5 caracteres de longitud.";
        } elseif (!preg_match('/^[a-zA-Z0-9]+$/', $userNew->getUsername())) {
            $errors[] = "El campo usuario debe contener solo caracteres alfanuméricos.";
        } else {
            $user = $this->User_model->get_by_username($userNew->getUsername());
            if ($user != null) {
                $errors[] = "El usuario ya se encuentra registrado.";
            }

        }

        if ($userNew->getEmail() == '' || $userNew->getEmail() == null) {
            $errors[] = "El campo correo electrónico es obligatorio.";
        } elseif (!filter_var($userNew->getEmail(), FILTER_VALIDATE_EMAIL)) {
            $errors[] = "El campo correo electrónico debe contener una dirección de correo válida.";
        } else {
            $user = $this->User_model->get_by_email($userNew->getEmail());
            if ($user != null) {
                $errors[] = "El correo electrónico ya se encuentra registrado.";
            }
        }

        if ($userNew->getPassword() == '' || $userNew->getPassword() == null) {
            $errors[] = "El campo contraseña es obligatorio.";
        } elseif (strlen($userNew->getPassword()) < 5) {
            $errors[] = "El campo contraseña debe contener al menos 5 caracteres de longitud.";
        }

        if (count($errors) > 0) {
            $data = array('status' => 'errors_exists', 'errors' => $errors);
            $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
        } else {
            $code = md5(date("YmdHis"));
            $userNew->setPassword(md5($userNew->getPassword()));
            $userNew->setActive(false);
            $userNew->setPhoto(null);
            $userNew->setLastConnection(null);
            $userNew->setRequestPassword(null);
            $userNew->setCodeActivation($code);
            $userNew->setCodePassword(null);
            $statusEmail = false;

            try {
                $user = $this->User_model->save($userNew);
                $statusEmail = $this->_send_email($this->config->item('email_noreply'), $userNew->getEmail(), $this->load->view('email/emailCreateUser_view', array('user' => $userNew, 'url' => $this->config->item('url_frontend') . '#!/validateAccount/' . $user->getId() . '/' . $code), true), 'Confirmación de la cuenta de ' . $this->config->item('name_web'));
            } catch (Exception $ex) {
                $data['status'] = 'unexpected_error';
                $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
            }

            if ($statusEmail) {
                $data['status'] = 'OK';
                $this->response($data, REST_Controller::HTTP_CREATED); // CREATED (201)
            } else {
                $data['status'] = 'unexpected_error';
                $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
            }

        }
    }
    public function login_post()
    {
        $errors = array();

        $this->session->unset_userdata('logout');
        $data_post = new stdClass();
        $data_post->username = $this->post('username');
        $data_post->password = ($this->post('password') == '' || $this->post('password') == null) ? null : md5($this->post('password'));

        if ($data_post->username == '' || $data_post->username == null) {
            $errors[] = "El campo usuario/email es obligatorio.";
        }

        if ($data_post->password == '' || $data_post->password == null) {
            $errors[] = "El campo contraseña es obligatorio.";
        }

        if (count($errors) > 0) {
            $data = array('status' => 'errors_exists', 'errors' => $errors);
            $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
        } else {
            $user = $this->User_model->get_by_username_password($data_post);

            if ($user == null) {
                $data = array('status' => 'incorrect_data');
                $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
            } elseif (!$user->getActive()) {
                $data = array('status' => 'no_active');
                $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
            } else {
                $this->session->set_userdata('userId', $user->getId());
                $this->getLoginStatus_get();
            }
        }
    }

    public function loginFb_post()
    {
        $data_post = new stdClass();
        $data_post->idFB = $this->post('id');
        $data_post->name = $this->post('first_name');
        $data_post->picture = $this->post('picture');
        $data_post->email = $this->post('email');

        $this->session->unset_userdata('logout');

        $user = ($data_post->idFB == null) ? null : $this->User_model->get_user_fb_id($data_post->idFB);

        if ($user == null) { // si no hay usuario con ese id de fb jamas se logeo con facebook
            $user = $this->User_model->get_by_email($data_post->email);
            if ($user == null) { // si el email no existe en la db el usuario es completamente nuevo
                $user = new User();
                $user->setPassword(null);
                $user->setUsername(null);
                $user->setActive(true);
                $user->setLastConnection(date_create());
                $user->setRequestPassword(null);
                $user->setCodeActivation(null);
                $user->setCodePassword(null);
            }
        }

        $user->setName($data_post->name);

        if ($data_post->picture != null && is_array($data_post->picture) && isset($data_post->picture['data']) && isset($data_post->picture['data']['url'])) {
            $user->setPhoto($data_post->picture['data']['url']);
        }

        $user->setEmail($data_post->email);
        $user->setIdFb($data_post->idFB);

        try {
            $userUpdated = $this->User_model->save($user);
        } catch (Exception $exc) {
            $data['status'] = 'error_saving';
            $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
        }

        $userId = $this->session->userdata('userId');

        if ($userId == null) {
            $userId = $this->session->set_userdata('userId', $userUpdated->getId());
        } elseif ($user->getId() != $this->session->userdata('userId')) {
            $this->session->set_userdata('userId', $user->getId());
        }

        $this->getLoginStatus_get();
    }

    public function logout_get()
    {
        $this->session->set_userdata('logout', 'no-logged');
        $this->session->set_userdata('userId', null);
        $data['status'] = 'OK';

        $this->response($data, REST_Controller::HTTP_OK); // OK (200)
    }

    public function forgotMyPassword_post()
    {
        $data = array('status' => null);
        $errors = array();

        $data_post = new stdClass();
        $data_post->email = $this->post('email');

        if ($data_post->email == '' || $data_post->email == null) {
            $errors[] = "El campo correo electrónico es obligatorio.";
        } elseif (!filter_var($data_post->email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "El campo correo electrónico debe contener una dirección de correo válida.";
        } else {
            $user = $this->User_model->get_by_email($data_post->email);
            if ($user == null) {
                $errors[] = "El correo electrónico no se encuentra registrado.";
            }

        }

        if (count($errors) > 0) {
            $data = array('status' => 'errors_exists', 'errors' => $errors);
            $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
        } else {
            $user = $this->User_model->get_by_email($data_post->email);

            $code = md5($user->getEmail() . date('siGdmY') . $user->getName());
            $user->setCodePassword($code);
            $user->setRequestPassword(date_create());
            $statusEmail = false;

            try {
                $user = $this->User_model->save($user);
                $statusEmail = $this->_send_email($this->config->item('email_noreply'), $data_post->email, $this->load->view('email/emailRecoveryPassword_view', array('user' => $user, 'url' => $this->config->item('url_frontend') . '#!/restablecer-contraseña/' . $user->getId() . '/' . $code), true), 'Solicitud para restablecer la contraseña.');
            } catch (Exception $exc) {
                $data['status'] = 'unexpected_error';
                $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
            }

            if ($statusEmail) {
                $data['status'] = 'OK';
                $this->response($data, REST_Controller::HTTP_CREATED); // CREATED (201)
            } else {
                $data['status'] = 'unexpected_error';
                $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
            }
        }
    }

    public function resetPassword_post()
    {
        $errors = array();

        $data_post = new stdClass();
        $data_post->password = $this->post('password');
        $data_post->code = $this->post('code');
        $data_post->userId = $this->post('userId');

        if ($data_post->password == '' || $data_post->password == null) {
            $errors[] = "El campo contraseña es obligatorio.";
        } elseif (strlen($data_post->password) < 5) {
            $errors[] = "El campo contraseña debe contener al menos 5 caracteres de longitud.";
        }

        if (count($errors) > 0) {
            $data = array('status' => 'errors_exists', 'errors' => $errors);
            $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
        } else {
            $user = $this->User_model->verify_code_password($data_post->userId, $data_post->code);

            if ($user != null) {
                $user->setPassword(md5($data_post->password));
                $user->setActive(true);
                $user->setCodePassword(null);
                $user->setRequestPassword(null);
                try {
                    $user = $this->User_model->save($user);
                    $data['status'] = 'OK';
                    $this->response($data, REST_Controller::HTTP_CREATED); // CREATED (201)
                } catch (Exception $exc) {
                    $data['status'] = 'unexpected_error';
                    $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
                }
            } else {
                $data['status'] = 'session_expired';
                $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
            }
        }
    }

    public function checkCode_post()
    {
        try {
            $data_post = new stdClass();
            $data_post->userId = $this->post('userId');
            $data_post->code = $this->post('code');

            if ($data_post->userId == null || $data_post->userId == '' || $data_post->code == null || $data_post->code == '') {
                throw new Exception('unexpected_error');
            }

            $user = $this->User_model->verify_code_password($data_post->userId, $data_post->code);

            if ($user == null) {
                throw new Exception('unexpected_error');
            } else {
                $data['status'] = 'OK';
                $this->response($data, REST_Controller::HTTP_CREATED); // OK (200)
            }
        } catch (Exception $ex) {
            $data['status'] = 'unexpected_error';
            $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
        }
    }

    public function validateAccount_post()
    {
        try {
            $data_post = new stdClass();
            $data_post->userId = $this->post('userId');
            $data_post->code = $this->post('code');

            if ($data_post->userId == null || $data_post->code == null) {
                throw new Exception('unexpected_error');
            } else {
                $user = $this->User_model->get_user_by_id_code_activation($data_post->userId, $data_post->code);
                if ($user != null) {

                    if ($user->getActive() == false) {
                        $user->setActive(true);
                        $this->User_model->save($user);
                    }

                    $data['status'] = 'OK';
                    $this->response($data, REST_Controller::HTTP_CREATED); // OK (200)
                } else {
                    throw new Exception('unexpected_error');
                }

            }
        } catch (Exception $ex) {
            $data['status'] = 'unexpected_error';
            $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
        }
    }

    public function removePhoto_get($userId)
    {
        try {
            $user = $this->isLoged();

            if ($userId != null && $user != null && $user->getId() == $userId) {
                $user->setPhoto(null);
                $this->User_model->save($user);
                $data = array('status' => 'OK');
                $this->response($data, REST_Controller::HTTP_CREATED); // OK (200)
            } else {
                throw new Exception('unexpected_error');
            }
        } catch (Exception $ex) {
            $data['status'] = 'unexpected_error';
            $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
        }
    }

    public function editPassword_post()
    {
        try {
            $data = array('status' => 'OK');

            $user = $this->isLoged();

            $userId = $this->post('userId');
            $password = $this->post('password');
            $newPassword = $this->post('newPassword');

            if ($userId != null && $user != null && $user->getId() == $userId) {
                $errors = array();

                if ($user->getPassword() == md5($password)) {
                    if ($newPassword == '' || $newPassword == null) {
                        $errors[] = "El campo nueva contraseña es obligatorio.";
                    } elseif (strlen($newPassword) < 5) {
                        $errors[] = "El campo nueva contraseña debe contener al menos 5 caracteres de longitud.";
                    }
                } else {
                    $errors[] = "Los datos ingresados son incorrectos.";
                }

                if (count($errors) > 0) {
                    $data = array('status' => 'errors_exists', 'errors' => $errors);
                    $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
                } else {
                    $user->setPassword(md5($newPassword));
                    $this->User_model->save($user);
                    $this->response($data, REST_Controller::HTTP_CREATED); // OK (200)
                }

            } else {
                throw new Exception('unexpected_error');
            }
        } catch (Exception $ex) {
            $data['status'] = 'unexpected_error';
            $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
        }
    }

    public function editPhoto_post()
    {
        try {
            $user = $this->isLoged();

            $userId = $this->post('userId');
            $photo = $this->post('photo');

            if ($userId != null && $user != null && $user->getId() == $userId && $photo != null && $photo != "") {
                $user->setPhoto($photo);
                $this->User_model->save($user);
                $data = array('status' => 'OK');
                $this->response($data, REST_Controller::HTTP_CREATED); // OK (200)
            } else {
                throw new Exception('unexpected_error');
            }
        } catch (Exception $ex) {
            $data['status'] = 'unexpected_error';
            $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
        }
    }

    public function _existsControlUser()
    {
        $data = array('status' => null);
        $userId = $this->session->userdata('userId');
        $user = ($userId != null) ? $this->User_model->load($userId) : null;
        if ($userId == null || $user == null) {
            $data['status'] = 'session_expired';
            $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
        }
        return $user;
    }

    public function _send_email($p_email_from, $p_email_to, $p_message, $p_subject)
    {
        $this->load->library('email');
        $this->email->from($p_email_from, $this->config->item('name_web'));
        $this->email->to($p_email_to);
        $this->email->subject($p_subject);
        $this->email->message($p_message);
        return $this->email->send();
    }

    private function isLoged()
    {
        $userId = $this->session->userdata('userId');

        if ($userId == null) {
            $data['status'] = 'session_expired';
            $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
        } else {
            $user = $this->User_model->load($userId);

            if ($userId == null) {
                $data['status'] = 'session_expired';
                $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
            }

            return $user;
        }
    }
}
