<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreLessonRequest;
use App\Http\Requests\UpdateLessonRequest;

class LessonController extends Controller
{
    public function store(StoreLessonRequest $request)
    {
            $validated = $request->validated();

            // Ensure the current user owns the course
            $course = Auth::user()
                ->createdCourses()
                ->whereKey($validated['course_id'])
                ->first();

            $lesson = Lesson::create($validated);

            return response()->json([
                'status' => '200',
                'message' => 'Lesson created successfully',
                'data' => $lesson,
            ]);
    }

    public function update(UpdateLessonRequest $request, $id)
    {
            $lesson = Lesson::findOrFail($id);

            $validated = $request->validated();

            //  Check course ownership
            if ($lesson->course->user_id !== Auth::id()) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            $lesson->update($validated);

            return response()->json([
                'status' => 200,
                'message' => 'Lesson updated successfully',
                'data' => $lesson,
            ]);

    }

    public function show(string $id)
    {
            $lesson = Leson::findOrFail($id);

            return response()->json($lesson, '200');
    }

    public function destroy($id)
    {
            $lesson = Lesson::findOrFail($id);
            $lesson->delete();

            return response()->json('Lesson Deleted successfully', 200);
    }
}
