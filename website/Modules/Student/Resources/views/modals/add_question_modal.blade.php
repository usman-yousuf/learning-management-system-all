<!--add comment modal-->
<div class="modal fade" id="ask_question-d-{{ $course_detail->uuid }}" tabindex="-1" role="comment" aria-labelledby="view-head" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header d-block">
                <div class="container">
                    <!--modal header-->
                    <div class="row">
                        <div class="col-12 text-right">
                            <a class="close pt-3 pr-0" data-dismiss="modal" aria-label="Close">
                                <img class="float-right" src="{{ asset('assets/images/cancel_circle.svg') }}" alt="">
                            </a>
                        </div>
                    </div>
                    <!--modal header end-->

                    <!--VIEW MODAL BODY-->
                    <form action="{{ route('quiz.addQuestion', $course_detail->uuid) }}" id="add_course_question-d" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="row pt-5">
                                <div class="col-12 ">
                                    <h4 class='text-wrap text-break'><strong>{{ $course_detail->title }}</strong></h4>
                                    <textarea class="form-control bg-light rounded-4 pt-2 mt-5" name="body" placeholder="Type your question......" id="" rows="6"></textarea>
                                </div>
                            </div>
                        </div>
                        <!--view modal body end-->
                        <!-- Modal footer -->
                        <div class="modal-footer border-0 mb-5 mt-xl-5 mt-lg-5 mt-sm-5 mt-3 justify-content-center">
                            <button type="submit" class="bg_success-s br_24-s py-2 px-5 text-white  border border-white ">
                                Send
                            </button>
                        </div>
                    </form>
                    <!-- Modal footer End -->
                </div>
            </div>
        </div>
    </div>
</div>
<!--add comment modal end-->
