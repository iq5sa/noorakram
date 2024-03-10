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

@if(isset($_GET["type"]) && $_GET["type"] === "onsite")
  @php $pageHeading = "الدورات الحضورية" @endphp

@endif
@section('content')
  @includeIf('frontend.partials.breadcrumb', ['img' => $breadcrumbImg, 'title' => $pageHeading ?? 'Courses'])

  <!--====== COURSES PART START ======-->
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

                        {{-- button class="btn btn-outline-primary" type="button" id="button-addon2"></button>--}}
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
                @if ($courses->count() > 0)
                  {{ $courses->appends([
                    'type' => request()->input('type'),
                    'category' => request()->input('category'),
                    'min' => request()->input('min'),
                    'max' => request()->input('max'),
                    'keyword' => request()->input('keyword'),
                    'sort' => request()->input('sort')
                  ])->links() }}
                @endif

              </div>


            </div>
          </div>


        </div>
      </div>
    </div>
  </section>
  <!--====== COURSES PART END ======-->

  <form id="filtersForm" class="d-none" action="{{ route('courses') }}" method="GET">
    <input type="hidden" id="type-id" name="type"
           value="{{ !empty(request()->input('type')) ? request()->input('type') : '' }}">

    <input type="hidden" id="category-id" name="category"
           value="{{ !empty(request()->input('category')) ? request()->input('category') : '' }}">

    <input type="hidden" id="min-id" name="min"
           value="{{ !empty(request()->input('min')) ? request()->input('min') : '' }}">

    <input type="hidden" id="max-id" name="max"
           value="{{ !empty(request()->input('max')) ? request()->input('max') : '' }}">

    <input type="hidden" id="keyword-id" name="keyword"
           value="{{ !empty(request()->input('keyword')) ? request()->input('keyword') : '' }}">

    <input type="hidden" id="sort-id" name="sort"
           value="{{ !empty(request()->input('sort')) ? request()->input('sort') : '' }}">

    <button type="submit" id="submitBtn"></button>
  </form>
@endsection

@section('script')
  <script>
    let searchForm = $("#searchForm");

    searchForm.on("keyup", "form", function (event) {
      if (event.keyCode === 13) {
        event.preventDefault();

        $(this).submit();
      }
    });

    function submitSearch() {
      searchForm.submit();
    }

    "use strict";
    let currency_info = {!! json_encode($currencyInfo) !!};
    let position = currency_info.base_currency_symbol_position;
    let symbol = currency_info.base_currency_symbol;
    let min_price = {!! htmlspecialchars($minPrice) !!};
    let max_price = {!! htmlspecialchars($maxPrice) !!};
    let curr_min = {!! !empty(request()->input('min')) ? htmlspecialchars(request()->input('min')) : htmlspecialchars($minPrice) !!};
    let curr_max = {!! !empty(request()->input('max')) ? htmlspecialchars(request()->input('max')) : htmlspecialchars($maxPrice) !!};
  </script>

  <script type="text/javascript" src="{{ asset('/js/course.js') }}"></script>

@endsection
