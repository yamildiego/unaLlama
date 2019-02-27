<?php

require_once APPPATH . "models/Entidades/models.php";

class Department_model extends CI_Model
{

    public $em;

    public function __construct()
    {
        parent::__construct();
        $this->em = $this->doctrine->em;
    }

    public function load($department_id)
    {
        return $this->em->find('Department', $department_id);
    }

    public function get_departments()
    {
        $query = $this->em->getRepository('Department')
            ->createQueryBuilder('d')
            ->orderBy('d.name', 'ASC');

        return $query->getQuery()->getResult();
    }
}
