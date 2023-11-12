@extends('frontend.layout')

@section('pageHeading')
    @if (!empty($pageHeading))
        {{ $pageHeading->contact_page_title ?? 'الكتب والمجلات' }}
    @endif
@endsection

@section('metaKeywords')
    @if (!empty($seoInfo))
        {{ $seoInfo->meta_keyword_contact }}
    @endif
@endsection

@section('metaDescription')
    @if (!empty($seoInfo))
        {{ $seoInfo->meta_description_contact }}
    @endif
@endsection

@section('content')
    @includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $bgImg->breadcrumb, 'title' => $pageHeading->contact_page_title ?? 'الكتب والمجلات'])

    <!--====== CONTACT INFO PART START ======-->
    <section class="contact-info-area">
        <div class="container">
            <div class="row align-items-center" style="display: block">

                <h2 class="text-center">لا يوجد كتب حاليا!</h2>
            </div>
        </div>
    </section>
    <!--====== CONTACT INFO PART END ======-->

@endsection
