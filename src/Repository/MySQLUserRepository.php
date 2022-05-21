<?php

declare(strict_types=1);

namespace Salle\PixSalle\Repository;

use PDO;
use Salle\PixSalle\Model\User;
use Salle\PixSalle\Repository\UserRepository;

final class MySQLUserRepository implements UserRepository
{
    private const DATE_FORMAT = 'Y-m-d H:i:s';

    private PDO $databaseConnection;

    public function __construct(PDO $database)
    {
        $this->databaseConnection = $database;
    }

    public function createUser(User $user): void
    {
        $query = <<<'QUERY'
        INSERT INTO users(email, password, createdAt, updatedAt, amount, username, phone, profile_picture, membership)
        VALUES(:email, :password, :createdAt, :updatedAt, :amount, '', '', '')
        QUERY;

        $statement = $this->databaseConnection->prepare($query);

        $email = $user->email();
        $password = $user->password();
        $amount = $user->amount();
        $membership = $user->membership();
        $createdAt = $user->createdAt()->format(self::DATE_FORMAT);
        $updatedAt = $user->updatedAt()->format(self::DATE_FORMAT);

        $statement->bindParam('email', $email, PDO::PARAM_STR);
        $statement->bindParam('password', $password, PDO::PARAM_STR);
        $statement->bindParam('createdAt', $createdAt, PDO::PARAM_STR);
        $statement->bindParam('updatedAt', $updatedAt, PDO::PARAM_STR);
        $statement->bindParam('amount', $amount, PDO::PARAM_STR);

        $statement->execute();
    }

    public function getUserByEmail(string $email)
    {
        $query = <<<'QUERY'
        SELECT * FROM users WHERE email = :email
        QUERY;

        $statement = $this->databaseConnection->prepare($query);

        $statement->bindParam('email', $email, PDO::PARAM_STR);

        $statement->execute();

        $count = $statement->rowCount();
        if ($count > 0) {
            $row = $statement->fetch(PDO::FETCH_OBJ);
            return $row;
        }
        return null;
    }

    public function getUserById(int $id)
    {
        $query = <<<'QUERY'
        SELECT * FROM users WHERE id = :id
        QUERY;

        $statement = $this->databaseConnection->prepare($query);

        $statement->bindParam('id', $id, PDO::PARAM_STR);

        $statement->execute();

        $count = $statement->rowCount();
        if ($count > 0) {
            $row = $statement->fetch(PDO::FETCH_OBJ);
            return $row;
        }
        return null;
    }

    public function updateProfile(int $id, string $username, string $phone, string $picture){
      $query = <<<'QUERY'
      UPDATE users
      SET username = :username, phone = :phone, profile_picture = :picture
      WHERE id = :id
      QUERY;

      $statement = $this->databaseConnection->prepare($query);

      $statement->bindParam('username', $username, PDO::PARAM_STR);
      $statement->bindParam('phone', $phone, PDO::PARAM_STR);
      $statement->bindParam('picture', $picture, PDO::PARAM_STR);
      $statement->bindParam('id', $id, PDO::PARAM_STR);

      $statement->execute();
    }

    public function updatePassword(int $id, string $password){
      $query = <<<'QUERY'
      UPDATE users
      SET password = :password
      WHERE id = :id
      QUERY;

      $statement = $this->databaseConnection->prepare($query);

      $statement->bindParam('password', $password, PDO::PARAM_STR);
      $statement->bindParam('id', $id, PDO::PARAM_STR);

      $statement->execute();
    }

    public function addAmount(int $id, string $amount){
      $query = <<<'QUERY'
      UPDATE users
      SET amount = amount + :amount
      WHERE id = :id
      QUERY;

      $statement = $this->databaseConnection->prepare($query);

      $statement->bindParam('amount', $amount, PDO::PARAM_STR);
      $statement->bindParam('id', $id, PDO::PARAM_STR);

      $statement->execute();
    }

    public function changePlan(int $id, string $newPlan){
      $query = <<<'QUERY'
      UPDATE users
      SET membership = :newPlan
      WHERE id = :id
      QUERY;

      $statement = $this->databaseConnection->prepare($query);

      $statement->bindParam('newPlan', $newPlan, PDO::PARAM_STR);
      $statement->bindParam('id', $id, PDO::PARAM_STR);

      $statement->execute();
    }
}
