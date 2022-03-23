<?php

namespace TanzilalGummilang\PHP\LoginManagement\Controller;

class ProductController
{
  function categories(string $productsId, string $categoryId): void
  {
    echo "PRODUCT $productsId CATEGORY $categoryId";
  }
}