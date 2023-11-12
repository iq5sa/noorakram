@extends('frontend.layout')

@section('pageHeading')
    @if (!empty($pageHeading))
        {{ $details->title ?? 'Blog Details' }}
    @endif
@endsection

@section('metaKeywords')
    {{ $details->meta_keywords }}
@endsection

@section('metaDescription')
    {{ $details->meta_description }}
@endsection

@section('content')
    @includeIf('frontend.partials.breadcrumb', ['img' => $breadcrumbImg, 'title' => $details->title ?? 'Blog Details'])

    <!--====== BLOG DETAILS PART START ======-->
    <section class="blog-standard-area gray-bg pt-80 pb-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="blog-standard-2">
                        <div class="single-blog-standard mt-40">
                            <div class="blog-dteails-content bg-white">
                                <div class="blog-details-bath pb-40">
                                    <img data-src="{{ asset('/img/blogs/' . $details->image) }}" class="lazy"
                                         alt="image">
                                </div>

                                <div class="blog-details-top">
                                    <span>{{ $details->categoryName }}</span>
                                    <h2 class="title">{{ $details->title }}</h2>
                                    <ul class="my-3">
                                        <li>
                                            <i class="fal fa-calendar-alt text-dark"></i> {{ date_format($details->created_at, 'M d, Y') }}
                                        </li>
                                    </ul>
                                    <div class="summernote-content">
                                        {!! replaceBaseUrl($details->content, 'summernote') !!}
                                    </div>
                                </div>

                                <div class="blog-details-bar d-flex justify-content-between mt-40 mb-50">
                                    <div class="blog-social">
                                        <h4 class="title">{{ __('Share') }}</h4>
                                        <ul>
                                            <li>
                                                <a href="//www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"><i
                                                            class="fab fa-facebook-f"></i></a></li>
                                            <li><a
                                                        href="//twitter.com/intent/tweet?text=my share text&amp;url={{ urlencode(url()->current()) }}"><i
                                                            class="fab fa-twitter"></i></a></li>
                                            <li><a
                                                        href="//www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(url()->current()) }}&amp;title={{ $details->title }}"><i
                                                            class="fab fa-linkedin"></i></a></li>
                                        </ul>
                                    </div>
                                </div>


                                <div class="blog-details-related-post mt-45">
                                    <h4 class="title">{{ __('Related Blog') }}</h4>
                                    @if (count($relatedBlogs) == 0)
                                        <div class="row text-center">
                                            <div class="col">
                                                <h5 class="mt-40">{{ __('No Related Blog Found') . '!' }}</h5>
                                            </div>
                                        </div>
                                    @else
                                        <div class="row">
                                            @foreach ($relatedBlogs as $relatedBlog)
                                                <div class="col-lg-6 col-md-6">
                                                    <div class="blog-details-related-item mt-30">
                                                        <div class="related-thumb">
                                                            <img data-src="{{ asset('/img/blogs/' . $relatedBlog->image) }}"
                                                                 class="lazy"
                                                                 alt="image">
                                                        </div>
                                                        <div class="related-content">
                                                            <span><i class="fal fa-calendar-alt"></i> {{ date_format($relatedBlog->created_at, 'M d, Y') }}</span>
                                                            <a class="d-block"
                                                               href="{{ route('blog_details', ['slug' => $relatedBlog->slug]) }}">
                                                                <h4
                                                                        class="title">{{ strlen($relatedBlog->title) > 30 ? mb_substr($relatedBlog->title, 0, 30, 'UTF-8') . '...' : $relatedBlog->title }}</h4>
                                                            </a>
                                                            <p>{!! strlen(strip_tags($relatedBlog->content)) > 100 ? mb_substr(strip_tags($relatedBlog->content), 0, 100, 'UTF-8') . '...' : strip_tags($relatedBlog->content) !!}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>


                                @if ($disqusInfo->disqus_status == 1)
                                    <div id="disqus_thread" class="mt-45"></div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @includeIf('frontend.journal.side-bar')
            </div>
        </div>
    </section>
    <!--====== BLOG DETAILS PART END ======-->
@endsection

@section('script')
    <script>
        "use strict";
        const shortName = '{{ $disqusInfo->disqus_short_name }}';
    </script>

    <script type="text/javascript" src="{{ asset('/js/blog.js') }}"></script>
@endsection
