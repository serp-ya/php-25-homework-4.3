<?php
interface AppModelInterface
{
  public function getFullData($userId, $sortBy);
  // public function getAssignedData($userId);
  public function addNewTask($userId, $newTask);
  public function changeTask($id, $description);
  public function doneTask($id);
  public function deleteTask($id);
}

?>
