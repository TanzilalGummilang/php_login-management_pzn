<?php

namespace TanzilalGummilang\PHP\LoginManagement\Controller;

use PHPUnit\Framework\TestCase;
use TanzilalGummilang\PHP\LoginManagement\Config\Database;
use TanzilalGummilang\PHP\LoginManagement\Domain\User;
use TanzilalGummilang\PHP\LoginManagement\Repository\UserRepository;


class UserControllerTest extends TestCase
{
  private UserController $userController;
  private UserRepository $userRepository;

  protected function setUp(): void
  {
    $this->userController = new UserController;
    $this->userRepository = new UserRepository(Database::getConnection());
    $this->userRepository->deleteAll();
  }

  // register view test
  public function testRegister()
  {
    $this->userController->register();

    $this->expectOutputRegex("[Register]");
    $this->expectOutputRegex("[Id]");
    $this->expectOutputRegex("[Name]");
    $this->expectOutputRegex("[Password]");
    $this->expectOutputRegex("[Register New User]");
  }

  public function testPostRegisterSuccess()
  {
    $_POST['id'] = "tanzilal";
    $_POST['name'] = "Tanzilal Gummilang";
    $_POST['password'] = "rahasia";

    $this->expectOutputString("");
  }

  public function testPostRegisterValidationError()
  {
    $_POST['id'] = "";
    $_POST['name'] = "";
    $_POST['password'] = "";

    $this->userController->postRegister();

    $this->expectOutputRegex("[Register]");
    $this->expectOutputRegex("[Id]");
    $this->expectOutputRegex("[Name]");
    $this->expectOutputRegex("[Password]");
    $this->expectOutputRegex("[Register New User]");
    $this->expectOutputRegex("[Id, Name, Password cannot blank !!]");
  }

  public function testPostRegisterDuplicate()
  {
    $user = new User;
    $user->id = "tanzilal";
    $user->name = "Tanzilal Gummilang";
    $user->password = "rahasia";

    $this->userRepository->save($user);

    $_POST['id'] = "tanzilal";
    $_POST['name'] = "Tanzilal Gummilang";
    $_POST['password'] = "rahasia";

    $this->userController->postRegister();

    $this->expectOutputRegex("[Register]");
    $this->expectOutputRegex("[Id]");
    $this->expectOutputRegex("[Name]");
    $this->expectOutputRegex("[Password]");
    $this->expectOutputRegex("[Register New User]");
    $this->expectOutputRegex("[User Id already exist]");
  }
  // end register view test

  // login view test
  public function testLogin()
  {
    $this->userController->login();

    $this->expectOutputRegex("[Login User]");
    $this->expectOutputRegex("[Id]");
    $this->expectOutputRegex("[Password]");
  }

  public function testLoginSuccess()
  {
    $user = new User;
    $user->id = "tanzilal";
    $user->name = "Tanzilal Gummilang";
    $user->password = password_hash("rahasia", PASSWORD_BCRYPT);

    $this->userRepository->save($user);

    $_POST['id'] = "tanzilal";
    $_POST['password'] = "rahasia";

    $this->userController->postLogin();

    $this->expectOutputRegex("[Location: /]");
  }

  public function testLoginValidationError()
  {

  }

  public function testLoginWrongPassword()
  {

  }

  public function testLoginUserNotFound()
  {

  }
  // end login view test
}