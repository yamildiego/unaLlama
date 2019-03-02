<?php

require_once APPPATH . "models/Entidades/models.php";

class Photo_model extends CI_Model
{

    public $em;

    public function __construct()
    {
        parent::__construct();
        $this->em = $this->doctrine->em;
    }

    public function remove($photo)
    {
        $this->em->remove($photo);
        $this->em->flush();
    }

    public function getPhotoByName($file)
    {
        $query = $this->em->getRepository('Photo')
            ->createQueryBuilder('p')
            ->where("p.name = :name")
            ->setParameter('name', $file);

        return $query->getQuery()->getOneOrNullResult();
    }
}
