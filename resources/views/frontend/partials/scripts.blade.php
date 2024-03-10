<script>
  "use strict";
  const baseURL = "{{ url('https://noorakram.com') }}";
  const vapid_public_key = "{{ env('VAPID_PUBLIC_KEY') }}";
  const langDir = {{ $currentLanguageInfo->direction }};
</script>

{{-- jQuery --}}
<script type="text/javascript" src="{{ asset('js/jquery-1.12.4.min.js?v=' . config('app_version')) }}"></script>

{{-- modernizr js --}}
<script type="text/javascript" src="{{ asset('js/modernizr-3.6.0.min.js?v=' . config('app_version')) }}"></script>

{{-- popper js --}}
<script type="text/javascript" src="{{ asset('js/popper.min.js?v=' . config('app_version')) }}"></script>

{{-- bootstrap js --}}
<script type="text/javascript" src="{{ asset('js/bootstrap.min.js?v=' . config('app_version')) }}"></script>

{{-- slick js --}}
<script type="text/javascript" src="{{ asset('js/slick.min.js?v=' . config('app_version')) }}"></script>

{{-- isotope-pkgd js --}}
<script type="text/javascript" src="{{ asset('js/isotope-pkgd-3.0.6.min.js?v=' . config('app_version')) }}"></script>

{{-- imagesloaded-pkgd js --}}
<script type="text/javascript" src="{{ asset('js/imagesloaded.pkgd.min.js?v=' . config('app_version')) }}"></script>

{{-- magnific-popup js --}}
<script type="text/javascript" src="{{ asset('js/jquery.magnific-popup.min.js?v=' . config('app_version')) }}"></script>

{{-- owl-carousel js --}}
<script type="text/javascript" src="{{ asset('js/owl-carousel.min.js?v=' . config('app_version')) }}"></script>

{{-- nice-select js --}}
<script type="text/javascript" src="{{ asset('js/jquery.nice-select.min.js?v=' . config('app_version')) }}"></script>

{{-- wow js --}}
<script type="text/javascript" src="{{ asset('js/wow.min.js?v=' . config('app_version')) }}"></script>

{{-- jquery-counterup js --}}
<script type="text/javascript" src="{{ asset('js/jquery.counterup.min.js?v=' . config('app_version')) }}"></script>

{{-- waypoints js --}}
<script type="text/javascript" src="{{ asset('js/waypoints.min.js?v=' . config('app_version')) }}"></script>

{{-- toastr js --}}
<script type="text/javascript" src="{{ asset('js/toastr.min.js?v=' . config('app_version')) }}"></script>

{{-- datatables js --}}
<script type="text/javascript" src="{{ asset('js/datatables-1.10.23.min.js?v=' . config('app_version')) }}"></script>

{{-- datatables bootstrap js --}}
<script type="text/javascript" src="{{ asset('js/datatables.bootstrap4.min.js?v=' . config('app_version')) }}"></script>
<script type="text/javascript" src="{{asset('js/dataTables.responsive.min.js?v=' . config('app_version'))}}"></script>


{{-- highlight js --}}
<script type="text/javascript" src="{{ asset('js/highlight.pack.js?v=' . config('app_version')) }}"></script>

{{-- jQuery-ui js --}}
<script type="text/javascript" src="{{ asset('js/jquery-ui.min.js?v=' . config('app_version')) }}"></script>

{{-- jQuery-syotimer js --}}
<script type="text/javascript" src="{{ asset('js/jquery-syotimer.min.js?v=' . config('app_version')) }}"></script>

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
<script type="text/javascript" src="{{ asset('js/vanilla-lazyload.min.js?v=' . config('app_version')) }}"></script>

@if (request()->routeIs('user.my_course.curriculum'))
  {{-- video js --}}
  <script type="text/javascript" src="{{ asset('js/video.min.js?v=' . config('app_version')) }}"></script>
@endif

{{-- main js --}}
<script type="text/javascript" src="{{ asset('js/main.js?v=' . config('app_version')) }}"></script>

{{-- push-notification js --}}
<script type="text/javascript" src="{{ asset('js/push-notification.js?v=' . config('app_version')) }}"></script>
