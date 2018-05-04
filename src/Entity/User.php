<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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

    /**
     * Une classe que l'on peut utiliser comme un tableau
     * @var ArrayCollection
     * OneToMany (facultatif) permet de pouvoir accéder aux publications
     * d'un utilisateur depuis un objet User dans cet attribut
     * mappedBy dit quel attribut dans Publication correspond à la clé
     * étrangère (L'attribut avec un ManyToOne vers cette classe)
     * @ORM\OneToMany(targetEntity="Publication", mappedBy="author", cascade={"persist"})
     */
    private $publications;

    public function __construct()
    {
        // on initialise avec un ArrayCollection vide
        $this->publications = new ArrayCollection();
    }
    
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

    public function getPublications() {
        return $this->publications;
    }

    public function setPublications($publications) {
        $this->publications = $publications;
        return $this;
    }

    public function addPublication(Publication $publication)
    {
        // on ajoute la publication à l'utilisateur
        $this->publications->add($publication);
        // eq : $this->publications[] = $publication;
        
        // on définit l'auteur de la publication avec
        // l'objet User qui appelle la méthode
        $publication->setAuthor($this);
        
        return $this;
    }
}
