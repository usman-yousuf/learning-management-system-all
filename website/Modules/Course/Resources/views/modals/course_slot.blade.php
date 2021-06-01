@php
    $slots = isset($slots)? $slots : [];
@endphp
    <div class="modal fade" id="add_slot_modal" tabindex="-1" role="tabpanel" aria-labelledby="modal-head" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="diaglog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="container mb-3 pb-3 ">
                        <!--Modal Header-->
                        <div class="row ml-3 ">
                            <div class="mt-3 mb-3 ">
                                <h4 class="modal-title text-success" id="modal-head">Course Slots</h4>
                            </div>
                        </div>
                        <!--Modal Header End-->
                        <!--modal body-->
                        @include('course::partials.course_slot', ['page' => 'dashboard', 'slots' => []])
                    </div>
                </div>
            </div>
        </div>
    </div>
