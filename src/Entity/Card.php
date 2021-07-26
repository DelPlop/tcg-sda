<?php

namespace App\Entity;

use App\Repository\CardRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

/**
 * @ORM\Entity(repositoryClass=CardRepository::class)
 */
class Card implements Translatable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=6)
     */
    private $code;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Gedmo\Locale
     */
    private $locale;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cost;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $strength;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $strengthModifier;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $vitality;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $vitalityModifier;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $resistance;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $resistanceModifier;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $siteNumber;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $shadowNumber;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isUnique;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isRingBearer;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isAuthorized;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text", nullable=true)
     */
    private $text;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $quote;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isTengwar;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isRf;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isRfa;

    /**
     * @ORM\Column(type="boolean")
     */
    private $hasLocalImage;

    /**
     * @ORM\Column(type="integer")
     */
    private $position;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDisplayable;

    /**
     * @ORM\ManyToOne(targetEntity=Edition::class, inversedBy="cards")
     * @ORM\JoinColumn(nullable=false)
     */
    private $edition;

    /**
     * @ORM\ManyToOne(targetEntity=Culture::class, inversedBy="cards")
     */
    private $culture;

    /**
     * @ORM\ManyToOne(targetEntity=Rarity::class, inversedBy="cards")
     * @ORM\JoinColumn(nullable=false)
     */
    private $rarity;

    /**
     * @ORM\ManyToOne(targetEntity=Type::class, inversedBy="cards")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Subtype::class, inversedBy="cards")
     */
    private $subtype;

    /**
     * @ORM\ManyToOne(targetEntity=Signet::class, inversedBy="cards")
     */
    private $signet;

    /**
     * @ORM\ManyToMany(targetEntity=Phase::class, inversedBy="cards")
     */
    private $phases;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="cards")
     */
    private $tags;

    /**
     * @ORM\ManyToMany(targetEntity=Card::class, inversedBy="reverseMultiCards")
     * @ORM\JoinTable(name="multi_card")
     */
    private $multiCards;

    /**
     * @ORM\ManyToMany(targetEntity=Card::class, mappedBy="multiCards")
     * @ORM\JoinTable(name="multi_card")
     */
    private $reverseMultiCards;

    public function __construct()
    {
        $this->phases = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->multiCards = new ArrayCollection();
        $this->reverseMultiCards = new ArrayCollection();
    }

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCost(): ?int
    {
        return $this->cost;
    }

    public function setCost(?int $cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    public function getStrength(): ?int
    {
        return $this->strength;
    }

    public function setStrength(?int $strength): self
    {
        $this->strength = $strength;

        return $this;
    }

    public function getStrengthModifier(): ?string
    {
        return $this->strengthModifier;
    }

    public function setStrengthModifier(?string $strengthModifier): self
    {
        $this->strengthModifier = $strengthModifier;

        return $this;
    }

    public function getVitality(): ?int
    {
        return $this->vitality;
    }

    public function setVitality(?int $vitality): self
    {
        $this->vitality = $vitality;

        return $this;
    }

    public function getVitalityModifier(): ?string
    {
        return $this->vitalityModifier;
    }

    public function setVitalityModifier(?string $vitalityModifier): self
    {
        $this->vitalityModifier = $vitalityModifier;

        return $this;
    }

    public function getResistance(): ?int
    {
        return $this->resistance;
    }

    public function setResistance(?int $resistance): self
    {
        $this->resistance = $resistance;

        return $this;
    }

    public function getResistanceModifier(): ?string
    {
        return $this->resistanceModifier;
    }

    public function setResistanceModifier(?string $resistanceModifier): self
    {
        $this->resistanceModifier = $resistanceModifier;

        return $this;
    }

    public function getSiteNumber(): ?int
    {
        return $this->siteNumber;
    }

    public function setSiteNumber(?int $siteNumber): self
    {
        $this->siteNumber = $siteNumber;

        return $this;
    }

    public function getShadowNumber(): ?int
    {
        return $this->shadowNumber;
    }

    public function setShadowNumber(?int $shadowNumber): self
    {
        $this->shadowNumber = $shadowNumber;

        return $this;
    }

    public function getIsUnique(): ?bool
    {
        return $this->isUnique;
    }

    public function setIsUnique(bool $isUnique): self
    {
        $this->isUnique = $isUnique;

        return $this;
    }

    public function getIsRingBearer(): ?bool
    {
        return $this->isRingBearer;
    }

    public function setIsRingBearer(bool $isRingBearer): self
    {
        $this->isRingBearer = $isRingBearer;

        return $this;
    }

    public function getIsAuthorized(): ?bool
    {
        return $this->isAuthorized;
    }

    public function setIsAuthorized(bool $isAuthorized): self
    {
        $this->isAuthorized = $isAuthorized;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getQuote(): ?string
    {
        return $this->quote;
    }

    public function setQuote(?string $quote): self
    {
        $this->quote = $quote;

        return $this;
    }

    public function getIsTengwar(): ?bool
    {
        return $this->isTengwar;
    }

    public function setIsTengwar(bool $isTengwar): self
    {
        $this->isTengwar = $isTengwar;

        return $this;
    }

    public function getIsRf(): ?bool
    {
        return $this->isRf;
    }

    public function setIsRf(bool $isRf): self
    {
        $this->isRf = $isRf;

        return $this;
    }

    public function getIsRfa(): ?bool
    {
        return $this->isRfa;
    }

    public function setIsRfa(bool $isRfa): self
    {
        $this->isRfa = $isRfa;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

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

    public function getEdition(): ?Edition
    {
        return $this->edition;
    }

    public function setEdition(?Edition $edition): self
    {
        $this->edition = $edition;

        return $this;
    }

    public function getCulture(): ?Culture
    {
        return $this->culture;
    }

    public function setCulture(?Culture $culture): self
    {
        $this->culture = $culture;

        return $this;
    }

    public function getRarity(): ?Rarity
    {
        return $this->rarity;
    }

    public function setRarity(?Rarity $rarity): self
    {
        $this->rarity = $rarity;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getSubtype(): ?Subtype
    {
        return $this->subtype;
    }

    public function setSubtype(?Subtype $subtype): self
    {
        $this->subtype = $subtype;

        return $this;
    }

    public function getSignet(): ?Signet
    {
        return $this->signet;
    }

    public function setSignet(?Signet $signet): self
    {
        $this->signet = $signet;

        return $this;
    }

    /**
     * @return Collection|Phase[]
     */
    public function getPhases(): Collection
    {
        return $this->phases;
    }

    public function addPhase(Phase $phase): self
    {
        if (!$this->phases->contains($phase)) {
            $this->phases[] = $phase;
        }

        return $this;
    }

    public function removePhase(Phase $phase): self
    {
        $this->phases->removeElement($phase);

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getMultiCards(): Collection
    {
        return $this->multiCards;
    }

    public function addMultiCard(self $multiCard): self
    {
        if (!$this->multiCards->contains($multiCard)) {
            $this->multiCards[] = $multiCard;
        }

        return $this;
    }

    public function removeMultiCard(self $multiCard): self
    {
        $this->multiCards->removeElement($multiCard);

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getReverseMultiCards(): Collection
    {
        return $this->reverseMultiCards;
    }

    /**
     * @return Collection|self[]
     */
    public function getAlternativeCards(): Collection
    {
        return new ArrayCollection(
            array_merge($this->reverseMultiCards->toArray(), $this->getMultiCards()->toArray())
        );
    }

    public function getHasLocalImage(): ?bool
    {
        return $this->hasLocalImage;
    }

    public function setHasLocalImage(bool $hasLocalImage): self
    {
        $this->hasLocalImage = $hasLocalImage;

        return $this;
    }
}
