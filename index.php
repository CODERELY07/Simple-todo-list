<?php

    // connection
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $db = 'todolist';

    $con = new mysqli($host,$username,$password,$db);

    if($con->connect_error){
        die("Connection failed: " .$con->connect_error);
    }

    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['submit'])){

        if(isset($_POST['submit'])){
            $todo = htmlspecialchars(trim($_POST['todo']));
           if($todo == ""){
                echo "<span class='error'>Please Put a task</span>";
           }else{
                //check if the task already exists
                $check_sql = "SELECT * FROM todo WHERE list = '$todo'";
                $check_result = $con->query($check_sql);

                if($check_result->num_rows > 0){
                    echo "<span class='error'>Task already Exsits!</span>";
                }else{
                    $sql = "INSERT INTO todo (list) VALUES('$todo')";
                    if($con->query($sql)){
                        echo "<span class='error'>Created New Task</span>";
                    }else{
                        echo "Error " . $sql . "<br>" .$con->error;
                    }
                }
           }
        }
       
    }
    if(isset($_POST['delete'])){
        $delete_id = $_POST['delete'];
        $sql_delete = "DELETE FROM todo WHERE id = $delete_id";
        $con->query($sql_delete);
        echo "<span class='error'>Deleted Task Succesfully!</span>";
    }

    //to output the task
    $sql = "SELECT * FROM todo";
    $result = $con->query($sql) or die($con->error);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMPE TO DO LIST</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Simple to do list</h1>
        <div class="wrapper">
            <form action="index.php" method="post">
                <div class="inputs">
                    <input type="text" placeholder="Enter your new to do list" name="todo" id="todo">
                    <input type="submit" value="Add" name="submit">
                </div>
                <div class="todo-list">
                    <ul class="list-items-con">
                        <!-- Output the Task from the database -->
                        <?php
                            if($result->num_rows > 0){
                                while($row = $result->fetch_assoc()){
                                    echo "<li class='list-items'>" . $row['list'] . " <button type = 'submit' name = 'delete' value='" . $row['id'] . "'>DELETE</button></li>";
                                }
                            }else{
                                echo "<span class='add-task'>ADD TASK</span>";
                            }
                        ?>
                    </ul>
                </div>
            </form>
        </div>
    </div>
</body>
</html>