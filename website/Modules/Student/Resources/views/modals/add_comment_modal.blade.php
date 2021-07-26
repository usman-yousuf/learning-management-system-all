 <!--add comment modal-->
 <div class="modal fade" id="add_comment-d" tabindex="-1" role="comment" aria-labelledby="view-head" aria-hidden="true">
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
                    <form action="{{ route('student.addComment') }}" id="add_review_post-d" method="post">
                        <div class="modal-body">
                            <div class="row text-center">
                                <div class="col-12">
                                    <img class="card-img-top rounded-circle img_120x120-s "src="{{ \Auth::user()->profile->profile_image }}" alt="student img" />
                                </div>
                                <div class="col-12 mt-4 star_rating-d">
                                    <h5 class="text-success student_name-d">{{ \Auth::user()->name }}</h5>
                                    <span class=""><strong >{!! $get_course_name ?? '' !!}Teacher</strong></span>
                                    <div class="rating-d">
                                        <input type="hidden" name="star_rating" value="" class="get_rating-d">
                                        {{-- {!! getStarRatingHTML(3.5) !!} --}}
                                        <ul class="mt-3 rating list-inline mx-auto justify-content-center">
                                            <li class="rating-item active mx-1" data-rate="1"></li>
                                            <li class="rating-item mx-1" data-rate="2"></li>
                                            <li class="rating-item mx-1" data-rate="3"></li>
                                            <li class="rating-item mx-1" data-rate="4"></li>
                                            <li class="rating-item mx-1" data-rate="5"></li>
                                        </ul>
                                        {{-- <img class="img_40_x_40-s " src="{{ asset('assets/images/yellow_star.svg') }}" alt="star img">
                                        <img class="img_40_x_40-s "  src="{{ asset('assets/images/yellow_star.svg') }}" alt="star img">
                                        <img class="img_40_x_40-s "  src="{{ asset('assets/images/yellow_star.svg') }}" alt="star img">
                                        <img class="img_40_x_40-s "  src="{{ asset('assets/images/yellow_star.svg') }}" alt="star img">
                                        <img class="img_40_x_40-s "  src="{{ asset('assets/images/yellow_star.svg') }}" alt="star img"> --}}
                                    </div>
                                </div>
                                <div class="col-12 mt-4">
                                    <div class="form-group">
                                        <textarea class="form-control bg-light rounded-4  pt-2" placeholder="Type your comment......" id="" name="message_body" rows="6"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--view modal body end-->
                        <!-- Modal footer -->
                        <div class="modal-footer border-0 mb-5  mt-4 justify-content-center">
                            <input type="hidden" name="course_uuid" value=" {!! $get_course_id ?? '' !!}">
                            <button type="submit" class="bg_success-s br_24-s py-2 px-5 text-white  border border-white ">
                                Submit
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

<style>
    .rating {
    display: flex;
    padding: 0;
    margin: 0;
    }

    .rating li {
    list-style-type: none
    }

    .rating-item {
    border: 1px solid #fff;
    cursor: pointer;
    font-size:2em;
    color: yellow;
    }

    /* initial: make all stars full */
    .rating-item::before {
    content: "\2605";
    }

    /* make until the clicked star (the rest) empty */
    .rating-item.active ~ .rating-item::before {
    content: "\2606";
    }

    /* on hover make all full */
    .rating:hover .rating-item::before {
    content: "\2605";
    }

    /* make until the hovered (the rest) empty */
    .rating-item:hover ~ .rating-item::before {
    content: "\2606";
    }

    </style>
