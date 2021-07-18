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
}
