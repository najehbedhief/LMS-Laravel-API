<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Services\FileStorageService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    use ApiResponse;

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

        return CourseResource::collection($courses);
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

        return $this->successResponse(new CourseResource($course), 'Course created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        return new CourseResource($course);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateCourse(UpdateCourseRequest $request, Course $course)
    {
        $this->authorize('update', $course);

        $validated = $request->validated();

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $this->fileStorage->replaceCourseThumbnail($course->thumbnail, $request->file('thumbnail'));
        }
        $course->update($validated);

        return new CourseResource($course);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);
        $course->delete();

        return $this->successResponse(null, 'Course deleted successfully', 200);
    }

    public function enrollToCourse(Request $request, Course $course)
    {
        $course->enrolledUsers()->attach(Auth::id());

        return $this->successResponse(null, 'User enrolled successfully',200);
    }
}
