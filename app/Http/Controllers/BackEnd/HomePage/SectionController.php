<?php

namespace App\Http\Controllers\BackEnd\HomePage;

use App\Http\Controllers\Controller;
use App\Http\Requests\SectionStatusRequest;
use App\Models\HomePage\Section;

class SectionController extends Controller
{
    public function index()
    {
        $sectionInfo = Section::first();


        return view('backend.home-page.section-customization', compact('sectionInfo'));
    }

    public function update(SectionStatusRequest $request)
    {
        $sectionInfo = Section::first();

        if (empty($sectionInfo)) {
            Section::query()->create($request->all());
        } else {
            $sectionInfo->update($request->all());
        }

        $request->session()->flash('success', 'Section status updated successfully!');

        return redirect()->back();
    }
}
