<?php

namespace App\Http\Controllers\BackEnd\Curriculum;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Http\Requests\Course\StoreRequest;
use App\Http\Requests\Course\UpdateRequest;
use App\Models\Curriculum\Course;
use App\Models\Curriculum\CourseInformation;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;

class CourseController extends Controller
{

    public function index(Request $request)
    {
        $information['langs'] = Language::all();

        $language = Language::where('code', $request->language)->first();
        $information['language'] = $language;

        $courses = Course::join('course_informations', 'courses.id', '=', 'course_informations.course_id')
            ->join('instructors', 'course_informations.instructor_id', '=', 'instructors.id')
            ->join('course_categories', 'course_categories.id', '=', 'course_informations.course_category_id')
            ->where('course_informations.language_id', '=', $language->id)
            ->select('courses.*', 'course_informations.id as courseInfoId', 'course_informations.title', 'instructors.name as instructorName', 'course_categories.name as category')
            ->orderByDesc('courses.id')
            ->get();

        $courses->map(function ($course) use ($language) {
            $courseInfo = $course->information()->where('language_id', $language->id)->first();
            if (empty($courseInfo)) {
                $language = Language::where('is_default', 1)->first();
                $courseInfo = $course->information()->where('language_id', $language->id)->first();
            }

            $module = $courseInfo->module()->where('status', 'published')->first();
            $lesson = !empty($module) ? $module->lesson()->where('status', 'published')->first() : NULL;
            $course['lesson_id'] = !empty($lesson) ? $lesson->id : NULL;
        });

        $information['courses'] = $courses;

        $information['currencyInfo'] = $this->getCurrencyInfo();

        return view('backend.curriculum.course.index', $information);
    }

    /**
     * Update status (draft/published) of a specified resource.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id)
    {
        $course = Course::find($id);

        $course->update([
            'status' => $request['status']
        ]);
        $request->session()->flash('success', 'Course status updated successfully!');

        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        $course = Course::find($id);

        // store new thumbnail image in storage
        if ($request->hasFile('thumbnail_image')) {
            $thumbImgName = UploadFile::update('./img/courses/thumbnails/', $request->file('thumbnail_image'), $course->thumbnail_image);
        }


        // store new cover image in storage
        if ($request->hasFile('cover_image')) {
            $coverImgName = UploadFile::update('./img/courses/covers/', $request->file('cover_image'), $course->cover_image);
        }
        if ($request->hasFile("video_intro")) {
            $uploadVideoIntro = UploadFile::store('./video/', $request->file('video_intro'));

        }

        // update data in db
        $course->update($request->except('thumbnail_image', 'video_link', 'cover_image') + [
                'thumbnail_image' => $request->hasFile('thumbnail_image') ? $thumbImgName : $course->thumbnail_image,
                'video_intro' => $request->hasFile("video_intro") ? $uploadVideoIntro : $course->video_intro,
                'cover_image' => $request->hasFile('cover_image') ? $coverImgName : $course->cover_image
            ]);

        $languages = Language::all();

        foreach ($languages as $language) {

            CourseInformation::updateOrCreate([
                'course_id' => $id,
                'language_id' => $language->id
            ], [
                'course_category_id' => $request[$language->code . '_category_id'],
                'title' => $request[$language->code . '_title'],
                'slug' => createSlug($request[$language->code . '_title']),
                'instructor_id' => $request[$language->code . '_instructor_id'],
                'features' => $request[$language->code . '_features'],
                'description' => Purifier::clean($request[$language->code . '_description']),
                'meta_keywords' => $request[$language->code . '_meta_keywords'],
                'meta_description' => $request[$language->code . '_meta_description']
            ]);
        }

        $request->session()->flash('success', 'Course updated successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        // store thumbnail image in storage
        $thumbImgName = UploadFile::store('./img/courses/thumbnails/', $request->file('thumbnail_image'));

        // format video link
//    $link = $request['video_link'];

//    if (strpos($link, '&') != 0) {
//      $link = substr($link, 0, strpos($link, '&'));
//    }

        // store cover image in storage
        $coverImgName = UploadFile::store('./img/courses/covers/', $request->file('cover_image'));
        $uploadVideoIntro = UploadFile::store('./video/', $request->file('video_intro'));

        // store data in db
        $course = Course::create($request->except('thumbnail_image', 'video_link', 'cover_image') + [
                'thumbnail_image' => $thumbImgName,
                'video_intro' => $uploadVideoIntro,
                'cover_image' => $coverImgName
            ]);

        $languages = Language::all();

        foreach ($languages as $language) {
            $courseInformation = new CourseInformation();
            $courseInformation->language_id = $language->id;
            $courseInformation->course_category_id = $request[$language->code . '_category_id'];
            $courseInformation->course_id = $course->id;
            $courseInformation->title = $request[$language->code . '_title'];
            $courseInformation->slug = createSlug($request[$language->code . '_title']);
            $courseInformation->instructor_id = $request[$language->code . '_instructor_id'];
            $courseInformation->features = $request[$language->code . '_features'];
            $courseInformation->description = Purifier::clean($request[$language->code . '_description']);
            $courseInformation->meta_keywords = $request[$language->code . '_meta_keywords'];
            $courseInformation->meta_description = $request[$language->code . '_meta_description'];
            $courseInformation->save();
        }

        $request->session()->flash('success', 'New course added successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // get all the languages from db
        $languages = Language::all();

        return view('backend.curriculum.course.create', compact('languages'));
    }

    /**
     * Update featured status of a specified resource.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function updateFeatured(Request $request, $id)
    {
        $course = Course::find($id);

        if ($request['is_featured'] == 'yes') {
            $course->update(['is_featured' => 'yes']);

            $request->session()->flash('success', 'Course featured successfully!');
        } else {
            $course->update(['is_featured' => 'no']);

            $request->session()->flash('success', 'Course unfeatured successfully!');
        }

        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $information['course'] = Course::find($id);

        $information['languages'] = Language::all();

        return view('backend.curriculum.course.edit', $information);
    }

    /**
     * Show the form for editing the thanks page of a specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function thanksPage($id)
    {
        $information['course'] = Course::find($id);

        $information['language'] = Language::where('is_default', 1)->first();

        $information['languages'] = Language::all();

        return view('backend.curriculum.course.thanks-page', $information);
    }

    /**
     * Update the thanks page of specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function updateThanksPage(Request $request, $id)
    {
        $languages = Language::all();

        $rules = $messages = [];

        foreach ($languages as $language) {
            $rules[$language->code . '_thanks_page_content'] = 'min:30';

            $messages[$language->code . '_thanks_page_content.min'] = 'The content must be at least 30 characters for ' . $language->name . ' language.';
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        foreach ($languages as $language) {
            $courseInfo = CourseInformation::where('course_id', $id)
                ->where('language_id', $language->id)
                ->first();

            $courseInfo->update([
                'thanks_page_content' => Purifier::clean($request[$language->code . '_thanks_page_content'])
            ]);
        }

        $request->session()->flash('success', 'Page content updated successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    /**
     * Show the certificate settings page of a specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function certificateSettings($id)
    {
        $information['course'] = Course::find($id);

        $information['language'] = Language::where('is_default', 1)->first();

        return view('backend.curriculum.course.certificate-settings', $information);
    }

    /**
     * Update the certificate settings of specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function updateCertificateSettings(Request $request, $id)
    {
        $course = Course::find($id);

        $course->update($request->except('certificate_text') + [
                'certificate_text' => Purifier::clean($request['certificate_text'])
            ]);

        $request->session()->flash('success', 'Certificate settings updated successfully.');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $course = Course::find($id);

        // check whether this course has any enrolment or not
        $totalEnrolment = $course->enrolment()->count();

        if ($totalEnrolment > 0) {
            return redirect()->back()->with('warning', 'First delete all the enrolments of this course!');
        }

        // get all the course informations of this course
        $courseInformations = $course->information()->get();

        foreach ($courseInformations as $courseInformation) {
            // get all the modules of each course-information
            $modules = $courseInformation->module()->get();

            foreach ($modules as $module) {
                // get all the lessons of each module
                $lessons = $module->lesson()->get();

                foreach ($lessons as $lesson) {
                    // get all the lesson contents of each lesson
                    $lessonContents = $lesson->content()->get();

                    foreach ($lessonContents as $lessonContent) {
                        // delete lesson content item by checking the 'type'
                        if ($lessonContent->type == 'video') {
                            @unlink('./video/' . $lessonContent->video_unique_name);
                        } else if ($lessonContent->type == 'file') {
                            @unlink('./file/lesson-contents/' . $lessonContent->file_unique_name);
                        } else if ($lessonContent->type == 'quiz') {
                            // get all the lesson quizzes of this lesson-content
                            $lessonQuizzes = $lessonContent->quiz()->get();

                            foreach ($lessonQuizzes as $lessonQuiz) {
                                $lessonQuiz->delete();
                            }
                        }

                        $lessonContent->delete();
                    }

                    $lesson->delete();
                }

                $module->delete();
            }

            $courseInformation->delete();
        }

        @unlink('./img/courses/thumbnails/' . $course->thumbnail_image);

        @unlink('./img/courses/covers/' . $course->cover_image);

        // get all the faqs of this course
        $courseFaqs = $course->faq()->get();

        foreach ($courseFaqs as $courseFaq) {
            $courseFaq->delete();
        }

        // get all the reviews of this course
        $reviews = $course->review()->get();

        foreach ($reviews as $review) {
            $review->delete();
        }

        // get all the quiz-scores of this course
        $quizScores = $course->quizScore()->get();

        foreach ($quizScores as $quizScore) {
            $quizScore->delete();
        }

        // finally delete the course
        $course->delete();

        return redirect()->back()->with('success', 'Course deleted successfully!');
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
            $course = Course::find($id);

            // check whether this course has any enrolment or not
            $totalEnrolment = $course->enrolment()->count();

            if ($totalEnrolment > 0) {
                $request->session()->flash('warning', 'First delete all the enrolments of selected courses!');

                return Response::json(['status' => 'success'], 200);
            }

            // get all the course informations of this course
            $courseInformations = $course->information()->get();

            foreach ($courseInformations as $courseInformation) {
                // get all the modules of each course-information
                $modules = $courseInformation->module()->get();

                foreach ($modules as $module) {
                    // get all the lessons of each module
                    $lessons = $module->lesson()->get();

                    foreach ($lessons as $lesson) {
                        // get all the lesson contents of each lesson
                        $lessonContents = $lesson->content()->get();

                        foreach ($lessonContents as $lessonContent) {
                            // delete lesson content item by checking the 'type'
                            if ($lessonContent->type == 'video') {
                                @unlink('./video/' . $lessonContent->video_unique_name);
                            } else if ($lessonContent->type == 'file') {
                                @unlink('./file/lesson-contents/' . $lessonContent->file_unique_name);
                            } else if ($lessonContent->type == 'quiz') {
                                // get all the lesson quizzes of this lesson-content
                                $lessonQuizzes = $lessonContent->quiz()->get();

                                foreach ($lessonQuizzes as $lessonQuiz) {
                                    $lessonQuiz->delete();
                                }
                            }

                            $lessonContent->delete();
                        }

                        $lesson->delete();
                    }

                    $module->delete();
                }

                $courseInformation->delete();
            }

            @unlink('./img/courses/thumbnails/' . $course->thumbnail_image);

            @unlink('./img/courses/covers/' . $course->cover_image);

            // get all the faqs of this course
            $courseFaqs = $course->faq()->get();

            foreach ($courseFaqs as $courseFaq) {
                $courseFaq->delete();
            }

            // get all the reviews of this course
            $reviews = $course->review()->get();

            foreach ($reviews as $review) {
                $review->delete();
            }

            // get all the quiz-scores of this course
            $quizScores = $course->quizScore()->get();

            foreach ($quizScores as $quizScore) {
                $quizScore->delete();
            }

            // finally delete the course
            $course->delete();
        }

        $request->session()->flash('success', 'Courses deleted successfully!');

        return Response::json(['status' => 'success'], 200);
    }
}
