<?php

require_once APPPATH . "models/Entidades/models.php";

class Article_model extends CI_Model
{

    public $em;
    private $days = 365;

    public function __construct()
    {
        parent::__construct();
        $this->em = $this->doctrine->em;
    }

    public function load($article_id)
    {
        return $this->em->find('Article', $article_id);
    }

    public function save($article)
    {
        $this->em->persist($article);
        $this->em->flush();
        return $article;
    }

    public function getArticles($filter, $quantity, $page)
    {
        $query = $this->em->getRepository('Article')->createQueryBuilder('a');

        $sql = ' a.active = 1 ';

        if (isset($filter->search) && $filter->search !== 0 && $filter->search !== "0" && $filter->search != "" && $filter->search != null) {
            $words = explode(" ", $filter->search);
            $sql .= ' AND (';

            foreach ($words as $key => $value) {
                $sql .= " (a.title LIKE :searchTitle" . $key . ") OR (a.description LIKE :searchDesc" . $key . ") OR ";
            }
            $sql .= '(1 = 0))';
        }

        if (isset($filter->category) && $filter->category != 0) {
            $query->join('a.category', 'c');
            $sql .= ' AND c.id = :category';
        }

        if (isset($filter->department) && $filter->department != 0) {
            $query->join('a.department', 'd');
            $sql .= ' AND d.id = :department';
        }

        if (isset($filter->operation) && $filter->operation != 0) {
            $sql .= ' AND a.operation = :operation';
        }

        if (isset($filter->priceMin) && $filter->priceMin != 0 && $filter->priceMin != null && $filter->priceMin != "") {
            $sql .= ' AND a.price >= :priceMin';
        }

        if (isset($filter->priceMax) && $filter->priceMax != 0 && $filter->priceMax != null && $filter->priceMax != "") {
            $sql .= ' AND a.price <= :priceMax';
        }

        if (isset($filter->yearMin) && $filter->yearMin != 0 && $filter->yearMin != null && $filter->yearMin != "") {
            $sql .= ' AND a.year >= :yearMin';
        }

        if (isset($filter->yearMax) && $filter->yearMax != 0 && $filter->yearMax != null && $filter->yearMax != "") {
            $sql .= ' AND a.year <= :yearMax';
        }

        if (isset($filter->kilometersMin) && $filter->kilometersMin != 0 && $filter->kilometersMin != null && $filter->kilometersMin != "") {
            $sql .= ' AND a.kilometers >= :kilometersMin';
        }

        if (isset($filter->kilometersMax) && $filter->kilometersMax != 0 && $filter->kilometersMax != null && $filter->kilometersMax != "") {
            $sql .= ' AND a.kilometers <= :kilometersMax';
        }

        if (isset($filter->state) && $filter->state != 0 && $filter->state != null && $filter->state != "") {
            $sql .= ' AND a.state = :states';
        }

        $query->where($sql);

        if (isset($filter->search) && $filter->search !== 0 && $filter->search !== "0" && $filter->search != "" && $filter->search != null) {
            foreach ($words as $key => $value) {
                $query->setParameter('searchTitle' . $key, '%' . $value . '%');
                $query->setParameter('searchDesc' . $key, '%' . $value . '%');
            }
        }

        if (isset($filter->category) && $filter->category != 0) {
            $query->setParameter('category', $filter->category);
        }

        if (isset($filter->department) && $filter->department != 0) {
            $query->setParameter('department', $filter->department);
        }

        if (isset($filter->operation) && $filter->operation != 0) {
            $query->setParameter('operation', $filter->operation);
        }

        if (isset($filter->priceMin) && $filter->priceMin != 0 && $filter->priceMin != null && $filter->priceMin != "") {
            $query->setParameter('priceMin', $filter->priceMin);
        }

        if (isset($filter->priceMax) && $filter->priceMax != 0 && $filter->priceMax != null && $filter->priceMax != "") {
            $query->setParameter('priceMax', $filter->priceMax);
        }

        if (isset($filter->yearMin) && $filter->yearMin != 0 && $filter->yearMin != null && $filter->yearMin != "") {
            $query->setParameter('yearMin', $filter->yearMin);
        }

        if (isset($filter->yearMax) && $filter->yearMax != 0 && $filter->yearMax != null && $filter->yearMax != "") {
            $query->setParameter('yearMax', $filter->yearMax);
        }

        if (isset($filter->kilometersMin) && $filter->kilometersMin != 0 && $filter->kilometersMin != null && $filter->kilometersMin != "") {
            $query->setParameter('kilometersMin', $filter->kilometersMin);
        }

        if (isset($filter->kilometersMax) && $filter->kilometersMax != 0 && $filter->kilometersMax != null && $filter->kilometersMax != "") {
            $query->setParameter('kilometersMax', $filter->kilometersMax);
        }

        if (isset($filter->state) && $filter->state != 0 && $filter->state != null && $filter->state != "") {
            $query->setParameter('states', $filter->state);
        }

        $query->orderBy('a.datePublication', 'DESC')->setFirstResult($quantity * ($page - 1))->setMaxResults($quantity);

        return $query->getQuery()->getResult();
    }

    public function getMyArticles($userId)
    {
        $query = $this->em->getRepository('Article')
            ->createQueryBuilder('a')
            ->where("a.user = :userId")
            ->setParameter('userId', $userId)
            ->orderBy('a.datePublication', 'DESC');

        return $query->getQuery()->getResult();
    }

    public function getUserArticles($userId)
    {
        $query = $this->em->getRepository('Article')
            ->createQueryBuilder('a')
            ->where("a.user = :userId AND a.active = 1 ")
            ->setParameter('userId', $userId)->orderBy('a.datePublication', 'DESC');

        return $query->getQuery()->getResult();
    }

    public function getArticlesMostRecent()
    {
        $query = $this->em->getRepository('Article')
            ->createQueryBuilder('a')
            ->where("a.active = 1")
            ->setMaxResults(4)
            ->orderBy('a.dateCreation', 'DESC');

        return $query->getQuery()->getResult();
    }

    public function getArticlesMostVisited()
    {
        $query = $this->em->getRepository('Article')
            ->createQueryBuilder('a')
            ->where("a.active = 1")
            ->setMaxResults(4)
            ->orderBy('a.viewed', 'DESC');

        return $query->getQuery()->getResult();
    }

    public function getArticlesMostPopular()
    {
        $sql = "SELECT COUNT(*) AS count, a.id FROM article a JOIN favorite f ON f.article_id = a.id WHERE a.active = 1 GROUP BY a.id ORDER BY COUNT(*) DESC LIMIT 4";
        $stmt = $this->em->getConnection()->prepare($sql);
        $stmt->execute();

        $articles = array();

        foreach ($stmt->fetchAll() as $element) {
            $articles[] = $this->load($element['id']);
        }
        return $articles;
    }

    public function getQuantityProducts($categoryId)
    {
        return $this->em->getRepository('Article')->createQueryBuilder('a')
            ->select('count(a.id)')
            ->where("a.category = :categoryId AND a.active = 1")
            ->setParameter('categoryId', $categoryId)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getTotalArticles($userId)
    {
        return $this->em->getRepository('Article')->createQueryBuilder('a')
            ->select('count(a.id)')
            ->where("a.user = :userId ")
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getSingleScalarResult();
    }

}