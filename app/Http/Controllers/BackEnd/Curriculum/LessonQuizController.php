<?php

namespace App\Http\Controllers\BackEnd\Curriculum;

use App\Http\Controllers\Controller;
use App\Models\Curriculum\Lesson;
use App\Models\Curriculum\LessonContent;
use App\Models\Curriculum\LessonQuiz;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class LessonQuizController extends Controller
{
    public function create($id)
    {
        $lesson = Lesson::find($id);
        $module = $lesson->module;
        $courseInfo = $module->courseInformation;
        $information['module'] = $module;
        $information['courseInfo'] = $courseInfo;
        $information['lesson'] = $lesson;

        return view('backend.curriculum.lesson-quiz.create', $information);
    }

    public function store($id, Request $request)
    {
        $rules = ['question' => 'required'];

        if (!$request->filled('right_answers') || !$request->filled('options')) {
            $rules['answer'] = 'required';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $quizTypeCount = LessonContent::where('lesson_id', $id)->where('type', 'quiz')->count();
        $maxOrderNo = LessonContent::where('lesson_id', $id)->max('order_no');

        if ($quizTypeCount == 0) {
            $content = new LessonContent();
            $content->lesson_id = $id;
            $content->type = 'quiz';
            $content->order_no = $maxOrderNo + 1;
            $content->save();
        } else {
            $contentId = LessonContent::where('lesson_id', $id)
                ->where('type', 'quiz')
                ->pluck('id')
                ->first();
        }

        $options = $request['options'];
        $rightAnswers = $request['right_answers'];
        $answers = [];

        foreach ($options as $key => $option) {
            $ansData = [
                'option' => $option,
                'rightAnswer' => in_array($key, $rightAnswers) ? 1 : 0
            ];

            $answers[$key] = $ansData;
        }

        $quiz = new LessonQuiz();
        $quiz->lesson_id = $id;
        $quiz->lesson_content_id = ($quizTypeCount == 0) ? $content->id : $contentId;
        $quiz->question = $request['question'];
        $quiz->answers = json_encode($answers);
        $quiz->save();

        $request->session()->flash('success', 'New quiz added successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function index($id, Request $request)
    {
        $lesson = Lesson::find($id);
        $module = $lesson->module;
        $courseInfo = $module->courseInformation;
        $information['module'] = $module;
        $information['courseInfo'] = $courseInfo;
        $information['lesson'] = $lesson;

        $information['quizzes'] = $lesson->quiz()->orderByDesc('id')->get();

        $information['language'] = Language::where('code', $request->language)->first();

        return view('backend.curriculum.lesson-quiz.index', $information);
    }

    public function edit($lessonId, $quizId)
    {
        $information['lesson'] = Lesson::find($lessonId);
        $information['quiz'] = LessonQuiz::find($quizId);

        return view('backend.curriculum.lesson-quiz.edit', $information);
    }

    public function getAns($id)
    {
        $quiz = LessonQuiz::find($id);
        $answers = json_decode($quiz->answers);

        return response()->json(['answers' => $answers]);
    }

    public function update(Request $request, $id)
    {
        $rules = ['question' => 'required'];

        if (!$request->filled('right_answers') || !$request->filled('options')) {
            $rules['answer'] = 'required';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $options = $request['options'];
        $rightAnswers = $request['right_answers'];
        $answers = [];

        foreach ($options as $key => $option) {
            $ansData = [
                'option' => $option,
                'rightAnswer' => in_array($key, $rightAnswers) ? 1 : 0
            ];

            $answers[$key] = $ansData;
        }

        $quiz = LessonQuiz::find($id);
        $quiz->update($request->except('answers') + [
                'answers' => json_encode($answers)
            ]);

        $request->session()->flash('success', 'Quiz updated successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function destroy($id)
    {
        $quiz = LessonQuiz::find($id);

        $content = $quiz->content()->first();

        $quiz->delete();

        if ($content->quiz()->count() == 0) {
            $content->delete();
        }

        return redirect()->back()->with('success', 'Quiz deleted successfully!');
    }
}
