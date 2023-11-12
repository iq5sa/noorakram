<?php

namespace App\Http\Controllers\BackEnd\Curriculum;

use App\Http\Controllers\Controller;
use App\Models\Curriculum\Course;
use App\Models\Curriculum\CourseFaq;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class CourseFaqController extends Controller
{
    public function index(Request $request, $id)
    {
        $information['course'] = Course::find($id);

        $language = Language::where('code', $request->language)->first();
        $information['language'] = $language;

        $information['faqs'] = CourseFaq::where('course_id', $id)
            ->where('language_id', $language->id)
            ->orderByDesc('id')
            ->get();

        $information['langs'] = Language::all();

        return view('backend.curriculum.faq.index', $information);
    }

    public function store(Request $request, $id)
    {
        $rules = [
            'language_id' => 'required',
            'question' => 'required',
            'answer' => 'required',
            'serial_number' => 'required'
        ];

        $message = [
            'language_id.required' => 'The language field is required.'
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        CourseFaq::create($request->except('course_id') + [
                'course_id' => $id
            ]);

        $request->session()->flash('success', 'New faq added successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function update(Request $request)
    {
        $rules = [
            'question' => 'required',
            'answer' => 'required',
            'serial_number' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        CourseFaq::find($request->id)->update($request->all());

        $request->session()->flash('success', 'FAQ updated successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function destroy($id)
    {
        CourseFaq::find($id)->delete();

        return redirect()->back()->with('success', 'FAQ deleted successfully!');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            CourseFaq::find($id)->delete();
        }

        $request->session()->flash('success', 'FAQs deleted successfully!');

        return Response::json(['status' => 'success'], 200);
    }
}
