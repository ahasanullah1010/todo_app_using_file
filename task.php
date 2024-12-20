<?php
define("FILE_NAME", "tasks.json");


// add a new task
function addTask(array $task): void{
    file_put_contents(FILE_NAME, json_encode($task, JSON_PRETTY_PRINT));
}


// read todos from file
function loadTasks(): array {
    if(!file_exists(FILE_NAME)){
        return [];
    }
    $data = file_get_contents(FILE_NAME);
    return $data ? json_decode($data, true) : [];
}
// Load tasks from the tasks.json file
$task = loadTasks();

//print_r($task);



if($_SERVER["REQUEST_METHOD"] == "POST"){
    //when find add a task request
    if($_POST["task"] && !empty(trim($_POST["task"]))){
        $task[] = [
            'task' => htmlspecialchars(trim($_POST['task'])),
            'done' => false
        ];
        addTask($task);
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
    else if(isset($_POST['toggle']) ){

            $task[$_POST["toggle"]]["done"] = !$task[$_POST["toggle"]]["done"];
            addTask($task);
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        
    }
    else if(isset($_POST['delete'])){
        unset($task[$_POST['delete']]);
       // $task = array_values($task); 
        addTask($task);
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }


}



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do App</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 20px;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: top;
            max-height: max-content;
        }

        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 800px;
            padding: 20px;
        }

        h1 {
            font-size: 24px;
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .input-section {
            display: flex;
            gap: 10px;
        }
        .output-section {
            display: flex;
            gap: 10px;
            
            padding: 10px;

        }
        .output-section .fo{
            width: 70%;
            cursor: pointer;
        }
        .output-section .f{
            
            
            padding: 10px 20px;
            font-size: 20px;
            
        }

        .input-section input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }


        .input-section button {
            background-color: #7d4af3;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .input-section button:hover {
            background-color: #693fcf;
        }

        .task-list {
            margin-top: 20px;
        }

        .task-list h2 {
            font-size: 20px;
            color: #555;
        }

        .task-done {
            text-decoration: line-through;
            color: #888;
        }


    </style>
</head>
<body>
    <div class="container">
        <h1>To-Do App</h1>
        
            <form method="post" class="input-section">
                <input type="text" name="task" placeholder="Enter a new task" required>
                <button type="submit">ADD TASK</button>
            </form>
        
        <div class="task-list">
            <h2>Task List</h2>
            <!-- Tasks will be added here dynamically -->
             <?php if(empty($task)){ ?>
                <h4>No tasks yet. Add one above!</h4>
             <?php }else{ ?>
             <ul style="list-style: none;">
             <?php foreach($task as $index => $data): ?>
                <li>
              
                    <div class="output-section">
                        <form method="post" class="fo">
                            <input type="hidden" name="toggle" value="<?=  $index ?>">
                            <button type="submit" style="border: none;  padding: 10px 30px;font-size: 20px;cursor: pointer; background: none;" class="<?= $data['done'] ? 'task-done' : '' ?>" > <?= $data["task"] ?></button>
                        </form>
                        <form method="post" class="f">
                            <input type="hidden" name="delete" value="<?=  $index ?>">
                            <button type="submit" style="border: none; border-radius: 5px; padding: 5px 30px;font-size: 16px;cursor: pointer; transition: background-color 0.3s ease;">Delete</button>
                        </form>
                        
                    </div>
                </li>
                <?php endforeach; ?>
             </ul>
             <?php } ?>
        </div>
    </div>
</body>
</html>
