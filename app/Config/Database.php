<?php

namespace TanzilalGummilang\PHP\LoginManagement\Config;
use PDO;


class Database
{
  private static ?PDO $pdo = null;

  public static function getConnection(string $env = "test"): PDO
  {
    if(self::$pdo == null){
      //  create new PDO
      require_once __DIR__ . '/../../config/Database.php';
      $config = getDatabaseConfig();
      self::$pdo = new PDO(
        $config['database'][$env]['url'],
        $config['database'][$env]['username'],
        $config['database'][$env]['password']
      );
    }
    
    return self::$pdo;
  }
}