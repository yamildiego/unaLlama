<?php

require_once APPPATH . "models/Entidades/models.php";

class Operation_model extends CI_Model
{

    private $operation = array(1 => 'Compro', 2 => 'Vendo', 3 => 'Alquilo', 4 => 'Regalo', 5 => 'Buscando trabajo', 6 => 'Ofreciendo un trabajo');
    public $em;

    public function __construct()
    {
        parent::__construct();
        $this->em = $this->doctrine->em;
    }

    public function getName($id)
    {
        if ($id < 1 || $id > 6) {
            return 'none';
        } else {
            return $this->operation[$id];
        }
    }
}
