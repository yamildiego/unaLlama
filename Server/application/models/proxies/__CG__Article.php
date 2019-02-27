<?php

namespace Proxies\__CG__;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class Article extends \Article implements \Doctrine\ORM\Proxy\Proxy
{
    private $_entityPersister;
    private $_identifier;
    public $__isInitialized__ = false;
    public function __construct($entityPersister, $identifier)
    {
        $this->_entityPersister = $entityPersister;
        $this->_identifier = $identifier;
    }
    /** @private */
    public function __load()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;

            if (method_exists($this, "__wakeup")) {
                // call this after __isInitialized__to avoid infinite recursion
                // but before loading to emulate what ClassMetadata::newInstance()
                // provides.
                $this->__wakeup();
            }

            if ($this->_entityPersister->load($this->_identifier, $this) === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            unset($this->_entityPersister, $this->_identifier);
        }
    }

    /** @private */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    
    public function getHousingType()
    {
        $this->__load();
        return parent::getHousingType();
    }

    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return (int) $this->_identifier["id"];
        }
        $this->__load();
        return parent::getId();
    }

    public function setTitle($title)
    {
        $this->__load();
        return parent::setTitle($title);
    }

    public function getTitle()
    {
        $this->__load();
        return parent::getTitle();
    }

    public function setDescription($description)
    {
        $this->__load();
        return parent::setDescription($description);
    }

    public function getDescription()
    {
        $this->__load();
        return parent::getDescription();
    }

    public function setPrice($price)
    {
        $this->__load();
        return parent::setPrice($price);
    }

    public function getPrice()
    {
        $this->__load();
        return parent::getPrice();
    }

    public function setPhone($phone)
    {
        $this->__load();
        return parent::setPhone($phone);
    }

    public function getPhone()
    {
        $this->__load();
        return parent::getPhone();
    }

    public function setAddress($address)
    {
        $this->__load();
        return parent::setAddress($address);
    }

    public function getAddress()
    {
        $this->__load();
        return parent::getAddress();
    }

    public function setEmail($email)
    {
        $this->__load();
        return parent::setEmail($email);
    }

    public function getEmail()
    {
        $this->__load();
        return parent::getEmail();
    }

    public function setState($state)
    {
        $this->__load();
        return parent::setState($state);
    }

    public function getState()
    {
        $this->__load();
        return parent::getState();
    }

    public function setOperation($operation)
    {
        $this->__load();
        return parent::setOperation($operation);
    }

    public function getOperation()
    {
        $this->__load();
        return parent::getOperation();
    }

    public function setYear($year)
    {
        $this->__load();
        return parent::setYear($year);
    }

    public function getYear()
    {
        $this->__load();
        return parent::getYear();
    }

    public function setKilometers($kilometers)
    {
        $this->__load();
        return parent::setKilometers($kilometers);
    }

    public function getKilometers()
    {
        $this->__load();
        return parent::getKilometers();
    }

    public function setViewed($viewed)
    {
        $this->__load();
        return parent::setViewed($viewed);
    }

    public function getViewed()
    {
        $this->__load();
        return parent::getViewed();
    }

    public function setDatePublication($datePublication)
    {
        $this->__load();
        return parent::setDatePublication($datePublication);
    }

    public function getDatePublication()
    {
        $this->__load();
        return parent::getDatePublication();
    }

    public function setDateCreation($dateCreation)
    {
        $this->__load();
        return parent::setDateCreation($dateCreation);
    }

    public function getDateCreation()
    {
        $this->__load();
        return parent::getDateCreation();
    }

    public function setCategory(\Category $category = NULL)
    {
        $this->__load();
        return parent::setCategory($category);
    }

    public function getCategory()
    {
        $this->__load();
        return parent::getCategory();
    }

    public function setDepartment(\Department $department = NULL)
    {
        $this->__load();
        return parent::setDepartment($department);
    }

    public function getDepartment()
    {
        $this->__load();
        return parent::getDepartment();
    }

    public function setUser(\User $user = NULL)
    {
        $this->__load();
        return parent::setUser($user);
    }

    public function getUser()
    {
        $this->__load();
        return parent::getUser();
    }

    public function setDeleted($deleted)
    {
        $this->__load();
        return parent::setDeleted($deleted);
    }

    public function getDeleted()
    {
        $this->__load();
        return parent::getDeleted();
    }

    public function getPhotos()
    {
        $this->__load();
        return parent::getPhotos();
    }

    public function setPhotos($photos)
    {
        $this->__load();
        return parent::setPhotos($photos);
    }

    public function addPhoto($photo)
    {
        $this->__load();
        return parent::addPhoto($photo);
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'title', 'description', 'price', 'phone', 'address', 'email', 'state', 'operation', 'year', 'kilometers', 'viewed', 'datePublication', 'dateCreation', 'deleted', 'category', 'department', 'user', 'photos', 'housingType');
    }

    public function __clone()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;
            $class = $this->_entityPersister->getClassMetadata();
            $original = $this->_entityPersister->load($this->_identifier);
            if ($original === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            foreach ($class->reflFields as $field => $reflProperty) {
                $reflProperty->setValue($this, $reflProperty->getValue($original));
            }
            unset($this->_entityPersister, $this->_identifier);
        }
        
    }
}