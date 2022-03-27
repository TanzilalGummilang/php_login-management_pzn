<?php

function getDatabaseConfig(): array
{
  return [
    "database" => [
      "test" => [
        "url" => "mysql:host=localhost:3306;dbname=db_pzn_php_login-management_test",
        "username" => "root",
        "password" => ""
      ],
      "prod" => [
        "url" => "mysql:host=localhost:3306;dbname=db_pzn_php_login-management",
        "username" => "root",
        "password" => ""
      ]
    ]
  ];
}