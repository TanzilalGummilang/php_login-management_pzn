<?php

namespace TanzilalGummilang\PHP\LoginManagement\Controller;
use TanzilalGummilang\PHP\LoginManagement\App\View;
use TanzilalGummilang\PHP\LoginManagement\Config\Database;
use TanzilalGummilang\PHP\LoginManagement\Repository\SessionRepository;
use TanzilalGummilang\PHP\LoginManagement\Repository\UserRepository;
use TanzilalGummilang\PHP\LoginManagement\Service\SessionService;


class HomeController
{
  private SessionService $sessionService;

  public function __construct()
  {
    $connection = Database::getConnection();
    $userRepository = new UserRepository($connection);
    $sessionRepository = new SessionRepository($connection);
    $this->sessionService = new SessionService($sessionRepository, $userRepository);
  }

  function index()
  {
    $user = $this->sessionService->current();

    if($user == null){
      View::render('Home/index', [
        "title" => "PHP Login management"
      ]);
    }else{
      View::render('Home/dashboard', [
        "title" => "Dashboard",
        "user" => [
          "name" => $user->name
        ]
      ]);
    }
  }
}