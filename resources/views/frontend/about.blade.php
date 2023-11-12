@extends('frontend.layout')

@section('pageHeading')
    @if (!empty($pageHeading))
        {{ $pageHeading }}
    @endif
@endsection


@section('content')
    @includeIf('frontend.partials.breadcrumb', ['img' => $breadcrumbImg, 'title' => $pageHeading ?? "Our Message"])

    <!--====== CONTACT ACTION PART START ======-->
    <section class="contact-action-area pt-100 pb-120">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="summernote-content">
                        <p style="text-align:justify;"><span><b><span style="font-size:18px;">من هي نور اكرم؟</span></b></span>
                        </p>
                        <p style="text-align:justify;"><span><b><span style="font-size:18px;"><br></span></b></span></p>
                        <p style="text-align:justify;"><span>نور اكرم :&nbsp;المؤثرة الاولى بالعراق بمجال التربية. ام لطفلين وصاحبة
                محتوى تربوي تعليمية على مواقع التواصل الاجتماعي. يتابعها اكثر من مليوني شخص. ويشاهد محتواها 24 مليون
                مشاهد خلال الثلاث سنوات السابق</span></p>
                        <div style="text-align:justify;"><br></div>
                        <div style="text-align:justify;"><span style="font-weight:600;"><span
                                        style="font-size:18px;">الرسالة؟</span></span></div>
                        <div style="text-align:justify;"><span style="font-weight:600;"><span
                                        style="font-size:18px;"><br></span></span></div>
                        <div style="text-align:justify;"><p>&nbsp;نحن نؤمن ان الطفل هو اهم شيء بحياة والديه وان الوالدين
                                يسعون بكل
                                الطرق التي يملكونها لتحقيق هدف مشترك وهو اسعاد الطفل.. نسعى لتجهيزك بمعدات تساهم بتحقيق
                                هدفك بتربية
                                ابنائك. نعلم ان لا سعادة ان لم يكن ناقل السعادة سعيد نعمل بجهد ودقة كبيرتين على توفير
                                الخدمات التي تجعل
                                منك سعيدا منطلقا قادرا على احتراف دورك التربوي وجعل حياتك متزنة بين ما تعطي وما تاخذ
                                خالقا مساحة امنة لك
                                للتعبير الحر ولابنائك للانطلاق معا برحلة اسرة متمكنة.&nbsp;</p>
                            <div><span style="font-size:18px;font-weight:600;">الرؤية؟</span><br></div>
                            <div><br></div>
                            <div><p>نؤمن ان الرؤية التي نرى بها دورنا كوالدين هي التي تحدد سلوكياتنا وبالتالي نحن نعمل
                                    على اعادة تعريف
                                    دور المربي. كل ام تستحق ان تشعر انها قادرة ومتمكنة منجزة وفعالة وتمارس حياتها باروع
                                    صورة ذهنية ترى
                                    نفسها بها حتى بوجود اطفالها وان يساهم وجود اطفالها بحياتها ايجابا بتدريبها على
                                    مهارات وامكانيات تزيد
                                    من فعاليتها بالمجتمع. نحن ملتزمون التزاما حقيقيا بجعل حياتك اسهل ونعمل على تقديم
                                    المعارف التي تساهم
                                    بشكل مباشر بجعل عملية تحقيق اهدافك ممكنة.&nbsp;</p>
                                <p><br></p>
                                <p><span style="font-size:18px;"><b>المهمة ؟</b></span></p>
                                <p><br></p>
                                <p>&nbsp;نهدف الى توفير برامج تدريبية فعالة مستمدة من الفجوات التي نراها كمختصين بوطننا
                                    العربي ونسعى الى
                                    تقديم خدمات فردية وجماعية حضورية وعن بعد لنغرس بذور التربية الواعية.</p></div>
                            <div><br></div>
                            <br></div>
                    </div>
                </div>

            </div>


        </div>
    </section>
    <!--====== CONTACT ACTION PART END ======-->
@endsection
