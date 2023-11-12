<?php

namespace App\Http\Controllers\BackEnd\Curriculum;

use App\Http\Controllers\Controller;
use App\Models\Curriculum\Course;
use App\Models\Curriculum\CourseInformation;
use App\Models\Curriculum\Module;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ModuleController extends Controller
{
    public function index(Request $request, $id)
    {
        $information['langs'] = Language::all();

        $information['language'] = Language::where('code', $request->language)->first();

        $information['course'] = Course::findOrFail($id);
        $information['courseInformation'] = CourseInformation::where('course_id', $id)->where('language_id', $information['language']->id)->first();

        if (!empty($information['courseInformation'])) {
            $id = $information['courseInformation']->id;

            $information['modules'] = Module::where('course_information_id', $id)
                ->orderBy('serial_number', 'ASC')
                ->get();
        } else {
            Session::flash('warning', 'Please add course contents for ' . $information['language']->name . ' first!');
            return back();
        }


        return view('backend.curriculum.module.index', $information);
    }

    public function store($id, Request $request)
    {
        $rules = [
            'title' => 'required',
            'status' => 'required',
            'serial_number' => 'required',
            'language_id' => 'required'
        ];

        $messages = [
            'language_id.required' => 'The language field is required'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $courseInfoId = CourseInformation::where('course_id', $id)->where('language_id', $request->language_id)->firstOrFail()->id;

        Module::create($request->except('course_information_id') + [
                'course_information_id' => $courseInfoId
            ]);

        $request->session()->flash('success', 'New module added successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function destroy($id)
    {
        $module = Module::find($id);
        $lessons = $module->lesson()->get();

        foreach ($lessons as $lesson) {
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

            $lesson->delete();
        }

        // find out the course of this module
        $courseInfo = $module->courseInformation;
        $course = $courseInfo->course;

        $module->delete();

        // update course status (draft) of this module, when no module left
        $totalModules = Module::where('course_information_id', $courseInfo->id)->where('status', 'published')->count();

        if ($totalModules == 0) {
            $course->update([
                'status' => 'draft'
            ]);
        }

        // update course's total period
        $totalModulePeriod = Module::where('course_information_id', $courseInfo->id)->sum(DB::raw('TIME_TO_SEC(duration)'));

        $courseDuration = date('H:i:s', $totalModulePeriod);

        $course->update([
            'duration' => $courseDuration
        ]);

        return redirect()->back()->with('success', 'Module deleted successfully!');
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

        Module::find($request->id)->update($request->all());

        $request->session()->flash('success', 'Module updated successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $module = Module::find($id);
            $lessons = $module->lesson()->get();

            foreach ($lessons as $lesson) {
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

                $lesson->delete();
            }

            // find out the course of this module
            $courseInfo = $module->courseInformation;
            $course = $courseInfo->course;

            $module->delete();
        }

        // update course status (draft) of this module, when no module left
        $totalModules = Module::where('course_information_id', $courseInfo->id)->where('status', 'published')->count();

        if ($totalModules == 0) {
            $course->update([
                'status' => 'draft'
            ]);
        }

        // update course's total period
        $totalModulePeriod = Module::where('course_information_id', $courseInfo->id)->sum(DB::raw('TIME_TO_SEC(duration)'));

        $courseDuration = date('H:i:s', $totalModulePeriod);

        $course->update([
            'duration' => $courseDuration
        ]);

        $request->session()->flash('success', 'Modules deleted successfully!');

        return Response::json(['status' => 'success'], 200);
    }
}
