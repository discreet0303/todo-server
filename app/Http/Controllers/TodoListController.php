<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\TodoList;

use Validator;

class TodoListController extends Controller
{
    public function index() {
        $todos = TodoList::all();

        return view('index', [
            'todos' => $todos
        ]);
    }

    public function create(Request $request) {
        $inputData = $request->all();

        $rules = [
            'todo' => 'required'
        ];

        $errorMessages = [
            'todo.required' => '請填寫待處理事項',
        ];

        $validator = Validator::make($inputData, $rules, $errorMessages);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('status', 'fail');
        }

        $todoItem = TodoList::create($inputData);

        return redirect('/')->with('status', 'success');
    }

    public function delete($todoId) {
        $todo = TodoList::find($todoId)->delete();

        return response()->json(['message' => 'success'], 200);
    }
}
