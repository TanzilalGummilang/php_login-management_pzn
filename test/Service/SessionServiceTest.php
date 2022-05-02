<?php

namespace TanzilalGummilang\PHP\LoginManagement\Service;

require_once __DIR__ . '/../Helper/helper.php';

use PHPUnit\Framework\TestCase;
use TanzilalGummilang\PHP\LoginManagement\Config\Database;
use TanzilalGummilang\PHP\LoginManagement\Domain\Session;
use TanzilalGummilang\PHP\LoginManagement\Domain\User;
use TanzilalGummilang\PHP\LoginManagement\Repository\SessionRepository;
use TanzilalGummilang\PHP\LoginManagement\Repository\UserRepository;


class SessionServiceTest extends TestCase
{
  private UserRepository $userRepository;
  private SessionRepository $sessionRepository;
  private SessionService $sessionService;

  protected function setUp(): void
  {
    $this->userRepository = new UserRepository(Database::getConnection());
    $this->sessionRepository = new SessionRepository(Database::getConnection());
    $this->sessionService = new SessionService($this->sessionRepository, $this->userRepository);

    $this->sessionRepository->deleteAll();
    $this->userRepository->deleteAll();

    $user = new User;
    $user->id = "tanzilal";
    $user->name = "Tanzilal Gummilang";
    $user->password = password_hash("rahasia", PASSWORD_BCRYPT);
    $this->userRepository->save($user);
  }

  public function testCreate()
  {
    $session = $this->sessionService->create("tanzilal");

    $this->expectOutputRegex("[X-PZN-SESSION: $session->id]");

    $result = $this->sessionRepository->findById($session->id);

    self::assertEquals("tanzilal", $result->userId);
  }

  public function testDestroy()
  {
    $session = new Session;
    $session->id = uniqid();
    $session->userId = "tanzilal";

    $this->sessionRepository->save($session);

    $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;
    
    $this->sessionService->destroy();

    $this->expectOutputRegex("[X-PZN-SESSION: ]");

    $result = $this->sessionRepository->findById($session->id);
    self::assertNull($result);
  }

  public function testCurrent()
  {
    $session = new Session;
    $session->id = uniqid();
    $session->userId = "tanzilal";

    $this->sessionRepository->save($session);

    $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

    $user = $this->sessionService->current();
    
    self::assertEquals($session->userId, $user->id);
  }
}