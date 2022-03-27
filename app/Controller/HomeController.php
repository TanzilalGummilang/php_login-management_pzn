<?php

namespace TanzilalGummilang\PHP\LoginManagement\Controller;
use TanzilalGummilang\PHP\LoginManagement\App\View;


class HomeController
{
  function index()
  {
    View::render('Home/index', [
      "title" => "PHP Login management"
    ]);
  }
}