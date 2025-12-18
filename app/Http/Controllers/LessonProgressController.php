<?php

namespace App\Http\Controllers;

use App\Models\LessonProgress;
use Illuminate\Http\Request;

class LessonProgressController extends Controller
{
    public function lessonProgress(Request $request)
    {
        $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'completed' => 'required|boolean',
        ]);

        $progress = LessonProgress::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'lesson_id' => $request->lesson_id,
            ],
            [
                'completed' => $request->completed,
            ]
        );

        return response()->json([
            'message' => 'Lesson progress updated',
            'data' => $progress,
        ]);

    }
}
