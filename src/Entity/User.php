<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use function sprintf;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 *
 * @psalm-suppress PropertyNotSetInConstructor
 * @psalm-suppress DeprecatedInterface
 */
class User extends BaseUser
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @noinspection PhpMissingFieldTypeInspection
     */
    protected $id;

    /** @ORM\Column(type="string", nullable=false) */
    private string $firstName;

    /** @ORM\Column(type="string", nullable=false) */
    private string $lastName;

    public function __construct(string $firstName, string $lastName, string $email, string $plainPassword)
    {
        parent::__construct();
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->setEmail($email);
        $this->setPlainPassword($plainPassword);
    }

    public function __toString()
    {
        return sprintf('%s %s', $this->getFirstName(), $this->getLastName());
    }

    /**
     * @psalm-suppress MissingParamType
     * @psalm-suppress ImplementedReturnTypeMismatch
     */
    public function setEmail($email): void
    {
        parent::setEmail($email);
        $this->setUsername($email);
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }
}
