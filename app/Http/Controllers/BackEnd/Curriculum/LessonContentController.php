<?php

namespace App\Http\Controllers\BackEnd\Curriculum;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Models\Curriculum\Lesson;
use App\Models\Curriculum\LessonContent;
use App\Models\Curriculum\Module;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class LessonContentController extends Controller
{
    public function contents($id, Request $request)
    {
        $lesson = Lesson::find($id);
        $module = $lesson->module;
        $courseInfo = $module->courseInformation;
        $information['module'] = $module;
        $information['courseInfo'] = $courseInfo;
        $information['lesson'] = $lesson;

        $information['contents'] = $lesson->content()->orderBy('order_no', 'asc')->get();

        $information['language'] = Language::where('code', $request->language)->first();

        return view('backend.curriculum.lesson-content.index', $information);
    }

    public function uploadVideo(Request $request)
    {

        ini_set('post_max_size', '3000M');
        ini_set('upload_max_filesize', '3000M');
        set_time_limit(3000);

        $rules = [
            'video' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $video = $request->file('video');
                    $vidExt = $video->getClientOriginalExtension();

                    if ($vidExt != 'mp4') {
                        $fail('Only .mp4 file is allowed for ' . $attribute);
                    }
                }
            ]
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'error' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $videoData = UploadFile::store('./video/', $request->file('video'));

        return Response::json([
            'originalName' => $videoData['originalName'],
            'uniqueName' => $videoData['uniqueName'],
            'duration' => $videoData['duration']
        ], 200);
    }

    public function removeVideo(Request $request)
    {
        if (empty($request['title'])) {
            return Response::json(['error' => 'The request has no file name.'], 400);
        } else {
            @unlink('video/' . $request['title']);

            return Response::json(['success' => 'The file has been deleted.'], 200);
        }
    }

    public function storeVideo(Request $request, $id)
    {
        $rule = $message = [];

        if (empty($request['vid_org']) || empty($request['vid_unq'])) {
            $rule['video_content'] = 'required';

            $message = [
                'video_content.required' => 'The video filed is required.'
            ];
        }

        $validator = Validator::make($request->all(), $rule, $message);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $unqNames = $request['vid_unq'];
        $orgNames = $request['vid_org'];
        $durations = $request['vid_time'];

        $maxOrderNo = LessonContent::where('lesson_id', $id)->max('order_no');

        for ($i = 0; $i < sizeOf($unqNames); $i++) {
            $lessonContent = new LessonContent();
            $lessonContent->lesson_id = $id;
            $lessonContent->video_unique_name = $unqNames[$i];
            $lessonContent->video_original_name = $orgNames[$i];
            $lessonContent->video_duration = $durations[$i];
            $lessonContent->type = 'video';
            $lessonContent->order_no = $maxOrderNo + 1;
            $lessonContent->save();
        }

        // store lesson's total period
        $totalPeriod = LessonContent::where([
            ['lesson_id', $id],
            ['type', 'video']
        ])
            ->sum(DB::raw('TIME_TO_SEC(video_duration)'));

        $lessonDuration = date('H:i:s', $totalPeriod);

        $lesson = Lesson::find($id);
        $lesson->update([
            'duration' => $lessonDuration
        ]);

        // store module's total period
        $totalLessonPeriod = Lesson::where('module_id', $lesson->module_id)->sum(DB::raw('TIME_TO_SEC(duration)'));

        $moduleDuration = date('H:i:s', $totalLessonPeriod);

        $module = $lesson->module;
        $module->update([
            'duration' => $moduleDuration
        ]);

        // store course's total period
        $totalModulePeriod = Module::where('course_information_id', $module->course_information_id)->sum(DB::raw('TIME_TO_SEC(duration)'));

        $courseDuration = date('H:i:s', $totalModulePeriod);

        $course = $module->courseInformation->course;
        $course->update([
            'duration' => $courseDuration
        ]);

        $request->session()->flash('success', 'Video added successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function uploadFile(Request $request)
    {
        $rules = [
            'file' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $file = $request->file('file');
                    $fileExt = $file->getClientOriginalExtension();
                    $allowedExts = array('txt', 'doc', 'docx', 'pdf', 'zip');

                    if (!in_array($fileExt, $allowedExts)) {
                        $fail('Only .txt, .doc, .docx, .pdf & .zip file is allowed for ' . $attribute);
                    }
                }
            ]
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'error' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $fileData = UploadFile::store('./file/lesson-contents/', $request->file('file'));

        return Response::json([
            'originalName' => $fileData['originalName'],
            'uniqueName' => $fileData['uniqueName']
        ], 200);
    }

    public function removeFile(Request $request)
    {
        if (empty($request['title'])) {
            return Response::json(['error' => 'The request has no file name.'], 400);
        } else {
            @unlink('file/lesson-contents/' . $request['title']);

            return Response::json(['success' => 'The file has been deleted.'], 200);
        }
    }

    public function storeFile(Request $request, $id)
    {
        $rule = $message = [];

        if (empty($request['file_org']) || empty($request['file_unq'])) {
            $rule['file_content'] = 'required';

            $message = [
                'file_content.required' => 'The file filed is required.'
            ];
        }

        $validator = Validator::make($request->all(), $rule, $message);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $unqNames = $request['file_unq'];
        $orgNames = $request['file_org'];

        $maxOrderNo = LessonContent::where('lesson_id', $id)->max('order_no');

        for ($i = 0; $i < sizeOf($unqNames); $i++) {
            $lessonContent = new LessonContent();
            $lessonContent->lesson_id = $id;
            $lessonContent->file_unique_name = $unqNames[$i];
            $lessonContent->file_original_name = $orgNames[$i];
            $lessonContent->type = 'file';
            $lessonContent->order_no = $maxOrderNo + 1;
            $lessonContent->save();
        }

        $request->session()->flash('success', 'File added successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function downloadFile($id)
    {
        $content = LessonContent::find($id);

        $pathToFile = './file/lesson-contents/' . $content->file_unique_name;
        $originalName = $content->file_original_name;

        try {
            return response()->download($pathToFile, $originalName);
        } catch (FileNotFoundException $e) {
            return redirect()->back()->with('error', 'Sorry, file not found!');
        }
    }

    public function storeText(Request $request, $id)
    {
        $rule = ['text' => 'min:30'];

        $message = ['text.min' => 'The text must be at least 30 characters.'];

        $validator = Validator::make($request->all(), $rule, $message);

        if ($validator->fails()) {
            return Response::json([
                'error' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $maxOrderNo = LessonContent::where('lesson_id', $id)->max('order_no');

        $lessonContent = new LessonContent();
        $lessonContent->lesson_id = $id;
        $lessonContent->text = Purifier::clean($request['text']);
        $lessonContent->type = 'text';
        $lessonContent->order_no = $maxOrderNo + 1;
        $lessonContent->save();

        $request->session()->flash('success', 'Text added successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function updateText(Request $request)
    {
        $rule = ['text' => 'min:30'];

        $message = ['text.min' => 'The text must be at least 30 characters.'];

        $validator = Validator::make($request->all(), $rule, $message);

        if ($validator->fails()) {
            return Response::json([
                'error' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $lessonContent = LessonContent::find($request['id']);

        $lessonContent->update([
            'text' => Purifier::clean($request['text'])
        ]);

        $request->session()->flash('success', 'Text updated successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function storeCode(Request $request, $id)
    {
        $rule = ['code' => 'required'];

        $validator = Validator::make($request->all(), $rule);

        if ($validator->fails()) {
            return Response::json([
                'error' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $maxOrderNo = LessonContent::where('lesson_id', $id)->max('order_no');

        $lessonContent = new LessonContent();
        $lessonContent->lesson_id = $id;
        $lessonContent->code = $request['code'];
        $lessonContent->type = 'code';
        $lessonContent->order_no = $maxOrderNo + 1;
        $lessonContent->save();

        $request->session()->flash('success', 'Code added successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function updateCode(Request $request)
    {
        $rule = ['code' => 'required'];

        $validator = Validator::make($request->all(), $rule);

        if ($validator->fails()) {
            return Response::json([
                'error' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $lessonContent = LessonContent::find($request['id']);

        $lessonContent->update([
            'code' => $request['code']
        ]);

        $request->session()->flash('success', 'Code updated successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function destroyContent($id)
    {
        $content = LessonContent::find($id);
        $lessonId = $content->lesson_id;
        $type = $content->type;

        @unlink('video/' . $content->video_unique_name);
        @unlink('file/lesson-contents/' . $content->file_unique_name);

        $content->delete();

        if ($type == 'video') {
            // update lesson's total period
            $totalPeriod = LessonContent::where([
                ['lesson_id', $lessonId],
                ['type', $type]
            ])
                ->sum(DB::raw('TIME_TO_SEC(video_duration)'));

            $lessonDuration = date('H:i:s', $totalPeriod);

            $lesson = Lesson::find($lessonId);
            $lesson->update([
                'duration' => $lessonDuration
            ]);

            // update module's total period
            $totalLessonPeriod = Lesson::where('module_id', $lesson->module_id)->sum(DB::raw('TIME_TO_SEC(duration)'));

            $moduleDuration = date('H:i:s', $totalLessonPeriod);

            $module = $lesson->module;
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
        }

        return redirect()->back()->with('success', 'Content deleted successfully!');
    }

    public function sort(Request $request)
    {
        $ids = $request['ids'];
        $orders = $request['orders'];

        for ($i = 0; $i < sizeof($ids); $i++) {
            $content = LessonContent::find($ids[$i]);
            $content->update([
                'order_no' => $orders[$i]
            ]);
        }

        return response()->json(['status' => 'Lesson contents sorted successfully.'], 200);
    }
}
