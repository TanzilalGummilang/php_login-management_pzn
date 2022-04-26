<?php

namespace TanzilalGummilang\PHP\LoginManagement\Service;

use TanzilalGummilang\PHP\LoginManagement\Domain\Session;
use TanzilalGummilang\PHP\LoginManagement\Domain\User;
use TanzilalGummilang\PHP\LoginManagement\Repository\SessionRepository;
use TanzilalGummilang\PHP\LoginManagement\Repository\UserRepository;


class SessionService
{
  public static string $COOKIE_NAME = "X-PZN-SESSION";

  public function __construct(
    private SessionRepository $sessionRepository,
    private UserRepository $userRepository
  ){}

  public function create(string $userId): Session
  {
    $session = new Session;
    $session->id = uniqid();
    $session->userId = $userId;

    $this->sessionRepository->save($session);

    setcookie(self::$COOKIE_NAME, $session->id, time() + (60*60*24*30), "/");
    // setcookie(self::$COOKIE_NAME, $session->id, time() + (60*60*24*1), "/");

    return $session;
  }

  public function destroy()
  {
    $sessionId = $_COOKIE[self::$COOKIE_NAME] ?? '';

    $this->sessionRepository->deleteById($sessionId);

    setcookie(self::$COOKIE_NAME, '', 1, "/");
  }

  public function current(): ?User
  {
    $sessionId = $_COOKIE[self::$COOKIE_NAME] ?? '';

    $session = $this->sessionRepository->findById($sessionId);
    if($session == null){
      return null;
    }

    return $this->userRepository->findById($session->userId);
  }
}
