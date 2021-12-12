<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Doctrine\ORM\EntityRepository;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User
{
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private int $id;

  /**
   * @ORM\Column(type="string", length=255)
   */
  private string $name;

  /**
   * @ORM\Column(type="string", length=255)
   */
  private string $firstName;

  /**
   * @ORM\Column(type="string", length=255)
   */
  private string $username;

  /**
   * @ORM\Column(type="string", length=255)
   */
  private string $password;

  /**
   * @ORM\Column(type="string", length=255)
   */
  private string $email;

  /**
   * @ORM\Column(type="datetime", nullable=true)
   */
  private DateTime $birthDate;

  /**
     *@ORM\Column(type="string", nullable=false)
     */
    private string $roles;


  public function getId(): int
  {
    return $this->id;
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function setName(string $name): self
  {
    $this->name = $name;

    return $this;
  }

  public function getFirstName(): string
  {
    return $this->firstName;
  }

  public function setFirstName(string $firstName): self
  {
    $this->firstName = $firstName;

    return $this;
  }

  public function getUsername(): string
  {
    return $this->username;
  }

  public function setUsername(string $username): self
  {
    $this->username = $username;

    return $this;
  }

  public function getPassword(): string
  {
    return $this->password;
  }

  public function setPassword(string $password): self
  {
    $this->password = $password;

    return $this;
  }

  public function getEmail(): string
  {
    return $this->email;
  }

  public function setEmail(string $email): self
  {
    $this->email = $email;

    return $this;
  }

  public function getBirthDate(): DateTime
  {
    return $this->birthDate;
  }

  public function setBirthDate(DateTime $birthDate): self
  {
    $this->birthDate = $birthDate;

    return $this;
  }

  public function getRoles(): string
  {
      //$roles = $this->roles;
      // guarantee every user at least has ROLE_USER

      return $this->roles;
  }

  public function setRoles(string $roles): self
  {
      $this->roles = $roles;

      return $this;
  }

  
}
