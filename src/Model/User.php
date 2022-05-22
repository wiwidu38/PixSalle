<?php

declare(strict_types=1);

namespace Salle\PixSalle\Model;

use DateTime;

class User
{

  private int $id;
  private string $email;
  private string $password;
  private string $username;
  private string $phone;
  private string $picture;
  private float $amount;
  private string $membership;
  private string $portfolio;
  private Datetime $createdAt;
  private Datetime $updatedAt;

  public function __construct(
    string $email,
    string $password,
    Datetime $createdAt,
    Datetime $updatedAt
  ) {
    $this->email = $email;
    $this->password = $password;
    $this->createdAt = $createdAt;
    $this->updatedAt = $updatedAt;
    $this->amount = 30.0;
  }

  public function id()
  {
    return $this->id;
  }

  public function email()
  {
    return $this->email;
  }

  public function password()
  {
    return $this->password;
  }

  public function createdAt()
  {
    return $this->createdAt;
  }

  public function updatedAt()
  {
    return $this->updatedAt;
  }

  public function username()
  {
    return $this->username;
  }

  public function phone()
  {
    return $this->phone;
  }

  public function picture()
  {
    return $this->picture;
  }

  public function amount()
  {
    return $this->amount;
  }

  public function membership()
  {
    return $this->membership;
  }

  public function portfolio()
  {
    return $this->portfolio;
  }
}
