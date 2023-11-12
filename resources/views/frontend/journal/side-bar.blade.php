<div class="col-lg-4 col-md-6 col-sm-8">
    <div class="blog-sidebar ml-10">
        <div class="blog-side-about white-bg mt-40">
            <div class="about-title">
                <h4 class="title">{{ __('Search Blog') }}</h4>
            </div>
            <div class="blog-Search-content text-center">
                <form action="{{ route('blogs') }}" method="GET">
                    <div class="input-box">
                        <input type="text" class="rounded-pill" placeholder="{{ __('Search By Title') }}" name="title"
                               value="{{ !empty(request()->input('title')) ? request()->input('title') : '' }}">
                        <input type="hidden" name="category"
                               value="{{ !empty(request()->input('category')) ? request()->input('category') : '' }}">
                        <button type="submit" class="rounded-pill"><i class="far fa-search"></i></button>
                    </div>
                </form>
            </div>
        </div>


    </div>

    {{-- search form start --}}
    <form class="d-none" action="{{ route('blogs') }}" method="GET">
        <input type="hidden" class="rounded-pill" name="title"
               value="{{ !empty(request()->input('title')) ? request()->input('title') : '' }}">

        <input type="hidden" id="categoryKey" name="category">

        <button type="submit" id="submitBtn" class="rounded-pill"></button>
    </form>
    {{-- search form end --}}
</div>
