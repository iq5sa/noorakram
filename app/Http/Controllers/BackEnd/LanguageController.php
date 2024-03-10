<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Language\StoreRequest;
use App\Http\Requests\Language\UpdateRequest;
use App\Models\Curriculum\Course;
use App\Models\Curriculum\CourseInformation;
use App\Models\CustomPage\Page;
use App\Models\CustomPage\PageContent;
use App\Models\Journal\Blog;
use App\Models\Journal\BlogInformation;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LanguageController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
    $languages = Language::all();

    return view('backend.language.index', compact('languages'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param Request $request
   * @return Response
   */
  public function store(StoreRequest $request)
  {
    // get all the keywords from the default file of language
    $data = file_get_contents(resource_path('lang/') . 'default.json');

    // make a new json file for the new language
    $fileName = strtolower($request->code) . '.json';

    // create the path where the new language json file will be stored
    $fileLocated = resource_path('lang/') . $fileName;

    // finally, put the keywords in the new json file and store the file in lang folder
    file_put_contents($fileLocated, $data);

    // then, store data in db
    Language::create($request->all());

    $request->session()->flash('success', 'Language added successfully!');

    return response()->json(['status' => 'success'], 200);
  }

  /**
   * Make a default language for this system.
   *
   * @param int $id
   * @return Response
   */
  public function makeDefault($id)
  {
    // first, make other languages to non-default language
    Language::where('is_default', 1)->update(['is_default' => 0]);

    // second, make the selected language to default language
    $language = Language::find($id);

    $language->update(['is_default' => 1]);

    return back()->with('success', $language->name . ' ' . 'is set as default language.');
  }

  /**
   * Update the specified resource in storage.
   *
   * @param Request $request
   * @param int $id
   * @return Response
   */
  public function update(UpdateRequest $request)
  {
    $language = Language::find($request->id);

    if ($language->code !== $request->code) {
      /**
       * get all the keywords from the previous file,
       * which was named using previous language code
       */
      $data = file_get_contents(resource_path('lang/') . $language->code . '.json');

      // make a new json file for the new language (code)
      $fileName = strtolower($request->code) . '.json';

      // create the path where the new language (code) json file will be stored
      $fileLocated = resource_path('lang/') . $fileName;

      // then, put the keywords in the new json file and store the file in lang folder
      file_put_contents($fileLocated, $data);

      // now, delete the previous language code file
      @unlink(resource_path('lang/') . $language->code . '.json');

      // finally, update the info in db
      $language->update($request->all());
    } else {
      $language->update($request->all());
    }

    $request->session()->flash('success', 'Language updated successfully!');

    return response()->json(['status' => 'success'], 200);
  }

  /**
   * Display all the keywords of specified resource.
   *
   * @param int $id
   * @return Response
   */
  public function editKeyword($id)
  {
    $language = Language::find($id);

    // get all the keywords of the selected language
    $jsonData = file_get_contents(resource_path('lang/') . $language->code . '.json');

    // convert json encoded string into a php associative array
    $keywords = json_decode($jsonData, true);

    return view('backend.language.edit-keyword', compact('language', 'keywords'));
  }

  /**
   * Update the keywords of specified resource in respective json file.
   *
   * @param Request $request
   * @param int $id
   * @return Response
   */
  public function updateKeyword(Request $request, $id)
  {
    $arrData = $request['keyValues'];

    // first, check each key has value or not
    foreach ($arrData as $key => $value) {
      if ($value == null) {
        $request->session()->flash('warning', 'Value is required for "' . $key . '" key.');

        return redirect()->back();
      }
    }

    $jsonData = json_encode($arrData);

    $language = Language::find($id);

    $fileLocated = resource_path('lang/') . $language->code . '.json';

    // put all the keywords in the selected language file
    file_put_contents($fileLocated, $jsonData);

    $request->session()
      ->flash('success', $language->name . ' language\'s keywords updated successfully!');

    return redirect()->back();
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param int $id
   * @return Response
   */
  public function destroy($id)
  {
    $language = Language::find($id);

    if ($language->is_default == 1) {
      return redirect()->back()->with('warning', 'Default language cannot be delete.');
    } else {
      /**
       * delete 'about us section' info
       */
      $aboutUsSec = $language->aboutUsSec()->first();

      if (!empty($aboutUsSec)) {
        @unlink('img/about-us-section/' . $aboutUsSec->image);
        $aboutUsSec->delete();
      }

      /**
       * delete 'action section' info
       */
      $actionSec = $language->actionSec()->first();

      if (!empty($actionSec)) {
        @unlink('img/action-section/' . $actionSec->background_image);
        @unlink('img/action-section/' . $actionSec->image);
        $actionSec->delete();
      }

      /**
       * delete 'blog infos'
       */
      $blogInfos = $language->blogInformation()->get();

      if (count($blogInfos) > 0) {
        foreach ($blogInfos as $blogData) {
          $blogInfo = $blogData;
          $blogData->delete();

          // delete the blog if, this blog does not contain any other blog information in any other language
          $otherBlogInfos = BlogInformation::query()->where('language_id', '<>', $language->id)->where('blog_id', '=', $blogInfo->blog_id)->get();

          if (count($otherBlogInfos) == 0) {
            $blog = Blog::query()->find($blogInfo->blog_id);
            @unlink('img/blogs/' . $blog->image);
            $blog->delete();
          }
        }
      }

      /**
       * delete 'blog categories' info
       */
      $blogCategories = $language->blogCategory()->get();

      if (count($blogCategories) > 0) {
        foreach ($blogCategories as $blogCategory) {
          $blogCategory->delete();
        }
      }

      /**
       * delete 'cookie alert' info
       */
      $cookieAlert = $language->cookieAlertInfo()->first();

      if (!empty($cookieAlert)) {
        $cookieAlert->delete();
      }

      /**
       * delete 'counter infos'
       */
      $counterInfos = $language->countInfo()->get();

      if (count($counterInfos) > 0) {
        foreach ($counterInfos as $counterInfo) {
          $counterInfo->delete();
        }
      }

      /**
       * delete 'course infos'
       */
      $courseInfos = $language->courseInformation()->get();

      if (count($courseInfos) > 0) {
        foreach ($courseInfos as $courseData) {
          $courseInfo = $courseData;

          // get all 'modules' of each course-info & delete them
          $modules = $courseInfo->module()->get();

          if (count($modules) > 0) {
            foreach ($modules as $module) {
              // get all 'lessons' of each module & delete them
              $lessons = $module->lesson()->get();

              if (count($lessons) > 0) {
                foreach ($lessons as $lesson) {
                  // get all 'lesson contents' of each lesson & delete them by checking the 'type'
                  $lessonContents = $lesson->content()->get();

                  if (count($lessonContents) > 0) {
                    foreach ($lessonContents as $lessonContent) {
                      if ($lessonContent->type == 'video') {
                        @unlink('video/' . $lessonContent->video_unique_name);
                      } else if ($lessonContent->type == 'file') {
                        @unlink('file/lesson-contents/' . $lessonContent->file_unique_name);
                      } else if ($lessonContent->type == 'quiz') {
                        // get all 'lesson quizzes' of this lesson-content & delete them
                        $lessonQuizzes = $lessonContent->quiz()->get();

                        if (count($lessonQuizzes) > 0) {
                          foreach ($lessonQuizzes as $lessonQuiz) {
                            $lessonQuiz->delete();
                          }
                        }
                      }

                      $lessonContent->delete();
                    }
                  }

                  $lesson->delete();
                }
              }

              $module->delete();
            }
          }

          $courseData->delete();

          // get all the 'course faqs' of this language & delete them
          $courseFaqs = $language->courseFaq()->where('course_id', '=', $courseInfo->course_id)->get();

          if (count($courseFaqs) > 0) {
            foreach ($courseFaqs as $courseFaq) {
              $courseFaq->delete();
            }
          }

          // delete the course if, this course does not contain any other course information in any other language
          $otherCourseInfos = CourseInformation::query()->where('language_id', '<>', $language->id)->where('course_id', '=', $courseInfo->course_id)->get();

          if (count($otherCourseInfos) == 0) {
            $course = Course::query()->find($courseInfo->course_id);

            // get all the 'course enrolments' of this course & delete them
            $enrolments = $course->enrolment()->get();

            if (count($enrolments) > 0) {
              foreach ($enrolments as $enrolment) {
                @unlink('file/attachments/' . $enrolment->attachment);
                @unlink('file/invoices/' . $enrolment->invoice);

                $enrolment->delete();
              }
            }

            // get all the 'reviews' of this course & delete them
            $reviews = $course->review()->get();

            if (count($reviews) > 0) {
              foreach ($reviews as $review) {
                $review->delete();
              }
            }

            // get all the 'quiz scores' of this course & delete them
            $quizScores = $course->quizScore()->get();

            if (count($quizScores) > 0) {
              foreach ($quizScores as $quizScore) {
                $quizScore->delete();
              }
            }

            @unlink('img/courses/thumbnails/' . $course->thumbnail_image);
            @unlink('img/courses/covers/' . $course->cover_image);

            // finally delete the course
            $course->delete();
          }
        }
      }

      /**
       * delete 'course categories' info
       */
      $courseCategories = $language->courseCategory()->get();

      if (count($courseCategories) > 0) {
        foreach ($courseCategories as $courseCategory) {
          $courseCategory->delete();
        }
      }

      /**
       * delete 'faqs' info
       */
      $faqs = $language->faq()->get();

      if (count($faqs) > 0) {
        foreach ($faqs as $faq) {
          $faq->delete();
        }
      }

      /**
       * delete 'features' info
       */
      $features = $language->feature()->get();

      if (count($features) > 0) {
        foreach ($features as $feature) {
          $feature->delete();
        }
      }

      /**
       * delete 'footer content' info
       */
      $footerContent = $language->footerContent()->first();

      if (!empty($footerContent)) {
        $footerContent->delete();
      }

      /**
       * delete 'fun fact section' info
       */
      $funFactSec = $language->funFactSec()->first();

      if (!empty($funFactSec)) {
        @unlink('img/fact-section/' . $funFactSec->background_image);
        $funFactSec->delete();
      }

      /**
       * delete 'hero section' info
       */
      $heroSec = $language->heroSec()->first();

      if (!empty($heroSec)) {
        @unlink('img/hero-section/' . $heroSec->background_image);
        @unlink('img/hero-section/' . $heroSec->image);
        $heroSec->delete();
      }

      /**
       * delete 'instructors' info
       */
      $instructors = $language->instructor()->get();

      if (count($instructors) > 0) {
        foreach ($instructors as $instructor) {
          // delete all 'social links' of each instructor
          $socialLinks = $instructor->socialPlatform()->get();

          if (count($socialLinks) > 0) {
            foreach ($socialLinks as $socialLink) {
              $socialLink->delete();
            }
          }

          @unlink('img/instructors/' . $instructor->image);
          $instructor->delete();
        }
      }

      /**
       * delete 'menu builders' info
       */
      $menuInfo = $language->menuInfo()->first();

      if (!empty($menuInfo)) {
        $menuInfo->delete();
      }

      /**
       * delete 'newsletter section' info
       */
      $newsletterSec = $language->newsletterSec()->first();

      if (!empty($newsletterSec)) {
        @unlink('img/newsletter-section/' . $newsletterSec->background_image);
        @unlink('img/newsletter-section/' . $newsletterSec->image);
        $newsletterSec->delete();
      }

      /**
       * delete 'page contents'
       */
      $customPageInfos = $language->customPageInfo()->get();

      if (count($customPageInfos) > 0) {
        foreach ($customPageInfos as $customPageData) {
          $customPageInfo = $customPageData;
          $customPageData->delete();

          // delete the 'custom page' if, this page does not contain any other page content in any other language
          $otherPageContents = PageContent::query()->where('language_id', '<>', $language->id)->where('page_id', '=', $customPageInfo->page_id)->get();

          if (count($otherPageContents) == 0) {
            $page = Page::query()->find($customPageInfo->page_id);
            $page->delete();
          }
        }
      }

      /**
       * delete 'page heading' info
       */
      $pageHeadingInfo = $language->pageName()->first();

      if (!empty($pageHeadingInfo)) {
        $pageHeadingInfo->delete();
      }

      /**
       * delete 'popup' infos
       */
      $popups = $language->announcementPopup()->get();

      if (count($popups) > 0) {
        foreach ($popups as $popup) {
          @unlink('img/popups/' . $popup->image);
          $popup->delete();
        }
      }

      /**
       * delete 'quick links'
       */
      $quickLinks = $language->footerQuickLink()->get();

      if (count($quickLinks) > 0) {
        foreach ($quickLinks as $quickLink) {
          $quickLink->delete();
        }
      }

      /**
       * delete 'section title' info
       */
      $sectionTitleInfo = $language->sectionTitle()->first();

      if (!empty($sectionTitleInfo)) {
        $sectionTitleInfo->delete();
      }

      /**
       * delete 'seo' info
       */
      $seoInfo = $language->seoInfo()->first();

      if (!empty($seoInfo)) {
        $seoInfo->delete();
      }

      /**
       * delete 'testimonials'
       */
      $testimonials = $language->testimonial()->get();

      if (count($testimonials) > 0) {
        foreach ($testimonials as $testimonial) {
          @unlink('img/clients/' . $testimonial->image);
          $testimonial->delete();
        }
      }

      /**
       * delete 'video section' info
       */
      $videoSec = $language->videoSec()->first();

      if (!empty($videoSec)) {
        @unlink('img/video-images/' . $videoSec->image);
        $videoSec->delete();
      }

      // delete the language json file
      @unlink(resource_path('lang/') . $language->code . '.json');

      // finally, delete the language info from db
      $language->delete();

      return redirect()->back()->with('success', 'Language deleted successfully!');
    }
  }

  /**
   * Check the specified language is RTL or not.
   *
   * @param int $id
   * @return Response
   */
  public function checkRTL($id)
  {
    if (!is_null($id)) {
      $direction = Language::where('id', $id)->pluck('direction')->first();

      return response()->json(['successData' => $direction], 200);
    } else {
      return response()->json(['errorData' => 'Sorry, an error has occured!'], 400);
    }
  }
}
