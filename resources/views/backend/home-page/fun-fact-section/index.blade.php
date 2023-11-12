@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl-style')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Fun Facts Section') }}</h4>
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
                <a href="#">{{ __('Home Page') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Fun Facts Section') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-10">
                            <div class="card-title">{{ __('Update Fun Facts Section') }}</div>
                        </div>

                        <div class="col-lg-2">
                            @includeIf('backend.partials.languages')
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 offset-lg-3">
                            <form id="factForm"
                                  action="{{ route('admin.home_page.update_fun_facts_section', ['language' => request()->input('language')]) }}"
                                  method="POST" enctype="multipart/form-data">
                                @csrf


                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="">{{ __('Title') }}</label>
                                            <input type="text" class="form-control" name="title"
                                                   value="{{ empty($data->title) ? '' : $data->title }}"
                                                   placeholder="Enter Section Title">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="submit" form="factForm" class="btn btn-success">
                                {{ __('Update') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card-title">{{ __('Counter Informations') }}</div>
                        </div>

                        <div class="col-lg-3">
                            @includeIf('backend.partials.languages')
                        </div>

                        <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
                            <a href="#" data-toggle="modal" data-target="#createModal"
                               class="btn btn-primary btn-sm float-lg-right float-left"><i
                                        class="fas fa-plus"></i> {{ __('Add') }}
                            </a>

                            <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete"
                                    data-href="{{ route('admin.home_page.bulk_delete_counter_info') }}">
                                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            @if (count($countInfos) == 0)
                                <h3 class="text-center mt-2">{{ __('NO INFORMATION FOUND') . '!' }}</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3" id="basic-datatables">
                                        <thead>
                                        <tr>
                                            <th scope="col">
                                                <input type="checkbox" class="bulk-check" data-val="all">
                                            </th>

                                            <th scope="col">{{ __('Icon') }}</th>

                                            <th scope="col">{{ __('Title') }}</th>
                                            <th scope="col">{{ __('Amount') }}</th>
                                            <th scope="col">{{ __('Serial Number') }}</th>
                                            <th scope="col">{{ __('Actions') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($countInfos as $countInfo)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="bulk-check"
                                                           data-val="{{ $countInfo->id }}">
                                                </td>

                                                <td>
                                                    @if (is_null($countInfo->icon))
                                                        -
                                                    @else
                                                        <i class="{{ $countInfo->icon }}"></i>
                                                    @endif
                                                </td>

                                                <td>
                                                    {{ strlen($countInfo->title) > 30 ? mb_substr($countInfo->title, 0, 30, 'UTF-8') . '...' : $countInfo->title }}
                                                </td>
                                                <td>{{ $countInfo->amount }}</td>
                                                <td>{{ $countInfo->serial_number }}</td>
                                                <td>
                                                    <a class="btn btn-secondary btn-sm mr-1 editBtn" href="#"
                                                       data-toggle="modal"
                                                       data-target="#editModal" data-id="{{ $countInfo->id }}"
                                                       data-icon="{{ $countInfo->icon }}"
                                                       data-color="{{ $countInfo->color }}"
                                                       data-title="{{ $countInfo->title }}"
                                                       data-amount="{{ $countInfo->amount }}"
                                                       data-serial_number="{{ $countInfo->serial_number }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                                                        {{ __('Edit') }}
                                                    </a>

                                                    <form class="deleteForm d-inline-block"
                                                          action="{{ route('admin.home_page.delete_counter_info', ['id' => $countInfo->id]) }}"
                                                          method="post">

                                                        @csrf
                                                        <button type="submit" class="btn btn-danger btn-sm deleteBtn">
                                <span class="btn-label">
                                  <i class="fas fa-trash"></i>
                                </span>
                                                            {{ __('Delete') }}
                                                        </button>
                                                    </form>
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

                <div class="card-footer"></div>
            </div>
        </div>
    </div>

    {{-- create modal --}}
    @include('backend.home-page.fun-fact-section.create')

    {{-- edit modal --}}
    @include('backend.home-page.fun-fact-section.edit')
@endsection
