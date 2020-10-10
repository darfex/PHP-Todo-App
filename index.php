<?php 
require 'connection.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Todo</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
</head>
<body>
    <div class ="main-section">
        <div class="header">
            <div>
                <p class="date">
                    <?php
                        $today = date("l, F j");
                        echo $today;
                    ?>
                </p>
                <p class="number-of-tasks">
                    <?php 
                        $tasks = $pdo->query("SELECT COUNT(id) FROM todos");
                        $row   = $tasks->fetchColumn();

                        if ($row <= 1){
                            echo "$row Active Task";
                        }else{
                            echo "$row Active Tasks";
                        }
                        
                    ?>
                </p>
            </div>
            <div class="add-section">
                <form action="todo.php" method="POST">
                    <?php if(isset($_GET['message']) && $_GET['message'] == 'error') : ?>
                        <input type="text" name="title" placeholder="Enter a task"/>
                        <button type="submit>">Add &nbsp; </button>
                        <small class="error">Please enter in a task</small>
                    <?php elseif(isset($_GET['message']) && $_GET['message'] == 'Task_exists') : ?>
                        <input type="text" name="title" placeholder="Enter a task"/>
                        <button type="submit>">Add &nbsp; <span>&#43;</span></button>
                        <small class="error">This task already exists</small>
                    <?php else: ?>
                        <input type="text" name="title" placeholder="Enter a task..." />
                        <button type="submit" class="add-btn">Add &nbsp; <span>&#43;</span</button>
                    <?php endif; ?>
                </form>
            </div>
        </div>
        <?php
            $todos = $pdo->query("SELECT * FROM todos ORDER BY id DESC");
        ?>
        <div class="task-section">
            <?php if($row == 0) : ?>
                <hr/>
                <p class="no-tasks">You currently have <span>0</span> tasks. Add a task to get started!</p>
                <img src="img/loading3.gif" id="loading">
            <? else : ?>
                <?php while($todo = $todos->fetch(PDO::FETCH_OBJ)) : ?>
                    <div class="task">
                        <form action="todo.php?id=<?= $todo->id; ?>" method="POST">
                            <button class="remove-task-btn"><i class="fa fa-trash fa-lg"></i></button>
                        </form>

                        <?php if($todo->checked) : ?>
                            <input type ="checkbox" class="check-box" checked todo-id = "<?= $todo->id; ?>" />
                            <p class="checked"><?= $todo->title; ?></p>
                        <?php else : ?>
                            <input type ="checkbox" class="check-box" todo-id = "<?= $todo->id; ?>"/>
                            <p><?= $todo->title; ?></p>
                        <?php endif; ?>

                        <br>
                        <small>Created: <?= $todo->date_time; ?></small>
                    </div>
                <?php endwhile; ?>
            <? endif; ?>
        </div>
    </div>

<script src="js/jquery-3.5.1.min.js"></script>
<script>
$(document).ready(function(){
    $(".check-box").click(function(){
        var id = $(this).attr('todo-id');
        $.post('todo.php',
        {
            id: id
        },
        (data) => {
                var p = $(this).next();
                if(data == '1'){
                    p.removeClass('checked');
                }else{
                    p.addClass('checked');
            }
        })
    })
})
</script>
</body>
</html>