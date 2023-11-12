<script>
    "use strict";
    const baseURL = "{{ url('/') }}";
    const vapid_public_key = "{{ env('VAPID_PUBLIC_KEY') }}";
    const langDir = {{ $currentLanguageInfo->direction }};
</script>

{{-- jQuery --}}
<script type="text/javascript" src="{{ asset('/js/jquery-1.12.4.min.js') }}"></script>

{{-- modernizr js --}}
<script type="text/javascript" src="{{ asset('/js/modernizr-3.6.0.min.js') }}"></script>

{{-- popper js --}}
<script type="text/javascript" src="{{ asset('/js/popper.min.js') }}"></script>

{{-- bootstrap js --}}
<script type="text/javascript" src="{{ asset('/js/bootstrap.min.js') }}"></script>

{{-- slick js --}}
<script type="text/javascript" src="{{ asset('/js/slick.min.js') }}"></script>

{{-- isotope-pkgd js --}}
<script type="text/javascript" src="{{ asset('/js/isotope-pkgd-3.0.6.min.js') }}"></script>

{{-- imagesloaded-pkgd js --}}
<script type="text/javascript" src="{{ asset('/js/imagesloaded.pkgd.min.js') }}"></script>

{{-- magnific-popup js --}}
<script type="text/javascript" src="{{ asset('/js/jquery.magnific-popup.min.js') }}"></script>

{{-- owl-carousel js --}}
<script type="text/javascript" src="{{ asset('/js/owl-carousel.min.js') }}"></script>

{{-- nice-select js --}}
<script type="text/javascript" src="{{ asset('/js/jquery.nice-select.min.js') }}"></script>

{{-- wow js --}}
<script type="text/javascript" src="{{ asset('/js/wow.min.js') }}"></script>

{{-- jquery-counterup js --}}
<script type="text/javascript" src="{{ asset('/js/jquery.counterup.min.js') }}"></script>

{{-- waypoints js --}}
<script type="text/javascript" src="{{ asset('/js/waypoints.min.js') }}"></script>

{{-- toastr js --}}
<script type="text/javascript" src="{{ asset('/js/toastr.min.js') }}"></script>

{{-- datatables js --}}
<script type="text/javascript" src="{{ asset('/js/datatables-1.10.23.min.js') }}"></script>

{{-- datatables bootstrap js --}}
<script type="text/javascript" src="{{ asset('/js/datatables.bootstrap4.min.js') }}"></script>
<script type="text/javascript" src="{{asset('/js/dataTables.responsive.min.js')}}"></script>


{{-- highlight js --}}
<script type="text/javascript" src="{{ asset('/js/highlight.pack.js') }}"></script>

{{-- jQuery-ui js --}}
<script type="text/javascript" src="{{ asset('/js/jquery-ui.min.js') }}"></script>

{{-- jQuery-syotimer js --}}
<script type="text/javascript" src="{{ asset('/js/jquery-syotimer.min.js') }}"></script>

@if (session()->has('success'))
    <script>
        "use strict";
        toastr['success']("{{ __(session('success')) }}");
    </script>
@endif

@if (session()->has('error'))
    <script>
        "use strict";
        toastr['error']("{{ __(session('error')) }}");
    </script>
@endif

@if (session()->has('warning'))
    <script>
        "use strict";
        toastr['warning']("{{ __(session('warning')) }}");
    </script>
@endif


{{-- vanilla-lazyload js --}}
<script type="text/javascript" src="{{ asset('/js/vanilla-lazyload.min.js') }}"></script>

@if (request()->routeIs('user.my_course.curriculum'))
    {{-- video js --}}
    <script type="text/javascript" src="{{ asset('/js/video.min.js') }}"></script>
@endif

{{-- main js --}}
<script type="text/javascript" src="{{ asset('/js/main.js') }}"></script>

{{-- push-notification js --}}
<script type="text/javascript" src="{{ asset('/js/push-notification.js') }}"></script>
