<?php

namespace App\Http\Controllers\API;

use App\Models\Todo;
use App\Http\Requests\TodoRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\TodoResource;
use Symfony\Component\HttpFoundation\Response;

class TodosController extends Controller
{
    /**
     * make new instance of the class
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('role_or_permission:view todo|super')->only(['index', 'show']);
        $this->middleware('role_or_permission:create todo|super')->only(['store']);
        $this->middleware('role_or_permission:edit todo|super')->only(['update']);
        $this->middleware('role_or_permission:delete todo|super')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return TodoResource::collection(Todo::orderBy('id', 'desc')->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\TodoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TodoRequest $request)
    {
        $todo = Todo::create($request->only(['name']));
        return response()->json(['data' => new TodoResource($todo)]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function show(Todo $todo)
    {
        return response()->json(['data' => new TodoResource($todo)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\TodoRequest  $request
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function update(TodoRequest $request, Todo $todo)
    {
        $todo->update($request->only(['name']));
        return response()->json(['data' => new TodoResource($todo)]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Todo $todo)
    {
        $todo->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
