<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class ArticleController extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Category_model');
        $this->load->model('Article_model');
        $this->load->model('Photo_model');
        $this->load->model('Comment_model');
    }

    public function getCategories_get()
    {
        $data = array('status' => null);
        $categories = $this->Category_model->get_categories();
        $categoriesData = array();

        foreach ($categories as $category) {
            $categoriesData[$category->getId()] = array('id' => $category->getId(), 'name' => $category->getName(), 'icon' => $category->getIcon(), 'quantity_products' => $this->Article_model->getQuantityProducts($category->getId()));
        }

        $data['status'] = 'OK';
        $data['data'] = $categoriesData;

        $this->response($data, REST_Controller::HTTP_OK); // OK (200)
    }

    public function newArticle_post()
    {

        $user = $this->isLoged();

        $article = new Article();
        $article->setUser($user);
        $article->setDeleted(0);
        $article->setTitle($this->post('title'));
        $article->setDescription($this->post('description'));
        $article->setPrice($this->post('price'));
        $article->setPhone($this->post('phone'));
        $article->setAddress($this->post('address'));
        $article->setEmail($this->post('email'));

        $category = $this->Category_model->load($this->post('category'));
        if ($category != null) {
            $article->setCategory($category);
        }

        $article->setState($this->post('state'));
        $article->setOperation($this->post('operation'));
        $article->setYear($this->post('year'));
        $article->setKilometers($this->post('kilometers'));
        $article->setDatePublication(date_create());
        $article->setDateCreation(date_create());
        $article->setPhotos(new \Doctrine\Common\Collections\ArrayCollection());

        $photosData = $this->post('photos');
        if ($photosData != null) {
            foreach ($photosData as $photoData) {
                $path = "./assets/php/files/";
                if (isset($photoData["name"]) && file_exists($path . $photoData["name"])) {
                    $photo = new Photo();
                    $photo->setName($photoData["name"]);
                    $photo->setArticle($article);
                    $article->addPhoto($photo);
                }
            }
        }

        $errors = array();
        if ($article->getTitle() == null || $article->getTitle() == "") {
            $errors[] = "required_title";
        }
        if (strlen($article->getTitle()) >= 100) {
            $errors[] = "long_title";
        }
        if ($article->getDescription() == null || $article->getDescription() == "") {
            $errors[] = "required_description";
        }
        if ($article->getCategory() == null) {
            $errors[] = "required_category";
        } elseif ($article->getCategory()->getId() == 1 || $article->getCategory()->getId() == 10) {
            if ($article->getOperation() == null || $article->getOperation() == "") {
                $errors[] = "required_operation";
            }
        } elseif ($article->getCategory()->getId() == 14) {
            if ($article->getOperation() == null || $article->getOperation() == "") {
                $errors[] = "required_operation";
            }
        } else {
            if ($article->getState() == null || $article->getState() == "") {
                $errors[] = "required_state";
            }
            if ($article->getOperation() == null || $article->getOperation() == "") {
                $errors[] = "required_operation";
            }
        }

        if (count($photosData) > 5) {
            $errors[] = "max_photo_limit";
        }

        if (count($errors) > 0) {
            $data = array('status' => 'errors_exists', 'errors' => $errors);
            $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
        } else {
            $data = array('status' => "OK");
            $this->Article_model->save($article);
            $this->response($data, REST_Controller::HTTP_OK); // OK (200)
        }

    }

    public function getArticles_get($quantity, $page)
    {
        try {
            $user = $this->getLoged();
            $myFavorites = array();

            if ($user != null) {
                foreach ($user->getServices() as $fb) {
                    $myFavorites[((string) $fb->getId())] = $fb->getId();
                }
            }

            $filter = json_decode($this->session->userdata('filter'));

            $articles = $this->Article_model->getArticles($filter, $quantity, $page);
            $articlesData = array();
            foreach ($articles as $article) {

                $articleData = array('id' => $article->getId(),
                    'title' => $article->getTitle(),
                    'description' => $article->getDescription(),
                    'price' => $article->getPrice(),
                    'category' => $article->getCategory()->getName(),
                    'viewed' => $article->getViewed(),
                    'operation' => $article->getOperation(),
                    'state' => $article->getState(),
                    'isFb' => (($user != null && isset($myFavorites[((string) $article->getId())])) ? true : false),
                    'date_publication' => $article->getDatePublication()->getTimestamp(),
                    'date_creation' => $article->getDateCreation()->getTimestamp());

                $photos = array();

                foreach ($article->getPhotos() as $photo) {
                    $pathThumbnail = "../Server/assets/php/files/thumbnail/";
                    $path = "../Server/assets/php/files/";
                    if (file_exists($path . $photo->getName())) {
                        $photos[] = array('thumbnail' => $pathThumbnail . $photo->getName(), 'original' => $path . $photo->getName());
                    }
                }

                if (count($photos) == 0) {
                    $photos[] = array('thumbnail' => './Content/images/default-image.png', 'original' => './Content/images/default-image.png');
                }

                $articleData["photos"] = $photos;
                $articlesData[] = $articleData;
            }

            $data = array('status' => "OK", 'data' => $articlesData);

            $this->response($data, REST_Controller::HTTP_OK); // OK (200)

        } catch (Exception $ex) {
            $data['status'] = 'unexpected_error';
            $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
        }
    }

    public function getArticlesMostVisited_get()
    {
        try {
            $articles = $this->Article_model->getArticlesMostVisited();
            $articlesData = array();
            foreach ($articles as $article) {

                $articleData = array('id' => $article->getId(),
                    'title' => $article->getTitle(),
                    'description' => $article->getDescription(),
                    'price' => $article->getPrice(),
                    'category' => $article->getCategory()->getName(),
                    'viewed' => $article->getViewed(),
                    'operation' => $article->getOperation(),
                    'state' => $article->getState(),
                    'date_publication' => $article->getDatePublication()->getTimestamp(),
                    'date_creation' => $article->getDateCreation()->getTimestamp());

                $photos = array();

                foreach ($article->getPhotos() as $photo) {
                    $pathThumbnail = "../Server/assets/php/files/thumbnail/";
                    $path = "../Server/assets/php/files/";
                    if (file_exists($path . $photo->getName())) {
                        $photos[] = array('thumbnail' => $pathThumbnail . $photo->getName(), 'original' => $path . $photo->getName());
                    }
                }

                if (count($photos) == 0) {
                    $photos[] = array('thumbnail' => './Content/images/default-image.png', 'original' => './Content/images/default-image.png');
                }

                $articleData["photos"] = $photos;
                $articlesData[] = $articleData;
            }

            $data = array('status' => "OK", 'data' => $articlesData);

            $this->response($data, REST_Controller::HTTP_OK); // OK (200)

        } catch (Exception $ex) {
            $data['status'] = 'unexpected_error';
            $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
        }
    }
    public function getArticlesMostPopular_get()
    {
        try {
            $articles = $this->Article_model->getArticlesMostPopular();
            $articlesData = array();
            foreach ($articles as $article) {

                $articleData = array('id' => $article->getId(),
                    'title' => $article->getTitle(),
                    'description' => $article->getDescription(),
                    'price' => $article->getPrice(),
                    'category' => $article->getCategory()->getName(),
                    'viewed' => $article->getViewed(),
                    'operation' => $article->getOperation(),
                    'state' => $article->getState(),
                    'date_publication' => $article->getDatePublication()->getTimestamp(),
                    'date_creation' => $article->getDateCreation()->getTimestamp());

                $photos = array();

                foreach ($article->getPhotos() as $photo) {
                    $pathThumbnail = "../Server/assets/php/files/thumbnail/";
                    $path = "../Server/assets/php/files/";
                    if (file_exists($path . $photo->getName())) {
                        $photos[] = array('thumbnail' => $pathThumbnail . $photo->getName(), 'original' => $path . $photo->getName());
                    }
                }

                if (count($photos) == 0) {
                    $photos[] = array('thumbnail' => './Content/images/default-image.png', 'original' => './Content/images/default-image.png');
                }

                $articleData["photos"] = $photos;
                $articlesData[] = $articleData;
            }

            $data = array('status' => "OK", 'data' => $articlesData);

            $this->response($data, REST_Controller::HTTP_OK); // OK (200)

        } catch (Exception $ex) {
            $data['status'] = 'unexpected_error';
            $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
        }
    }

    public function getArticlesMostRecent_get()
    {
        try {
            $articles = $this->Article_model->getArticlesMostRecent();
            $articlesData = array();
            foreach ($articles as $article) {

                $articleData = array('id' => $article->getId(),
                    'title' => $article->getTitle(),
                    'description' => $article->getDescription(),
                    'price' => $article->getPrice(),
                    'category' => $article->getCategory()->getName(),
                    'viewed' => $article->getViewed(),
                    'operation' => $article->getOperation(),
                    'state' => $article->getState(),
                    'date_publication' => $article->getDatePublication()->getTimestamp(),
                    'date_creation' => $article->getDateCreation()->getTimestamp());

                $photos = array();

                foreach ($article->getPhotos() as $photo) {
                    $pathThumbnail = "../Server/assets/php/files/thumbnail/";
                    $path = "../Server/assets/php/files/";
                    if (file_exists($path . $photo->getName())) {
                        $photos[] = array('thumbnail' => $pathThumbnail . $photo->getName(), 'original' => $path . $photo->getName());
                    }
                }

                if (count($photos) == 0) {
                    $photos[] = array('thumbnail' => './Content/images/default-image.png', 'original' => './Content/images/default-image.png');
                }

                $articleData["photos"] = $photos;
                $articlesData[] = $articleData;
            }

            $data = array('status' => "OK", 'data' => $articlesData);

            $this->response($data, REST_Controller::HTTP_OK); // OK (200)

        } catch (Exception $ex) {
            $data['status'] = 'unexpected_error';
            $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
        }
    }

    public function deletePhoto_get($name)
    {
        $path = "./assets/php/files/";
        if (file_exists($path . $name)) {
            unlink($path . $name);
        }

        if (file_exists($path . "thumbnail/" . $name)) {
            unlink($path . "thumbnail/" . $name);
        }

        $data = array('status' => "OK");

        $this->response($data, REST_Controller::HTTP_OK); // OK (200)
    }

    public function setFilter_post()
    {
        $data = array('status' => "OK");

        $category = (($this->post('category') == null || $this->post('category') == 0 || ($this->Category_model->load($this->post('category')) == null)) ? "0" : $this->post('category'));

        $data['data'] = array("category" => $category,
            "search" => $this->post('search'),
            "operation" => $this->post('operation'),
            "priceMin" => $this->post('priceMin'),
            "priceMax" => $this->post('priceMax'),
            "state" => $this->post('state'),
            "yearMin" => $this->post('yearMin'),
            "yearMax" => $this->post('yearMax'),
            "kilometersMin" => $this->post('kilometersMin'),
            "kilometersMax" => $this->post('kilometersMax'),
        );

        $this->session->set_userdata('filter', json_encode($data['data']));

        $this->response($data, REST_Controller::HTTP_OK); // OK (200)
    }

    public function getFilter_get()
    {
        $data = array('status' => "OK");

        $filter = json_decode($this->session->userdata('filter'));

        if (is_object($filter) && isset($filter->category)) {
            $data['data'] = $filter;
        } else {
            $data['data'] = array("category" => "0",
                "search" => "",
                "operation" => "0",
                "priceMin" => "",
                "priceMax" => "",
                "state" => "0",
                "yearMin" => "",
                "yearMax" => "",
                "kilometersMin" => "",
                "kilometersMax" => "",
            );
        }

        $this->response($data, REST_Controller::HTTP_OK); // OK (200)
    }

    public function getArticle_post()
    {
        try {
            $user = $this->getLoged();
            $articleId = $this->post('articleId');
            $isViewing = $this->post('isViewing');

            $myFavorites = array();

            if ($user != null) {
                foreach ($user->getServices() as $fb) {
                    $myFavorites[((string) $fb->getId())] = $fb->getId();
                }
            }

            if ($articleId != null && $articleId != 0) {
                $article = $this->Article_model->load($articleId);

                if ($article != null) {

                    if ($article->getDeleted()) {
                        $data['status'] = 'article_deleted';
                        $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
                    }

                    $date = $article->getDatePublication();
                    $date->modify('+30 days');
                    $today = date_create();

                    $articleData = array('id' => $article->getId(),
                        'title' => $article->getTitle(),
                        'description' => $article->getDescription(),
                        'price' => $article->getPrice(),
                        'phone' => $article->getPhone(),
                        'email' => $article->getEmail(),
                        'isFb' => (($user != null && isset($myFavorites[((string) $article->getId())])) ? true : false),
                        'address' => $article->getAddress(),
                        'categoryId' => $article->getCategory()->getId() . '',
                        'category' => $article->getCategory()->getName(),
                        'viewed' => $article->getViewed(),
                        'operation' => $article->getOperation() . '',
                        'state' => $article->getState() . '',
                        'userId' => $article->getUser()->getId(),
                        'isPublished' => ($date->format('Y-m-d H:i:s') > $today->format('Y-m-d H:i:s')),
                        'date_publication' => $article->getDatePublication()->getTimestamp(),
                        'date_creation' => $article->getDateCreation()->getTimestamp());

                    if ($articleData["categoryId"] == "2") {
                        $articleData["year"] = $article->getYear();
                        $articleData["kilometers"] = $article->getKilometers();
                    }

                    $photos = array();

                    foreach ($article->getPhotos() as $photo) {
                        $pathThumbnail = "../Server/assets/php/files/thumbnail/";
                        $path = "../Server/assets/php/files/";
                        if (file_exists($path . $photo->getName())) {
                            $photos[] = array('thumbnail' => $pathThumbnail . $photo->getName(), 'original' => $path . $photo->getName());
                        }
                    }

                    $articleData["photos"] = $photos;

                    if ($isViewing === true) {
                        $article->setViewed($article->getViewed() + 1);
                        $this->Article_model->save($article);
                    }

                    $data = array('status' => "OK", 'data' => $articleData);

                    $this->response($data, REST_Controller::HTTP_OK); // OK (200)
                } else {
                    throw new Exception('unexpected_error');
                }

            } else {
                throw new Exception('unexpected_error');
            }
        } catch (Exception $ex) {
            $data['status'] = 'unexpected_error';
            $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
        }
    }

    public function getMyArticles_get()
    {
        try {
            $user = $this->isLoged();

            $articles = $this->Article_model->getMyArticles($user->getId());
            $articlesData = array();
            foreach ($articles as $article) {
                $articleData = array('id' => $article->getId(),
                    'title' => $article->getTitle(),
                    'description' => $article->getDescription(),
                    'price' => $article->getPrice(),
                    'category' => $article->getCategory()->getName(),
                    'viewed' => $article->getViewed(),
                    'operation' => $article->getOperation(),
                    'state' => $article->getState(),
                    'date_publication' => $article->getDatePublication()->getTimestamp(),
                    'date_creation' => $article->getDateCreation()->getTimestamp());

                $photos = array();

                foreach ($article->getPhotos() as $photo) {
                    $pathThumbnail = "../Server/assets/php/files/thumbnail/";
                    $path = "../Server/assets/php/files/";
                    if (file_exists($path . $photo->getName())) {
                        $photos[] = array('thumbnail' => $pathThumbnail . $photo->getName(), 'original' => $path . $photo->getName());
                    }
                }

                if (count($photos) == 0) {
                    $photos[] = array('thumbnail' => './Content/images/default-image.png', 'original' => './Content/images/default-image.png');
                }

                $articleData["photos"] = $photos;
                $articlesData[] = $articleData;
            }

            $data = array('status' => "OK", 'data' => $articlesData);

            $this->response($data, REST_Controller::HTTP_OK); // OK (200)

        } catch (Exception $ex) {
            $data['status'] = 'unexpected_error';
            $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
        }
    }

    public function getUserArticles_get($userId)
    {
        try {
            $user = $this->getLoged();

            $myFavorites = array();

            if ($user != null) {
                foreach ($user->getServices() as $fb) {
                    $myFavorites[((string) $fb->getId())] = $fb->getId();
                }
            }

            $articles = $this->Article_model->getUserArticles($userId);
            $articlesData = array();
            foreach ($articles as $article) {
                $articleData = array('id' => $article->getId(),
                    'title' => $article->getTitle(),
                    'description' => $article->getDescription(),
                    'price' => $article->getPrice(),
                    'category' => $article->getCategory()->getName(),
                    'viewed' => $article->getViewed(),
                    'operation' => $article->getOperation(),
                    'state' => $article->getState(),
                    'date_publication' => $article->getDatePublication()->getTimestamp(),
                    'date_creation' => $article->getDateCreation()->getTimestamp());

                if ($user != null) {
                    $articleData["isFb"] = (($user != null && isset($myFavorites[((string) $article->getId())])) ? true : false);
                } else {
                    $articleData["isFb"] = false;
                }

                $photos = array();

                foreach ($article->getPhotos() as $photo) {
                    $pathThumbnail = "../Server/assets/php/files/thumbnail/";
                    $path = "../Server/assets/php/files/";
                    if (file_exists($path . $photo->getName())) {
                        $photos[] = array('thumbnail' => $pathThumbnail . $photo->getName(), 'original' => $path . $photo->getName());
                    }
                }

                if (count($photos) == 0) {
                    $photos[] = array('thumbnail' => './Content/images/default-image.png', 'original' => './Content/images/default-image.png');
                }

                $articleData["photos"] = $photos;
                $articlesData[] = $articleData;
            }

            $data = array('status' => "OK", 'data' => $articlesData);

            $this->response($data, REST_Controller::HTTP_OK); // OK (200)

        } catch (Exception $ex) {
            $data['status'] = 'unexpected_error';
            $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
        }
    }

    private function isLoged()
    {
        $userId = $this->session->userdata('userId');

        if ($userId == null) {
            $data['status'] = 'session_expired';
            $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
        } else {
            $user = $this->User_model->load($userId);

            if ($userId == null) {
                $data['status'] = 'session_expired';
                $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
            }

            return $user;
        }
    }

    private function getLoged()
    {
        $userId = $this->session->userdata('userId');

        if ($userId == null) {
            return null;
        } else {
            $user = $this->User_model->load($userId);

            if ($userId == null) {
                return null;
            }

            return $user;
        }
    }

    public function republishArticle_post()
    {
        try {
            $user = $this->isLoged();
            $userId = $this->post('userId');
            $articleId = $this->post('articleId');
            if ($userId != null && $articleId != null && $user->getId() == $userId) {
                $article = $this->Article_model->load($articleId);

                if ($article->getUser()->getId() == $userId) {
                    $article->setDatePublication(date_create());
                    $this->Article_model->save($article);

                    $data = array('status' => "OK");
                    $this->response($data, REST_Controller::HTTP_OK); // OK (200)
                } else {
                    $data['status'] = 'unexpected_error';
                    $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
                }
            } else {
                $data['status'] = 'unexpected_error';
                $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
            }
        } catch (Exception $ex) {
            $data['status'] = 'unexpected_error';
            $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
        }
    }

    public function removeArticle_post()
    {
        try {
            $user = $this->isLoged();
            $userId = $this->post('userId');
            $articleId = $this->post('articleId');
            if ($userId != null && $articleId != null && $user->getId() == $userId) {
                $article = $this->Article_model->load($articleId);

                if ($article->getUser()->getId() == $userId) {
                    $article->setDeleted(1);
                    $this->Article_model->save($article);

                    $data = array('status' => "OK");
                    $this->response($data, REST_Controller::HTTP_OK); // OK (200)
                } else {
                    $data['status'] = 'unexpected_error';
                    $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
                }
            } else {
                $data['status'] = 'unexpected_error';
                $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
            }
        } catch (Exception $ex) {
            $data['status'] = 'unexpected_error';
            $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
        }

    }

    public function addRemoveFb_post()
    {
        try {
            $user = $this->isLoged();
            $userId = $this->post('userId');
            $articleId = $this->post('articleId');
            $operation = $this->post('operation');

            if ($userId != null && $articleId != null) {
                $article = $this->Article_model->load($articleId);

                $data = array('status' => "OK");
                $data['data'] = false;
                if ($operation == 'add') {
                    $user->addService($article);
                    $data['data'] = true;
                }

                if ($operation == 'remove') {
                    $user->removeService($article);
                    $data['data'] = true;
                }

                $this->User_model->save($user);

                $this->response($data, REST_Controller::HTTP_OK); // OK (200)
            } else {
                throw new Exception('unexpected_error');
            }
        } catch (Exception $ex) {
            $data['status'] = 'unexpected_error';
            $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
        }
    }

    public function getMyFavoritesArticles_post()
    {
        try {
            $user = $this->isLoged();
            $userId = $this->post('userId');

            $articlesData = array();
            foreach ($user->getServices() as $article) {

                if ($article->getDeleted() == 0) {
                    $articleData = array('id' => $article->getId(),
                        'title' => $article->getTitle(),
                        'description' => $article->getDescription(),
                        'price' => $article->getPrice(),
                        'category' => $article->getCategory()->getName(),
                        'viewed' => $article->getViewed(),
                        'operation' => $article->getOperation(),
                        'state' => $article->getState(),
                        'isFb' => true,
                        'date_publication' => $article->getDatePublication()->getTimestamp(),
                        'date_creation' => $article->getDateCreation()->getTimestamp());

                    $photos = array();

                    foreach ($article->getPhotos() as $photo) {
                        $pathThumbnail = "../Server/assets/php/files/thumbnail/";
                        $path = "../Server/assets/php/files/";
                        if (file_exists($path . $photo->getName())) {
                            $photos[] = array('thumbnail' => $pathThumbnail . $photo->getName(), 'original' => $path . $photo->getName());
                        }
                    }

                    if (count($photos) == 0) {
                        $photos[] = array('thumbnail' => './Content/images/default-image.png', 'original' => './Content/images/default-image.png');
                    }

                    $articleData["photos"] = $photos;
                    $articlesData[] = $articleData;
                }
            }

            $data = array('status' => "OK", 'data' => $articlesData);

            $this->response($data, REST_Controller::HTTP_OK); // OK (200)

        } catch (Exception $ex) {
            $data['status'] = 'unexpected_error';
            $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
        }
    }

    public function editArticle_post()
    {
        try {
            $user = $this->isLoged();
            $userId = $this->post('userId');
            $articleId = $this->post('id');

            if ($userId != null && $articleId != null && $user->getId() == $userId) {
                $article = $this->Article_model->load($articleId);

                if ($article->getUser()->getId() == $userId) {

                    $article->setTitle($this->post('title'));
                    $article->setDescription($this->post('description'));
                    $article->setPrice($this->post('price'));
                    $article->setPhone($this->post('phone'));
                    $article->setAddress($this->post('address'));
                    $article->setEmail($this->post('email'));

                    $category = $this->Category_model->load($this->post('category'));
                    if ($category != null) {
                        $article->setCategory($category);
                    }

                    $article->setState($this->post('state'));
                    $article->setOperation($this->post('operation'));
                    $article->setYear($this->post('year'));
                    $article->setKilometers($this->post('kilometers'));

                    $photosCurrent = $article->getPhotos();
                    $article->setPhotos(new \Doctrine\Common\Collections\ArrayCollection());

                    $photosData = $this->post('photos');

                    $photoRemoves = array();

                    if ($photosData != null) {
                        foreach ($photosData as $photoData) {

                            if (isset($photoData["thumbnail"])) {
                                $arrayPath = explode("/", $photoData["thumbnail"]);
                                $namePicture = $arrayPath[count($arrayPath) - 1];
                                $photoOld = $this->getItem($photosCurrent, $namePicture);

                                if (isset($photoData["forRemove"]) && $photoData["forRemove"]) {
                                    $photoRemoves[] = $photoOld;
                                } else {
                                    $article->addPhoto($photoOld);
                                }
                            } else {
                                $path = "./assets/php/files/";

                                if (file_exists($path . $photoData["name"])) {
                                    $photo = new Photo();
                                    $photo->setName($photoData["name"]);
                                    $photo->setArticle($article);
                                    $article->addPhoto($photo);
                                }
                            }

                        }
                    }

                    $errors = array();
                    if ($article->getTitle() == null || $article->getTitle() == "") {
                        $errors[] = "required_title";
                    }
                    if (strlen($article->getTitle()) >= 100) {
                        $errors[] = "long_title";
                    }
                    if ($article->getDescription() == null || $article->getDescription() == "") {
                        $errors[] = "required_description";
                    }
                    if ($article->getCategory() == null) {
                        $errors[] = "required_category";
                    } elseif ($article->getCategory()->getId() == 1 || $article->getCategory()->getId() == 10) {
                        if ($article->getOperation() == null || $article->getOperation() == "") {
                            $errors[] = "required_operation";
                        }
                    } elseif ($article->getCategory()->getId() == 14) {
                        if ($article->getOperation() == null || $article->getOperation() == "") {
                            $errors[] = "required_operation";
                        }
                    } else {
                        if ($article->getState() == null || $article->getState() == "") {
                            $errors[] = "required_state";
                        }
                        if ($article->getOperation() == null || $article->getOperation() == "") {
                            $errors[] = "required_operation";
                        }
                    }

                    $cont = 0;
                    foreach ($photosData as $pic) {
                        if (isset($pic['forRemove']) && $pic['forRemove']) {

                        } else {
                            $cont++;
                        }
                    }

                    if ($cont > 5) {
                        $errors[] = "max_photo_limit";
                    }

                    if (count($errors) > 0) {
                        $data = array('status' => 'errors_exists', 'errors' => $errors);
                        $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
                    } else {
                        $data = array('status' => "OK", "data" => $article->getId());
                        $this->Article_model->save($article);

                        foreach ($photoRemoves as $photoRemove) {
                            $this->Photo_model->remove($photoRemove);
                        }
                        $this->response($data, REST_Controller::HTTP_OK); // OK (200)
                    }

                } else {
                    throw new Exception('unexpected_error');
                }
            } else {
                throw new Exception('unexpected_error');
            }

        } catch (Exception $ex) {
            $data['status'] = 'unexpected_error';
            $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
        }
    }

    public function sendComment_post()
    {
        try {
            $user = $this->isLoged();
            $text = $this->post('text');
            $articleId = $this->post('articleId');
            $commentId = $this->post('commentId');

            $article = $this->Article_model->load($articleId);
            $comment = null;

            if ($commentId != null) {
                $comment = $this->Comment_model->load($commentId);
            }

            if ($article != null && $user != null && $text != null) {
                $newComment = new Comment();
                $newComment->setMessage($text);
                $newComment->setUser($user);
                $newComment->setDate(date_create());
                $newComment->setArticle($article);

                if ($comment != null) {
                    $newComment->setComment($comment);
                }

                $data = array('status' => "OK");
                $this->Comment_model->save($newComment);

                $this->response($data, REST_Controller::HTTP_OK); // OK (200)

            } else {
                throw new Exception('unexpected_error');
            }
        } catch (Exception $ex) {
            $data['status'] = 'unexpected_error';
            $this->response($data, REST_Controller::HTTP_FOUND); // FOUND (302)
        }
    }
    public function getComments_get($articleId)
    {
        $comments = $this->Comment_model->get_comments($articleId);

        $dataComments = array();

        $today = date_create();

        foreach ($comments as $comment) {
            $replys = array();
            foreach ($comment->getComments() as $reply) {
                $replyPhoto = ($reply->getUser()->getPhoto() == null) ? 'user.png' : $reply->getUser()->getPhoto();
                $dataReply = array(
                    'id' => $reply->getId(),
                    'message' => $reply->getMessage(),
                    'date' => $reply->getDate()->getTimestamp(),
                    'user' => array(
                        'id' => $reply->getUser()->getId(),
                        'name' => $reply->getUser()->getName(),
                        'photo' => $replyPhoto));
                $replys[] = $dataReply;
            }

            $commentPhoto = ($comment->getUser()->getPhoto() == null) ? 'user.png' : $comment->getUser()->getPhoto();

            $dataComment = array(
                'id' => $comment->getId(),
                'message' => $comment->getMessage(),
                'date' => $comment->getDate()->getTimestamp(),
                'user' => array(
                    'id' => $comment->getUser()->getId(),
                    'name' => $comment->getUser()->getName(),
                    'photo' => $commentPhoto),
                'replys' => $replys);

            $dataComments[] = $dataComment;
        }

        $data = array('status' => "OK", "data" => $dataComments);
        $this->response($data, REST_Controller::HTTP_OK); // OK (200)
    }

    private function getItem($collection, $pName)
    {
        $filteredCollection = $collection->filter(function ($element) use ($pName) {
            return ($element->getName() === $pName);
        });

        return (($filteredCollection->count() == 0) ? null : $filteredCollection->first());
    }

}
