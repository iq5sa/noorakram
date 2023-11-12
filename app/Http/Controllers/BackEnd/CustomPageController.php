<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\CustomPage\Page;
use App\Models\CustomPage\PageContent;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;

class CustomPageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // first, get the language info from db
        $language = Language::where('code', $request->language)->first();
        $information['language'] = $language;

        // then, get the custom pages of that language from db
        $information['pages'] = DB::table('pages')
            ->join('page_contents', 'pages.id', '=', 'page_contents.page_id')
            ->where('page_contents.language_id', '=', $language->id)
            ->orderByDesc('pages.id')
            ->get();

        // also, get all the languages from db
        $information['langs'] = Language::all();

        return view('backend.custom-page.index', $information);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // get all the languages from db
        $information['languages'] = Language::all();

        return view('backend.custom-page.create', $information);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = ['status' => 'required'];

        $languages = Language::all();

        $messages = [];

        foreach ($languages as $language) {
            $slug = createSlug($request[$language->code . '_title']);
            $rules[$language->code . '_title'] = [
                'required',
                'max:255',
                function ($attribute, $value, $fail) use ($slug, $language) {
                    $pcs = PageContent::where('language_id', $language->id)->get();
                    foreach ($pcs as $key => $pc) {
                        if (strtolower($slug) == strtolower($pc->slug)) {
                            $fail('The title field must be unique for ' . $language->name . ' language.');
                        }
                    }
                }
            ];
            $rules[$language->code . '_content'] = 'min:15';

            $messages[$language->code . '_title.required'] = 'The title field is required for ' . $language->name . ' language.';

            $messages[$language->code . '_title.max'] = 'The title field cannot contain more than 255 characters for ' . $language->name . ' language.';

            $messages[$language->code . '_title.unique'] = 'The title field must be unique for ' . $language->name . ' language.';

            $messages[$language->code . '_content.min'] = 'The content field atleast have 15 characters for ' . $language->name . ' language.';
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $page = new Page();

        $page->status = $request->status;
        $page->save();

        foreach ($languages as $language) {
            $pageContent = new PageContent();
            $pageContent->language_id = $language->id;
            $pageContent->page_id = $page->id;
            $pageContent->title = $request[$language->code . '_title'];
            $pageContent->slug = createSlug($request[$language->code . '_title']);
            $pageContent->content = Purifier::clean($request[$language->code . '_content']);
            $pageContent->meta_keywords = $request[$language->code . '_meta_keywords'];
            $pageContent->meta_description = $request[$language->code . '_meta_description'];
            $pageContent->save();
        }

        $request->session()->flash('success', 'New page added successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $information['page'] = Page::findOrFail($id);

        // get all the languages from db
        $information['languages'] = Language::all();

        return view('backend.custom-page.edit', $information);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = ['status' => 'required'];

        $languages = Language::all();

        $messages = [];

        foreach ($languages as $language) {
            $slug = createSlug($request[$language->code . '_title']);
            $rules[$language->code . '_title'] = [
                'required',
                'max:255',
                function ($attribute, $value, $fail) use ($slug, $id, $language) {
                    $pcs = PageContent::where('page_id', '<>', $id)->where('language_id', $language->id)->get();
                    foreach ($pcs as $key => $pc) {
                        if (strtolower($slug) == strtolower($pc->slug)) {
                            $fail('The title field must be unique for ' . $language->name . ' language.');
                        }
                    }
                }
            ];

            $rules[$language->code . '_content'] = 'min:15';

            $messages[$language->code . '_title.required'] = 'The title field is required for ' . $language->name . ' language.';

            $messages[$language->code . '_title.max'] = 'The title field cannot contain more than 255 characters for ' . $language->name . ' language.';

            $messages[$language->code . '_title.unique'] = 'The title field must be unique for ' . $language->name . ' language.';

            $messages[$language->code . '_content.min'] = 'The content field atleast have 15 characters for ' . $language->name . ' language.';
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $page = Page::findOrFail($id);

        $page->update(['status' => $request->status]);

        foreach ($languages as $language) {
            $pageContent = PageContent::where('page_id', $id)
                ->where('language_id', $language->id)
                ->first();

            $pageContent->update([
                'title' => $request[$language->code . '_title'],
                'slug' => createSlug($request[$language->code . '_title']),
                'content' => Purifier::clean($request[$language->code . '_content']),
                'meta_keywords' => $request[$language->code . '_meta_keywords'],
                'meta_description' => $request[$language->code . '_meta_description']
            ]);
        }

        $request->session()->flash('success', 'Page updated successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Page::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Page deleted successfully!');
    }

    /**
     * Remove the selected or all resources from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            Page::findOrFail($id)->delete();
        }

        $request->session()->flash('success', 'Pages deleted successfully!');

        return Response::json(['status' => 'success'], 200);
    }
}
