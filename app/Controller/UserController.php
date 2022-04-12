<?php

namespace TanzilalGummilang\PHP\LoginManagement\Controller;

use TanzilalGummilang\PHP\LoginManagement\App\View;
use TanzilalGummilang\PHP\LoginManagement\Config\Database;
use TanzilalGummilang\PHP\LoginManagement\Exception\ValidationException;
use TanzilalGummilang\PHP\LoginManagement\Model\UserRegisterRequest;
use TanzilalGummilang\PHP\LoginManagement\Repository\UserRepository;
use TanzilalGummilang\PHP\LoginManagement\Service\UserService;


class UserController
{
  private UserService $userService;

  public function __construct()
  {
    $connection = Database::getConnection();
    $userRepository = new UserRepository($connection);
    $this->userService = new UserService($userRepository);
  }

  public function register()
  {
    View::render('User/register', [
      'title' => "Register New User"
    ]);
  }

  public function postRegister()
  {
    $request = new UserRegisterRequest;
    $request->id = $_POST['id'];
    $request->name = $_POST['name'];
    $request->password = $_POST['password'];

    try{
      $this->userService->register($request);
      View::redirect('/users/login');
    }catch (ValidationException $exception){
      View::render('User/register', [
        'title' => "Register New User",
        'error' => $exception->getMessage()
      ]);
    }
  }

}