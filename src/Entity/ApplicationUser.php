<?php

namespace App\Entity;

use DelPlop\UserBundle\Repository\UserRepository;
use DelPlop\UserBundle\Trait\UserTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="user")
 * @UniqueEntity(fields={"login"}, message="There is already an account with this login")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class ApplicationUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    use UserTrait;
    use TimestampableEntity;

    public const DEFAULT_USER_ID = 1;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $anonymizedAt;

    /**
     * @ORM\ManyToMany(targetEntity=Card::class)
     * @ORM\JoinTable(name="user_owned_card")
     */
    private $ownedCards;

    /**
     * @ORM\ManyToMany(targetEntity=Card::class)
     * @ORM\JoinTable(name="user_wanted_card")
     */
    private $wantedCards;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
        $this->ownedCards = new ArrayCollection();
        $this->wantedCards = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnonymizedAt(): ?\DateTimeImmutable
    {
        return $this->anonymizedAt;
    }

    public function setAnonymizedAt(?\DateTimeImmutable $anonymizedAt): self
    {
        $this->anonymizedAt = $anonymizedAt;

        return $this;
    }

    /**
     * @return Collection|Card[]
     */
    public function getOwnedCards(): Collection
    {
        return $this->ownedCards;
    }

    public function addOwnedCard(Card $ownedCard): self
    {
        if (!$this->ownedCards->contains($ownedCard)) {
            $this->ownedCards[] = $ownedCard;
        }

        return $this;
    }

    public function removeOwnedCard(Card $ownedCard): self
    {
        $this->ownedCards->removeElement($ownedCard);

        return $this;
    }

    /**
     * @return Collection|Card[]
     */
    public function getWantedCards(): Collection
    {
        return $this->wantedCards;
    }

    public function addWantedCard(Card $wantedCard): self
    {
        if (!$this->wantedCards->contains($wantedCard)) {
            $this->wantedCards[] = $wantedCard;
        }

        return $this;
    }

    public function removeWantedCard(Card $wantedCard): self
    {
        $this->wantedCards->removeElement($wantedCard);

        return $this;
    }
}
