<?php
require_once('classes/Controller.abs.class.php');
require_once('classes/App/AppModel.class.php');
require_once('classes/App/AppController.interface.php');

try {

  class AppController extends Controller implements AppControllerInterface {
    private $newTaskDescription;
    private $newDescription;
    private $sortBy;
    private $taskId;
    private $action;

    public function __construct() 
    {
      $this->sqlConnection = new AppModel();

      if (!empty($_POST['new-task'])) {
        $this->newTaskDescription = (string) $_POST['new-task'];

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
      $newTaskDescription = $this->newTaskDescription;
      $sortBy = $this->sortBy ? $this->sortBy : 'id';
      $newDescription = $this->newDescription;
      $requestConfig = [
        'sortBy' => $sortBy,
      ];

      $action = $this->action;
      $taskId = $this->taskId;

      if ($newTaskDescription) {
        try {
          $this->sqlConnection->addNewTask($newTaskDescription);
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
} catch (Exception $error) {
  exit('Error: ' . $error->getMessage());
}

?>
