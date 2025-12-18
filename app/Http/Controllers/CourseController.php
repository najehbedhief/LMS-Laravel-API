<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Services\FileStorageService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;

class CourseController extends Controller
{
    public function __construct(private FileStorageService $fileStorage)
    {
        $this->fileStorage = $fileStorage;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
            $courses = Course::all();

            return response()->json($courses, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseRequest $request)
    {
            $validated = $request->validated();
            $validated['user_id'] = Auth::id();
            if ($request->hasFile('thumbnail')) {
                $validated['thumbnail'] = $request->file('thumbnail')->store('courses', 'public');
            }
            $course = Course::create($validated);

            return response()->json([
                'status' => '200',
                'message' => 'Course created successfully',
                'data' => $course,
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
            $course = Course::findOrFail($id);

            return response()->json($course, '200');
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateCourse(UpdateCourseRequest $request, string $id)
    {
            $course = Course::findOrFail($id);
            $this->authorize('update', $course);

            $validated = $request->validated();

            if ($request->hasFile('thumbnail')) {
                $validated['thumbnail'] = $this->fileStorage->replaceCourseThumbnail($course->thumbnail,$request->file('thumbnail'));
            }
            $course->update($validated);

            return response()->json($course, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
            $course = Course::findOrFail($id);
            $course->delete();

            return response()->json('Course Deleted successfully', 200); 
    }

    public function enrollToCourse(Request $request, $courseId)
    {
            $course = Course::findOrFail($courseId);
            $course->enrolledUsers()->attach(Auth::id());

            return response()->json('User attached successfully', 200);
        
    }
}