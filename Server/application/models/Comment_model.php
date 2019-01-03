<?php

require_once APPPATH . "models/Entidades/models.php";

class Comment_model extends CI_Model
{

    public $em;

    public function __construct()
    {
        parent::__construct();
        $this->em = $this->doctrine->em;
    }

    public function save($comment)
    {
        $this->em->persist($comment);
        $this->em->flush();
        return $comment;
    }

    public function load($comment_id)
    {
        return $this->em->find('Comment', $comment_id);
    }

    public function get_comments($articleId)
    {
        $query = $this->em->getRepository('Comment')
            ->createQueryBuilder('c')
            ->where('c.comment IS NULL AND c.article = :articleId')
            ->setParameter('articleId', $articleId);
        return $query->getQuery()->getResult();
    }
}
