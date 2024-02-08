@php use Carbon\Carbon; @endphp

<div class="single-courses">
  <div class="courses-thumb">
    <a href="{{ route('course_details', ['slug' => $course->slug]) }}" class="d-block">
      <img
        data-src="{{ asset('/img/courses/thumbnails/' . $course->thumbnail_image)  }}"
        data-lazy="{{ asset('/img/courses/thumbnails/' . $course->thumbnail_image)  }}"
        class="lazy entered loaded" alt="image">
    </a>
  </div>

  <div class="courses-content">
    <a href="{{ route('course_details', ['slug' => $course->slug]) }}">
      <h4 class="title text-dark">
        {{ strlen($course->title) > 45 ? mb_substr($course->title, 0, 45, 'UTF-8') . '...' : $course->title }}
      </h4>
    </a>


    <div class="courses-info d-flex justify-content-between align-items-center">
      <div class="item d-flex align-items-center">
        <img alt="instructor" src="{{ asset('/img/instructors/' . $course->instructorImage) }}">
        <p class="text-sm">
          {{ strlen($course->instructorName) > 10 ? mb_substr($course->instructorName, 0, 10, 'utf-8') : $course->instructorName }}
        </p>
      </div>

      <div class="price">
        @if ($course->pricing_type == 'premium')
          <span title="السعر بعد الخصم">
                        {{ $currencyInfo->base_currency_symbol_position == 'left' ? $currencyInfo->base_currency_symbol : '' }}
            {{ number_format($course->current_price) }}
            {{ $currencyInfo->base_currency_symbol_position == 'right' ? $currencyInfo->base_currency_symbol : '' }}
                    </span>

          @if (!is_null($course->previous_price))
            <span title="السعر السابق" class="pre-price">
                            {{ $currencyInfo->base_currency_symbol_position == 'left' ? $currencyInfo->base_currency_symbol : '' }}
              {{ number_format($course->previous_price) }}
              {{ $currencyInfo->base_currency_symbol_position == 'right' ? $currencyInfo->base_currency_symbol : '' }}
                        </span>
          @endif
        @else
          <span class="text-primary">مجاناً</span>
        @endif
      </div>
    </div>

    @php
      $period = $course->duration;
      $array = explode(':', $period);
      $hour = $array[0];
      $courseDuration = Carbon::parse($period);
    @endphp

    <ul class="d-flex justify-content-center">
      <li>
        <!-- Display Course Type Icons -->
        @if ($course->type == 'online')
          <i class="fas fa-globe"></i>على الانترنت
          <!-- Replace 'fas fa-globe' with the appropriate Font Awesome icon for online courses -->
        @elseif ($course->type == 'onsite')
          <i class="fal fa-map-marker-alt"></i>على ارض الواقع
          <!-- Replace 'fas fa-map-marker-alt' with the appropriate Font Awesome icon for onsite courses -->
        @endif
      </li>
      <li><i class="fal fa-clock"></i>
        {{ intval($courseDuration->format('h')) . ' س ' . intval($courseDuration->format('i')) . ' د ' }}

      </li>
    </ul>
  </div>
</div>
