<!-- Modal -->
<div class="modal fade" id="searchPopup" data-backdrop="static" data-keyboard="false" tabindex="-1"
     aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content search-content-modal">
            <div class="modal-header">
                <div class="modal-title">

                    <p>البحث في الدورات</p>
                </div>
                <button type="button" class="close"
                        data-dismiss="modal" aria-label="Close">
                    <span class="text-secondary" aria-hidden="true"><i class="fa fa-times"></i> </span>
                </button>
            </div>
            <form method="get" id="searchForm" action="{{route("courses")}}">
                <div class="modal-body">
                    <div class="input-box">
                        <div class="input-group">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <i class="fas fa-search" id="course-search-icon"
                                       onclick="document.getElementById('searchForm').submit()"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" placeholder="البحث في الدورات" name="keyword">

                        </div>
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="submit" class="btn btn-primary">ابدأ البحث</button>
                </div>

            </form>

        </div>
    </div>
</div>

<style>
    .input-box {
        position: relative;
    }


    .search-content-modal .close {
        opacity: 1 !important;
    }
</style>
