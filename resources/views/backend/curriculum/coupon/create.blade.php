<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Coupon') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="ajaxForm" class="modal-form" action="{{ route('admin.course_management.store_coupon') }}"
                      method="post">
                    @csrf
                    <div class="row no-gutters">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">{{ __('Name') . '*' }}</label>
                                <input type="text" class="form-control" name="name" placeholder="Enter Coupon Name">
                                <p id="err_name" class="mt-1 mb-0 text-danger em"></p>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">{{ __('Code') . '*' }}</label>
                                <input type="text" class="form-control" name="code" placeholder="Enter Coupon Code">
                                <p id="err_code" class="mt-1 mb-0 text-danger em"></p>
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">{{ __('Coupon Type') . '*' }}</label>
                                <select name="type" class="form-control">
                                    <option selected disabled>{{ __('Select a Type') }}</option>
                                    <option value="fixed">{{ __('Fixed') }}</option>
                                    <option value="percentage">{{ __('Percentage') }}</option>
                                </select>
                                <p id="err_type" class="mt-1 mb-0 text-danger em"></p>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">{{ __('Value') . '*' }}</label>
                                <input type="number" step="0.01" class="form-control" name="value"
                                       placeholder="Enter Coupon Value">
                                <p id="err_value" class="mt-1 mb-0 text-danger em"></p>
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">{{ __('Start Date') . '*' }}</label>
                                <input type="text" class="form-control datepicker" name="start_date"
                                       placeholder="Enter Start Date">
                                <p id="err_start_date" class="mt-1 mb-0 text-danger em"></p>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">{{ __('End Date') . '*' }}</label>
                                <input type="text" class="form-control datepicker" name="end_date"
                                       placeholder="Enter End Date">
                                <p id="err_end_date" class="mt-1 mb-0 text-danger em"></p>
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">{{__('Courses')}}</label>
                                <select class="select2" name="courses[]" multiple="multiple"
                                        placeholder="Select Courses">
                                    @foreach ($courses as $course)
                                        @php
                                            $courseInfo = $course->information()->where('language_id', $deLang->id)->select('title', 'id')->first();
                                            $title = $courseInfo->title;
                                            $id = $course->id;
                                        @endphp
                                        <option value="{{$id}}">
                                            {{$title}}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="mb-0 text-warning">This coupon can be applied to these courses</p>
                                <p class="mb-0 text-warning">Leave this field blank for all courses</p>
                                <p id="errcourses" class="mb-0 text-danger em"></p>
                            </div>
                        </div>
                    </div>

                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">
                    {{ __('Close') }}
                </button>
                <button id="submitBtn" type="button" class="btn btn-sm btn-primary">
                    {{ __('Save') }}
                </button>
            </div>
        </div>
    </div>
</div>
