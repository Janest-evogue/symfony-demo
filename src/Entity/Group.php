<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * On nomme explicitement la table groups
 * parce group est un mot clé en SQL
 * @ORM\Table(name="groups")
 * @ORM\Entity(repositoryClass="App\Repository\GroupRepository")
 */
class Group
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;
    
    /**
     *
     * @var ArrayCollection
     * Relation n-n entre group et user
     * L'attribut qui fait la relation dans l'autre sens
     * (dans la class User) est $groups
     * @ORM\ManyToMany(targetEntity="User", inversedBy="groups")
     * table de relation user_group
     * @ORM\JoinTable(name="user_group")
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

        public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
    
    public function getUsers() {
        return $this->users;
    }

    public function setUsers($users) {
        $this->users = $users;
        return $this;
    }

    public function addUser(User $user)
    {
        // on ajoute l'utilisateur au groupe
        $this->users->add($user);
        // on ajoute ce groupe à l'utilisateur
        // il suffira d'enregistrer le groupe en bdd
        // pour que cela enregistre le lien avec chacun
        // des utilisateurs qu'on lui a ajouté
        // dans la table user_group
        $user->addGroup($this);
    }
    
    /**
     * Permet de faire un echo sur un objet Group
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
}
