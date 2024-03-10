<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Http\Requests\Popup\StoreRequest;
use App\Http\Requests\Popup\UpdateRequest;
use App\Models\Language;
use App\Models\Popup;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PopupController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index(Request $request)
  {
    // first, get the language info from db
    $language = Language::where('code', $request->language)->first();
    $information['language'] = $language;

    $information['popups'] = Popup::where('language_id', $language->id)
      ->orderBy('id', 'desc')
      ->get();

    // also, get all the languages from db
    $information['langs'] = Language::all();

    return view('backend.popup.index', $information);
  }

  /**
   * Show the popup type page to select one of them.
   *
   * @return Response
   */
  public function popupType()
  {
    return view('backend.popup.popup-type');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param Request $request
   * @return Response
   */
  public function store(StoreRequest $request)
  {
    $imageName = UploadFile::store('./img/popups/', $request->file('image'));

    Popup::create($request->except('image', 'end_date', 'end_time') + [
        'image' => $imageName,
        'end_date' => $request->has('end_date') ? Carbon::parse($request['end_date']) : null,
        'end_time' => $request->has('end_time') ? date('h:i', strtotime($request['end_time'])) : null
      ]);

    $request->session()->flash('success', 'New popup added successfully!');

    return response()->json(['status' => 'success'], 200);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create($type)
  {
    $information['popupType'] = $type;

    // get all the languages from db
    $information['languages'] = Language::all();

    return view('backend.popup.create', $information);
  }

  /**
   * Update the status of specified resource.
   *
   * @param Request $request
   * @param int $id
   * @return Response
   */
  public function updateStatus(Request $request, $id)
  {
    $popup = Popup::find($id);

    if ($request->status == 1) {
      $popup->update(['status' => 1]);

      $request->session()->flash('success', 'Popup activated successfully!');
    } else {
      $popup->update(['status' => 0]);

      $request->session()->flash('success', 'Popup deactivated successfully!');
    }

    return redirect()->back();
  }

  /**
   * Update the specified resource in storage.
   *
   * @param Request $request
   * @param int $id
   * @return Response
   */
  public function update(UpdateRequest $request, $id)
  {
    $popup = Popup::find($id);

    if ($request->hasFile('image')) {
      $imageName = UploadFile::update('./img/popups/', $request->file('image'), $popup->image);
    }

    $popup->update($request->except('image', 'end_date', 'end_time') + [
        'image' => $request->hasFile('image') ? $imageName : $popup->image,
        'end_date' => $request->has('end_date') ? Carbon::parse($request['end_date']) : null,
        'end_time' => $request->has('end_time') ? date('h:i', strtotime($request['end_time'])) : null
      ]);

    $request->session()->flash('success', 'Popup updated successfully!');

    return response()->json(['status' => 'success'], 200);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param int $id
   * @return Response
   */
  public function edit($id)
  {
    $popup = Popup::find($id);

    return view('backend.popup.edit', compact('popup'));
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param int $id
   * @return Response
   */
  public function destroy($id)
  {
    $popup = Popup::find($id);

    @unlink('img/popups/' . $popup->image);

    $popup->delete();

    return redirect()->back()->with('success', 'Popup deleted successfully!');
  }

  /**
   * Remove the selected or all resources from storage.
   *
   * @param Request $request
   * @return Response
   */
  public function bulkDestroy(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $popup = Popup::find($id);

      @unlink('img/popups/' . $popup->image);

      $popup->delete();
    }

    $request->session()->flash('success', 'Popups deleted successfully!');

    return response()->json(['status' => 'success'], 200);
  }
}
