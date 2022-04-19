<?php

namespace TanzilalGummilang\PHP\LoginManagement\Repository;

use LDAP\Result;
use PHPUnit\Framework\TestCase;
use TanzilalGummilang\PHP\LoginManagement\Config\Database;
use TanzilalGummilang\PHP\LoginManagement\Domain\Session;


class SessionRepositoryTest extends TestCase
{
  private SessionRepository $sessionRepository;

  protected function setUp(): void
  {
    $this->sessionRepository = new SessionRepository(Database::getConnection());
    $this->sessionRepository->deleteAll();
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