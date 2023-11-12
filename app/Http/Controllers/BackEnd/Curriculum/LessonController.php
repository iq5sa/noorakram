<?php

namespace App\Http\Controllers\BackEnd\Curriculum;

use App\Http\Controllers\Controller;
use App\Models\Curriculum\Lesson;
use App\Models\Curriculum\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class LessonController extends Controller
{
    public function store($id, Request $request)
    {
        $rules = [
            'title' => 'required',
            'status' => 'required',
            'serial_number' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        Lesson::create($request->except('module_id') + [
                'module_id' => $id
            ]);

        $request->session()->flash('success', 'New lesson added successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function destroy($id)
    {
        $lesson = Lesson::find($id);
        $lessonContents = $lesson->content()->get();

        foreach ($lessonContents as $lessonContent) {
            if ($lessonContent->type == 'video') {
                @unlink('./video/' . $lessonContent->video_unique_name);
            } else if ($lessonContent->type == 'file') {
                @unlink('./file/lesson-contents/' . $lessonContent->file_unique_name);
            } else if ($lessonContent->type == 'quiz') {
                $lessonQuizzes = $lessonContent->quiz()->get();

                foreach ($lessonQuizzes as $lessonQuiz) {
                    $lessonQuiz->delete();
                }
            }

            $lessonContent->delete();
        }

        // find out the module of this lesson
        $module = $lesson->module;

        $lesson->delete();

        // update module's total period
        $totalLessonPeriod = Lesson::where('module_id', $module->id)->sum(DB::raw('TIME_TO_SEC(duration)'));

        $moduleDuration = date('H:i:s', $totalLessonPeriod);

        $module->update([
            'duration' => $moduleDuration
        ]);

        // update course's total period
        $totalModulePeriod = Module::where('course_information_id', $module->course_information_id)->sum(DB::raw('TIME_TO_SEC(duration)'));

        $courseDuration = date('H:i:s', $totalModulePeriod);

        $course = $module->courseInformation->course;
        $course->update([
            'duration' => $courseDuration
        ]);

        return redirect()->back()->with('success', 'Lesson deleted successfully!');
    }

    public function update(Request $request)
    {
        $rules = [
            'title' => 'required',
            'status' => 'required',
            'serial_number' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        Lesson::find($request->id)->update($request->all());

        $request->session()->flash('success', 'Lesson updated successfully!');

        return Response::json(['status' => 'success'], 200);
    }
}
