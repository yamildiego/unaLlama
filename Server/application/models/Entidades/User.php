<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity
 */
class User
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=100, nullable=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=200, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="text", nullable=true)
     */
    private $photo;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="text", nullable=true)
     */
    private $password;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean", nullable=false)
     */
    private $active;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_connection", type="datetime", nullable=true)
     */
    private $lastConnection;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="request_password", type="datetime", nullable=true)
     */
    private $requestPassword;

    /**
     * @var string
     *
     * @ORM\Column(name="code_activation", type="text", nullable=true)
     */
    private $codeActivation;

    /**
     * @var string
     *
     * @ORM\Column(name="code_password", type="text", nullable=true)
     */
    private $codePassword;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_fb", type="integer", nullable=true)
     */
    private $idFb;


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
}
