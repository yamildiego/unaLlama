<?php
defined('BASEPATH') or exit('No direct script access allowed');
define('FACEBOOK_SDK_V4_SRC_DIR', APPPATH . '../assets/php-graph-sdk/src/Facebook/');

require_once APPPATH . '../assets/php-graph-sdk/src/Facebook/autoload.php';
require APPPATH . 'libraries/REST_Controller.php';

class FBController extends REST_Controller
{
    private $appId = "1194368740723464";
    private $appSecret = "8be1f3511fed28524fe60fbd02178c20";
    private $pageAccessToken = "EAAQZBRaSFiwgBAMG0qLC4KZB9RQxFFZBKyTDCD093d8vo7dFQgddkg89yAmofIK1l1zhZAInEKbLokEeZCs0wAZBmth8tv5DN6YJJl6MwYfGvg9ObqOxeZAVXxUEsGPi9SwFHBfUjqdK7IeBalqeZASlpTKAcFCComO6cBShk8qiuAZDZD";
    private $pageId = "341522199799094";

    public function index_get()
    {
    }

    public function index_post()
    {
        $data = array('status' => null);

        $user = $this->isLoged();

        $this->load->model('Operation_model');
        $this->load->model('Article_model');

        $fb = new Facebook\Facebook(array(
            'app_id' => $this->appId,
            'app_secret' => $this->appSecret,
        ));

        $articleId = $this->post('articleId');

        if ($articleId != null && $articleId != 0) {
            $article = $this->Article_model->load($articleId);
            if ($article != null) {
                $dataFB = array();
                $dataFB['operation'] = $this->replaceWord($this->Operation_model->getName($article->getOperation()));
                $dataFB['title'] = $article->getTitle();
                $dataFB['description'] = $article->getDescription();
                if ($article->getPrice() != null && $article->getPrice() != 0 && $article->getPrice() != 0.0) {
                    $dataFB['price'] = "\n" . '𝗣𝗥𝗘𝗖𝗜𝗢: $' . number_format($article->getPrice(), 2, ',', '.');
                } else {
                    $dataFB['price'] = "\n";
                }

                $dataFB['link'] = "https://www.unallama.com.ar/#!/ver-anuncio/" . $article->getId();
            }
        }

        if (isset($dataFB)) {
            $attachment = array(
                'message' => "|| " . $dataFB['operation'] . " ||\n\n" . $dataFB['title'] . $dataFB['price'] . "\n" . "\n" . $dataFB['description'],
                'link' => $dataFB['link'],
            );
        } else {
            $data['status'] = 'ignore_data';
            $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
            exit;
        }

        $now = new DateTime();
        $now->modify('+ 8 minutes');

        if ($user->getId() === $article->getUser()->getId() && $now > $article->getDateCreation() && $article->getPostId() == null) {
            try {
                $response = $fb->post('/' . $this->pageId . '/feed', $attachment, $this->pageAccessToken);
                if ($response->getHttpStatusCode() == 200) {
                    $body = $response->getDecodedBody();

                    if ($body["id"]) {
                        $article->setPostId($body["id"]);
                        $article = $this->Article_model->save($article);
                    }

                    $data['status'] = 'OK';
                    $this->response($data, REST_Controller::HTTP_OK); // OK (200)
                } else {
                    $data['status'] = 'unexpected_error';
                    $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
                    exit;
                }

            } catch (FacebookResponseException $e) {
                // echo 'Graph returned an error: ' . $e->getMessage();
                $data['status'] = 'unexpected_error_SDK1';
                $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
                exit;
            } catch (FacebookSDKException $e) {
                // echo 'Facebook SDK returned an error: ' . $e->getMessage();
                $data['status'] = 'unexpected_error_SDK2';
                $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
                exit;
            }
        } else {
            $data['status'] = 'ignore';
            $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
            exit;
        }
    }

    private function isLoged()
    {
        $userId = $this->session->userdata('userId');

        if ($userId == null) {
            $data['status'] = 'ignore';
            $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
        } else {
            $user = $this->User_model->load($userId);

            if ($userId == null) {
                $data['status'] = 'ignore';
                $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
            }

            return $user;
        }
    }

    private function replaceWord($word)
    {
        $word = strtoupper($word);
        $wordNew = "";
        for ($i = 0; $i < strlen($word); $i++) {
            $wordNew .= $this->replaceLetter($word[$i]);
        }
        return $wordNew;
    }

    private function replaceLetter($letter)
    {
        $newLetter = "";
        switch ($letter) {
            case ' ':$newLetter = ' ';
                break;
            case 'A':$newLetter = '𝑨';
                break;
            case 'B':$newLetter = '𝑩';
                break;
            case 'C':$newLetter = '𝑪';
                break;
            case 'D':$newLetter = '𝑫';
                break;
            case 'E':$newLetter = '𝑬';
                break;
            case 'F':$newLetter = '𝑭';
                break;
            case 'G':$newLetter = '𝑮';
                break;
            case 'H':$newLetter = '𝑯';
                break;
            case 'I':$newLetter = '𝑰';
                break;
            case 'J':$newLetter = '𝑱';
                break;
            case 'K':$newLetter = '𝑲';
                break;
            case 'L':$newLetter = '𝑳';
                break;
            case 'M':$newLetter = '𝑴';
                break;
            case 'N':$newLetter = '𝑵';
                break;
            case 'Ñ':$newLetter = 'Ñ';
                break;
            case 'O':$newLetter = '𝑶';
                break;
            case 'P':$newLetter = '𝑷';
                break;
            case 'Q':$newLetter = '𝑸';
                break;
            case 'R':$newLetter = '𝑹';
                break;
            case 'S':$newLetter = '𝑺';
                break;
            case 'T':$newLetter = '𝑻';
                break;
            case 'U':$newLetter = '𝑼';
                break;
            case 'V':$newLetter = '𝑽';
                break;
            case 'W':$newLetter = '𝑾';
                break;
            case 'X':$newLetter = '𝑿';
                break;
            case 'Y':$newLetter = '𝒀';
                break;
            case 'Z':$newLetter = '𝒁';
                break;
        }
        return $newLetter;
    }
}
