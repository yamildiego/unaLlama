<?php
defined('BASEPATH') or exit('No direct script access allowed');
// define('FACEBOOK_SDK_V4_SRC_DIR', APPPATH . '../assets/php-graph-sdk/src/Facebook/');

require_once APPPATH . '../assets/php-graph-sdk/src/Facebook/autoload.php';
require APPPATH . 'libraries/REST_Controller.php';

use Facebook\Facebook;

class FBController extends REST_Controller
{
    private $appid = "736236396755616";
    private $appsecret = "9b907314149a8261ca3734074fc21b6f";
    private $pageAccessToken = "EAAKdmmTmOqABAGvMPzunXwHhPCQZCMD84XxGZCm2ADpdfo0P08lQtWvEiahKsZBLuvw3CMR2Wsf7JSZAfkWadLJUMGIsZBtyk7bUk0EzjLM0kUtsvFb313cZBMwUUvYF3Xnc6kHJzoxfreul5eSwjS8l3SREk6MVlsbn0HZAPZCOtAZDZD";
    // private $pageAccessToken = "337494159672316";
    
    private $pageFeed = "/me/feed";

    public function index_get()
    {
        try {
            $pagTitulo = "AFFINITY DESIGNER, UNA ALTERNATIVA SERIA A ILLUSTRATOR Y FIREWORKS";
            $pagURL = "https://www.primemonkey.com/affinity-designer-una-alternativa-seria-a-illustrator-y-fireworks/";

            $fb = new Facebook([
                'app_id' => $this->appid,
                'app_secret' => $this->appsecret,
            ]);

            $linkData = [
                'link' => $pagURL,
                'message' => $pagTitulo,
            ];

            $response = $fb->post($this->pageFeed, $linkData, $this->pageAccessToken);

            $graphNode = $response->getGraphNode();

            $this->response("OK", REST_Controller::HTTP_OK); // OK (200)
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;

        } catch (\Exeption $th) {
            var_dump("todomal");
        }
    }
}
