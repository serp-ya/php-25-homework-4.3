<?php
require_once('classes/Model.abs.class.php');
require_once('classes/App/AppModel.interface.php');

try {

  class AppModel extends Model implements AppModelInterface {
    private $taskTableName = 'task';
    private $userTableName = 'user';

    public function getTasks($userId, $sortBy, $mode = 'full') 
    {
      $query = $this->getTasksQuery($userId, $sortBy, $mode);
      $stmt = $this->executeQuery($query);
      return $this->transformStmtToArray($stmt); 
    }

    private function getTasksQuery($userId, $sortBy, $mode = 'full')
    {
      $taskTableName = $this->taskTableName;
      $userTableName = $this->userTableName;
      $whereCondition = $mode === 'full'
        ? "tt.user_id = $userId"
        : "tt.user_id != $userId AND tt.assigned_user_id = $userId";

      return (
        "SELECT
          tt.id, 
          ut.login as author, 
          uta.login as assigned_user, 
          tt.description, 
          tt.is_done, 
          tt.date_added
        FROM $taskTableName as tt
        JOIN $userTableName as ut ON ut.id = tt.user_id
        JOIN $userTableName as uta ON uta.id = tt.assigned_user_id
        WHERE $whereCondition
        ORDER BY $sortBy
        "
      );
    }

    public function getUsersList()
    {
      $query = $this->getUsersListQuery();
      $stmt = $this->executeQuery($query);
      return $this->transformStmtToArray($stmt); 
    }

    private function getUsersListQuery()
    {
      return (
        "SELECT id, login
         FROM $this->userTableName"
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
      $crudeId = (int) strip_tags($userId);
      $crudeTask = (string) strip_tags($newTask);
      $dateNow = date('Y-m-d h:i:s');
      return (
        "INSERT INTO $this->taskTableName (user_id, assigned_user_id, description, is_done, date_added)
         VALUES ($crudeId, $crudeId, '$crudeTask', 0, '$dateNow')
        "
      );
    }

    public function changeTaskDescription($id, $description) 
    {
      $query = $this->changeTaskDescriptionQuery($id, $description);
      $stmt = $this->executeQuery($query);
      return true;
    }

    private function changeTaskDescriptionQuery($id, $description)
    {
      $crudeId = (int) strip_tags($id);
      $crudeDescription = (string) strip_tags($description);
      return (
        "UPDATE $this->taskTableName 
         SET description='{$crudeDescription}' 
         WHERE id={$crudeId}
        "
      );
    }

    public function newAssignTask($taskId, $assignedUserId) 
    {
      $query = $this->newAssignTaskQuery($taskId, $assignedUserId);
      $stmt = $this->executeQuery($query);
      return true;
    }

    private function newAssignTaskQuery($taskId, $assignedUserId)
    {
      $crudeTaskId = (int) strip_tags($taskId);
      $assignedUserId = (string) strip_tags($assignedUserId);
      return (
        "UPDATE $this->taskTableName 
         SET assigned_user_id='{$assignedUserId}' 
         WHERE id={$crudeTaskId}
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
