<?php

namespace App\Entity;

use DelPlop\WishListBundle\Entity\Article;
use DelPlop\UserBundle\Repository\UserRepository;
use DelPlop\UserBundle\Trait\UserTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="user")
 * @UniqueEntity(fields={"login"}, message="There is already an account with this login")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class ApplicationUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    use UserTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="DelPlop\WishListBundle\Entity\Article", mappedBy="user")
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $articles;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): ?Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setUser($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            if ($article->getUser() === $this) {
                $article->setUser(null);
            }
        }

        return $this;
    }
}
