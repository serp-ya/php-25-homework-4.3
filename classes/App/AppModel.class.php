<?php
require_once('classes/Model.abs.class.php');
require_once('classes/App/AppModel.interface.php');

try {

  class AppModel extends Model implements AppModelInterface {
    protected $tableName = 'task';

    public function getFullData($requestConfig) 
    {
      $sortBy = strip_tags($requestConfig['sortBy']);
      $query = "SELECT * FROM $this->tableName ORDER BY $sortBy";
      $stmt = $this->executeQuery($query);
      return $this->transformStmtToArray($stmt); 
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
        "INSERT INTO $this->tableName 
         VALUES (null, '$crudeTask', 0, '$dateNow')
        "
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
        "UPDATE $this->tableName 
         SET description='{$crudeDescription}' 
         WHERE id={$crudeId}
        "
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
        "UPDATE $this->tableName 
         SET is_done=1 
         WHERE id={$crudeId}
        "
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
        "DELETE 
         FROM $this->tableName 
         WHERE id={$crudeId}
        "
      );
    }
  }
} catch (Exception $error) {
  exit('Error: ' . $error->getMessage());
}

?>
