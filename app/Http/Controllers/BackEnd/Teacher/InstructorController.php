<?php

namespace App\Http\Controllers\BackEnd\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Http\Requests\Instructor\StoreRequest;
use App\Http\Requests\Instructor\UpdateRequest;
use App\Models\Language;
use App\Models\Teacher\Instructor;
use Illuminate\Http\Request;
use Mews\Purifier\Facades\Purifier;

class InstructorController extends Controller
{

    public function index(Request $request)
    {
        // first, get the language info from db
        $language = Language::where('code', $request->language)->first();
        $information['language'] = $language;

        $information['instructors'] = Instructor::where('language_id', $language->id)
            ->orderByDesc('id')
            ->get();

        // also, get all the languages from db
        $information['langs'] = Language::all();


        return view('backend.instructor.index', $information);
    }

    public function store(StoreRequest $request)
    {
        $imageName = UploadFile::store('./img/instructors/', $request->file('image'));

        Instructor::create($request->except('image', 'description') + [
                'image' => $imageName,
                'description' => Purifier::clean($request->description)
            ]);

        $request->session()->flash('success', 'New instructor added successfully!');

        return response()->json(['status' => 'success'], 200);
    }

    public function create()
    {
        // get all the languages from db
        $information['languages'] = Language::all();

        return view('backend.instructor.create', $information);
    }

    public function updateFeatured(Request $request, $id)
    {
        $instructor = Instructor::find($id);

        if ($request['is_featured'] == 'yes') {
            $instructor->update(['is_featured' => 'yes']);

            $request->session()->flash('success', 'Instructor featured successfully!');
        } else {
            $instructor->update(['is_featured' => 'no']);

            $request->session()->flash('success', 'Instructor unfeatured successfully!');
        }

        return redirect()->back();
    }

    public function update(UpdateRequest $request, $id)
    {
        $instructor = Instructor::find($id);

        if ($request->hasFile('image')) {
            $imageName = UploadFile::update(
                './img/instructors/',
                $request->file('image'),
                $instructor->image
            );
        }

        $instructor->update($request->except('image', 'description') + [
                'image' => $request->hasFile('image') ? $imageName : $instructor->image,
                'description' => Purifier::clean($request->description)
            ]);

        $request->session()->flash('success', 'Instructor updated successfully!');

        return response()->json(['status' => 'success'], 200);
    }

    public function edit(Request $request, $id)
    {
        $langCode = $request['language'];

        $information['language'] = Language::where('code', $langCode)->first();

        $information['instructor'] = Instructor::find($id);

        return view('backend.instructor.edit', $information);
    }

    public function destroy($id)
    {
        $instructor = Instructor::find($id);
        $courseCount = $instructor->courseList()->count();

        if ($courseCount > 0) {
            return redirect()->back()->with('warning', 'First delete all the courses of this instructor!');
        } else {
            @unlink('img/instructors/' . $instructor->image);

            $instructor->delete();

            return redirect()->back()->with('success', 'Instructor deleted successfully!');
        }
    }


    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;

        $errorOccured = false;

        foreach ($ids as $id) {
            $instructor = Instructor::find($id);
            $courseCount = $instructor->courseList()->count();

            if ($courseCount > 0) {
                $errorOccured = true;
                break;
            } else {
                @unlink('img/instructors/' . $instructor->image);

                $instructor->delete();
            }
        }

        if ($errorOccured == true) {
            $request->session()->flash('warning', 'First delete all the courses of those instructors!');
        } else {
            $request->session()->flash('success', 'Instructors deleted successfully!');
        }

        return response()->json(['status' => 'success'], 200);
    }
}
