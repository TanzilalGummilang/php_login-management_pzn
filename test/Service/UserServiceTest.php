<?php

namespace TanzilalGummilang\PHP\LoginManagement\Service;

use PHPUnit\Framework\TestCase;
use TanzilalGummilang\PHP\LoginManagement\Config\Database;
use TanzilalGummilang\PHP\LoginManagement\Domain\User;
use TanzilalGummilang\PHP\LoginManagement\Exception\ValidationException;
use TanzilalGummilang\PHP\LoginManagement\Model\UserRegisterRequest;
use TanzilalGummilang\PHP\LoginManagement\Repository\UserRepository;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotEquals;
use function PHPUnit\Framework\assertTrue;


class UserServiceTest extends TestCase
{
  private UserService $userService;
  private UserRepository $userRepository;

  protected function setUp(): void
  {
    $connection = Database::getConnection();
    $this->userRepository = new UserRepository($connection);
    $this->userService = new UserService($this->userRepository);

    $this->userRepository->deleteAll();
  }

  public function testRegisterSuccess()
  {
    $request = new UserRegisterRequest;
    $request->id = "001";
    $request->name = "Tanzilal";
    $request->password = "rahasia";

    $response = $this->userService->register($request);

    assertEquals($request->id, $response->user->id);
    assertEquals($request->name, $response->user->name);
    assertNotEquals($request->password, $response->user->password);

    assertTrue(password_verify($request->password, $response->user->password));
  }

  public function testRegisterFailure()
  {
    $this->expectException(ValidationException::class);

    $request = new UserRegisterRequest;
    $request->id = "";
    $request->name = "";
    $request->password = "";

    $this->userService->register($request);
  }

  public function testRegisterDuplicate()
  {
    $user = new User;
    $user->id = "001";
    $user->name = "Tanzilal";
    $user->password = "rahasia";

    $this->userRepository->save($user);

    $this->expectException(ValidationException::class);

    $request = new UserRegisterRequest;
    $request->id = "001";
    $request->name = "Tanzilal";
    $request->password = "rahasia";

    $this->userService->register($request);
  }
}