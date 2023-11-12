<?php

namespace App\Http\Controllers\BackEnd\Curriculum;

use App\Http\Controllers\Controller;
use App\Http\Requests\CouponRequest;
use App\Models\Curriculum\Coupon;
use App\Models\Curriculum\Course;
use App\Models\Language;
use Carbon\Carbon;

class CouponController extends Controller
{
    public function index()
    {
        // get the coupons from db
        $information['coupons'] = Coupon::orderByDesc('id')->get();
        $information['courses'] = Course::where('status', 'published')->get();
        $information['deLang'] = Language::where('is_default', 1)->first();

        // also, get the currency information from db
        $information['currencyInfo'] = $this->getCurrencyInfo();

        return view('backend.curriculum.coupon.index', $information);
    }

    public function store(CouponRequest $request)
    {
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        Coupon::create($request->except('start_date', 'end_date', 'courses') + [
                'courses' => json_encode($request->courses),
                'start_date' => date_format($startDate, 'Y-m-d'),
                'end_date' => date_format($endDate, 'Y-m-d')
            ]);

        $request->session()->flash('success', 'New coupon added successfully!');

        return response()->json(['status' => 'success'], 200);
    }

    public function update(CouponRequest $request)
    {
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $courses = !empty($request->courses) ? json_encode($request->courses) : NULL;

        Coupon::findOrFail($request->id)->update(
            $request->except('start_date', 'end_date', 'courses') + [
                'courses' => $courses,
                'start_date' => date_format($startDate, 'Y-m-d'),
                'end_date' => date_format($endDate, 'Y-m-d')
            ]
        );

        $request->session()->flash('success', 'Coupon updated successfully!');

        return response()->json(['status' => 'success'], 200);
    }

    public function destroy($id)
    {
        Coupon::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Coupon deleted successfully!');
    }
}
