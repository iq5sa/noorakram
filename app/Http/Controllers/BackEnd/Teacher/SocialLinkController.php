<?php

namespace App\Http\Controllers\BackEnd\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Teacher\Instructor;
use App\Models\Teacher\SocialLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class SocialLinkController extends Controller
{
    public function links($id)
    {
        $information['instructor'] = Instructor::find($id);

        $information['socialLinks'] = SocialLink::where('instructor_id', $id)
            ->orderByDesc('id')
            ->get();

        return view('backend.instructor.social-links.index', $information);
    }

    public function storeLink($id, Request $request)
    {
        $rules = [
            'icon' => 'required',
            'url' => 'required',
            'serial_number' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        SocialLink::create($request->except('instructor_id') + [
                'instructor_id' => $id
            ]);

        $request->session()->flash('success', 'New social link added successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function editLink($instructor_id, $id)
    {
        $information['instructor'] = Instructor::where('id', $instructor_id)->first();

        $information['socialLink'] = SocialLink::find($id);

        return view('backend.instructor.social-links.edit', $information);
    }

    public function updateLink(Request $request, $id)
    {
        $rules = [
            'url' => 'required',
            'serial_number' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        SocialLink::find($id)->update($request->all());

        $request->session()->flash('success', 'Social link updated successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function destroyLink($id)
    {
        SocialLink::find($id)->delete();

        return redirect()->back()->with('success', 'Social link deleted successfully!');
    }
}
