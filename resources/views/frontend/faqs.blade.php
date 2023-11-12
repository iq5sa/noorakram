@extends('frontend.layout')

@section('pageHeading')
    @if (!empty($pageHeading))
        {{ $pageHeading->faq_page_title ?? 'F.A.Q' }}
    @endif
@endsection

@section('metaKeywords')
    @if (!empty($seoInfo))
        {{ $seoInfo->meta_keyword_faq }}
    @endif
@endsection

@section('metaDescription')
    @if (!empty($seoInfo))
        {{ $seoInfo->meta_description_faq }}
    @endif
@endsection

@section('content')
    @includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $bgImg->breadcrumb, 'title' => $pageHeading->faq_page_title ?? 'F.A.Q'])

    <!--====== FAQ PART START ======-->
    <section class="faq-area pb-120">
        <div class="container">
            <div class="row">
                <div class="col">
                    @if (count($faqs) == 0)
                        <h3 class="text-center">{{ __('No FAQ Found') . '!' }}</h3>
                    @else
                        <div class="faq-accordion">
                            <div class="accordion" id="accordionExample">
                                @foreach ($faqs as $faq)
                                    <div class="card">
                                        <div class="card-header" id="{{ 'heading-' . $faq->id }}">
                                            <a class="{{ $loop->first ? '' : 'collapsed' }}" href=""
                                               data-toggle="collapse" data-target="{{ '#collapse-' . $faq->id }}"
                                               aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                               aria-controls="{{ 'collapse-' . $faq->id }}">
                                                {{ $faq->question }}
                                            </a>
                                        </div>

                                        <div id="{{ 'collapse-' . $faq->id }}"
                                             class="collapse {{ $loop->first ? 'show' : '' }}"
                                             aria-labelledby="{{ 'heading-' . $faq->id }}"
                                             data-parent="#accordionExample">
                                            <div class="card-body">
                                                <p>{{ $faq->answer }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>


        </div>
    </section>
    <!--====== FAQ PART END ======-->
@endsection
