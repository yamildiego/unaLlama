<?php
defined('BASEPATH') or exit('No direct script access allowed');
define('FACEBOOK_SDK_V4_SRC_DIR', APPPATH . '../assets/php-graph-sdk/src/Facebook/');

require_once APPPATH . '../assets/php-graph-sdk/src/Facebook/autoload.php';
require APPPATH . 'libraries/REST_Controller.php';

class FBController extends REST_Controller
{
    private $appid = "1194368740723464";
    private $appsecret = "8be1f3511fed28524fe60fbd02178c20";
    private $pageAccessToken = "EAAQZBRaSFiwgBAHZBmGjIzDvZBhQDUG9F7euM9ffYhTpu4falQ2OYYRL9raylZClTm3NzCUPYrGsiZCCzAtsUhqqTp3kGhuBAt4ZAiiXF4JtrXFI4BwDrZCf88Bdin8ck04lquaryikrYVHMIDQTvilkqYZCFTpRs4ElKSSnLgTXF0w2Nk34TXdwfGExpNqCuw4ZD";
    private $pageFeed = "/me/feed";

    public function index_get()
    {
        // try {
        $pagTitulo = "AFFINITY DESIGNER, UNA ALTERNATIVA SERIA A ILLUSTRATOR Y FIREWORKS";
        $pagURL = "https://www.primemonkey.com/affinity-designer-una-alternativa-seria-a-illustrator-y-fireworks/";

        $fb = new Facebook\Facebook([
            'app_id' => $this->appid,
            'app_secret' => $this->appsecret,
        ]);

        $linkData = [
            'link' => $pagURL,
            'message' => $pagTitulo,
        ];

        $response = $fb->post($this->pageFeed, $linkData, $this->pageAccessToken);
        // $response = $fb->post('/' . $group_id . '/feed', $linkData, $accessToken);

        // $graphNode = $response->getGraphNode();

        // $this->response("OK", REST_Controller::HTTP_OK); // OK (200)
        // } catch (Facebook\Exceptions\FacebookResponseException $e) {
        //     echo 'Graph returned an error: ' . $e->getMessage();
        //     exit;
        // } catch (Facebook\Exceptions\FacebookSDKException $e) {
        //     echo 'Facebook SDK returned an error: ' . $e->getMessage();
        //     exit;

        // } catch (\Exeption $th) {
        //     var_dump("todomal");
        // }
    }

}
