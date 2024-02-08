@extends('frontend.layout')

@section('pageHeading')
    @if (!empty($pageHeading))
        {{ $pageHeading}}
    @endif
@endsection

@section('metaKeywords')
    @if (!empty($seoInfo))
        {{ $seoInfo->meta_keyword_signup }}
    @endif
@endsection

@section('metaDescription')
    @if (!empty($seoInfo))
        {{ $seoInfo->meta_description_signup }}
    @endif
@endsection
@section("style")
    <style>
        .container {
            padding-top: 100px;
            padding-bottom: 120px;
        }

        .page-heading {
            vertical-align: inherit;
            color: #333;
            font-size: 24px;
            font-weight: bold;
        }

        .update-date {
            vertical-align: inherit;
            color: #666;
            font-size: 14px;
        }

        /* Add your new styles here */
        p {
            font-size: 16px;
            color: #333;
        }

        ul li {
            font-size: 14px;
            color: #666;
        }

        h2, h3, h4 {
            color: #333;
            font-weight: bold;
        }

    </style>
@endsection

@section('content')
    @includeIf('frontend.partials.breadcrumb', ['img' => $breadcrumbImg, 'title' => $pageHeading ?? 'Sign Up'])


    <div class="container">

        <h1 class="page-heading">سياسة الخصوصية</h1>
        <p class="update-date">آخر تحديث: 28 نوفمبر 2023</p>
        <p class="privacy-policy-description">
            تصف سياسة الخصوصية هذه سياساتنا وإجراءاتنا بشأن جمع معلوماتك واستخدامها والكشف
            عنها عند استخدام الخدمة وتخبرك بحقوق الخصوصية الخاصة بك وكيف يحميك القانون.
        </p>
        <p>
  <span
  ><span>نحن نستخدم بياناتك الشخصية لتوفير الخدمة وتحسينها. </span
      ><span
      >باستخدام الخدمة، فإنك توافق على جمع واستخدام المعلومات وفقًا لسياسة
      الخصوصية هذه.
    </span></span
  >
        </p>
        <h2>
            <span><span>التفسير والتعاريف</span></span>
        </h2>
        <h3>
            <span><span>تفسير</span></span>
        </h3>
        <p>
  <span
  ><span
      >الكلمات التي يتم كتابة الحرف الأول منها بالأحرف الكبيرة لها معاني محددة
      وفقًا للشروط التالية. </span
      ><span
      >يكون للتعريفات التالية نفس المعنى بغض النظر عما إذا كانت تظهر بصيغة
      المفرد أو الجمع.</span
      ></span
  >
        </p>
        <h3>
            <span><span>تعريفات</span></span>
        </h3>
        <p>
            <span><span>لأغراض سياسة الخصوصية هذه:</span></span>
        </p>
        <ul>
            <li>
                <p>
                    <strong
                    ><span><span>الحساب</span></span></strong
                    ><span
                    ><span>
          يعني حسابًا فريدًا تم إنشاؤه لك للوصول إلى خدمتنا أو أجزاء من
          خدمتنا.</span
                        ></span
                    >
                </p>
            </li>
            <li>
                <p>
                    <strong
                    ><span><span>الشركة التابعة</span></span></strong
                    ><span
                    ><span>
          تعني كيان يسيطر أو يخضع لسيطرة أو يخضع لسيطرة مشتركة مع طرف ما، حيث
          تعني كلمة "السيطرة" ملكية 50٪ أو أكثر من الأسهم أو حصص الأسهم أو
          الأوراق المالية الأخرى التي يحق لها التصويت لانتخاب أعضاء مجلس الإدارة
          أو أي سلطة إدارية أخرى .</span
                        ></span
                    >
                </p>
            </li>
            <li>
                <p>
                    <strong
                    ><span><span>تشير الشركة</span></span></strong
                    ><span
                    ><span>
          (المشار إليها إما بـ "الشركة" أو "نحن" أو "نا" أو "خاصتنا" في هذه
          الاتفاقية) إلى أكاديمية نور أكرم.</span
                        ></span
                    >
                </p>
            </li>
            <li>
                <p>
                    <strong
                    ><span><span>ملفات تعريف الارتباط</span></span></strong
                    ><span
                    ><span>
          هي ملفات صغيرة يتم وضعها على جهاز الكمبيوتر الخاص بك أو جهازك المحمول
          أو أي جهاز آخر عن طريق موقع ويب، وتحتوي على تفاصيل سجل التصفح الخاص بك
          على موقع الويب هذا من بين استخداماته المتعددة.
        </span></span
                    >
                </p>
            </li>
            <li>
                <p>
                    <strong
                    ><span><span>الدولة</span></span></strong
                    ><span><span> تشير إلى: العراق</span></span>
                </p>
            </li>
            <li>
                <p>
                    <strong
                    ><span><span>الجهاز</span></span></strong
                    ><span
                    ><span>
          يعني أي جهاز يمكنه الوصول إلى الخدمة مثل جهاز كمبيوتر أو هاتف محمول أو
          جهاز لوحي رقمي.</span
                        ></span
                    >
                </p>
            </li>
            <li>
                <p>
                    <strong
                    ><span><span>البيانات الشخصية</span></span></strong
                    ><span
                    ><span> هي أي معلومات تتعلق بفرد محدد أو يمكن التعرف عليه. </span></span
                    >
                </p>
            </li>
            <li>
                <p>
                    <strong
                    ><span><span>تشير الخدمة</span></span></strong
                    ><span><span> إلى الموقع.</span></span>
                </p>
            </li>
            <li>
                <p>
                    <strong
                    ><span><span>مقدم الخدمة</span></span></strong
                    ><span
                    ><span>
          يعني أي شخص طبيعي أو اعتباري يقوم بمعالجة البيانات نيابة عن الشركة. </span
                        ><span
                        >ويشير إلى شركات الطرف الثالث أو الأفراد العاملين لدى الشركة لتسهيل
          الخدمة، أو تقديم الخدمة نيابة عن الشركة، أو أداء الخدمات المتعلقة
          بالخدمة أو مساعدة الشركة في تحليل كيفية استخدام الخدمة.</span
                        ></span
                    >
                </p>
            </li>
            <li>
                <p>
                    <strong
                    ><span
                        ><span>تشير خدمة الوسائط الاجتماعية التابعة لجهة خارجية</span></span
                        ></strong
                    ><span
                    ><span>
          إلى أي موقع ويب أو أي موقع ويب لشبكة اجتماعية يمكن للمستخدم من خلاله
          تسجيل الدخول أو إنشاء حساب لاستخدام الخدمة.</span
                        ></span
                    >
                </p>
            </li>
            <li>
                <p>
                    <strong
                    ><span><span>تشير بيانات الاستخدام</span></span></strong
                    ><span
                    ><span>
          إلى البيانات التي يتم جمعها تلقائيًا، إما الناتجة عن استخدام الخدمة أو
          من البنية التحتية للخدمة نفسها (على سبيل المثال، مدة زيارة
          الصفحة).</span
                        ></span
                    >
                </p>
            </li>
            <li>
                <p>
                    <strong
                    ><span><span>يشير الموقع الإلكتروني</span></span></strong
                    ><span><span> إلى أكاديمية نور أكرم، ويمكن الوصول إليه من </span></span
                    ><a
                            href="https://noorakram.com"
                            rel="external nofollow noopener"
                            target="_blank"
                    ><span><span>https://noorakram.com</span></span></a
                    >
                </p>
            </li>
            <li>
                <p>
                    <strong
                    ><span><span>أنت</span></span></strong
                    ><span
                    ><span>
          تعني الفرد الذي يصل إلى الخدمة أو يستخدمها، أو الشركة أو أي كيان
          قانوني آخر يقوم هذا الفرد بالنيابة عنه بالوصول إلى الخدمة أو
          استخدامها، حسب الاقتضاء.</span
                        ></span
                    >
                </p>
            </li>
        </ul>
        <h2>جمع واستخدام بياناتك الشخصية</h2>
        <h3>أنواع البيانات التي تم جمعها</h3>
        <h4>
            <span><span>بيانات شخصية</span></span>
        </h4>
        <p>
  <span
  ><span
      >أثناء استخدام خدمتنا، قد نطلب منك تزويدنا ببعض معلومات التعريف الشخصية
      التي يمكن استخدامها للاتصال بك أو التعرف عليك. </span
      ><span
      >قد تتضمن معلومات التعريف الشخصية، على سبيل المثال لا الحصر، ما يلي:</span
      ></span
  >
        </p>
        <ul>
            <li>
                <p>
                    <span><span>عنوان البريد الإلكتروني</span></span>
                </p>
            </li>
            <li>
                <p>
                    <span><span>الاسم الأول واسم العائلة</span></span>
                </p>
            </li>
            <li>
                <p>
                    <span><span>رقم التليفون</span></span>
                </p>
            </li>
            <li>
                <p>
      <span
      ><span
          >العنوان، الولاية، المقاطعة، الرمز البريدي/الرمز البريدي،
          المدينة</span
          ></span
      >
                </p>
            </li>
            <li>
                <p>
                    <span><span>بيانات الاستخدام</span></span>
                </p>
            </li>
        </ul>
        <h4>
            <span><span>بيانات الاستخدام</span></span>
        </h4>
        <p>
  <span
  ><span>يتم جمع بيانات الاستخدام تلقائيًا عند استخدام الخدمة.</span></span
  >
        </p>
        <p>
  <span
  ><span
      >قد تتضمن بيانات الاستخدام معلومات مثل عنوان بروتوكول الإنترنت الخاص
      بجهازك (على سبيل المثال عنوان IP)، ونوع المتصفح، وإصدار المتصفح، وصفحات
      خدمتنا التي تزورها، ووقت وتاريخ زيارتك، والوقت الذي تقضيه في تلك الصفحات،
      والجهاز الفريد المعرفات والبيانات التشخيصية الأخرى.</span
      ></span
  >
        </p>
        <p>
  <span
  ><span
      >عندما تصل إلى الخدمة عن طريق أو من خلال جهاز محمول، قد نقوم بجمع معلومات
      معينة تلقائيًا، بما في ذلك، على سبيل المثال لا الحصر، نوع الجهاز المحمول
      الذي تستخدمه، والمعرف الفريد لجهازك المحمول، وعنوان IP الخاص بجهازك
      المحمول، وهاتفك المحمول. نظام التشغيل ونوع متصفح الإنترنت عبر الهاتف
      المحمول الذي تستخدمه ومعرفات الجهاز الفريدة والبيانات التشخيصية
      الأخرى.</span
      ></span
  >
        </p>
        <p>
  <span
  ><span
      >يجوز لنا أيضًا جمع المعلومات التي يرسلها متصفحك عندما تزور خدمتنا أو
      عندما تصل إلى الخدمة عن طريق جهاز محمول أو من خلاله.</span
      ></span
  >
        </p>
        <h4>
  <span
  ><span>معلومات من خدمات الوسائط الاجتماعية التابعة لجهات خارجية</span></span
  >
        </h4>
        <p>
  <span
  ><span
      >تسمح لك الشركة بإنشاء حساب وتسجيل الدخول لاستخدام الخدمة من خلال خدمات
      الوسائط الاجتماعية التابعة لجهات خارجية التالية:</span
      ></span
  >
        </p>
        <ul>
            <li>
                <span><span>جوجل</span></span>
            </li>
            <li>
                <span><span>فيسبوك</span></span>
            </li>
            <li>
                <span><span>انستغرام</span></span>
            </li>
            <li>
                <span><span>تويتر</span></span>
            </li>
            <li>
                <span><span>ينكدين</span></span>
            </li>
        </ul>
        <p>
  <span
  ><span
      >إذا قررت التسجيل من خلال خدمة وسائط اجتماعية تابعة لجهة خارجية أو منحتنا
      حق الوصول إليها، فقد نقوم بجمع البيانات الشخصية المرتبطة بالفعل بحساب خدمة
      الوسائط الاجتماعية التابعة لجهة خارجية، مثل اسمك وعنوان بريدك الإلكتروني
      وأنشطتك أو قائمة جهات الاتصال الخاصة بك المرتبطة بهذا الحساب.</span
      ></span
  >
        </p>
        <p>
  <span
  ><span
      >قد يكون لديك أيضًا خيار مشاركة معلومات إضافية مع الشركة من خلال حساب خدمة
      الوسائط الاجتماعية التابعة لجهة خارجية. </span
      ><span
      >إذا اخترت تقديم هذه المعلومات والبيانات الشخصية، أثناء التسجيل أو غير
      ذلك، فإنك تمنح الشركة الإذن باستخدامها ومشاركتها وتخزينها بطريقة تتفق مع
      سياسة الخصوصية هذه.</span
      ></span
  >
        </p>
        <h4>
            <span><span>تقنيات التتبع وملفات تعريف الارتباط</span></span>
        </h4>
        <p>
  <span
  ><span
      >نحن نستخدم ملفات تعريف الارتباط وتقنيات التتبع المشابهة لتتبع النشاط على
      خدمتنا وتخزين معلومات معينة. </span
      ><span
      >تقنيات التتبع المستخدمة هي الإشارات والعلامات والبرامج النصية لجمع
      المعلومات وتتبعها ولتحسين خدمتنا وتحليلها. </span
      ><span>قد تشمل التقنيات التي نستخدمها ما يلي:</span></span
  >
        </p>
        <ul>
            <li>
                <strong
                ><span
                    ><span
                        >ملفات تعريف الارتباط أو ملفات تعريف الارتباط للمتصفح.
        </span></span
                    ></strong
                ><span
                ><span>ملف تعريف الارتباط هو ملف صغير يتم وضعه على جهازك. </span
                    ><span
                    >يمكنك توجيه متصفحك لرفض جميع ملفات تعريف الارتباط أو الإشارة إلى وقت
        إرسال ملف تعريف الارتباط. </span
                    ><span
                    >ومع ذلك، إذا كنت لا تقبل ملفات تعريف الارتباط، فقد لا تتمكن من استخدام
        بعض أجزاء خدمتنا. </span
                    ><span
                    >ما لم تقم بتعديل إعداد المتصفح الخاص بك بحيث يرفض ملفات تعريف الارتباط،
        فقد تستخدم خدمتنا ملفات تعريف الارتباط.
      </span></span
                >
            </li>
            <li>
                <strong
                ><span><span>منارات الويب. </span></span></strong
                ><span
                ><span
                    >قد تحتوي أقسام معينة من خدمتنا ورسائل البريد الإلكتروني الخاصة بنا على
        ملفات إلكترونية صغيرة تُعرف باسم إشارات الويب (يُشار إليها أيضًا باسم
        صور GIF الواضحة وعلامات البكسل وصور GIF أحادية البكسل) والتي تسمح
        للشركة، على سبيل المثال، بإحصاء المستخدمين الذين قاموا بزيارة تلك
        الصفحات أو فتح بريد إلكتروني للحصول على إحصائيات موقع الويب الأخرى ذات
        الصلة (على سبيل المثال، تسجيل شعبية قسم معين والتحقق من سلامة النظام
        والخادم).
      </span></span
                >
            </li>
        </ul>
        <p>
  <span
  ><span>يمكن أن تكون ملفات تعريف الارتباط "دائمة" أو "جلسة". </span
      ><span
      >تظل ملفات تعريف الارتباط الدائمة على جهاز الكمبيوتر الشخصي أو الجهاز
      المحمول الخاص بك عندما تكون غير متصل بالإنترنت، بينما يتم حذف ملفات تعريف
      الارتباط الخاصة بالجلسة بمجرد إغلاق متصفح الويب الخاص بك.
    </span></span
  >
            <span><span> .</span></span>
        </p>
        <p>
  <span
  ><span
      >نحن نستخدم كلاً من ملفات تعريف الارتباط الخاصة بالجلسة والدائمة للأغراض
      المبينة أدناه:</span
      ></span
  >
        </p>
        <ul>
            <li>
                <p>
                    <strong
                    ><span
                        ><span>ملفات تعريف الارتباط الضرورية / الأساسية</span></span
                        ></strong
                    >
                </p>
                <p>
                    <span><span>النوع: ملفات تعريف الارتباط للجلسة</span></span>
                </p>
                <p>
                    <span><span>بأدارة : نحن</span></span>
                </p>
                <p>
      <span
      ><span
          >الغرض: تعد ملفات تعريف الارتباط هذه ضرورية لتزويدك بالخدمات المتاحة
          من خلال الموقع ولتمكينك من استخدام بعض ميزاته. </span
          ><span
          >فهي تساعد على مصادقة المستخدمين ومنع الاستخدام الاحتيالي لحسابات
          المستخدمين. </span
          ><span
          >بدون ملفات تعريف الارتباط هذه، لا يمكن تقديم الخدمات التي طلبتها،
          ونحن نستخدم ملفات تعريف الارتباط هذه فقط لتزويدك بهذه الخدمات.</span
          ></span
      >
                </p>
            </li>
            <li>
                <p>
                    <strong
                    ><span
                        ><span
                            >سياسة ملفات تعريف الارتباط / ملفات تعريف الارتباط لقبول
            الإشعار</span
                            ></span
                        ></strong
                    >
                </p>
                <p>
                    <span><span>النوع: ملفات تعريف الارتباط الدائمة</span></span>
                </p>
                <p>
                    <span><span>بأدارة : نحن</span></span>
                </p>
                <p>
      <span
      ><span
          >الغرض: تحدد ملفات تعريف الارتباط هذه ما إذا كان المستخدمون قد قبلوا
          استخدام ملفات تعريف الارتباط على موقع الويب.</span
          ></span
      >
                </p>
            </li>
            <li>
                <p>
                    <strong
                    ><span><span>ملفات تعريف الارتباط الوظيفية</span></span></strong
                    >
                </p>
                <p>
                    <span><span>النوع: ملفات تعريف الارتباط الدائمة</span></span>
                </p>
                <p>
                    <span><span>بأدارة : نحن</span></span>
                </p>
                <p>
      <span
      ><span
          >الغرض: تسمح لنا ملفات تعريف الارتباط هذه بتذكر الاختيارات التي تقوم
          بها عند استخدام موقع الويب، مثل تذكر تفاصيل تسجيل الدخول أو تفضيلات
          اللغة. </span
          ><span
          >الغرض من ملفات تعريف الارتباط هذه هو تزويدك بتجربة شخصية أكثر وتجنب
          الاضطرار إلى إعادة إدخال تفضيلاتك في كل مرة تستخدم فيها موقع
          الويب.</span
          ></span
      >
                </p>
            </li>
        </ul>
        <p>
  <span
  ><span
      >لمزيد من المعلومات حول ملفات تعريف الارتباط التي نستخدمها واختياراتك فيما
      يتعلق بملفات تعريف الارتباط، يرجى زيارة سياسة ملفات تعريف الارتباط الخاصة
      بنا أو قسم ملفات تعريف الارتباط في سياسة الخصوصية الخاصة بنا.</span
      ></span
  >
        </p>
        <h3>
            <span><span>استخدام بياناتك الشخصية</span></span>
        </h3>
        <p>
  <span
  ><span>يجوز للشركة استخدام البيانات الشخصية للأغراض التالية:</span></span
  >
        </p>
        <ul>
            <li>
                <p>
                    <strong
                    ><span><span>لتوفير خدمتنا والحفاظ عليها</span></span></strong
                    ><span><span> ، بما في ذلك مراقبة استخدام خدمتنا. </span></span>
                </p>
            </li>
            <li>
                <p>
                    <strong
                    ><span><span>لإدارة حسابك:</span></span></strong
                    ><span
                    ><span> لإدارة تسجيلك كمستخدم للخدمة. </span
                        ><span
                        >يمكن أن تمنحك البيانات الشخصية التي تقدمها إمكانية الوصول إلى وظائف
          مختلفة للخدمة المتاحة لك كمستخدم مسجل.</span
                        ></span
                    >
                </p>
            </li>
            <li>
                <p>
                    <strong
                    ><span><span>لتنفيذ العقد:</span></span></strong
                    ><span
                    ><span>
          التطوير والامتثال والتعهد بعقد الشراء للمنتجات أو العناصر أو الخدمات
          التي اشتريتها أو أي عقد آخر معنا من خلال الخدمة.</span
                        ></span
                    >
                </p>
            </li>
            <li>
                <p>
                    <strong
                    ><span><span>للاتصال بك:</span></span></strong
                    ><span
                    ><span>
          للاتصال بك عن طريق البريد الإلكتروني أو المكالمات الهاتفية أو الرسائل
          النصية القصيرة أو أي أشكال أخرى مماثلة من الاتصالات الإلكترونية، مثل
          إشعارات تطبيق الهاتف المحمول فيما يتعلق بالتحديثات أو الاتصالات
          الإعلامية المتعلقة بالوظائف أو المنتجات أو الخدمات المتعاقد عليها، بما
          في ذلك التحديثات الأمنية، عندما يكون ذلك ضروريا أو معقولا
          لتنفيذها.</span
                        ></span
                    >
                </p>
            </li>
            <li>
                <p>
                    <strong
                    ><span><span>لتزويدك</span></span></strong
                    ><span
                    ><span>
          بالأخبار والعروض الخاصة والمعلومات العامة حول السلع والخدمات والأحداث
          الأخرى التي نقدمها والمشابهة لتلك التي اشتريتها بالفعل أو استفسرت عنها
          ما لم تكن قد اخترت عدم تلقي هذه المعلومات.</span
                        ></span
                    >
                </p>
            </li>
            <li>
                <p>
                    <strong
                    ><span><span>لإدارة طلباتك:</span></span></strong
                    ><span><span> لحضور وإدارة طلباتك المقدمة إلينا.</span></span>
                </p>
            </li>
            <li>
                <p>
                    <strong
                    ><span><span>بالنسبة لعمليات نقل الأعمال:</span></span></strong
                    ><span
                    ><span>
          قد نستخدم معلوماتك لتقييم أو إجراء عملية دمج أو تصفية أو إعادة هيكلة
          أو إعادة تنظيم أو حل أو بيع أو نقل آخر لبعض أو كل أصولنا، سواء كمنشأة
          مستمرة أو كجزء من الإفلاس أو التصفية أو أو إجراء مماثل، حيث تكون
          البيانات الشخصية التي نحتفظ بها حول مستخدمي خدمتنا من بين الأصول
          المنقولة.</span
                        ></span
                    >
                </p>
            </li>
            <li>
                <p>
                    <strong
                    ><span><span>لأغراض أخرى</span></span></strong
                    ><span
                    ><span>
          : قد نستخدم معلوماتك لأغراض أخرى، مثل تحليل البيانات وتحديد اتجاهات
          الاستخدام وتحديد فعالية حملاتنا الترويجية وتقييم وتحسين خدمتنا
          ومنتجاتنا وخدماتنا والتسويق وتجربتك.</span
                        ></span
                    >
                </p>
            </li>
        </ul>
        <p>
            <span><span>قد نشارك معلوماتك الشخصية في الحالات التالية:</span></span>
        </p>
        <ul>
            <li>
                <strong
                ><span><span>مع مقدمي الخدمة:</span></span></strong
                ><span
                ><span>
        قد نشارك معلوماتك الشخصية مع مقدمي الخدمة لمراقبة وتحليل استخدام خدمتنا،
        للاتصال بك.
      </span></span
                >
            </li>
            <li>
                <strong
                ><span><span>بالنسبة لعمليات نقل الأعمال:</span></span></strong
                ><span
                ><span>
        يجوز لنا مشاركة معلوماتك الشخصية أو نقلها فيما يتعلق أو أثناء المفاوضات
        بشأن أي اندماج أو بيع أصول الشركة أو تمويل أو الاستحواذ على كل أو جزء من
        أعمالنا لشركة أخرى.
      </span></span
                >
            </li>
            <li>
                <strong
                ><span><span>مع الشركات التابعة:</span></span></strong
                ><span
                ><span>
        قد نشارك معلوماتك مع الشركات التابعة لنا، وفي هذه الحالة سنطلب من تلك
        الشركات التابعة احترام سياسة الخصوصية هذه. </span
                    ><span
                    >تشمل الشركات التابعة شركتنا الأم وأي شركات فرعية أخرى أو شركاء في
        مشاريع مشتركة أو شركات أخرى نسيطر عليها أو تخضع لسيطرة مشتركة معنا.
      </span></span
                >
            </li>
            <li>
                <strong
                ><span><span>مع شركاء العمل:</span></span></strong
                ><span
                ><span>
        قد نشارك معلوماتك مع شركاء العمل لدينا لنقدم لك منتجات أو خدمات أو عروض
        ترويجية معينة.
      </span></span
                >
            </li>
            <li>
                <strong
                ><span><span>مع مستخدمين آخرين:</span></span></strong
                ><span
                ><span>
        عندما تشارك معلومات شخصية أو تتفاعل بطريقة أخرى في المناطق العامة مع
        مستخدمين آخرين، فقد يتم عرض هذه المعلومات من قبل جميع المستخدمين وقد يتم
        توزيعها للعامة في الخارج. </span
                    ><span
                    >إذا كنت تتفاعل مع مستخدمين آخرين أو قمت بالتسجيل من خلال خدمة وسائط
        اجتماعية تابعة لجهة خارجية، فقد ترى جهات الاتصال الخاصة بك على خدمة
        الوسائط الاجتماعية التابعة لجهة خارجية اسمك وملفك الشخصي وصورك ووصف
        نشاطك. </span
                    ><span
                    >وبالمثل، سيتمكن المستخدمون الآخرون من عرض أوصاف نشاطك والتواصل معك وعرض
        ملفك الشخصي.
      </span></span
                >
            </li>
            <li>
                <strong
                ><span><span>بموافقتك</span></span></strong
                ><span
                ><span>
        : يجوز لنا الكشف عن معلوماتك الشخصية لأي غرض آخر بموافقتك.
      </span></span
                >
            </li>
        </ul>
        <h3>
            <span><span>الاحتفاظ ببياناتك الشخصية</span></span>
        </h3>
        <p>
  <span
  ><span
      >ستحتفظ الشركة ببياناتك الشخصية فقط طالما كان ذلك ضروريًا للأغراض المنصوص
      عليها في سياسة الخصوصية هذه. </span
      ><span
      >سنحتفظ ببياناتك الشخصية ونستخدمها بالقدر اللازم للامتثال لالتزاماتنا
      القانونية (على سبيل المثال، إذا طُلب منا الاحتفاظ ببياناتك للامتثال
      للقوانين المعمول بها)، وحل النزاعات، وإنفاذ اتفاقياتنا وسياساتنا
      القانونية.</span
      ></span
  >
        </p>
        <p>
  <span
  ><span>ستحتفظ الشركة أيضًا ببيانات الاستخدام لأغراض التحليل الداخلي. </span
      ><span
      >يتم الاحتفاظ ببيانات الاستخدام عمومًا لفترة زمنية أقصر، إلا عندما يتم
      استخدام هذه البيانات لتعزيز الأمان أو لتحسين وظائف خدمتنا، أو عندما نكون
      ملزمين قانونًا بالاحتفاظ بهذه البيانات لفترات زمنية أطول.</span
      ></span
  >
        </p>
        <h3>
            <span><span>نقل بياناتك الشخصية</span></span>
        </h3>
        <p>
  <span
  ><span
      >تتم معالجة معلوماتك، بما في ذلك البيانات الشخصية، في مكاتب تشغيل الشركة
      وفي أي أماكن أخرى تتواجد فيها الأطراف المشاركة في المعالجة. </span
      ><span
      >وهذا يعني أنه قد يتم نقل هذه المعلومات إلى - والاحتفاظ بها - على أجهزة
      الكمبيوتر الموجودة خارج ولايتك أو مقاطعتك أو بلدك أو أي ولاية قضائية
      حكومية أخرى حيث قد تختلف قوانين حماية البيانات عن تلك الموجودة في ولايتك
      القضائية.</span
      ></span
  >
        </p>
        <p>
  <span
  ><span
      >إن موافقتك على سياسة الخصوصية هذه متبوعة بتقديمك لهذه المعلومات تمثل
      موافقتك على هذا النقل.</span
      ></span
  >
        </p>
        <p>
  <span
  ><span
      >ستتخذ الشركة جميع الخطوات الضرورية بشكل معقول لضمان التعامل مع بياناتك
      بشكل آمن ووفقًا لسياسة الخصوصية هذه ولن يتم نقل بياناتك الشخصية إلى منظمة
      أو دولة ما لم تكن هناك ضوابط كافية مطبقة بما في ذلك أمان بياناتك
      والمعلومات الشخصية الأخرى.</span
      ></span
  >
        </p>
        <h3>
            <span><span>احذف بياناتك الشخصية</span></span>
        </h3>
        <p>
  <span
  ><span
      >لديك الحق في حذف أو طلب المساعدة في حذف البيانات الشخصية التي جمعناها
      عنك.</span
      ></span
  >
        </p>
        <p>
  <span
  ><span
      >قد تمنحك خدمتنا القدرة على حذف معلومات معينة عنك من داخل الخدمة.</span
      ></span
  >
        </p>
        <p>
  <span
  ><span
      >يمكنك تحديث معلوماتك أو تعديلها أو حذفها في أي وقت عن طريق تسجيل الدخول
      إلى حسابك، إذا كان لديك حساب، وزيارة قسم إعدادات الحساب الذي يسمح لك
      بإدارة معلوماتك الشخصية. </span
      ><span
      >يمكنك أيضًا الاتصال بنا لطلب الوصول إلى أو تصحيح أو حذف أي معلومات شخصية
      قدمتها لنا.</span
      ></span
  >
        </p>
        <p>
  <span
  ><span
      >ومع ذلك، يرجى ملاحظة أننا قد نحتاج إلى الاحتفاظ بمعلومات معينة عندما يكون
      لدينا التزام قانوني أو أساس قانوني للقيام بذلك.</span
      ></span
  >
        </p>
        <h3>
            <span><span>الكشف عن بياناتك الشخصية</span></span>
        </h3>
        <h4>
            <span><span>المعاملات التجارية</span></span>
        </h4>
        <p>
  <span
  ><span
      >إذا كانت الشركة متورطة في عملية دمج أو استحواذ أو بيع أصول، فقد يتم نقل
      بياناتك الشخصية. </span
      ><span
      >سنقدم إشعارًا قبل نقل بياناتك الشخصية وقبل أن تصبح خاضعة لسياسة خصوصية
      مختلفة.</span
      ></span
  >
        </p>
        <h4>
            <span><span>تطبيق القانون</span></span>
        </h4>
        <p>
  <span
  ><span
      >في ظل ظروف معينة، قد يُطلب من الشركة الكشف عن بياناتك الشخصية إذا كان ذلك
      مطلوبًا بموجب القانون أو استجابة لطلبات صحيحة من السلطات العامة (مثل
      المحكمة أو وكالة حكومية).</span
      ></span
  >
        </p>
        <h4>
            <span><span>المتطلبات القانونية الأخرى</span></span>
        </h4>
        <p>
  <span
  ><span
      >يجوز للشركة الكشف عن بياناتك الشخصية بحسن نية أن هذا الإجراء ضروري من
      أجل:</span
      ></span
  >
        </p>
        <ul>
            <li>
                <span><span>الامتثال لالتزام قانوني</span></span>
            </li>
            <li>
                <span><span>حماية والدفاع عن حقوق أو ممتلكات الشركة</span></span>
            </li>
            <li>
    <span
    ><span>منع أو التحقيق في أي مخالفات محتملة فيما يتعلق بالخدمة</span></span
    >
            </li>
            <li>
                <span><span>حماية السلامة الشخصية لمستخدمي الخدمة أو الجمهور</span></span>
            </li>
            <li>
                <span><span>الحماية من المسؤولية القانونية</span></span>
            </li>
        </ul>
        <h3>
            <span><span>أمن بياناتك الشخصية</span></span>
        </h3>
        <p>
  <span
  ><span
      >يعد أمان بياناتك الشخصية أمرًا مهمًا بالنسبة لنا، ولكن تذكر أنه لا توجد
      طريقة نقل عبر الإنترنت أو طريقة تخزين إلكترونية آمنة بنسبة 100%. </span
      ><span
      >بينما نسعى جاهدين لاستخدام وسائل مقبولة تجاريًا لحماية بياناتك الشخصية،
      لا يمكننا ضمان أمانها المطلق.</span
      ></span
  >
        </p>
        <h2>
            <span><span>خصوصية الأطفال</span></span>
        </h2>
        <p>
  <span
  ><span
      >لا تتناول خدمتنا أي شخص يقل عمره عن 13 عامًا. نحن لا نجمع معلومات التعريف
      الشخصية عن قصد من أي شخص يقل عمره عن 13 عامًا. إذا كنت أحد الوالدين أو
      الوصي وكنت على علم بأن طفلك قد زودنا ببيانات شخصية، فيرجى اتصل بنا. </span
      ><span
      >إذا علمنا أننا قمنا بجمع بيانات شخصية من أي شخص يقل عمره عن 13 عامًا دون
      التحقق من موافقة الوالدين، فإننا نتخذ خطوات لإزالة تلك المعلومات من
      خوادمنا.</span
      ></span
  >
        </p>
        <p>
  <span
  ><span
      >إذا كنا بحاجة إلى الاعتماد على الموافقة كأساس قانوني لمعالجة معلوماتك
      وكان بلدك يتطلب موافقة أحد الوالدين، فقد نطلب موافقة والديك قبل أن نقوم
      بجمع تلك المعلومات واستخدامها.</span
      ></span
  >
        </p>
        <h2>
            <span><span>روابط لمواقع أخرى</span></span>
        </h2>
        <p>
  <span
  ><span>قد تحتوي خدمتنا على روابط لمواقع أخرى لا نقوم بإدارتها. </span
      ><span
      >إذا نقرت على رابط طرف ثالث، فسيتم توجيهك إلى موقع الطرف الثالث. </span
      ><span>ننصحك بشدة بمراجعة سياسة الخصوصية لكل موقع تزوره.</span></span
  >
        </p>
        <p>
  <span
  ><span
      >ليس لدينا أي سيطرة ولا نتحمل أي مسؤولية عن المحتوى أو سياسات الخصوصية أو
      الممارسات الخاصة بأي مواقع أو خدمات تابعة لجهات خارجية.</span
      ></span
  >
        </p>
        <h2>
            <span><span>التغييرات على سياسة الخصوصية هذه</span></span>
        </h2>
        <p>
  <span
  ><span>قد نقوم بتحديث سياسة الخصوصية الخاصة بنا من وقت لآخر. </span
      ><span
      >وسوف نقوم بإعلامك بأي تغييرات عن طريق نشر سياسة الخصوصية الجديدة على هذه
      الصفحة.</span
      ></span
  >
        </p>
        <p>
  <span
  ><span
      >سنخبرك عبر البريد الإلكتروني و/أو إشعار بارز على خدمتنا، قبل أن يصبح
      التغيير ساري المفعول ونقوم بتحديث تاريخ "آخر تحديث" في الجزء العلوي من
      سياسة الخصوصية هذه.</span
      ></span
  >
        </p>
        <p>
  <span
  ><span>ننصحك بمراجعة سياسة الخصوصية هذه بشكل دوري لمعرفة أي تغييرات. </span
      ><span
      >تصبح التغييرات التي يتم إجراؤها على سياسة الخصوصية هذه فعالة عند نشرها
      على هذه الصفحة.</span
      ></span
  >
        </p>
        <h2>
            <span><span>اتصل بنا</span></span>
        </h2>
        <p>
  <span
  ><span
      >إذا كانت لديك أي أسئلة حول سياسة الخصوصية هذه، يمكنك الاتصال بنا:</span
      ></span
  >
        </p>
        <ul>
            <li>
    <span><span>عبر البريد الإلكتروني: </span></span
    ><a href="mailto:info@noorakram.com" class="__cf_email__"
                ><span><span>info@noorakram.com</span></span></a
                >
            </li>
        </ul>

    </div>
@endsection