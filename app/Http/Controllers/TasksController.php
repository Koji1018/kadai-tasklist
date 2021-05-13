<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;

class TasksController extends Controller
{
    public function index()
    {
        $data = [];
        if (\Auth::check()) { // 認証済みの場合
            // 認証済みユーザを取得
            $user = \Auth::user();
            // ユーザのタスク一覧を取得(作成日時の降順)
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(25);
            
            $data = [ 'user' => $user, 'tasks' => $tasks, ];
        }
        // タスク一覧ビューでそれを表示
        return view('tasks.index', $data);
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
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required',
        ]);
        
        // 認証済みユーザ（閲覧者）のタスクとして作成（リクエストされた値をもとに作成）
        $request->user()->tasks()->create([
            'status' => $request->status, 
            'content' => $request->content,
        ]);
        
        // トップページへリダイレクトさせる
        return redirect('/');
    }

    public function show($id)
    {
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        
        // 認証済みユーザのタスクである場合は、タスク詳細ビューでそれを表示
        if (\Auth::id() === $task->user_id) {
            return view('tasks.show', [ 'task' => $task, ]);
        }
        // 認証済みユーザのタスクでない場合は、トップページにリダイレクト
        else{
            return redirect('/');
        }
    }

    public function edit($id)
    {
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        
        // 認証済みユーザのタスクである場合は、タスク編集ビューでそれを表示
        if (\Auth::id() === $task->user_id) {
            return view('tasks.edit', [ 'task' => $task, ]);
        }
        // 認証済みユーザのタスクでない場合は、トップページにリダイレクト
        else{
            return redirect('/');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required',
        ]);
        
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        
        // タスクを更新
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();
        
        // トップページへリダイレクトさせる
        return redirect('/');
    }

    public function destroy($id)
    {
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        // 認証済みユーザである場合は、タスクを削除
        if (\Auth::id() === $task->user_id) {
            $task->delete();
        }

        // トップページへリダイレクトさせる
        return redirect('/');
    }
}
