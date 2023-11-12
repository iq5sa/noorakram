@extends('frontend.layout')

@section('pageHeading')
    @if (isset($pageHeading))
        {{ $pageHeading}}
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
    @includeIf('frontend.partials.breadcrumb', ['img' => $breadcrumbImg, 'title' => $pageHeading ?? 'Contact Us'])

    <!--====== CONTACT INFO PART START ======-->
    <section class="contact-info-area">
        <div class="container">
            <div class="row align-items-center">
                <div class="col">
                    <div class="contact-info-content">
                        <div class="single-contact-info">
                            <div class="info-icon">
                                <i class="fal fa-phone bg-dark"></i>
                            </div>
                            <div class="info-contact">
                                <h4 class="title">{{ __('Phone Number') }}</h4>
                                <a href="https://wa.me/+9647726908090"><span
                                            style="direction: ltr">+964 772 690 8090</span></a>
                            </div>
                        </div>

                        <div class="single-contact-info item-2 d-flex align-items-center">
                            <div class="info-icon">
                                <i class="fal fa-envelope bg-secondary"></i>
                            </div>
                            <div class="info-contact">
                                <h4 class="title">{{ __('Email Address') }}</h4>
                                <a href="mailto:info@noorakram.com">
                                    info@noorakram.com
                                </a>

                            </div>
                        </div>

                        <div class="single-contact-info item-3 d-flex align-items-center">
                            <div class="info-icon">
                                <i class="fal fa-map-marker-alt bg-success"></i>
                            </div>
                            <div class="info-contact">
                                <h4 class="title">{{ __('Location') }}</h4>
                                <span>العراق - بغداد</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--====== CONTACT INFO PART END ======-->

    <!--====== CONTACT ACTION PART START ======-->
    <section class="contact-action-area pt-0 pb-120">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="contact-action-item">
                        <h2 class="title mb-4">اكتب رسالة</h2>
                        <form action="{{ route('contact.send_mail') }}" method="post">
                            @csrf


                            <div class="row mb-4">
                                <div class="col-lg-6">
                                    <div class="input-box">
                                        <label for="fullName" class="label">الاسم الثلاثي:</label>
                                        <div class="input-group">

                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <i class="fal fa-user"></i>
                                                </div>
                                            </div>

                                            <input required id="fullName" class="form-control" name="name" type="text"
                                                   placeholder="الاسم الثلاثي" aria-label="Username"
                                                   aria-describedby="basic-addon1">

                                        </div>
                                        @error('name')
                                        <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="input-box">
                                        <label for="email" class="label">البريد الإلكتروني:</label>
                                        <div class="input-group">
                                            <div class="input-group-append">
                                                <div class="input-group-text ">
                                                    <i class="fal fa-envelope"></i>
                                                </div>
                                            </div>
                                            <input required id="email" name="email" class="form-control" type="email"
                                                   placeholder="name@gmail.com">
                                        </div>
                                        @error('email')
                                        <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="input-box">
                                        <label for="subject" class="label">الموضوع:</label>
                                        <div class="input-group">
                                            <div class="input-group-append">
                                                <div class="input-group-text ">
                                                    <i class="fal fa-edit"></i>
                                                </div>
                                            </div>

                                            <input required id="subject" name="subject" class="form-control" type="text"
                                                   placeholder="الموضوع">
                                        </div>
                                        @error('subject')
                                        <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="input-box">
                                        <label for="message" class="label">نص الرسالة:</label>
                                        <div class="input-group">
                      <textarea required id="message" name="message" class="form-control textarea" cols="30"
                                rows="10"
                                placeholder="اكتب رسالتك هنا"></textarea>
                                        </div>
                                        @error('message')
                                        <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="input-group">
                                <button class="btn btn-primary btn-lg px-5 py-2" type="submit">{{ __('Send') }}</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>


        </div>
    </section>
    <!--====== CONTACT ACTION PART END ======-->
@endsection
