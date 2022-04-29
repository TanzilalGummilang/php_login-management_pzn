<?php

namespace TanzilalGummilang\PHP\LoginManagement\Repository;

use PHPUnit\Framework\TestCase;
use TanzilalGummilang\PHP\LoginManagement\Config\Database;
use TanzilalGummilang\PHP\LoginManagement\Domain\User;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNull;


class UserRepositoryTest extends TestCase
{
  private UserRepository $userRepository;
  private SessionRepository $sessionRepository;

  protected function setUp(): void
  {
    $this->sessionRepository = new SessionRepository(Database::getConnection());
    $this->sessionRepository->deleteAll();
    
    $this->userRepository = new UserRepository(Database::getConnection());
    $this->userRepository->deleteAll();
  }

  public function testSaveSuccess()
  {
    $user = new User;
    $user->id = "usr001";
    $user->name = "Tanzilal";
    $user->password = "rahasia";

    $this->userRepository->save($user);

    $result = $this->userRepository->findById($user->id);

    assertEquals($user->id, $result->id);
    assertEquals($user->name, $result->name);
    assertEquals($user->password, $result->password);
  }

  public function testFindByIdNotFound()
  {
    $user = $this->userRepository->findById("notfound");
    assertNull($user);
  }

  // update test
  public function testUpdate()
  {
    $user = new User;
    $user->id = "tanzilal";
    $user->name = "Tanzilal Gummilang";
    $user->password = password_hash("rahasia", PASSWORD_BCRYPT);
    $this->userRepository->save($user);

    $user->name = "Gummilang Tanzilal";
    $this->userRepository->update($user);

    $result = $this->userRepository->findById($user->id);

    self::assertEquals($user->id, $result->id);
    self::assertEquals($user->name, $result->name);
    self::assertEquals($user->password, $result->password);
  }
  // end update test
}