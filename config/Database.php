<?php

function getDatabaseConfig(): array
{
  $host = "localhost";
  $port = 3306;
  $dbNameProd = "db_pzn_php_login-management";
  $dbNameTest = "db_pzn_php_login-management_test";
  $username = "root";
  $password = "";

  return [
    "database" => [
      "test" => [
        "url" => "mysql:host=$host:$port;dbname=$dbNameTest",
        "username" => $username,
        "password" => $password
      ],
      "prod" => [
        "url" => "mysql:host=$host:$port;dbname=$dbNameProd",
        "username" => $username,
        "password" => $password
      ]
    ]
  ];
}