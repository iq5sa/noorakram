@extends('backend.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Course Enrolments') }}</h4>
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
                <a href="#">{{ __('Course Enrolments') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <form class="d-flex flex-row align-items-center"
                                          action="{{ route('admin.course_enrolments') }}"
                                          method="GET">
                                        <input name="order_id" type="text" class="form-control"
                                               placeholder="Search By Order ID"
                                               value="{{ !empty(request()->input('order_id')) ? request()->input('order_id') : '' }}">
                                    </form>
                                </div>

                                <div class="col-md-6 d-flex justify-content-end">
                                    <form class="d-flex ml-3 flex-row align-items-center" id="searchByStatusForm"
                                          action="{{ route('admin.course_enrolments') }}" method="GET">
                                        <label class="mr-2">Payment:</label>
                                        <select class="form-control" name="status"
                                                onchange="document.getElementById('searchByStatusForm').submit()">
                                            <option value="" {{ empty(request()->input('status')) ? 'selected' : '' }}>
                                                {{ __('All') }}
                                            </option>
                                            <option value="completed" {{ request()->input('status') == 'completed' ? 'selected' : '' }}>
                                                Completed
                                            </option>
                                            <option value="pending" {{ request()->input('status') == 'pending' ? 'selected' : '' }}>
                                                Pending
                                            </option>
                                            <option value="rejected" {{ request()->input('status') == 'rejected' ? 'selected' : '' }}>
                                                Rejected
                                            </option>
                                        </select>
                                    </form>
                                    <form id="searchByTypeForm" class="d-flex ml-3 flex-row align-items-center"
                                          action="{{ route('admin.course_enrolments') }}" method="GET">
                                        <label class="mr-2">Type:</label>
                                        <select class="form-control" name="type"
                                                onchange="document.getElementById('searchByTypeForm').submit()">
                                            <option value="all" {{ request()->input('type') =='all' ? 'selected' : '' }}>
                                                All
                                            </option>
                                            <option value="free" {{ request()->input('type') =='free' ? 'selected' : '' }}>
                                                Free
                                            </option>
                                            <option value="premium" {{ request()->input('type') =='premium' ? 'selected' : '' }}>
                                                Premium
                                            </option>
                                        </select>
                                    </form>

                                    <button class="btn btn-danger btn-sm d-none bulk-delete ml-3 mt-1"
                                            data-href="{{ route('admin.course_enrolments.bulk_delete') }}">
                                        <i class="flaticon-interface-5"></i> Delete
                                    </button>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (count($enrolments) == 0)
                                <h3 class="text-center mt-2">{{ __('NO ENROLMENT FOUND') . '!' }}</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3">
                                        <thead>
                                        <tr>
                                            <th scope="col">
                                                <input type="checkbox" class="bulk-check" data-val="all">
                                            </th>
                                            <th scope="col">Order ID</th>
                                            <th scope="col">Course</th>
                                            <th scope="col">Username</th>
                                            <th scope="col">Paid via</th>
                                            <th scope="col">Payment Status</th>
                                            <th scope="col">Created at</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($enrolments as $enrolment)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="bulk-check"
                                                           data-val="{{ $enrolment->id }}">
                                                </td>
                                                <td>{{ '#' . $enrolment->order_id }}</td>

                                                @php
                                                    $course = $enrolment->course()->first();
                                                    $courseInfo = $course->information()->where('language_id', $defaultLang->id)->first();
                                                    $title = $courseInfo->title;
                                                    $slug = $courseInfo->slug;
                                                @endphp

                                                <td>
                                                    <a href="{{ route('course_details', ['slug' => $slug]) }}"
                                                       target="_blank">
                                                        {{ strlen($title) > 35 ? mb_substr($title, 0, 35, 'utf-8') . '...' : $title }}
                                                    </a>
                                                </td>

                                                @php
                                                    $user = $enrolment->userInfo()->first();
                                                @endphp


                                                <td>{{ $user->username }}</td>
                                                <td>
                                                    @if(empty($enrolment->payment_method))
                                                        Free
                                                    @else
                                                        {{$enrolment->payment_method}}
                                                    @endif

                                                </td>


                                                <td>
                                                    @if ($enrolment->payment_status =='completed')
                                                        <h2 class="d-inline-block"><span class="badge badge-success">Completed</span>
                                                        </h2>
                                                    @elseif($enrolment->payment_status =='pending')
                                                        <h2 class="d-inline-block"><span class="badge badge-warning">Pending</span>
                                                        </h2>

                                                    @elseif($enrolment->payment_status =='rejected')
                                                        <h2 class="d-inline-block"><span class="badge badge-danger">Rejected</span>
                                                        </h2>

                                                    @endif
                                                </td>
                                                <td>
                                                    {{$enrolment->created_at}}
                                                </td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-secondary btn-sm dropdown-toggle"
                                                                type="button"
                                                                id="dropdownMenuButton" data-toggle="dropdown"
                                                                aria-haspopup="true"
                                                                aria-expanded="false">
                                                            {{ __('Select') }}
                                                        </button>

                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            <a href="{{ route('admin.course_enrolment.details', ['id' => $enrolment->id]) }}"
                                                               class="dropdown-item">
                                                                {{ __('Details') }}
                                                            </a>

                                                            <a href="{{ asset('/file/invoices/' . $enrolment->invoice) }}"
                                                               class="dropdown-item"
                                                               target="_blank">
                                                                {{ __('Invoice') }}
                                                            </a>

                                                            <form class="deleteForm d-block"
                                                                  action="{{ route('admin.course_enrolment.delete', ['id' => $enrolment->id]) }}"
                                                                  method="post">

                                                                @csrf
                                                                <button type="submit" class="deleteBtn">
                                                                    {{ __('Delete') }}
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>

                                            @includeIf('backend.curriculum.enrolment.show-attachment')
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-footer text-center">
                    <div class="d-inline-block mt-3">
                        {{ $enrolments->appends([
                          'order_id' => request()->input('order_id'),
                          'status' => request()->input('status'),
                          'type' => request()->input('type')
                        ])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
