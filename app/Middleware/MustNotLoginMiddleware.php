<?php

namespace TanzilalGummilang\PHP\LoginManagement\Middleware;

use TanzilalGummilang\PHP\LoginManagement\App\View;
use TanzilalGummilang\PHP\LoginManagement\Config\Database;
use TanzilalGummilang\PHP\LoginManagement\Repository\SessionRepository;
use TanzilalGummilang\PHP\LoginManagement\Repository\UserRepository;
use TanzilalGummilang\PHP\LoginManagement\Service\SessionService;


class MustNotLoginMiddleware implements Middleware
{
  private SessionService $sessionService;

  public function __construct()
  {
    $userRepository = new UserRepository(Database::getConnection());
    $sessionRepository = new SessionRepository(Database::getConnection());
    $this->sessionService = new SessionService($sessionRepository, $userRepository);
  }

  function before(): void
  {
    $user = $this->sessionService->current();
    if($user != null){
      View::redirect('/');
    }
  }
}