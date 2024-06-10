<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'data' => Job::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'title' => 'required|string',
            'company' => 'required|string',
            'site' => 'url',
            'desc' => 'string|max:3000'
        ]);

        $token = PersonalAccessToken::findToken($request->user()->currentAccessToken());
        $user = $token->tokenable;

        $fields[] = ['user_id' => $user->id];

        $job = Job::create($fields);

        return response()->json([
            'message' => 'Job listed.',
            'title' => $job->title,
            'company' => $job->company
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json([
            'data' => Job::find($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $fields = $request->validate([
            'title' => 'string',
            'company' => 'string',
            'site' => 'url',
            'desc' => 'string|max:3000'
        ]);

        $job = Job::find($id)->update($fields);

        return response()->json([
            'message' => 'Job updated.',
            'data' => Job::find($id)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $job = Job::find($id);
        $job->delete();
        return response()->json([
            'message' => 'Job deleted.',
            'data' => $job
        ]);
    }
}
