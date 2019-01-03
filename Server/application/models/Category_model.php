<?php

require_once APPPATH . "models/Entidades/models.php";

class Category_model extends CI_Model
{

    public $em;

    public function __construct()
    {
        parent::__construct();
        $this->em = $this->doctrine->em;
    }

    public function load($category_id)
    {
        return $this->em->find('Category', $category_id);
    }

    public function get_categories()
    {
        $query = $this->em->getRepository('Category')
            ->createQueryBuilder('c')
            ->orderBy('c.name', 'ASC');

        return $query->getQuery()->getResult();
    }
}
