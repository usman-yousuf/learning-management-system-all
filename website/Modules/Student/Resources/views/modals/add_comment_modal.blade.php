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
                                <img class="float-right" src="../assets/group@2x.svg" alt="">
                            </a>
                        </div>
                    </div>
                    <!--modal header end-->

                    <!--VIEW MODAL BODY-->
                    <div class="modal-body">
                        <div class="row text-center">
                            <div class="col-12">
                                <img class="card-img-top rounded-circle img_120x120-s "src="../assets/preview/student1.png" alt="student img" />
                            </div>
                            <div class="col-12 mt-4">
                                <h5 class="text-success student_name-d">James</h5>
                                <span class=""><strong >Mobile APP Designing Teacher</strong></span>
                                <div class="mt-3">
                                    {!! getStarRatingHTML(3.5) !!}
                                    {{-- <img class="img_40_x_40-s " src="{{ asset('assets/images/yellow_star.svg') }}" alt="star img">
                                    <img class="img_40_x_40-s "  src="{{ asset('assets/images/yellow_star.svg') }}" alt="star img">
                                    <img class="img_40_x_40-s "  src="{{ asset('assets/images/yellow_star.svg') }}" alt="star img">
                                    <img class="img_40_x_40-s "  src="{{ asset('assets/images/yellow_star.svg') }}" alt="star img">
                                    <img class="img_40_x_40-s "  src="{{ asset('assets/images/yellow_star.svg') }}" alt="star img"> --}}
                                </div>
                            </div>
                            <div class="col-12 mt-4">
                                <div class="form-group">
                                    <textarea class="form-control bg-light rounded-4  pt-2" placeholder="Type your comment......" id="" rows="6"></textarea>
                                  </div>
                            </div>
                        </div>
                    </div>
                    <!--view modal body end--> 
                    <!-- Modal footer -->
                    <div class="modal-footer border-0 mb-5  mt-4 justify-content-center">
                        <button type="button" class="bg_success-s br_24-s py-2 px-5 text-white  border border-white ">
                            Submit
                        </button>
                    </div>
                    <!-- Modal footer End -->      
                </div>
            </div>
        </div>
    </div>          
</div>
<!--add comment modal end-->