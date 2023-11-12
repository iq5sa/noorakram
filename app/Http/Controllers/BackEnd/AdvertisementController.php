<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Http\Requests\Advertisement\StoreRequest;
use App\Http\Requests\Advertisement\UpdateRequest;
use App\Models\Advertisement;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    public function index()
    {
        $ads = Advertisement::orderBy('id', 'desc')->get();

        return view('backend.advertisement.index', compact('ads'));
    }

    public function store(StoreRequest $request)
    {
        if ($request->hasFile('image')) {
            $imageName = UploadFile::store('./img/advertisements/', $request->file('image'));
        }

        Advertisement::create($request->except('image') + [
                'image' => $request->hasFile('image') ? $imageName : null
            ]);

        $request->session()->flash('success', 'New advertisement added successfully!');

        return response()->json(['status' => 'success'], 200);
    }

    public function update(UpdateRequest $request)
    {
        $ad = Advertisement::find($request->id);

        if ($request->hasFile('image')) {
            $imageName = UploadFile::update('./img/advertisements/', $request->file('image'), $ad->image);
        }

        if ($request->ad_type == 'adsense') {
            // if ad type change to google adsense then delete the image from local storage.
            @unlink('./img/advertisements/' . $ad->image);
        }

        $ad->update($request->except('image') + [
                'image' => $request->hasFile('image') ? $imageName : $ad->image
            ]);

        $request->session()->flash('success', 'Advertisement updated successfully!');

        return response()->json(['status' => 'success'], 200);
    }

    public function destroy($id)
    {
        $ad = Advertisement::find($id);

        if ($ad->ad_type == 'banner') {
            @unlink('img/advertisements/' . $ad->image);
        }

        $ad->delete();

        return redirect()->back()->with('success', 'Advertisement deleted successfully!');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $ad = Advertisement::find($id);

            if ($ad->ad_type == 'banner') {
                @unlink('img/advertisements/' . $ad->image);
            }

            $ad->delete();
        }

        $request->session()->flash('success', 'Advertisements deleted successfully!');

        return response()->json(['status' => 'success'], 200);
    }
}
