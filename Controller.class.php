<?php
require_once('./SqlCore.class.php');

class Controller {
  private $sqlConnection;
  private $newTask;
  private $newDescription;
  private $sortBy;
  private $taskId;
  private $action;

  public function __construct() 
  {
    $this->sqlConnection = new SqlCore();

    if (!empty($_POST['new-task'])) {
      $this->newTask = (string) $_POST['new-task'];

    } else if (!empty($_POST['sort-by'])) {
      $this->sortBy = (string) $_POST['sort-by'];

    } else if (!empty($_POST['description'])) {
      $this->newDescription = (string) $_POST['description'];

    } else if (!empty($_POST['id'])) {
      $this->taskId = (string) $_POST['id'];
    }
    
    if (!empty($_GET['id']) && !empty($_GET['action'])) {
      $this->taskId = (int) $_GET['id'];
      $this->action = (string) $_GET['action'];
    }    
  }

  public function getData() 
  {
    $newTask = $this->newTask;
    $sortBy = $this->sortBy ? $this->sortBy : 'id';
    $newDescription = $this->newDescription;
    $requestConfig = [
      'sortBy' => $sortBy,
    ];

    $action = $this->action;
    $taskId = $this->taskId;

    if ($newTask) {
      try {
        $this->sqlConnection->addNewTask($newTask);
      } catch (Exception $error) {
        exit("Error: $e->getMessage()");
      }
    }
    
    if ($newDescription) {
      $this->sqlConnection->changeTask($taskId, $newDescription);
      header("Location: ./");
    }

    switch($action) {      
      case 'done': {
        $this->sqlConnection->doneTask($taskId);
        break;
      }
      
      case 'delete': {
        $this->sqlConnection->deleteTask($taskId);
        break;
      }
    }

    return $this->sqlConnection->getFullData($requestConfig);
  }
}