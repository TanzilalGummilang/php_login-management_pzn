<?php

namespace TanzilalGummilang\PHP\LoginManagement\App {

  function header(string $value){
    echo $value;
  }
}

namespace TanzilalGummilang\PHP\LoginManagement\Service {

  function setcookie(string $name, string $value)
  {
    echo "$name: $value";
  }
}