@if ($footerSecStatus == 1)
    <footer class="footer-area footer-bg">
        <div class="container mt-40">
            <div class="row pb-5 justify-content-around">
                <div class="col-lg-4 col-md-12 col-12">
                    <div class="footer-item about-footer-item">

                        <div class="text-center mb-3">
                            <img data-src="{{ asset('img/logo/logo-name.png')}}" style="width: 124px"
                                 class="img-fluid lazy"
                                 alt="website logo">
                        </div>

                        <div class="about-content">
                            <p class="text-justify" style="line-height: 2">
                                نور أكرم : ألمؤثرة ألأولى بالعراق بمجال التربية. ام لطفلين وصاحبة محتوى تربوي تعليمي على
                                مواقع التواصل
                                الاجتماعي. يتابعها اكثر من مليوني شخص. ويشاهد محتواها 24 مليون مشاهد خلال الثلاث سنوات
                                السابقة.

                            </p>
                        </div>

                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 mt-md-5 mt-5 mt-lg-0">
                    <div class="footer-item">
                        <div class="footer-title d-flex item-2">
                            <i class="fal fa-link text-secondary"></i>
                            <h4 class="title mr-2 text-secondary">صفحات قد تهمك</h4>
                        </div>

                        <div class="footer-list-area">
                            <div class="footer-list d-block d-sm-flex">
                                <ul>
                                    <li><a href="{{route('books')}}"><i class="fal fa-angle-left"></i> الكتب
                                            والمجلات</a></li>
                                    <li><a href="{{route('faqs')}}"><i class="fal fa-angle-left"></i> آلأسئلة
                                            الشائعة</a></li>
                                    <li><a href="{{route('user.login')}}"><i class="fal fa-angle-left"></i> تسجيل الدخول</a>
                                    </li>
                                    <li>
                                        <a href="{{route('home.termsPage')}}">
                                            <i class="fal fa-angle-left"></i> البنود والأحكام</a></li>

                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 mt-md-5 mt-5 mt-lg-0">
                    @includeIf('frontend.partials.footer.contact-info')

                </div>

            </div>
            <div class="row border-top text-center pt-5 border-secondary">
                <div class="col">
                    <p class="text-light">
                        {!! !empty($footerInfo->copyright_text) ? $footerInfo->copyright_text : '' !!}
                    </p>
                </div>
            </div>
        </div>
    </footer>
@endif
