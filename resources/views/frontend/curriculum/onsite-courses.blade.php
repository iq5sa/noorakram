@extends('frontend.layout')

@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading}}
  @endif
@endsection

@section('metaKeywords')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_keyword_courses }}
  @endif
@endsection

@section('metaDescription')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_description_courses }}
  @endif
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', ['img' => $breadcrumbImg, 'title' => "الدورات الحضورية"])

  <section class="course-grid-area pt-10 pb-120 courses-page">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-12">
          <div class="course-grid mt-30">
            <div class="row mb-5">
              <div class="col-md-12 col-lg-8 col-sm-12 col-12 mb-sm-3 mb-lg-0">
                <form method="get" id="searchForm">
                  <div class="input-box">
                    <label for="searchInput" class="form-control-label">البحث في الدورات</label>
                    <div class="input-group">
                      <div class="input-group-append">
                                            <span style="cursor: pointer" class="input-group-text"
                                                  onclick="submitSearch()">
                                                <i class="fa fa-search"></i>
                                            </span>

                      </div>
                      <input required type="text" class="form-control" name="keyword"
                             placeholder="اكتب عبارة البحث"
                             aria-label="ابحث عن الدورة" aria-describedby="button-addon2">

                    </div>
                  </div>
                </form>


              </div>


              <div class="col-md-12 col-lg-4 col-sm-12 col-12 ">
                <div class="input-box">
                  <label for="sort-type" class="label">فلتر البحث</label>
                  <select id="sort-type" class="nice-select custom-select">
                    <option value="new" selected>دورة جديدة</option>
                    <option {{ request()->input('sort') == 'old' ? 'selected' : '' }} value="old">
                      {{ __('Old Course') }}
                    </option>
                    <option {{ request()->input('sort') == 'ascending' ? 'selected' : '' }} value="ascending">
                      {{ __('Price') . ': ' . __('Ascending') }}
                    </option>
                    <option {{ request()->input('sort') == 'descending' ? 'selected' : '' }} value="descending">
                      {{ __('Price') . ': ' . __('Descending') }}
                    </option>
                  </select>
                </div>
              </div>

            </div>

            <div class="row mt-5">
              @if ($courses->count() == 0)
                <div class="col-lg-12">
                  <h3 class="text-center mt-30">{{ __('No Course Found') . '!' }}</h3>
                  <div class="text-center mt-5">
                    <a href="{{route('courses')}}"
                       class="btn btn-outline-primary">رجوع</a>
                  </div>

                </div>
              @else
                @foreach ($courses as $course)
                  <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                    @include("frontend.partials.single-course")
                  </div>
                @endforeach
              @endif

              <div class="col-lg-12">


              </div>


            </div>
          </div>


        </div>
      </div>
    </div>
  </section>
@endsection
