<?php

namespace TanzilalGummilang\PHP\LoginManagement\Service;

use Exception;
use TanzilalGummilang\PHP\LoginManagement\Config\Database;
use TanzilalGummilang\PHP\LoginManagement\Domain\User;
use TanzilalGummilang\PHP\LoginManagement\Exception\ValidationException;
use TanzilalGummilang\PHP\LoginManagement\Model\UserLoginRequest;
use TanzilalGummilang\PHP\LoginManagement\Model\UserLoginResponse;
use TanzilalGummilang\PHP\LoginManagement\Model\UserRegisterRequest;
use TanzilalGummilang\PHP\LoginManagement\Model\UserRegisterResponse;
use TanzilalGummilang\PHP\LoginManagement\Repository\UserRepository;


class UserService
{
  public function __construct(private UserRepository $userRepository){}

  // register
  public function register(UserRegisterRequest $request): UserRegisterResponse
  {
    $this->validateUserRegistrationRequest($request);

    try{
      Database::beginTransaction();
      $user = new User;
      $user->id = $request->id;
      $user->name = $request->name;
      $user->password = password_hash($request->password, PASSWORD_BCRYPT);

      $this->userRepository->save($user);

      $response = new UserRegisterResponse;
      $response->user = $user;

      Database::commitTransaction();
      return $response;
    }catch(Exception $exception){
      Database::rollbackTransaction();
      throw $exception;
    }
  }

  private function validateUserRegistrationRequest(UserRegisterRequest $request)
  {
    if($request->id == null or $request->name == null or $request == null or trim($request->id == "") or trim($request->name == "") or trim($request->password == "")){
      throw new ValidationException("Id, Name, Password cannot blank !!");
    }

    $user = $this->userRepository->findByID($request->id);
    if($user != null){
      throw new ValidationException("User Id already exist");
    }
  }
  // end register

  // login
  public function login(UserLoginRequest $request): UserLoginResponse
  {
    $this->validateUserLoginRequest($request);

    $user = $this->userRepository->findByID($request->id);
    if($user == null){
      throw new ValidationException("Id or Password wrong !!");
    }
    if(password_verify($request->password, $user->password)){
      $response = new UserLoginResponse;
      $response->user = $user;
      return $response;
    }else{
      throw new ValidationException("Password or Id wrong !!");
    }
  }

  private function validateUserLoginRequest(UserLoginRequest $request)
  {
    if($request->id == null or $request == null or trim($request->id == "") or trim($request->password == "")){
      throw new ValidationException("Id or Password cannot blank !!");
    }
  }
  // end login
}