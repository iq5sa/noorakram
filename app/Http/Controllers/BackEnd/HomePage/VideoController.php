<?php

namespace App\Http\Controllers\BackEnd\HomePage;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Models\HomePage\VideoSection;
use App\Models\Language;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VideoController extends Controller
{
    public function index(Request $request)
    {
        $language = Language::where('code', $request->language)->first();
        $information['language'] = $language;

        $information['data'] = $language->videoSec()->first();

        $information['langs'] = Language::all();

        return view('backend.home-page.video-section', $information);
    }

    public function update(Request $request)
    {
        $language = Language::where('code', $request->language)->first();

        $videoInfo = $language->videoSec()->first();

        $rules = [];

        if (empty($videoInfo)) {
            $rules['image'] = 'required';
        }
        if ($request->hasFile('image')) {
            $rules['image'] = new ImageMimeTypeRule();
        }

        $rules['link'] = 'required|url';

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        // store data in db start
        $link = $request->link;

        if (strpos($link, '&') != 0) {
            $link = substr($link, 0, strpos($link, '&'));
        }

        if (empty($videoInfo)) {
            $imageName = UploadFile::store('./img/video-images/', $request->file('image'));

            VideoSection::create($request->except('language_id', 'image', 'link') + [
                    'language_id' => $language->id,
                    'image' => $imageName,
                    'link' => $link
                ]);

            $request->session()->flash('success', 'Video information added successfully!');

            return redirect()->back();
        } else {
            if ($request->hasFile('image')) {
                $imageName = UploadFile::update('./img/video-images/', $request->file('image'), $videoInfo->image);
            }

            $videoInfo->update($request->except('image', 'link') + [
                    'image' => $request->hasFile('image') ? $imageName : $videoInfo->image,
                    'link' => $link
                ]);

            $request->session()->flash('success', 'Video information updated successfully!');

            return redirect()->back();
        }
    }
}
