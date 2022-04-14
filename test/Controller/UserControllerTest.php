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

  public function testRegister()
  {
    $this->userController->register();

    $this->expectOutputRegex("[Register]");
    $this->expectOutputRegex("[Id]");
    $this->expectOutputRegex("[Name]");
    $this->expectOutputRegex("[Password]");
    $this->expectOutputRegex("[Register New User]");
  }

  public function testSuccess()
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
}