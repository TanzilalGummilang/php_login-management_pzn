<?php

namespace TanzilalGummilang\PHP\LoginManagement\Middleware;


class AuthMiddleware implements Middleware
{
  function before(): void
  {
    session_start();
    if(!isset($_SESSION['login'])){
      header('location: /login');
      exit();
    }
  }
}