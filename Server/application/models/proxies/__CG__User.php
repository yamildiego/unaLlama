<?php

namespace Proxies\__CG__;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class User extends \User implements \Doctrine\ORM\Proxy\Proxy
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

    
    public function addService(\Article $service)
    {
        $this->__load();
        return parent::addService($service);
    }

    public function removeService(\Article $service)
    {
        $this->__load();
        return parent::removeService($service);
    }

    public function getServices()
    {
        $this->__load();
        return parent::getServices();
    }

    public function setServices($services)
    {
        $this->__load();
        return parent::setServices($services);
    }

    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return (int) $this->_identifier["id"];
        }
        $this->__load();
        return parent::getId();
    }

    public function setName($name)
    {
        $this->__load();
        return parent::setName($name);
    }

    public function getName()
    {
        $this->__load();
        return parent::getName();
    }

    public function setUsername($username)
    {
        $this->__load();
        return parent::setUsername($username);
    }

    public function getUsername()
    {
        $this->__load();
        return parent::getUsername();
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

    public function setPhoto($photo)
    {
        $this->__load();
        return parent::setPhoto($photo);
    }

    public function getPhoto()
    {
        $this->__load();
        return parent::getPhoto();
    }

    public function setPassword($password)
    {
        $this->__load();
        return parent::setPassword($password);
    }

    public function getPassword()
    {
        $this->__load();
        return parent::getPassword();
    }

    public function setActive($active)
    {
        $this->__load();
        return parent::setActive($active);
    }

    public function getActive()
    {
        $this->__load();
        return parent::getActive();
    }

    public function setLastConnection($lastConnection)
    {
        $this->__load();
        return parent::setLastConnection($lastConnection);
    }

    public function getLastConnection()
    {
        $this->__load();
        return parent::getLastConnection();
    }

    public function setRequestPassword($requestPassword)
    {
        $this->__load();
        return parent::setRequestPassword($requestPassword);
    }

    public function getRequestPassword()
    {
        $this->__load();
        return parent::getRequestPassword();
    }

    public function setCodeActivation($codeActivation)
    {
        $this->__load();
        return parent::setCodeActivation($codeActivation);
    }

    public function getCodeActivation()
    {
        $this->__load();
        return parent::getCodeActivation();
    }

    public function setCodePassword($codePassword)
    {
        $this->__load();
        return parent::setCodePassword($codePassword);
    }

    public function getCodePassword()
    {
        $this->__load();
        return parent::getCodePassword();
    }

    public function setIdFb($idFb)
    {
        $this->__load();
        return parent::setIdFb($idFb);
    }

    public function getIdFb()
    {
        $this->__load();
        return parent::getIdFb();
    }

    public function getFavorites()
    {
        $this->__load();
        return parent::getFavorites();
    }

    public function addFavorites($favorite)
    {
        $this->__load();
        return parent::addFavorites($favorite);
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'name', 'username', 'email', 'photo', 'password', 'active', 'lastConnection', 'requestPassword', 'codeActivation', 'codePassword', 'idFb', 'service');
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