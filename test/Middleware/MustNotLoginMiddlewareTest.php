<?php

namespace TanzilalGummilang\PHP\LoginManagement\App {

  function header(string $value){
    echo $value;
  }
}

namespace TanzilalGummilang\PHP\LoginManagement\Middleware {

  use PHPUnit\Framework\TestCase;
  use TanzilalGummilang\PHP\LoginManagement\Config\Database;
  use TanzilalGummilang\PHP\LoginManagement\Domain\Session;
  use TanzilalGummilang\PHP\LoginManagement\Domain\User;
  use TanzilalGummilang\PHP\LoginManagement\Repository\SessionRepository;
  use TanzilalGummilang\PHP\LoginManagement\Repository\UserRepository;
  use TanzilalGummilang\PHP\LoginManagement\Service\SessionService;


  class MustNotLoginMiddlewareTest extends TestCase
  {
    private UserRepository $userRepository;
    private SessionRepository $sessionRepository;
    private MustNotLoginMiddleware $middleware;
  
    protected function setUp(): void
    {
      $this->middleware = new MustNotLoginMiddleware;
      putenv("mode=test");

      $this->userRepository = new UserRepository(Database::getConnection());
      $this->sessionRepository = new SessionRepository(Database::getConnection());

      $this->sessionRepository->deleteAll();
      $this->userRepository->deleteAll();
    }
  
    public function testBeforeGuest()
    {
      $this->middleware->before();

      $this->expectOutputString("");
    }

    public function testBeforeLoginUser()
    {
      $user = new User;
      $user->id = "tanzilal";
      $user->name = "Tanzilal Gummilang";
      $user->password = password_hash("rahasia", PASSWORD_BCRYPT);
      $this->userRepository->save($user);

      $session = new Session;
      $session->id = uniqid();
      $session->userId = $user->id;
      $this->sessionRepository->save($session);

      $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

      $this->middleware->before();

      $this->expectOutputRegex("[Location: /]");
    }

  }
}