<?php

declare(strict_types=1);

namespace Salle\PixSalle\Service;

class ValidatorService
{
  public function __construct()
  {
  }

  public function validateEmail(string $email)
  {
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return 'The email address is not valid';
    } else if (!strpos($email, "@salle.url.edu")) {
      return 'Only emails from the domain @salle.url.edu are accepted.';
    }
    return '';
  }

  public function validatePassword(string $password)
  {
    if (empty($password) || strlen($password) < 6) {
      return 'The password must contain at least 6 characters.';
    } else if (!preg_match("~[0-9]+~", $password) || !preg_match("/[a-z]/", $password) || !preg_match("/[A-Z]/", $password)) {
      return 'The password must contain both upper and lower case letters and numbers';
    }
    return '';
  }

  public function validatePhone(string $phone)
  {
    if (!preg_match("/^(6\d{8})$/", $phone)){
      return 'The phone number is not valid';
    }
    return '';
  }

  public function validateUsername(string $username)
  {
    if(!preg_match('/^[a-zA-Z0-9_]+$/', $username))
    {
      return "Only alphanumeric characters allowed for username";
    }
    return '';
  }

  public function validatePassWithBDD(string $password, string $passBDD){
    if(strcmp(md5($password),$passBDD) != 0)
      return "The password doesn't match with the password in Database";
    return '';
  }

  public function comparePassword(string $passwordNew, string $passwordConfirm){
    if(strcmp($passwordNew,$passwordConfirm) != 0)
      return "Passwords doesn't match between us";
    return '';
  }
}
