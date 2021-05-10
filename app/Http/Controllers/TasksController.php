<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;

class TasksController extends Controller
{
    public function index()
    {
        // タスク一覧を取得
        $tasks = Task::all();
        
        // タスク一覧ビューでそれを表示
        return view('tasks.index', [ 'tasks' => $tasks, ]);
    }

    public function create()
    {
        // フォームの入力項目用のインスタンスを生成
        $task = new Task;
        
        // タスク作成ビューでそれを表示
        return view('tasks.create', [ 'task' => $task, ]);
    }

    public function store(Request $request)
    {
        // タスクを作成
        $task = new Task;
        $task->content = $request->content;
        $task->save();

        // トップページへリダイレクトさせる
        return redirect('/');
    }

    public function show($id)
    {
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        
        // タスク詳細ビューでそれを表示
        return view('tasks.show', [ 'task' => $task, ]);
    }

    public function edit($id)
    {
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        
        // タスク編集ビューでそれを表示
        return view('tasks.edit', [ 'task' => $task, ]);
    }

    public function update(Request $request, $id)
    {
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        
        // タスクを更新
        $task->content = $request->content;
        $task->save();
        
        // トップページへリダイレクトさせる
        return redirect('/');
    }

    public function destroy($id)
    {
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        // タスクを削除
        $task->delete();

        // トップページへリダイレクトさせる
        return redirect('/');
    }
}
