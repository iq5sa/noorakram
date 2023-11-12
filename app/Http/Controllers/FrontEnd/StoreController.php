<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;

class StoreController extends Controller
{
    public function index()
    {
        $language = $this->getLanguage();


        $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_contact', 'meta_description_contact')->first();

        $queryResult['pageHeading'] = "contact";

        $queryResult['bgImg'] = $this->getBreadcrumb();


        return view('frontend.store.index', $queryResult);
    }


}
