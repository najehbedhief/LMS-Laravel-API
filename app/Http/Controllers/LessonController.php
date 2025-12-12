<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreLessonRequest;
use App\Http\Requests\UpdateLessonRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LessonController extends Controller
{
    public function store(StoreLessonRequest $request)
    {
        try {
            $validated = $request->validated();

            // Ensure the current user owns the course
            $course = Auth::user()
                ->createdCourses()
                ->whereKey($validated['course_id'])
                ->first();

            if (! $course) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            $lesson = Lesson::create($validated);

            if (! $lesson) {
                return response()->json([
                    'success' => false,
                ]);
            }

            return response()->json([
                'status' => '200',
                'message' => 'Lesson created successfully',
                'data' => $lesson,
            ]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function update(UpdateLessonRequest $request, $id)
    {
        try {
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

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 404,
                'message' => 'Lesson not found',
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $lesson = Leson::findOrFail($id);

            return response()->json($lesson, '200');

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Lesson not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    public function destroy($id)
    {

        try {
            $lesson = Lesson::findOrFail($id);
            $lesson->delete();

            return response()->json('Lesson Deleted successfully', 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Lesson not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Something went wrong while deleting the lesson'], 500);
        }

    }
}
