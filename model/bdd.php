<?php
// Singleton pour connecter au bd.
class Bdd {
  // Gardez l'instance de classe.
  private static $instance = null;
  private $conn;
   
  // La connexion au bd est établie dans le constructeur privé.
  private function __construct()
  {
    // Connexion en ligne
    $this->conn = new PDO('mysql:host=localhost;dbname=vipa0880_vpetit_api', 'vipa0880_vpetit', 'JW6owufSU+MX', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    // Connexion local
    //$this->conn = new PDO('mysql:host=localhost;dbname=cotton_ticket', 'root', '', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
  }
  
  public static function getInstance()
  {
    if(!self::$instance)
    {
      self::$instance = new Bdd();
    }
   
    return self::$instance;
  }
  
  public function getConnection()
  {
    return $this->conn;
  }
}
?>