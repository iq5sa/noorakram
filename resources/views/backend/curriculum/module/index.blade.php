@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl-style')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Modules') }}</h4>
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
                <a href="#">{{ __('Course Management') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="{{route('admin.course_management.courses', ['language' => $defaultLang->code])}}">{{ __('Courses') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            @if (!empty($courseInformation))
                <li class="nav-item">
                    <a href="#">{{ strlen($courseInformation->title) > 35 ? mb_substr($courseInformation->title, 0, 35, 'UTF-8') . '...' : $courseInformation->title }}</a>
                </li>
            @endif
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Modules') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card-title d-inline-block">
                                {{ __('Modules') . ' (' . $language->name . ' ' . __('Language') . ')' }}
                            </div>
                        </div>

                        <div class="col-lg-3">
                            @if (!empty($langs))
                                <select name="language" class="form-control"
                                        onchange="window.location='{{ url()->current() . '?language=' }}' + this.value">
                                    <option selected disabled>{{ __('Select a Language') }}</option>
                                    @foreach ($langs as $lang)
                                        <option value="{{ $lang->code }}" {{ $lang->code == $language->code ? 'selected' : '' }}>
                                            {{ $lang->name }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif

                        </div>

                        <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
                            <a class="btn btn-info btn-sm float-right ml-2"
                               href="{{ route('admin.course_management.courses', ['language' => $defaultLang->code]) }}">
                <span class="btn-label">
                  <i class="fas fa-backward"></i>
                </span>
                                {{ __('Back') }}
                            </a>

                            <a href="#" data-toggle="modal" data-target="#createModal"
                               class="btn btn-primary btn-sm float-lg-right float-left"><i
                                        class="fas fa-plus"></i> {{ __('Add Module') }}</a>

                            <button class="btn btn-danger float-right btn-sm mr-2 d-none bulk-delete"
                                    data-href="{{ route('admin.course_management.course.bulk_delete_module') }}">
                                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (count($modules) == 0)
                                <h3 class="text-center mt-2">{{ __('NO MODULE FOUND') . '!' }}</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3" id="basic-datatables">
                                        <thead>
                                        <tr>
                                            <th scope="col">
                                                <input type="checkbox" class="bulk-check" data-val="all">
                                            </th>
                                            <th scope="col">{{ __('Title') }}</th>
                                            <th scope="col">{{ __('Status') }}</th>
                                            <th scope="col">{{ __('Serial Number') }}</th>
                                            <th scope="col">{{ __('Actions') }}</th>
                                            <th scope="col">{{ __('Lesson') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($modules as $module)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="bulk-check"
                                                           data-val="{{ $module->id }}">
                                                </td>
                                                <td width="20%">
                                                    {{$module->title}}
                                                </td>
                                                <td>
                                                    @if ($module->status == 'draft')
                                                        <span class="badge badge-warning">{{ ucfirst($module->status) }}</span>
                                                    @else
                                                        <span class="badge badge-primary">{{ ucfirst($module->status) }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $module->serial_number }}</td>
                                                <td>
                                                    <a class="btn btn-secondary btn-sm mr-1 editBtn" href="#"
                                                       data-toggle="modal" data-target="#editModal"
                                                       data-id="{{ $module->id }}" data-title="{{ $module->title }}"
                                                       data-status="{{ $module->status }}"
                                                       data-serial_number="{{ $module->serial_number }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                                                        {{ __('Edit') }}
                                                    </a>

                                                    <form class="deleteForm d-inline-block"
                                                          action="{{ route('admin.course_management.course.delete_module', ['id' => $module->id]) }}"
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
                                                <td>
                                                    <a href="#" data-toggle="modal"
                                                       data-target="#createLessonModal-{{ $module->id }}"
                                                       class="btn btn-primary btn-sm mr-1">
                              <span class="btn-label">
                                <i class="fas fa-plus"></i>
                              </span>
                                                        {{ __('Add') }}
                                                    </a>

                                                    <a href="#" data-toggle="modal"
                                                       data-target="#viewLessonModal-{{ $module->id }}"
                                                       class="btn btn-success btn-sm">
                              <span class="btn-label">
                                <i class="fas fa-eye"></i>
                              </span>
                                                        {{ __('View') }}
                                                    </a>


                                                    {{-- view modal (lesson) --}}
                                                    @include('backend.curriculum.lesson.index')

                                                    {{-- create modal (lesson) --}}
                                                    @include('backend.curriculum.lesson.create')
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
    @include('backend.curriculum.module.create')

    {{-- edit modal --}}
    @include('backend.curriculum.module.edit')

    {{-- edit modal (lesson) --}}
    @include('backend.curriculum.lesson.edit')
@endsection
