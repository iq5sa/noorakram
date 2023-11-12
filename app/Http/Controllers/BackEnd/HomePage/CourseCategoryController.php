<?php

namespace App\Http\Controllers\BackEnd\HomePage;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CourseCategoryController extends Controller
{
    public function index()
    {
        $information['data'] = DB::table('basic_settings')->select('course_categories_section_image')->first();

        return view('backend.home-page.course-category-section', $information);
    }

    public function updateImage(Request $request)
    {
        $data = DB::table('basic_settings')->select('course_categories_section_image')->first();

        $rules = [];

        if (!$request->filled('image') && empty($data->course_categories_section_image)) {
            $rules['image'] = 'required';
        }
        if ($request->hasFile('image')) {
            $rules['image'] = new ImageMimeTypeRule();
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        if ($request->hasFile('image')) {
            $imgName = UploadFile::update('./img/', $request->file('image'), $data->course_categories_section_image);

            // finally, store the image into db
            DB::table('basic_settings')->updateOrInsert(
                ['uniqid' => 12345],
                ['course_categories_section_image' => $imgName]
            );

            $request->session()->flash('success', 'Image updated successfully!');
        }

        return redirect()->back();
    }
}
