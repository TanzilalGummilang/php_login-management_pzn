<?php

namespace TanzilalGummilang\PHP\LoginManagement\Repository;

use PHPUnit\Framework\TestCase;
use TanzilalGummilang\PHP\LoginManagement\Config\Database;
use TanzilalGummilang\PHP\LoginManagement\Domain\Session;
use TanzilalGummilang\PHP\LoginManagement\Domain\User;


class SessionRepositoryTest extends TestCase
{
  private UserRepository $userRepository;
  private SessionRepository $sessionRepository;

  protected function setUp(): void
  {
    $this->userRepository = new UserRepository(Database::getConnection());
    $this->sessionRepository = new SessionRepository(Database::getConnection());

    $this->sessionRepository->deleteAll();
    $this->userRepository->deleteAll();

    $user = new User;
    $user->id = "tanzilal";
    $user->name = "Tanzilal Gummilang";
    $user->password = "rahasia";

    $this->userRepository->save($user);
  }

  public function testSaveSuccess()
  {
    $session = new Session;
    $session->id = uniqid();
    $session->userId = "tanzilal";

    $this->sessionRepository->save($session);

    $result = $this->sessionRepository->findById($session->id);

    self::assertEquals($session->id, $result->id);
    self::assertEquals($session->userId, $result->userId);
  }

  public function testDeleteByIdSuccess()
  {
    $session = new Session;
    $session->id = uniqid();
    $session->userId = "tanzilal";

    $this->sessionRepository->save($session);

    $result = $this->sessionRepository->findById($session->id);

    self::assertEquals($session->id, $result->id);
    self::assertEquals($session->userId, $result->userId);

    $result = $this->sessionRepository->deleteById($session->id);
    self::assertNull($result);
  }

  public function testFindByIdNotFound()
  {
    $result = $this->sessionRepository->deleteById("Krisno");
    self::assertNull($result);
  }
}