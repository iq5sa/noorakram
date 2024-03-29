@extends('backend.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">
            Report
        </h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{route('admin.dashboard')}}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">Course Enrolments</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">Report</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header p-1">
                    <div class="row">
                        <div class="col-lg-10">
                            <form action="{{url()->full()}}" class="form-inline">
                                <div class="form-group">
                                    <label for="">From</label>
                                    <input class="form-control datepicker" type="text" name="from_date"
                                           placeholder="From"
                                           value="{{request()->input('from_date') ? request()->input('from_date') : '' }}"
                                           required
                                           autocomplete="off">
                                </div>

                                <div class="form-group">
                                    <label for="">To</label>
                                    <input class="form-control datepicker ml-1" type="text" name="to_date"
                                           placeholder="To"
                                           value="{{request()->input('to_date') ? request()->input('to_date') : '' }}"
                                           required
                                           autocomplete="off">
                                </div>

                                <div class="form-group">
                                    <label for="">Payment Method</label>
                                    <select name="payment_method" class="form-control ml-1">
                                        <option value="" selected>All</option>
                                        @if (!empty($onPms))
                                            @foreach ($onPms as $onPm)
                                                <option
                                                        value="{{$onPm->keyword}}" {{request()->input('payment_method') == $onPm->keyword ? 'selected' : ''}}>{{$onPm->name}}</option>
                                            @endforeach
                                        @endif
                                        @if (!empty($offPms))
                                            @foreach ($offPms as $offPm)
                                                <option
                                                        value="{{$offPm->name}}" {{request()->input('payment_method') == $offPm->name ? 'selected' : ''}}>{{$offPm->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="">Payment Status</label>
                                    <select name="payment_status" class="form-control ml-1">
                                        <option value="" selected>All</option>
                                        <option value="Pending" {{request()->input('payment_status') == 'Pending' ? 'selected' : ''}}>
                                            Pending
                                        </option>
                                        <option value="Completed" {{request()->input('payment_status') == 'Completed' ? 'selected' : ''}}>
                                            Completed
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-sm ml-1">Submit</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-2">
                            <form action="{{route('admin.course_enrolments.export')}}"
                                  class="form-inline justify-content-end">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success btn-sm ml-1" title="CSV Format">
                                        Export
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (count($enrolments) > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3">
                                        <thead>
                                        <tr>
                                            <th scope="col">Order</th>
                                            <th scope="col">Course</th>
                                            <th scope="col">Price</th>
                                            <th scope="col">Discount</th>
                                            <th scope="col">Total</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Phone</th>
                                            <th scope="col">City</th>
                                            <th scope="col">State</th>
                                            <th scope="col">Country</th>
                                            <th scope="col">Gateway</th>
                                            <th scope="col">Payment</th>
                                            <th scope="col">Date</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($enrolments as $key => $enrolment)
                                            <tr>
                                                <td>#{{$enrolment->order_id}}</td>

                                                @php
                                                    $courseInfo = $enrolment->course->information()->where('language_id', $deLang->id)->select('title', 'slug')->first();
                                                    $title = $courseInfo->title;
                                                    $slug = $courseInfo->slug;
                                                @endphp
                                                <td><a href="{{route('course_details', ['slug' => $slug])}}"
                                                       target="_blank">{{strlen($title) > 35 ? mb_substr($title, 0, 35, 'utf-8') . '...' : $title}}</a>
                                                </td>

                                                <td>{{$abs->base_currency_symbol_position == 'left' ? $abs->base_currency_symbol : ''}}{{round($enrolment->course_price,2)}}{{$abs->base_currency_symbol_position == 'right' ? $abs->base_currency_symbol : ''}}</td>

                                                <td>{{$abs->base_currency_symbol_position == 'left' ? $abs->base_currency_symbol : ''}}{{round($enrolment->discount,2)}}{{$abs->base_currency_symbol_position == 'right' ? $abs->base_currency_symbol : ''}}</td>

                                                <td>{{$abs->base_currency_symbol_position == 'left' ? $abs->base_currency_symbol : ''}}{{round($enrolment->grand_total,2)}}{{$abs->base_currency_symbol_position == 'right' ? $abs->base_currency_symbol : ''}}</td>

                                                <td>{{$enrolment->billing_first_name}}</td>
                                                <td>{{$enrolment->billing_email}}</td>
                                                <td>{{$enrolment->billing_contact_number}}</td>
                                                <td>{{$enrolment->billing_city}}</td>
                                                <td>{{$enrolment->billing_state}}</td>
                                                <td>{{$enrolment->billing_country}}</td>
                                                <td>{{ucfirst($enrolment->payment_method)}}</td>
                                                <td>
                                                    @if ($enrolment->payment_status == 'Pending')
                                                        <span class="badge badge-warning">Pending</span>
                                                    @elseif ($enrolment->payment_status == 'Completed')
                                                        <span class="badge badge-success">Completed</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{$enrolment->created_at}}
                                                </td>
                                            </tr>


                                            {{-- Receipt Modal --}}
                                            <div class="modal fade" id="receiptModal{{$enrolment->id}}" tabindex="-1"
                                                 role="dialog"
                                                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Receipt
                                                                Image</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <img src="{{asset('/front/receipt/' . $enrolment->receipt)}}"
                                                                 alt="Receipt"
                                                                 width="100%">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Close
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            @endif
                        </div>
                    </div>
                </div>

                @if (!empty($enrolments))
                    <div class="card-footer">
                        <div class="row">
                            <div class="d-inline-block mx-auto">
                                {{$enrolments->links()}}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection
