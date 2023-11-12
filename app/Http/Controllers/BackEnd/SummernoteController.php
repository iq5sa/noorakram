<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use Illuminate\Http\Request;

class SummernoteController extends Controller
{
    public function upload(Request $request)
    {
        $imageName = UploadFile::store('./img/summernotes/', $request->file('image'));

        return url('/') . '/img/summernotes/' . $imageName;
    }

    public function remove(Request $request)
    {
        @unlink('img/summernotes/' . $request->image);

        return response()->json(['data' => 'Image removed successfully!'], 200);
    }
}
