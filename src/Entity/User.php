<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    /**
     * Clé primaire
     * @ORM\Id()
     * auto-increment
     * @ORM\GeneratedValue()
     * champ de type integer dans la table en bdd
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * lastname : varchar(100) dans la table en bdd
     * @ORM\Column(type="string", length=100)
     */
    private $lastname;
    
    /**
     * @var string
     * firstname : varchar(100) dans la table en bdd
     * @ORM\Column(type="string", length=100)
     */
    private $firstname;
    
    /**
     * @var string 
     * email : varchar(255) unique
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;
    
    /**
     * @var \Datetime
     * birthdate : date en bdd
     * @ORM\Column(type="date")
     */
    private $birthdate;


    public function getId()
    {
        return $this->id;
    }
    
    public function getLastname() {
        return $this->lastname;
    }

    public function getFirstname() {
        return $this->firstname;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getBirthdate() {
        return $this->birthdate;
    }

    public function setLastname($lastname) {
        $this->lastname = $lastname;
        return $this;
    }

    public function setFirstname($firstname) {
        $this->firstname = $firstname;
        return $this;
    }

    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    public function setBirthdate(\Datetime $birthdate) {
        $this->birthdate = $birthdate;
        return $this;
    }


}
