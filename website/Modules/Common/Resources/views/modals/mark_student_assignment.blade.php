  <!--assignment modal-->
  <div class="modal fade" id="mark_student_assignment-d" tabindex="-1"  aria-labelledby="view-head" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" >
        <div class="modal-content ">
            <div class="modal-header d-block">    
                <div class="container pb-5">
                    <!--modal header-->
                    <div class="row">
                        <div class="col-12 text-right">
                            <a class="close pt-3 pr-0" data-dismiss="modal" aria-label="Close">
                                <img class="float-right" src="{{ asset('assets/images/cancel_circle.svg') }}" alt="">
                            </a>
                        </div>
                    </div>
                    <!--modal header end-->

                    <!-- MODAL BODY-->
                    <form action="#" method="post">
                        <div class="modal-body ">
                            <div class="row pb-4">
                                <div class="col-12 text-center">
                                    <h2 class="fg-success-s pb-5">Assignment</h2>
                                </div>
                            </div>
                            <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Student Name</th>
                                    <th scope="col">Course Title</th>
                                    <th scope="col">Teacher Name</th>
                                    <th scope="col">Marks Obtained</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">1</th>
                                    <td>Mark</td>
                                    <td>Otto</td>
                                    <td>@mdo</td>
                                    <td><input type="text" name="obtained_marks-d" id=""></td>
                                </tr>
                            
                            </tbody>
                            </table>  
                        </div>
                        <!-- modal body end-->  
                        <!-- Modal footer -->
                        <div class="modal-footer border-0 mb-5 mt-3 d-flex justify-content-around">
                            <button type="submit" class="btn bg_success-s br_24-s py-2  text-white w_315px-s border border-white mark_assignment-d" >
                            Save
                            </button>
                        </div>
                        <!-- Modal footer End -->     
                    </form> 
                </div>
            </div>
        </div>
    </div>          
</div>
<!--assignment modal end-->