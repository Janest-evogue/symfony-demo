<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PublicationRepository")
 */
class Publication
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $content;
    
    /**
     * @var User
     * // clé étrangère vers user
     * @ORM\ManyToOne(targetEntity="User")
     * // non null
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }
    
    public function getAuthor()
    {
        return $this->author;
    }

    public function setAuthor(User $author)
    {
        $this->author = $author;
        return $this;
    }
    
    /**
     * 
     * @return string Le nom complet de l'auteur
     */
    public function getAuthorFullname()
    {
        if (!is_null($this->author)) {
            return $this->author->getFirstname()
                . ' ' . $this->author->getLastname();
        }
    }
}
