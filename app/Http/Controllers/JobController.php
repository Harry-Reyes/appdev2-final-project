<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Application;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only([
            'store',
            'update',
            'destroy',
            'apply'
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'data' => Job::with('user:id,username,email')->orderByDesc('updated_at')->get([
                'id',
                'user_id',
                'title',
                'company',
                'site',
                'desc',
                'updated_at'
            ])
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
            'site' => 'required|url',
            'desc' => 'required|string|max:3000'
        ]);

        $user = $request->user();

        $fields['user_id'] = $user->id;

        $job = Job::create($fields);

        return response()->json([
            'message' => 'Job listed.',
            'data' => Job::findOrFail($job->id, [
                'id',
                'title',
                'company',
                'site',
                'desc',
                'updated_at'
            ])
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json([
            'data' => Job::with('user:id,username,email')->findOrFail($id, [
                'id',
                'user_id',
                'title',
                'company',
                'site',
                'desc',
                'updated_at'
            ])
        ]);
    }
    /**
     * Display a filtered listing of the resource.
     */
    public function search(Request $request)
    {
        $input = trim($request->input('q')) ? $request->input('q') : null;

        if ($input == null)
        {
            return response()->json([
                'message' => 'Search by job title or company.'
            ]);
        }

        return response()->json([
            'data' => Job::with('user:id,username,email')
                         ->where('title', 'like', "%{$input}%")
                         ->orWhere('company', 'like', "%{$input}%")
                         ->get([
                            'id',
                            'user_id',
                            'title',
                            'company',
                            'site',
                            'desc',
                            'updated_at'
                         ])
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

        $job = Job::with('applications')->findOrFail($id);

        if ($request->user()->id !== $job->user_id)
        {
            return response()->json([
                'message' => "You cannot edit other's job."
            ], 401);
        } else if ($job->applications->first())
        {
            return response()->json([
                'message' => 'You cannot edit a job with applications.'
            ]);
        }

        $job->update($fields);

        return response()->json([
            'message' => 'Job updated.',
            'data' => $job->only([
                'id',
                'title',
                'company',
                'site',
                'desc',
                'updated_at'
            ])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $job = Job::findOrFail($id);

        if ($request->user()->id !== $job->user_id)
        {
            return response()->json([
                'message' => "You cannot delete other's job."
            ], 401);
        }

        $job->delete();

        return response()->json([
            'message' => 'Job deleted.',
            'data' => $job->only([
                'id',
                'title',
                'company',
                'site',
                'desc',
                'updated_at'
            ])
        ]);
    }

    public function apply(Request $request, string $id)
    {
        $job = Job::findOrFail($id);

        if ($request->user()->id === $job->user_id)
        {
            return response()->json([
                'message' => "You cannot apply to a job listed by you."
            ], 422);
        }

        $application = Application::create([
            'user_id' => $request->user()->id,
            'job_id' => $job->id
        ]);

        return response()->json([
            'message' => 'Sent an application.',
            'data' => Application::with([
                'user:id,username',
                'job:id,title,company'
            ])->findOrFail($application->id, [
                'id',
                'user_id',
                'job_id',
                'created_at'
            ])
        ]);
    }
}
