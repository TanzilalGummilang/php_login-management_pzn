<?php

namespace TanzilalGummilang\PHP\LoginManagement\App;
use PHPUnit\Framework\TestCase;


class ViewTest extends TestCase
{
  public function testRender()
  {
    View::render('Home/index', [
      "title" => "PHP Login management"
    ]);

    $this->expectOutputRegex('[PHP Login Management]');
    $this->expectOutputRegex('[html]');
    $this->expectOutputRegex('[body]');
    $this->expectOutputRegex('[Login Management]');
    $this->expectOutputRegex('[Login]');
    $this->expectOutputRegex('[Register]');
  }
}