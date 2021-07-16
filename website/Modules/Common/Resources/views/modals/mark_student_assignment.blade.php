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
                    <form action="{{ route('markedAssignment') }}" id="frm_mark_student_assignment-d" method="post">
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
                                        <td class="mark_student_name-d"></td>
                                        <td class="student_assignment_title-d" ></td>
                                        <td class="mark_teacher_name-d"></td>
                                        <td><input type="text" name="obtained_marks" class="obtained_marks-d"></td>
                                    </tr>
                                
                                </tbody>
                            </table>  
                        </div>
                        <!-- modal body end-->  
                        <!-- Modal footer -->
                        <div class="modal-footer border-0 mb-5 mt-3 d-flex justify-content-around">
                            <input type="hidden" name="student_assignment_uuid"  class="get_student_assignment_uuid-d" >
                            {{-- <input type="hidden" name="status" value="marked"> --}}
                            <button type="submit" class="btn bg_success-s br_24-s py-2  text-white w_315px-s border border-white" >
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