<?php

namespace App\Entity;

use App\Repository\EditionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EditionRepository::class)
 */
class Edition
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $editionNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $localName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $originalName;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isSpecial;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDisplayable;

    /**
     * @ORM\OneToMany(targetEntity=Card::class, mappedBy="edition")
     */
    private $cards;

    public function __construct()
    {
        $this->cards = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getLocalName() ?: $this->getOriginalName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEditionNumber(): ?int
    {
        return $this->editionNumber;
    }

    public function setEditionNumber(int $editionNumber): self
    {
        $this->editionNumber = $editionNumber;

        return $this;
    }

    public function getLocalName(): ?string
    {
        return $this->localName;
    }

    public function setLocalName(?string $localName): self
    {
        $this->localName = $localName;

        return $this;
    }

    public function getOriginalName(): ?string
    {
        return $this->originalName;
    }

    public function setOriginalName(string $originalName): self
    {
        $this->originalName = $originalName;

        return $this;
    }

    public function getIsSpecial(): ?bool
    {
        return $this->isSpecial;
    }

    public function setIsSpecial(bool $isSpecial): self
    {
        $this->isSpecial = $isSpecial;

        return $this;
    }

    public function getIsDisplayable(): ?bool
    {
        return $this->isDisplayable;
    }

    public function setIsDisplayable(bool $isDisplayable): self
    {
        $this->isDisplayable = $isDisplayable;

        return $this;
    }

    /**
     * @return Collection|Card[]
     */
    public function getCards(): Collection
    {
        return $this->cards;
    }

    public function addCard(Card $card): self
    {
        if (!$this->cards->contains($card)) {
            $this->cards[] = $card;
            $card->setEdition($this);
        }

        return $this;
    }

    public function removeCard(Card $card): self
    {
        if ($this->cards->removeElement($card)) {
            // set the owning side to null (unless already changed)
            if ($card->getEdition() === $this) {
                $card->setEdition(null);
            }
        }

        return $this;
    }
}