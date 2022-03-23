<?php

namespace TanzilalGummilang\PHP\LoginManagement\Middleware;


interface Middleware
{
  function before(): void;
}