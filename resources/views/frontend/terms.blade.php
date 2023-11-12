@extends('frontend.layout')

@section('pageHeading')
    @if (!empty($pageHeading))
        {{ $pageHeading }}
    @endif
@endsection


@section('metaDescription')
    @if (!empty($seoInfo))
        {{ $seoInfo->meta_description_terms }}
    @endif
@endsection

@section('content')
    @includeIf('frontend.partials.breadcrumb', ['img' => $breadcrumbImg, 'title' => $pageHeading ?? 'Terms & conditions'])


    <!--====== CONTACT ACTION PART START ======-->
    <section class="contact-action-area pt-100 pb-120">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="summernote-content">
                        <p><br></p>
                        <div>
                            <div><b><span
                                            style="">تحديد الأطراف المعنية</span><span>:</span></b></div>
                            <p style="text-align:right;"><span><b>1.1 </b></span><span style="">يعتبر المتجر الإلكتروني هو المسؤول عن
توفير منصة لبيع دورات التدريبية والجلسات الخاصة بالأمهات عبر الإنترنت</span><span>.</span></p>
                            <p style="text-align:right;"><span><br></span></p>
                            <p style="text-align:right;"><b>1.2</b> <span style="">يشمل مصطلح "العملاء" كل شخص
يدخل الموقع ويقوم بشراء دورة تدريبية أو حجز جلسة عبر الإنترنت من خلال الموقع</span>.</p>
                            <p style="text-align:right;"><br></p>
                            <p style="text-align:right;"><b>&nbsp;2 <span
                                            style="">شروط الاستخدام</span>:</b></p>
                            <p style="text-align:right;"><span><br></span></p>
                            <p style="text-align:right;"><span><b>2.1 </b></span><span style="">يتوجب على العملاء استخدام المنصة
بطريقة قانونية وفقًا للشروط والأحكام الواردة في هذه الاتفاقية</span><span>.</span></p>
                            <p style="text-align:right;"><span><br></span></p>
                            <p style="text-align:right;"><span><b>2.2 </b></span><span style="">يجب على العملاء الامتناع عن القيام
بأي نشاط يتسبب في تعطيل الموقع أو الإضرار به أو بأي جزء منه</span><span>.</span></p>
                            <p style="text-align:right;"><span><br></span></p>
                            <p style="text-align:right;"><b>2.3 </b><span style="">يجب على العملاء تزويد المنصة
بالمعلومات الصحيحة والدقيقة عندما يتعلق الأمر بتسجيل الدخول وإنشاء حساب</span>.</p>
                            <p style="text-align:right;"><span><br></span></p>
                            <p style="text-align:right;"><b>2.4</b> <span style="">يجب على العملاء عدم إعادة بيع أو
توزيع أي جزء من المحتوى المقدم عبر المنصة لأي غرض تجاري</span>.</p>
                            <p style="text-align:right;"><br></p>
                            <p style="text-align:right;"><b><span>&nbsp;3 </span><span
                                            style="">الدفع والإلغاء</span><span>:</span></b>
                            </p>
                            <p style="text-align:right;"><span><br></span></p>
                            <p style="text-align:right;"><span><b>3.1 </b></span><span style="">يتم قبول الدفع الإلكتروني من خلال
وسائل الدفع المتاحة على الموقع</span><span>.</span></p>
                            <p style="text-align:right;"><b>3.2</b> <span style="">يجب على العملاء إلغاء أو تغيير مواعيد
الحجوزات المقررة بشكل مسبق، وفقًا لسياسة الإلغاء المحددة في كل دورة
تدريبية&nbsp;أو&nbsp;جلسة</span>.</p>
                            <p style="text-align:right;"><br></p>
                            <p style="text-align:right;"><b>4<span
                                            style="color:rgb(0,0,0);font-size:14px;font-style:normal;text-align:right;background-color:rgb(255,255,255);">ا</span></b><b>لمسؤولية</b><span
                                        style=""><b> والخصوصية:</b></span></p>
                            <p style="text-align:right;"><span style=""><b><br></b></span></p>
                            <p style="text-align:right;"><span style=""><b>4.1</b> يجب على
العملاء الالتزام بسياسة الخصوصية الخاصة بالموقع والتي تحدد كيفية جمع واستخدام
وحفظ المعلومات الشخصية للعملاء.</span></p>
                            <p style="text-align:right;"><span style=""><b>4.1&nbsp;</b>يتحمل المتجر
الإلكتروني المسؤولية الكاملة عن حماية البيانات الشخصية للعملاء وعدم الكشف عنها
لأي طرف ثالث، وفقًا للقوانين واللوائح المعمول بها.</span></p>
                            <p style="text-align:right;"><span style=""><br></span></p>
                            <p style="text-align:right;"><span style=""><b>5</b>&nbsp;</span><b>&nbsp;التعديلات
                                    على الشروط والأحكام:</b></p>
                            <p style="text-align:right;"><br></p>
                            <p style="text-align:right;"><span style=""><b>5.1</b> يحق للمتجر
الإلكتروني إجراء التعديلات على الشروط والأحكام في أي وقت دون إشعار مسبق، ويجب
على العملاء الالتزام بالتعديلات المحدثة.</span></p>
                            <p style="text-align:right;"><span style=""><b>5.2</b> يتحمل
العملاء المسؤولية الكاملة عن مراجعة الشروط والأحكام بشكل دوري للتأكد من تحديثها
وملاءمتها&nbsp;لاحتياجاتهم.</span></p></div>
                    </div>
                </div>

            </div>


        </div>
    </section>
    <!--====== CONTACT ACTION PART END ======-->
@endsection
