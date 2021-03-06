<?php

namespace TanzilalGummilang\PHP\LoginManagement\Controller;

use TanzilalGummilang\PHP\LoginManagement\App\View;
use TanzilalGummilang\PHP\LoginManagement\Config\Database;
use TanzilalGummilang\PHP\LoginManagement\Exception\ValidationException;
use TanzilalGummilang\PHP\LoginManagement\Model\UserLoginRequest;
use TanzilalGummilang\PHP\LoginManagement\Model\UserPasswordUpdateRequest;
use TanzilalGummilang\PHP\LoginManagement\Model\UserProfileUpdateRequest;
use TanzilalGummilang\PHP\LoginManagement\Model\UserRegisterRequest;
use TanzilalGummilang\PHP\LoginManagement\Repository\SessionRepository;
use TanzilalGummilang\PHP\LoginManagement\Repository\UserRepository;
use TanzilalGummilang\PHP\LoginManagement\Service\SessionService;
use TanzilalGummilang\PHP\LoginManagement\Service\UserService;


class UserController
{
  private UserService $userService;
  private SessionService $sessionService;

  public function __construct()
  {
    $connection = Database::getConnection();
    $userRepository = new UserRepository($connection);
    $this->userService = new UserService($userRepository);

    $sessionRepository = new SessionRepository($connection);
    $this->sessionService = new SessionService($sessionRepository, $userRepository);
  }

  // register view
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
  // end register view

  // login view
  public function login()
  {
    View::render('User/login', [
      'title' => "Login User"
    ]);
  }

  public function postLogin()
  {
    $request = new UserLoginRequest;
    $request->id = $_POST['id'];
    $request->password = $_POST['password'];

    try{
      $response = $this->userService->login($request);
      $this->sessionService->create($response->user->id);
      View::redirect("/");
    }catch(ValidationException $exception){
      View::render('User/login', [
        'title' => "Login user",
        'error' => $exception->getMessage()
      ]);
    }
  }
  // end login view

  // logout
  public function logout()
  {
    $this->sessionService->destroy();
    View::redirect("/");
  }
  // end logout

  // update profile
  public function updateProfile()
  {
    $user = $this->sessionService->current();

    View::render('User/profile', [
      'title' => "Update User Profile",
      'user' => [
        'id' => $user->id,
        'name' => $user->name
      ]
    ]);
  }

  public function postUpdateProfile()
  {
    $user = $this->sessionService->current();

    $request = new UserProfileUpdateRequest;
    $request->id = $user->id;
    $request->name = $_POST['name'];

    try {
      $this->userService->updateProfile($request);
      View::redirect('/');
    }catch(ValidationException $exception){
      View::render('User/profile', [
        'title' => "Update User Profile",
        'error' => $exception->getMessage(),
        'user' => [
          'id' => $user->id,
          'name' => $user->name
        ]
      ]);
    }
  }
  // end update profile

  // update password
  public function updatePassword()
  {
    $user = $this->sessionService->current();

    View::render('User/password', [
      'title' => "Update User Password",
      'user' => [
        'id' => $user->id
      ]
    ]);
  }

  public function postUpdatePassword()
  {
    $user = $this->sessionService->current();

    $request = new UserPasswordUpdateRequest;
    $request->id = $user->id;
    $request->oldPassword = $_POST['oldPassword'];
    $request->newPassword = $_POST['newPassword'];

    try{
      $this->userService->updatePassword($request);
      View::redirect('/');
    }catch(ValidationException $exception){
      View::render('User/password', [
        'title' => "Update User Password",
        'error' => $exception->getMessage(),
        'user' => [
          'id' => $user->id
        ]
      ]);
    }
  }
  // end update password
}