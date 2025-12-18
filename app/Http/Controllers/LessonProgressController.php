<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;
use App\Models\LessonProgress;
use Illuminate\Support\Facades\DB;

class LessonProgressController extends Controller
{
    public function lessonProgress(Request $request, Lesson $lesson)
    {
        $user = $request->user();
        $request->validate([
            'completed' => 'required|boolean',
        ]);

        $isEnrolled = $user->enrolledCourses()
            ->where('courses.id', $lesson->course_id)
            ->exists();

        if (! $isEnrolled) {
            return response()->json([
                'message' => 'You are not enrolled in this course',
            ], 403);
        }
        $progress = DB::transaction(function () use ($user, $lesson, $request) {
            return LessonProgress::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'lesson_id' => $lesson->id,
                    'completed' => (bool) $request->completed,
                ]
            );
        });

        return response()->json([
            'message' => 'Lesson progress updated',
            'data' => [
                'lesson_id' => $lesson->id,
                'is_completed' => $progress->completed,
            ],
        ]);

    }
}
