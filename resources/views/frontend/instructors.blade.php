@extends('frontend.layout')

@section('pageHeading')
    @if (!empty($pageHeading))
        {{ $pageHeading->instructors_page_title ?? 'Instructors' ?? 'Instructors' }}
    @endif
@endsection

@section('metaKeywords')
    @if (!empty($seoInfo))
        {{ $seoInfo->meta_keyword_instructors }}
    @endif
@endsection

@section('metaDescription')
    @if (!empty($seoInfo))
        {{ $seoInfo->meta_description_instructors }}
    @endif
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('/asset/css/summernote-content.css') }}">
@endsection

@section('content')
    @includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $bgImg->breadcrumb, 'title' => $pageHeading->instructors_page_title ?? 'Instructors'])

    <!--====== INSTRUCTORS PART START ======-->
    <section class="speakers-area pt-90 pb-90">
        <div class="container">
            <div class="row">
                @if (count($instructors) == 0)
                    <div class="col">
                        <h3 class="text-center">{{ __('No Instructor Found') . '!' }}</h3>
                    </div>
                @else
                    @foreach ($instructors as $instructor)
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="single-speakers mt-30">
                                <div class="speakers-thumb">
                                    <img data-src="{{ asset('/img/instructors/' . $instructor->image) }}" class="lazy"
                                         alt="image">
                                    <a href="#" data-toggle="modal"
                                       data-target="{{ '#staticBackdrop-' . $instructor->id }}"><i
                                                class="fas fa-plus"></i></a>
                                </div>
                                <div class="speakers-content text-center">
                                    <span>{{ $instructor->occupation }}</span>
                                    <h4 class="title">{{ $instructor->name }}</h4>
                                </div>
                            </div>
                        </div>

                        <!-- Modal -->
                        <div class="modal fade instructor-modal" id="{{ 'staticBackdrop-' . $instructor->id }}"
                             data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                             aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title"
                                            id="staticBackdropLabel">{{ __('Information of') . ' ' . $instructor->name }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="summernote-content">
                                            {!! replaceBaseUrl($instructor->description, 'summernote') !!}
                                        </div>

                                        @php $socials = $instructor->socials; @endphp

                                        @if (count($socials) > 0)
                                            <h5 class="my-3">{{ __('Follow Me') . ':' }}</h5>
                                            <div class="btn-group" role="group" aria-label="Social Links">
                                                @foreach ($socials as $social)
                                                    <a href="{{ $social->url }}" class="btn social-link-btn mr-2"
                                                       target="_blank"><i
                                                                class="{{ $social->icon }}"></i></a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">{{ __('Close') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>


        </div>
    </section>
    <!--====== INSTRUCTORS PART END ======-->
@endsection
