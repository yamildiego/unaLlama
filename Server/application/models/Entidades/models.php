<?php

/**
 * User
 *
 * @Table(name="user")
 * @Entity
 */
class User
{
    /**
     * @var integer
     *
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @Column(name="username", type="string", length=100, nullable=false)
     */
    private $username;

    /**
     * @var string
     *
     * @Column(name="email", type="string", length=200, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @Column(name="photo", type="text", nullable=true)
     */
    private $photo;

    /**
     * @var string
     *
     * @Column(name="password", type="text", nullable=false)
     */
    private $password;

    /**
     * @var boolean
     *
     * @Column(name="active", type="boolean", nullable=false)
     */
    private $active;

    /**
     * @var \DateTime
     *
     * @Column(name="last_connection", type="datetime", nullable=true)
     */
    private $lastConnection;

    /**
     * @var \DateTime
     *
     * @Column(name="request_password", type="datetime", nullable=true)
     */
    private $requestPassword;

    /**
     * @var string
     *
     * @Column(name="code_activation", type="text", nullable=true)
     */
    private $codeActivation;

    /**
     * @var string
     *
     * @Column(name="code_password", type="text", nullable=true)
     */
    private $codePassword;

    /**
     * @var integer
     *
     * @Column(name="id_fb", type="integer", nullable=true)
     */
    private $idFb;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ManyToMany(targetEntity="Article", inversedBy="housingType")
     * @JoinTable(name="favorite",
     *   joinColumns={
     *     @JoinColumn(name="user_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @JoinColumn(name="article_id", referencedColumnName="id")
     *   }
     * )
     */
    private $service;

    // /**
    //  * @ManyToMany(targetEntity="Article")
    //  * @JoinTable(
    //  *  name="favorite",
    //  *  joinColumns={
    //  *      @JoinColumn(name="user_id", referencedColumnName="id")
    //  *  },
    //  *  inverseJoinColumns={
    //  *      @JoinColumn(name="article_id", referencedColumnName="id")
    //  *  }
    //  * )
    //  */
    // private $favorites;

    public function __construct()
    {
        $this->service = new \Doctrine\Common\Collections\ArrayCollection();
        // $this->favorites = new \Doctrine\Common\Collections\ArrayCollection();

    }

    /**
     * Add service
     *
     * @param \Article $service
     * @return User
     */
    public function addService(\Article $service)
    {
        $this->service[] = $service;
        return $this;
    }

    /**
     * Remove service
     *
     * @param \Article $service
     */
    public function removeService(\Article $service)
    {
        $this->service->removeElement($service);
    }

    /**
     * Get services
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServices()
    {
        return $this->service;
    }

    /**
     * Set services
     *
     * @return User
     */
    public function setServices($services)
    {
        $this->service = $services;
        return $this;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set photo
     *
     * @param string $photo
     * @return User
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return User
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set lastConnection
     *
     * @param \DateTime $lastConnection
     * @return User
     */
    public function setLastConnection($lastConnection)
    {
        $this->lastConnection = $lastConnection;

        return $this;
    }

    /**
     * Get lastConnection
     *
     * @return \DateTime
     */
    public function getLastConnection()
    {
        return $this->lastConnection;
    }

    /**
     * Set requestPassword
     *
     * @param \DateTime $requestPassword
     * @return User
     */
    public function setRequestPassword($requestPassword)
    {
        $this->requestPassword = $requestPassword;

        return $this;
    }

    /**
     * Get requestPassword
     *
     * @return \DateTime
     */
    public function getRequestPassword()
    {
        return $this->requestPassword;
    }

    /**
     * Set codeActivation
     *
     * @param string $codeActivation
     * @return User
     */
    public function setCodeActivation($codeActivation)
    {
        $this->codeActivation = $codeActivation;

        return $this;
    }

    /**
     * Get codeActivation
     *
     * @return string
     */
    public function getCodeActivation()
    {
        return $this->codeActivation;
    }

    /**
     * Set codePassword
     *
     * @param string $codePassword
     * @return User
     */
    public function setCodePassword($codePassword)
    {
        $this->codePassword = $codePassword;

        return $this;
    }

    /**
     * Get codePassword
     *
     * @return string
     */
    public function getCodePassword()
    {
        return $this->codePassword;
    }

    /**
     * Set idFb
     *
     * @param integer $idFb
     * @return User
     */
    public function setIdFb($idFb)
    {
        $this->idFb = $idFb;

        return $this;
    }

    /**
     * Get idFb
     *
     * @return integer
     */
    public function getIdFb()
    {
        return $this->idFb;
    }
    /**
     * Get favorites
     *
     * @return \ArrayCollection
     */
    public function getFavorites()
    {
        return $this->favorites;
    }

    /**
     * Add favorite
     *
     * @return \Article
     */
    public function addFavorites($favorite)
    {
        $this->favorites->add($favorite);
        return $this;
    }
}

/**
 * Category
 *
 * @Table(name="category")
 * @Entity
 */
class Category
{
    /**
     * @var integer
     *
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @Column(name="name", type="string", length=150, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @Column(name="icon", type="string", length=50, nullable=false)
     */
    private $icon;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set icon
     *
     * @param string $icon
     * @return Category
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get icon
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }
}

/**
 * Article
 *
 * @Table(name="article")
 * @Entity
 */
class Article
{
    /**
     * @var integer
     *
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @Column(name="title", type="string", length=200, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @Column(name="description", type="text", nullable=false)
     */
    private $description;

    /**
     * @var float
     *
     * @Column(name="price", type="float", nullable=true)
     */
    private $price;

    /**
     * @var string
     *
     * @Column(name="phone", type="string", length=50, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @Column(name="address", type="string", length=100, nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @Column(name="email", type="string", length=100, nullable=true)
     */
    private $email;

    /**
     * @var integer
     *
     * @Column(name="state", type="integer", nullable=true)
     */
    private $state;

    /**
     * @var integer
     *
     * @Column(name="operation", type="integer", nullable=true)
     */
    private $operation;

    /**
     * @var integer
     *
     * @Column(name="year", type="integer", nullable=true)
     */
    private $year;

    /**
     * @var integer
     *
     * @Column(name="kilometers", type="integer", nullable=true)
     */
    private $kilometers;

    /**
     * @var integer
     *
     * @Column(name="viewed", type="integer", nullable=false)
     */
    private $viewed = 0;

    /**
     * @var \DateTime
     *
     * @Column(name="date_publication", type="datetime", nullable=true)
     */
    private $datePublication;

    /**
     * @var \DateTime
     *
     * @Column(name="date_creation", type="datetime", nullable=true)
     */
    private $dateCreation;

    /**
     * @var \Category
     *
     * @ManyToOne(targetEntity="Category")
     * @JoinColumns({
     *   @JoinColumn(name="category_id", referencedColumnName="id")
     * })
     */
    private $category;

    /**
     * @var \User
     *
     * @ManyToOne(targetEntity="User")
     * @JoinColumns({
     *   @JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var boolean
     *
     * @Column(name="deleted", type="boolean", nullable=false)
     */
    private $deleted;

    /**
     * @OneToMany(targetEntity="Photo", mappedBy="article", cascade={"all"})
     */
    private $photos;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ManyToMany(targetEntity="User", mappedBy="service")
     */
    private $housingType;

    public function __construct()
    {
        $this->photos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->housingType = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get housingType
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHousingType()
    {
        return $this->housingType;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Article
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set price
     *
     * @param float $price
     * @return Article
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Article
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }
    /**
     * Set address
     *
     * @param string $address
     * @return Article
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }
    /**
     * Set email
     *
     * @param string $email
     * @return Article
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set state
     *
     * @param integer $state
     * @return Article
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return integer
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set operation
     *
     * @param integer $operation
     * @return Article
     */
    public function setOperation($operation)
    {
        $this->operation = $operation;

        return $this;
    }

    /**
     * Get operation
     *
     * @return integer
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     * Set year
     *
     * @param integer $year
     * @return Article
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return integer
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set kilometers
     *
     * @param integer $kilometers
     * @return Article
     */
    public function setKilometers($kilometers)
    {
        $this->kilometers = $kilometers;

        return $this;
    }

    /**
     * Get kilometers
     *
     * @return integer
     */
    public function getKilometers()
    {
        return $this->kilometers;
    }

    /**
     * Set viewed
     *
     * @param integer $viewed
     * @return Article
     */
    public function setViewed($viewed)
    {
        $this->viewed = $viewed;

        return $this;
    }

    /**
     * Get viewed
     *
     * @return integer
     */
    public function getViewed()
    {
        return $this->viewed;
    }

    /**
     * Set datePublication
     *
     * @param \DateTime $datePublication
     * @return Article
     */
    public function setDatePublication($datePublication)
    {
        $this->datePublication = $datePublication;

        return $this;
    }

    /**
     * Get datePublication
     *
     * @return \DateTime
     */
    public function getDatePublication()
    {
        return $this->datePublication;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return Article
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set category
     *
     * @param \Category $category
     * @return Article
     */
    public function setCategory(\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set user
     *
     * @param \User $user
     * @return Article
     */
    public function setUser(\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set deleted
     *
     * @param boolean $deleted
     * @return Article
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted
     *
     * @return boolean
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * Get photos
     *
     * @return \ArrayCollection
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * Set photos
     *
     * @param \ArrayCollection $photos
     * @return \Article
     */
    public function setPhotos($photos)
    {
        $this->photos = $photos;
        return $this;
    }

    /**
     * Add photo
     *
     * @return \Article
     */
    public function addPhoto($photo)
    {
        $this->photos->add($photo);
        return $this;
    }
}

/**
 * Photo
 *
 * @Table(name="photo")
 * @Entity
 */
class Photo
{
    /**
     * @var integer
     *
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @Column(name="name", type="text", nullable=false)
     */
    private $name;

    /**
     * @var \Article
     *
     * @ManyToOne(targetEntity="Article")
     * @JoinColumns({
     *   @JoinColumn(name="article_id", referencedColumnName="id")
     * })
     */
    private $article;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Photo
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set article
     *
     * @param \Article $article
     * @return Photo
     */
    public function setArticle(\Article $article = null)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Get article
     *
     * @return \Article
     */
    public function getArticle()
    {
        return $this->article;
    }
}

/**
 * Comment
 *
 * @Table(name="comment")
 * @Entity
 */
class Comment
{
    /**
     * @var integer
     *
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @Column(name="message", type="text", nullable=false)
     */
    private $message;

    /**
     * @var \DateTime
     *
     * @Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var \Article
     *
     * @ManyToOne(targetEntity="Article")
     * @JoinColumns({
     *   @JoinColumn(name="article_id", referencedColumnName="id")
     * })
     */
    private $article;

    /**
     * @var \Comment
     *
     * @ManyToOne(targetEntity="Comment")
     * @JoinColumns({
     *   @JoinColumn(name="comment_id", referencedColumnName="id")
     * })
     */
    private $comment;

    /**
     * @OneToMany(targetEntity="Comment", mappedBy="comment", cascade={"all"})
     */
    private $comments;

    /**
     * @var \User
     *
     * @ManyToOne(targetEntity="User")
     * @JoinColumns({
     *   @JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return Comment
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Comment
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set article
     *
     * @param \Article $article
     * @return Comment
     */
    public function setArticle(\Article $article = null)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Get article
     *
     * @return \Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * Set comment
     *
     * @param \Comment $comment
     * @return Comment
     */
    public function setComment(\Comment $comment = null)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return \Comment
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Get comments
     *
     * @return \ArrayCollection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set comments
     *
     * @param \ArrayCollection $comments
     * @return \Comment
     */
    public function setComments($photos)
    {
        $this->comments = $comments;
        return $this;
    }

    /**
     * Add comment
     *
     * @return \Article
     */
    public function addComment($comments)
    {
        $this->photos->add($comments);
        return $this;
    }

    /**
     * Set user
     *
     * @param \User $user
     * @return Comment
     */
    public function setUser(\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \User
     */
    public function getUser()
    {
        return $this->user;
    }
}
