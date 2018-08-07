<?php
interface AppModelInterface
{
  public function getFullData($requestConfig);
  public function addNewTask($newTask);
  public function changeTask($id, $description);
  public function doneTask($id);
  public function deleteTask($id);
}

?>
