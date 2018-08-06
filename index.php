<?php
date_default_timezone_set('UTC');
require_once('./Controller.class.php');

$controller = new Controller();
$taskData = $controller->getData();
$isEdit = false;
$editedId;
$editedDescription;

if (!empty($_GET['action']) && $_GET['action'] === 'change') {
  $isEdit = true;
  $editedId = $_GET['id'];
}

if ($isEdit) {
  foreach ($taskData as $task) {
    if ((int) $task['id'] === (int) $editedId) {
      $editedDescription = $task['description'];
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>hw-4-2</title>
</head>
<body style="margin: 0 auto; width: 960px;">

<h1>Список дел:</h1>
  
<div style="display: flex; justify-content: space-around;">
  <form method="POST">
    <?php if ($isEdit): ?>
      <input 
        type="text" 
        name="description" 
        placeholder="Описание задачи"
        value="<?php echo $editedDescription; ?>"
      >
      <input type="hidden" name="id" value="<?php echo $editedId;?>">
      <input type="submit" value="Сохранить">
    <?php else: ?>
      <input type="text" name="new-task" placeholder="Описание задачи">
      <input type="submit" value="Добавить">
    <?php endif; ?>
  </form>


  <form method="POST">
    Сортировать по: 
    <select name="sort-by">
      <option value="date_added">
        Дате добавления
      </option>
    
      <option value="is_done">
        Статусу
      </option>
    
      <option value="description">
        Описанию
      </option>
    </select>
    <input type="submit" value="Отсортировать">
  </form>
</div>

<table style="width: 100%">
  <thead style="background-color: #bfbfbf;">
    <tr>
      <td>Описание задачи</td>
      <td>Дата добпаления</td>
      <td>Статус</td>
      <td></td>
    </tr>
  </thead>

  <tbody>
    <?php foreach($taskData as $task): ?>
      <tr>
        <td><?php echo $task['description']; ?></td>
        <td><?php echo $task['date_added']; ?></td>
        <td><?php echo $task['is_done'] ? 'Выполнено' : 'Не выполнено'; ?></td>
        <td>
          <a href="?id=<?php echo $task['id'] ?>&action=change">Изменить</a> 
          <a href="?id=<?php echo $task['id'] ?>&action=done">Выполнить</a>
          <a href="?id=<?php echo $task['id'] ?>&action=delete">Удалить</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

</body>
</html>