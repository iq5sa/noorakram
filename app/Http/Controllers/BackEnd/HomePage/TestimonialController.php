<?php

namespace App\Http\Controllers\BackEnd\HomePage;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Http\Requests\Testimonial\StoreRequest;
use App\Http\Requests\Testimonial\UpdateRequest;
use App\Models\HomePage\Testimonial;
use App\Models\Language;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TestimonialController extends Controller
{
    public function index(Request $request)
    {
        $language = Language::where('code', $request->language)->first();
        $information['language'] = $language;


        $information['testimonials'] = $language->testimonial()->orderByDesc('id')->get();

        $information['langs'] = Language::all();

        return view('backend.home-page.testimonial-section.index', $information);
    }

    public function updateImage(Request $request)
    {
        $data = DB::table('basic_settings')->select('testimonials_section_image')->first();

        $rules = [];

        if (!$request->filled('image') && empty($data->testimonials_section_image)) {
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
            $imgName = UploadFile::update('./img/', $request->file('image'), $data->testimonials_section_image);

            // finally, store the image into db
            DB::table('basic_settings')->updateOrInsert(
                ['uniqid' => 12345],
                ['testimonials_section_image' => $imgName]
            );

            $request->session()->flash('success', 'Image updated successfully!');
        }

        return redirect()->back();
    }

    public function update(UpdateRequest $request)
    {
        $testimonial = Testimonial::find($request->id);

        if ($request->hasFile('image')) {
            $imageName = UploadFile::update('./img/clients/', $request->file('image'), $testimonial->image);
        }

        $testimonial->update($request->except('image') + [
                'image' => $request->hasFile('image') ? $imageName : $testimonial->image
            ]);

        $request->session()->flash('success', 'Testimonial updated successfully!');

        return response()->json(['status' => 'success'], 200);
    }

    public function store(StoreRequest $request)
    {
        $imageName = UploadFile::store('./img/clients/', $request->file('image'));

        Testimonial::create($request->except('image') + [
                'image' => $imageName
            ]);

        $request->session()->flash('success', 'New testimonial added successfully!');

        return response()->json(['status' => 'success'], 200);
    }

    public function destroy($id)
    {
        $testimonial = Testimonial::find($id);

        // delete client picture
        @unlink('img/clients/' . $testimonial->image);

        $testimonial->delete();

        return redirect()->back()->with('success', 'Testimonial deleted successfully!');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $testimonial = Testimonial::find($id);

            // delete client picture
            @unlink('img/clients/' . $testimonial->image);

            $testimonial->delete();
        }

        $request->session()->flash('success', 'Testimonials deleted successfully!');

        return response()->json(['status' => 'success'], 200);
    }
}
