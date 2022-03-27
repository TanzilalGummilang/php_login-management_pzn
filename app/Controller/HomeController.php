<?php

namespace TanzilalGummilang\PHP\LoginManagement\Controller;
use TanzilalGummilang\PHP\LoginManagement\App\View;

class HomeController
{
  function index(): void
  {
    $model = [
      "title" => "Belajar PHP Login Management",
      "content" => "Selamat Belajar PHP Studi Kasus Web Login Management"
    ];

    View::render('Home/index', $model);
  }

  function hello(): void
  {
    echo "HomeController.hello()";
  }

  function world(): void
  {
    echo "HomeController.world()";
  }

  function about(): void
  {
    echo "Author : Tanzilal Gummilang";
  }

  function login(): void{
    $request = [
      "username" => $_POST['username'],
      "password" => $_POST['password']
    ];

    // query ke db
    $user = [];

    $response = [
      "message" => "login sukses"
    ];

    // kirim  response ke view
  }
}