<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Teacher\Instructor;

class InstructorController extends Controller
{
    public function instructors()
    {
        $language = $this->getLanguage();

        $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_instructors', 'meta_description_instructors')->first();

        $queryResult['pageHeading'] = $this->getPageHeading($language);

        $queryResult['bgImg'] = $this->getBreadcrumb();

        $instructors = Instructor::where('language_id', $language->id)->get();

        $instructors->map(function ($instructor) {
            $instructor['socials'] = $instructor->socialPlatform()->orderBy('serial_number', 'asc')->get();
        });

        $queryResult['instructors'] = $instructors;

        return view('frontend.instructors', $queryResult);
    }
}
