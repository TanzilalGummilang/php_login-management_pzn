<?php

namespace TanzilalGummilang\PHP\LoginManagement\Controller;

use PHPUnit\Framework\TestCase;
use TanzilalGummilang\PHP\LoginManagement\Config\Database;
use TanzilalGummilang\PHP\LoginManagement\Domain\Session;
use TanzilalGummilang\PHP\LoginManagement\Domain\User;
use TanzilalGummilang\PHP\LoginManagement\Repository\SessionRepository;
use TanzilalGummilang\PHP\LoginManagement\Repository\UserRepository;
use TanzilalGummilang\PHP\LoginManagement\Service\SessionService;

class HomeControllerTest extends TestCase
{
  private HomeController $homeController;
  private UserRepository $userRepository;
  private SessionRepository $sessionRepository;

  protected function setUp(): void
  {
    $this->homeController = new HomeController;
    $this->userRepository = new UserRepository(Database::getConnection());
    $this->sessionRepository = new SessionRepository(Database::getConnection());

    $this->sessionRepository->deleteAll();
    $this->userRepository->deleteAll();
  }

  public function testGuest()
  {
    $this->homeController->index();

    $this->expectOutputRegex("[Login Management]");
  }

  public function testUserLogin()
  {
    $user = new User;
    $user->id = "tanzilal";
    $user->name = "Tanzilal HomeController Test";
    $user->password = password_hash("rahasia", PASSWORD_BCRYPT);
    $this->userRepository->save($user);

    $session = new Session;
    $session->id = uniqid();
    $session->userId = $user->id;
    $this->sessionRepository->save($session);

    $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

    $this->homeController->index();
    
    $this->expectOutputRegex("[Hello, $user->name]");
  }
}