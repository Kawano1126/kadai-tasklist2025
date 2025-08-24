<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Task;

class TasksController extends Controller
{

    public function index()
    {
        $data = [];
        if (\Auth::check()) { // 認証済みの場合
            // 認証済みユーザーを取得
            $user = \Auth::user();
            // ユーザーの投稿の一覧を作成日時の降順で取得
            // （後のChapterで他ユーザーの投稿も取得するように変更しますが、現時点ではこのユーザーの投稿のみ取得します）
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
        }

        // dashboardビューでそれらを表示
        return view('dashboard', $data);
    }
    public function create()
    {
        $task = new Task;

        // メッセージ作成ビューを表示
        return view('tasks.create', [
            'task' => $task,
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required|max:255',
        ]);

        // 認証済みユーザー（閲覧者）の投稿として作成（リクエストされた値をもとに作成）
        $request->user()->tasks()->create([
            'content' => $request->content,
            'status' => $request->status,
        ]);

        // 前のURLへリダイレクトさせる
        return redirect('/');
    }
    public function destroy(string $id)
    {
        // idの値で投稿を検索して取得
        $task = Task::findOrFail($id);

        // 認証済みユーザー（閲覧者）がその投稿の所有者である場合は投稿を削除
        if (\Auth::id() === $task->user_id) {
            $task->delete();
            return redirect('/')
                ->with('success','Delete Successful');
        }

        // 前のURLへリダイレクトさせる
        return redirect('/');
        //return back()
            //->with('Delete Failed');
    }
    public function show(string $id)
    {
        $task = Task::findOrFail($id);

        if (\Auth::id() === $task->user_id) {
            return view('tasks.show', ['task' => $task]);
        }

        return redirect('/')->with('error', 'Unauthorized access.');
    }

    public function edit(string $id)
    {
        $task = Task::findOrFail($id);

        if (\Auth::id() === $task->user_id) {
            return view('tasks.edit', ['task' => $task]);
        }

        return redirect('/')->with('error', 'Unauthorized access.');
    }

    public function update(Request $request, string $id)
    {
        $task = Task::findOrFail($id);

        if (\Auth::id() === $task->user_id) {
            $request->validate([
                'status' => 'required|max:10',
                'content' => 'required|max:255',
            ]);

            $task->status = $request->status;
            $task->content = $request->content;
            $task->save();

            return redirect()->route('tasks.index')->with('success', 'Update successful');
        }

        return redirect('/')->with('error', 'Unauthorized access.');
    }
}