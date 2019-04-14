<?php

require_once APPPATH . "models/Entidades/models.php";

class Participant_model extends CI_Model
{

    public $em;

    public function __construct()
    {
        parent::__construct();
        $this->em = $this->doctrine->em;
    }

    public function getParticipants()
    {
        $query = $this->em->getRepository('Article')->createQueryBuilder('a');

        $datetime = new DateTime();
        $datetime->setDate('2019', '04', '30');
        $datetime->setTime('23', '59', '59');

        $sql = 'a.datePublication <= :date ';
        $query->join('a.user', 'u');

        $query->setParameter('date', $datetime->format('Y-m-d G:i:s'))->orderBy('a.datePublication', 'DESC');
        $query->where($sql)->orderBy('u.id', 'ASC');

        return $query->getQuery()->getResult();
    }
}
