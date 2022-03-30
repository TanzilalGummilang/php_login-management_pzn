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

  protected function setUp(): void
  {
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

    $result = $this->userRepository->findByID($user->id);

    assertEquals($user->id, $result->id);
    assertEquals($user->name, $result->name);
    assertEquals($user->password, $result->password);
  }

  public function testFindByIdNotFound()
  {
    $user = $this->userRepository->findByID("notfound");
    assertNull($user);
  }
}