@php use Illuminate\Support\Str; @endphp
@extends('frontend.layout')

@section('pageHeading')
  {{ __('Home') }}
@endsection

@section('metaKeywords')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_keyword_home }}
  @endif
@endsection

@section('metaDescription')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_description_home }}
  @endif
@endsection

@section('style')

  <style>
    .community-area::before {
      background-image: url('https://noorakram.b-cdn.net/img/hero-section/mobile-sized.png');
    }
  </style>
@endsection

@section('content')
  <!--====== BANNER PART START ======-->
  <section class="banner-area bg_cover lazy"
           data-bg="https://noorakram.b-cdn.net/img/hero-section/hero-1-min.png">
    <div class="container banner-container">
      <div class="row justify-content-center">
        <div class="col-lg-12 col-md-8 col-sm-8 col-7">
          <div class="banner-content text-center">
            <div class="btn-group-lg d-flex flex-column d-md-block " role="group"
                 aria-label="banner buttons group">
              <a class="btn btn-primary rounded-pill mb-3 mb-md-0 "
                 href="{{route("courses")}}">
                                <span>
                                    <i class="far fa-shopping-cart"></i>
                                </span>
                <span>
                                  الدورات
                                </span>
              </a>


              <a class="btn btn-secondary  rounded-pill mr-md-5 mr-0 "
                 href="{{route("appointment.index")}}">
                               <span>
                                <i class="far fa-calendar"></i>
                               </span>
                <span>
                                حجز موعد
                              </span>
              </a>
            </div>

          </div>
        </div>
      </div>
    </div>
  </section>

  <!--====== COURSES PART START ======-->
  <section class="advance-courses-area pb-100 pt-5">
    <div class="container">
      <div class="row">
        <div class="col-lg-7">
          <div class="section-title">
            <h3
              class="title">{{ !empty($secTitleInfo->featured_courses_section_title) ? $secTitleInfo->featured_courses_section_title : '' }}</h3>
          </div>
        </div>
      </div>

      @if (count($courses) == 0)
        <div class="row text-center">
          <div class="col">
            <h3>{{ __('No Featured Course Found') . '!' }}</h3>
          </div>
        </div>
      @else
        <div class="courses-active">
          @foreach ($courses as $course)
            @include("frontend.partials.single-course")
          @endforeach
        </div>

      @endif
    </div>
  </section>
  <!--====== COURSES PART END ======-->

  <!--====== TESTIMONIALS PART START ======-->
  <section class="testimonials-area pb-120 bg_cover lazy entered loaded"
           data-bg="{{ asset('img/testimonials-bg-section.png')}}">
    <div class="container">
      @if (count($testimonials) == 0)
        <div class="row text-center">
          <div class="col">
            <h3>{{ __('No Testimonial Found') . '!' }}</h3>
          </div>
        </div>
      @else
        <div class="row testimonials-active">
          @foreach ($testimonials as $testimonial)
            <div class="col-lg-12">
              <div class="testimonials-content text-center">
                <i class="fas fa-quote-left"></i>
                <p>{{ Str::substr($testimonial->comment,0,200) . '..' }}</p>
                <img data-lazy="{{ asset('img/client-icon.png') }}"
                     class="lazy" alt="client">
                <h5>{{ $testimonial->name }}</h5>
                <span>{{ $testimonial->occupation }}</span>
              </div>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </section>
  <!--====== TESTIMONIALS PART END ======-->

  <!--======NEWSLETTER PART START ======-->
  <section class="community-area pt-50">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 col-md-12">
          <div class="community-content">
            <h3 class="title">أكاديميه نور اكرم</h3>
            <p class="mt-3 text-justify">
              نعمل هنا على تسهيل عملية حصولك على المعارف التي تخدم رحلتك الارضية ودورك الحالي كام او اب..
              نور اكرم هي امراة عراقية ولدت ببيئة نسيجها التربوي يعتمد على التخويف والطاعة.. تعرضها لمختلف
              انواع التجارب التي تهمش دورها كأنثى كان الدافع الاكبر لها لتدخل برحلة التفكر عن ماهية
              التحسين.. بسن التاسع عشر اصبحت ام لاول مرة لتكتشف هنا ان جذر تحسين حياة كل منا هو الوعي على
              اساليب تربوية تخدم البشرية.. درست بمجال الطفل لعشر سنوات واضافت خبرتها مع اطفالها الاثنين
              تجربة حقيقة ودعم واقعي لما تعلمته من الدراسة خارج بلدها الام. وبالتالي نجتمع هنا لنلف حول
              قيمة حقيقة كفيلة بتغيير حياة اطفالنا بتمكينهم على الارض من خلالك. كل برنامج ستجده على هذه
              المنصة وضع من رغبة عميقة لتمكينك وجعلك القائد الذي يلهم اطفاله للقيادة ... نحن نؤمن ان
              التحسين يبدأ الان ومن خلالك


            </p>

            <form class="subscriptionForm" action="{{ route('store_subscriber') }}" method="POST">
              @csrf
              <div class="input-box">
                <input type="email" placeholder="{{ __('Enter Your Email Address') }}"
                       name="email_id">
                <button type="submit"
                        class="btn btn-primary rounded-pill btn-lg px-3">{{ __('Subscribe') }}</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--======NEWSLETTER PART END ======-->
@endsection
