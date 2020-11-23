<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    // VIEW ALL TASKS
    public function index()
    {
        echo("FIND ALL TASKS @ TASKCONTROLLER");
        // $tasks = auth()->user()->tasks();
        // info($tasks->toJSON());
        // if($tasks) {
        //     $tasksJSON = $tasks->toJson();
        //     return $tasksJSON;
        // } else {
        //     return "[NULL]";
        // }
    }


    // VIEW ONE TASK
    public function findOne($taskId)
    {
        echo("FIND ONE TASK @ TASKCONTROLLER");

        $task = Task::find($taskId);
        echo($task);
        if($task) {
            $taskJSON = $task -> toJson();
            return $taskJSON;
        } else {
            return "[NULL]";
        }
    }



    // CREATE NEW TASK
    public function createNew(Request $request)
    // public function createNew()
    {
        echo("ADD NEW TASK @ TASKCONTROLLER \n");
        echo($request);
        // // $inputted = $request->all();
        $inputted = $request->all();
        print_r($inputted);
        print_r($request->description);
        
        $newTask = new Task();
        $newTask->description = $request->description;

        print_r("THIS IS NEW TASK \n");
        print_r($newTask);
        print_r("\n");
        print_r("TASK OWNER \n");
        print_r($newTask->user_id);
    }


    // UPDATE TASK AS COMPLETE/INCOMPLETE
    public function updateTaskStatus($taskId)
    {
        echo("EDIT TASK STATUS @ TASKCONTROLLER");
        $task = Task::find($taskId);

        if($task->completed == 0) {
            $task->completed = 1;
        } else {
            $task->completed = 0;
        }

        $taskJSON = $task -> toJSON();
        $task->save();

        return Response::json(array('status' => '200', 'updated_data' => $taskJSON));

    }



    // UPDATE TASK DESCRIPTION
    public function updateTaskDesc(Request $request, $taskId)
    {
        echo("EDIT TASK DESCRIPTION @ TASKCONTROLLER");
        $task = Task::find($taskId);

        if($task) {
            echo("THIS IS REQUEST FORM");
            echo($request);
            
        } else {
            return "[NULL]";
        }

        // $taskJSON = $task -> toJSON();
        // $task->save();

        // return Response::json(array('status' => '200', 'updated_data' => $taskJSON));

    }



     // DELETE TASK
     public function dropTask($taskId)
     {
        echo("EDIT TASK STATUS @ TASKCONTROLLER");
         $task = Task::find($taskId);
        
         if($task) {
             $taskJSON = $task->toJSON();
             $task->delete();
             return Response::json(array('status' => '200', 'deleted_data' => $taskJSON));
         } else {
             return "[NULL]";
         }
 
     }

}
