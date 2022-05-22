<?php

declare(strict_types=1);

namespace Salle\PixSalle\Repository;

use Salle\PixSalle\Model\User;

interface UserRepository
{
    public function createUser(User $user): void;
    public function getUserByEmail(string $email);
    public function getUserById(int $id);
    public function updateProfile(int $id, string $username, string $phone, string $picture);
    public function updatePassword(int $id, string $password);
    public function addAmount(int $id, string $amount);
    public function changePlan(int $id, string $newPlan);
    public function addPortfolio(int $id, string $portfolio);
}
