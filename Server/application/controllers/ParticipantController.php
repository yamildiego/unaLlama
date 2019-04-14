<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class ParticipantController extends REST_Controller
{

    public function getParticipants_get()
    {
        $this->load->model('Participant_model');
        $participans = $this->Participant_model->getParticipants();
        $participansData = array();
        foreach ($participans as $participan) {
            if ($participan->getUser()->getUsername() != null) {
                $participansData[$participan->getUser()->getId()] = array('id' => $participan->getUser()->getId(), 'name' => $participan->getUser()->getUsername());
            } else {
                $participansData[$participan->getUser()->getId()] = array('id' => $participan->getUser()->getId(), 'name' => $participan->getUser()->getName());
            }
        }

        $participansResultData = array();
        foreach ($participansData as $key => $participan) {
            $participansResultData[] = $participan;
        }

        $data['status'] = 'OK';
        $data['data'] = $participansResultData;

        $this->response($data, REST_Controller::HTTP_OK); // OK (200)
    }
}
