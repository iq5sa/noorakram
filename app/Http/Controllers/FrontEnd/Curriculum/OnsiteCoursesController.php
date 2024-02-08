<?php

namespace App\Http\Controllers\FrontEnd\Curriculum;

use App\Http\Controllers\Controller;

class OnsiteCoursesController extends Controller
{
  //

  public function index()
  {
    $breadcrumbImg = asset("img/frontend/breadcrumb/onsite-courses.jpg");
    $courses = \App\Models\Curriculum\Course::where("type", "=", "online")->get();
    return view("frontend.Curriculum.onsite-courses", compact('breadcrumbImg', "courses"));
  }
}
