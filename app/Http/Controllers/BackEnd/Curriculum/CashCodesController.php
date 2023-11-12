<?php

namespace App\Http\Controllers\BackEnd\Curriculum;

use App\Http\Controllers\Controller;
use App\Models\CashCode;
use App\Models\Curriculum\Course;
use App\Models\Language;
use Illuminate\Http\Request;

class CashCodesController extends Controller
{
    //

    public function index()
    {
        $information['codes'] = CashCode::orderByDesc('id')->get();
        $information['courses'] = Course::where('status', 'published')->get();
        $information['deLang'] = Language::where('is_default', 1)->first();

        // also, get the currency information from db
        $information['currencyInfo'] = $this->getCurrencyInfo();

        return view('backend.curriculum.codes.index', $information);
    }

    public function store(Request $request)
    {

        $courses = $request->course;
        $code = $request->code;
        $cashCode = new cashCodes();
        $cashCode->course_id = $courses;
        $cashCode->code = $code;
        $cashCode->expire = 0;
        $cashCode->save();

        return response()->json(['status' => 'success'], 200);

    }

    public function destroy($id)
    {
        cashCodes::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Coupon deleted successfully!');
    }
}
