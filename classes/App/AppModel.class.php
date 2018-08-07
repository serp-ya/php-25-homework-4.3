<?php
require_once('classes/Model.abs.class.php');
require_once('classes/App/AppModel.interface.php');

try {

  class AppModel extends Model implements AppModelInterface {
    private $taskTableName = 'task';
    private $userTableName = 'user';

    public function getFullData($userId, $sortBy) 
    {
      $query = $this->getFullDataQuery($userId, $sortBy);
      $stmt = $this->executeQuery($query);
      return $this->transformStmtToArray($stmt); 
    }

    private function getFullDataQuery($userId, $sortBy)
    {
      $taskTableName = $this->taskTableName;
      $userTableName = $this->userTableName;
      return (
        "SELECT
          $taskTableName.id, 
          $userTableName.login as author, 
          (
            SELECT login 
            FROM $userTableName 
            WHERE id=$taskTableName.assigned_user_id
          ) as assigned_user, 
          $taskTableName.description, 
          $taskTableName.is_done, 
          $taskTableName.date_added
         FROM $userTableName
         JOIN $taskTableName
            on $taskTableName.user_id = user.id
         WHERE $taskTableName.user_id = 7
        "
      );
    }

    public function addNewTask($userId, $newTask) 
    {
      $query = $this->addNewTaskQuery($userId, $newTask);
      $stmt = $this->executeQuery($query);
      return true;
    }

    private function addNewTaskQuery($userId, $newTask)
    {
      $crudeId = strip_tags($userId);
      $crudeTask = strip_tags($newTask);
      $dateNow = date('Y-m-d h:i:s');
      return (
        "INSERT INTO $this->taskTableName (user_id, assigned_user_id, description, is_done, date_added)
         VALUES ($crudeId, $crudeId, '$crudeTask', 0, '$dateNow')
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
        "UPDATE $this->taskTableName 
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
        "UPDATE $this->taskTableName 
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
         FROM $this->taskTableName 
         WHERE id={$crudeId}
        "
      );
    }
  }
} catch (Exception $error) {
  exit('Error: ' . $error->getMessage());
}

?>
