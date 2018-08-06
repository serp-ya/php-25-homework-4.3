<?php

try {

  class SqlCore {
    private $db;
    private $config;
    private $tableName = 'dump';

    public function __construct() 
    {
      $configJson = file_get_contents('./config.json');
      $this->config = json_decode($configJson, true);

      $userName = $this->config['username'];
      $password = $this->config['password'];

      $connectQuery = $this->getConnectQuery();
      $this->db = new PDO($connectQuery, $userName, $password);
    }

    private function getConnectQuery() 
    {
      $serverName = $this->config['server'];
      $dbName = $this->config['dbName'];
      return "mysql:host={$serverName};dbname={$dbName}";
    }

    public function getFullData($requestConfig) 
    {
      $sortBy = strip_tags($requestConfig['sortBy']);
      $query = "SELECT * FROM $this->tableName ORDER BY $sortBy";
      $stmt = $this->executeQuery($query);
      return $this->transformStmtToArray($stmt); 
    }

    private function executeQuery($query)
    {
      $stmt = $this->db->prepare($query);
      $stmt->execute();
      return $stmt;
    }

    private function transformStmtToArray($stmt)
    {
      $response = [];

      while($row = $stmt->fetch()) {
        $response[] = $row;
      }

      return $response;
    }

    public function addNewTask($newTask) 
    {
      $query = $this->addNewTaskQuery($newTask);
      $stmt = $this->executeQuery($query);
      return true;
    }

    private function addNewTaskQuery($newTask)
    {
      $crudeTask = strip_tags($newTask);
      $dateNow = date('Y-m-d h:i:s');
      return (
        "INSERT INTO $this->tableName VALUES (null, '$crudeTask', 0, '$dateNow')"
      );
    }

    public function changeTask($id, $description) 
    {
      $query = $this->changeTaskQuery($id, $description);
      $stmt = $this->executeQuery($query);
      return true;
    }

    private function changeTaskQuery($id, $description)
    {
      $crudeId = strip_tags($id);
      $crudeDescription = strip_tags($description);
      return (
        "UPDATE $this->tableName SET description='{$crudeDescription}' WHERE id={$crudeId}"
      );
    }

    public function doneTask($id) 
    {
      $query = $this->doneTaskQuery($id);
      $stmt = $this->executeQuery($query);
      return true;
    }

    private function doneTaskQuery($id)
    {
      $crudeId = strip_tags($id);
      return (
        "UPDATE $this->tableName SET is_done=1 WHERE id={$crudeId}"
      );
    }

    public function deleteTask($id) 
    {
      $query = $this->deleteTaskQuery($id);
      $stmt = $this->executeQuery($query);
      return true;
    }

    private function deleteTaskQuery($id)
    {
      $crudeId = strip_tags($id);
      return (
        "DELETE FROM $this->tableName WHERE id={$crudeId}"
      );
    }
  }

} catch(PDOException $error) {
  exit("Error: $e->getMessage()");
}