<?php

namespace App;

include 'connection.php';

class Todo
{
    protected $pdo;
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function add($title)
    {
        if(empty($title)){
            header("Location: index.php?message=error");
        }
        else{
            $query = $this->pdo->prepare("SELECT * FROM todos WHERE title=?");
            $query->execute([$title]);
            $result = $query->fetch();
            
            if($result){
                header("Location: index.php?message=Task_exists");
            }
            else{
                try{
                    $statement = $this->pdo->prepare("INSERT INTO todos(title) VALUE(?)");
                    $result    = $statement->execute([$title]);
                }
                catch(Exception $e){
                    die('Whoops, something went wrong');
                }

                if($result){
                    header("Location: index.php");
                }
                else{
                    header("Location: index.php?message=error");
                }
                $pdo = null;
                exit();
            }
        }
    }

    public function remove($id)
    {
        $id = $_GET['id'];

        $statement = $this->pdo->prepare("DELETE FROM todos WHERE id=?");
        $result = $statement->execute([$id]);

        if($result){
            header("Location: index.php");
        }
        else{
            header("Location: index.php?message=Failed_to_delete_task");
        }
        $pdo = null;
        exit();
    }

    public function check($id)
    {
        $id = $_POST['id'];

        $statement = $this->pdo->prepare("SELECT checked FROM todos WHERE id=?");
        $statement->execute([$id]);

        $todo = $statement->fetch();

        $checked = $todo['checked'];

        if($checked){
            $update = 0;
        }
        else{
            $update = 1;
        }

        try{
            $result = $this->pdo->query("UPDATE todos SET checked=$update WHERE id=$id"); 
        }
        catch(Exception $e){
            die('Whoops, something went wrong');
        }

        if($result){
            echo $checked;   
        }

        $pdo = null;
        exit();
    }
}

$action = new Todo($pdo);

if(isset($_POST['title']))
{
    $title = $_POST['title'];
    $action->add($title);
}

elseif(isset($_GET['id']))
{
    $id = $_GET['id'];
    $action->remove($id);
}

elseif(isset($_POST['id']))
{
    $id = $_POST['id'];
    $action->check($id);
}