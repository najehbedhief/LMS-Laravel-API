<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Course;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $courses = Course::all();

            return response()->json($courses, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseRequest $request)
    {
        try {
            $user_id = Auth::user()->id;
            $validated = $request->validated();
            $validated['user_id'] = $user_id;
            if ($request->hasFile('thumbnail')) {
                $path = $request->file('thumbnail')->store('courses', 'public');
                $validated['thumbnail'] = $path;
            }
            $course = Course::create($validated);

            if (! $course) {
                return response()->json([
                    'success' => false,
                ]);
            }

            return response()->json([
                'status' => '200',
                'message' => 'Course created successfully',
                'data' => $course,
            ]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $course = Course::findOrFail($id);

            return response()->json($course, '200');

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Course not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateCourse(UpdateCourseRequest $request, string $id)
    {

        try {
            $user_id = Auth::user()->id;

            $course = Course::findOrFail($id);

            if ($course->user_id != $user_id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            $validated = $request->validated();
            // Handle thumbnail file
            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $extension = $file->getClientOriginalExtension();
                $filename = time().'_'.uniqid().'.'.$extension; // Unique filename
                $path = 'courses/'.$filename;

                // Delete old thumbnail if exists
                if ($course->thumbnail && Storage::disk('public')->exists($course->thumbnail)) {
                    Storage::disk('public')->delete($course->thumbnail);
                }

                // Store new file
                $file->storeAs('courses', $filename, 'public');
                $validated['thumbnail'] = $path;
            }

            $course->update($validated);

            return response()->json($course, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Task not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $course = Course::findOrFail($id);
            $course->delete();

            return response()->json('Course Deleted successfully', 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Course not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Something went wrong while deleting the course'], 500);
        }
    }
}
