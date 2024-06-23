<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only([
            'index',
            'check_applications',
            'check_applicants',
            'accept',
            'decline',
            'undo',
            'destroy'
        ]);
        $this->middleware('admin')->only([
            'index',
            'undo'
        ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'data' => Application::with([
                'user:id,username,email',
                'job:id,title,company'
            ])->orderByDesc('created_at')
            ->get([
                'id',
                'user_id',
                'job_id',
                'status',
                'created_at'
            ])
        ]);
    }

    /**
     * Display your job applications.
     */
    public function check_applications(Request $request)
    {
        return response()->json([
            'data' => Application::with([
                'job:id,user_id,title,company',
                'job.user:id,username,email'
            ])->where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->get([
                'id',
                'user_id',
                'job_id',
                'status',
                'created_at'
            ])
        ]);
    }

    /**
     * Display applicants of your job listings.
     */
    public function check_applicants(Request $request)
    {
        return response()->json([
            'data' => Job::with([
                'applications:id,job_id,user_id,created_at',
                'applications.user:id,username,email'
            ])->where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->get([
                'id',
                'title',
                'company',
                'site',
                'desc',
                'created_at'
            ])
        ]);
    }

    /**
     * Accept a job application.
     */
    public function accept(Request $request, string $id)
    {
        $application = Application::with('job')->findOrFail($id);

        if ($request->user()->id === $application->user_id)
        {
            return response()->json([
                'message' => 'You cannot accept your own job application.'
            ], 422);
        } else if ($request->user()->id !== $application->job->user_id)
        {
            return response()->json([
                'message' => "You cannot accept other's job application."
            ], 401);
        }

        if ($application->status === 'pending')
        {
            $application->update([
                'status' => 'accepted'
            ]);

            return response()->json([
                'message' => 'A job application has been accepted.',
                'data' => $application->only([
                    'id',
                    'job_id',
                    'status',
                    'created_at'
                ])
            ]);
        }

        return response()->json([
            'message' => 'Your request cannot be processed. Please check if the application is pending.'
        ], 422);
    }

    /**
     * Decline a job application.
     */
    public function decline(Request $request, string $id)
    {
        $application = Application::findOrFail($id);

        if ($request->user()->id === $application->user_id)
        {
            return response()->json([
                'message' => 'You cannot decline your own job application.'
            ], 422);
        } else if ($request->user()->id !== $application->job->user_id)
        {
            return response()->json([
                'message' => "You cannot decline other's job application."
            ], 401);
        }

        if ($application->status === 'pending')
        {
            $application->update([
                'status' => 'declined'
            ]);

            return response()->json([
                'message' => 'A job application has been declined.',
                'data' => $application->only([
                    'id',
                    'job_id',
                    'status',
                    'created_at'
                ])
            ]);
        }

        return response()->json([
            'message' => 'Your request cannot be processed. Please check if the application is pending.'
        ], 422);
    }

    public function undo(string $id)
    {
        $application = Application::findOrFail($id);

        if ($application->status === 'pending')
        {
            return response()->json([
                'message' => 'The job application is neither accepted nor declined.'
            ], 422);
        }

        $application->update(['status' => 'pending']);

        return response()->json([
            'message' => 'The job application status is on pending again.',
            'data' => $application->only([
                'id',
                'user_id',
                'job_id',
                'status',
                'created_at'
            ])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $application = Application::findOrFail($id);

        if ($request->user()->id !== $application->user_id)
        {
            return response()->json([
                'message' => "You cannot delete other's job application."
            ], 401);
        }

        $application->delete();

        return response()->json([
            'message' => 'Job application deleted.',
            'data' => $application->only([
                'id',
                'user_id',
                'job_id',
                'status',
                'updated_at'
            ])
        ]);
    }
}
