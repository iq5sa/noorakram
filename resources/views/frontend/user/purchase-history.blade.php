@extends('frontend.layout')

@section('pageHeading')
    {{ 'سجل المشتريات'}}
@endsection

@section('content')
    @includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $bgImg->breadcrumb, 'title' => 'سجل المشتريات'])

    <!-- Start Purchase History Section -->
    <section class="user-dashboard" id="scrollTarget">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="account-info">
                                <div class="title">
                                    <h4>{{ 'سجل المشتريات' }}</h4>
                                </div>

                                <div class="main-info">
                                    <div class="main-table">
                                        @if (count($allPurchase) == 0)
                                            <h5 class="text-center mt-3">{{ __('No Information Found') . '!' }}</h5>
                                        @else
                                            <div class="table-responsive">
                                                <table id="user-dataTable"
                                                       class="display dataTables_wrapper dt-responsive table-striped dt-bootstrap4">
                                                    <thead>
                                                    <tr>
                                                        <th>رقم الطلب</th>
                                                        <th>{{ __('Date') }}</th>
                                                        <th>{{ __('Course') }}</th>
                                                        <th>{{ __('Price') }}</th>
                                                        <th>وسيلة الدفع</th>
                                                        <th>حالة الدفع</th>
                                                        <th>{{ __('Invoice') }}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach ($allPurchase as $purchase)
                                                        <tr>
                                                            <td>{{ '#' . $purchase->order_id }}</td>

                                                            <td>{{ date_format($purchase->created_at, 'M d, Y') }}</td>

                                                            <td>
                                                                <a target="_blank"
                                                                   href="{{ route('course_details', ['slug' => $purchase->slug]) }}">
                                                                    {{ strlen($purchase->title) > 30 ? mb_substr($purchase->title, 0, 30, 'UTF-8') . '...' : $purchase->title }}
                                                                </a>
                                                            </td>

                                                            <td class="text-center">
                                                                @if (!is_null($purchase->course_price))
                                                                    {{ number_format($purchase->course_price) }} د.ع
                                                                @else
                                                                    {{ __('Free') }}
                                                                @endif
                                                            </td>

                                                            <td class="text-center">
                                                                @if (is_null($purchase->payment_method))
                                                                    ...
                                                                @else
                                                                    {{ $purchase->payment_method }}
                                                                @endif
                                                            </td>

                                                            <td class="text-center">
                                                                @if ($purchase->payment_status == 'completed')
                                                                    <span
                                                                            class="completed {{ $currentLanguageInfo->direction == 1 ? 'mr-2' : 'ml-2' }}">{{ __('Completed') }}</span>
                                                                @elseif ($purchase->payment_status == 'pending')
                                                                    <span
                                                                            class="pending {{ $currentLanguageInfo->direction == 1 ? 'mr-2' : 'ml-2' }}">{{ __('Pending') }}</span>
                                                                @else
                                                                    <span
                                                                            class="rejected {{ $currentLanguageInfo->direction == 1 ? 'mr-2' : 'ml-2' }}">{{ __('Rejected') }}</span>
                                                                @endif
                                                            </td>

                                                            <td>
                                                                @if (is_null($purchase->invoice))
                                                                    غير متوفرة
                                                                @else
                                                                    <a href="{{ asset('/file/invoices/' . $purchase->invoice) }}"
                                                                       class="btn"
                                                                       target="_blank">
                                                                        {{ __('Show') }}
                                                                    </a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Purchase History Section -->
@endsection
