<?php

namespace App\Entity;

use App\Repository\UserOwnedCardRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserOwnedCardRepository::class)
 * @ORM\Table(name="user_owned_card")
 */
class UserOwnedCard
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=ApplicationUser::class, inversedBy="userOwnedCards")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Card::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $card;

    /**
     * @ORM\Column(type="integer")
     */
    private $number;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $language;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isForTrade;

    public function __toString()
    {
        return $this->getCard()->__toString();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?ApplicationUser
    {
        return $this->user;
    }

    public function setUser(?ApplicationUser $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCard(): ?Card
    {
        return $this->card;
    }

    public function setCard(?Card $card): self
    {
        $this->card = $card;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(?int $number): self
    {
        $this->number = $number ?: 1;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(?string $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getIsForTrade(): ?bool
    {
        return $this->isForTrade;
    }

    public function setIsForTrade(bool $isForTrade): self
    {
        $this->isForTrade = $isForTrade;

        return $this;
    }
}
