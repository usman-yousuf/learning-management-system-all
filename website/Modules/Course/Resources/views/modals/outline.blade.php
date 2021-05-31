
<div class="modal fade" id="add_outline" tabindex="-1" role="tabpanel" aria-labelledby="modal-head" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="diaglog">
            <div class="modal-content pb-5 ">
                <div class="modal-header border-0 ">
                    <div class="container ml-lg-4">
                        <div class="row pb-5 pt-3 ">
                            <div class="col ">
                                <h4 class="modal-title text-success" id="modal-head">Course Outline</h4>
                            </div>
                        </div>

                        @include('course::partials.course_outline', ['page' => 'dashboard', 'outlines' => []])
                    </div>
                </div>
            </div>
        </div>
    </div>
