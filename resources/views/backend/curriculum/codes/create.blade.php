<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Code</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="ajaxForm" class="modal-form" action="{{ route('admin.course_management.store_code') }}"
                      method="post">
                    @csrf
                    <div class="row no-gutters">


                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">Code</label>
                                <input type="text" class="form-control" name="code"
                                       value="{{\Illuminate\Support\Str::uuid()}}" placeholder="">
                                <p id="err_code" class="mt-1 mb-0 text-danger em"></p>
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">{{__('Courses')}}</label>
                                <select class="select2" name="courses" placeholder="Select Course">
                                    @foreach($courses as $course)

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
