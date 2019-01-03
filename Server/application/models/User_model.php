<?php

require_once APPPATH . "models/Entidades/models.php";

class User_model extends CI_Model
{

    public $em;

    public function __construct()
    {
        parent::__construct();
        $this->em = $this->doctrine->em;
    }

    public function save($user)
    {
        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }

    public function load($user_id)
    {
        return $this->em->find('User', $user_id);
    }

    public function get_by_username_password($data)
    {
        $query = $this->em->getRepository('User')
            ->createQueryBuilder('u');
        $query->where('u.password = :password AND (u.email= :email OR u.username= :email )')
            ->setParameter('password', $data->password)
            ->setParameter('email', $data->username);

        return $query->getQuery()->getOneOrNullResult();
    }

    public function get_by_username($username)
    {
        $query = $this->em->getRepository('User')
            ->createQueryBuilder('u')
            ->where('u.username= :username')
            ->setParameter('username', $username);

        return $query->getQuery()->getOneOrNullResult();
    }

    public function get_by_email($email)
    {
        $query = $this->em->getRepository('User')
            ->createQueryBuilder('u')
            ->where('u.email = :email')
            ->setParameter('email', $email);

        return $query->getQuery()->getOneOrNullResult();
    }

    public function verify_code_password($userId, $codePassword)
    {
        $query = $this->em->getRepository('User')
            ->createQueryBuilder('u');

        $today = new DateTime();
        $today->modify('- 2 days');

        $query->where('u.id = :id AND u.codePassword = :codePassword AND u.requestPassword IS NOT NULL AND u.requestPassword >= :date')
            ->setParameter('id', $userId)
            ->setParameter('codePassword', $codePassword)
            ->setParameter('date', $today->format('Y-m-d G:i:s'));

        return $query->getQuery()->getOneOrNullResult();
    }
    public function get_user_by_id_code_activation($userId, $codeActivation)
    {
        $query = $this->em->getRepository('User')
            ->createQueryBuilder('u');

        $query->where('u.id = :id AND u.codeActivation = :codeActivation')
            ->setParameter('id', $userId)
            ->setParameter('codeActivation', $codeActivation);
        return $query->getQuery()->getOneOrNullResult();
    }

    public function get_user_fb_id($FbId)
    {
        $query = $this->em->getRepository('User')
            ->createQueryBuilder('u');

        $query->where('u.idFb = :FbId')
            ->setParameter('FbId', $FbId);

        return $query->getQuery()->getOneOrNullResult();
    }

}
