
    <div class="modal fade" id="" tabindex="-1" role="tabpanel" aria-labelledby="modal-head" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="diaglog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="container mb-3 pb-3 ">
                        <!--Modal Header-->
                        <div class="row ml-3 ">
                            <div class="mt-3 mb-3 ">
                                <h4 class="modal-title text-success" id="modal-head">Course Content</h4>
                            </div>
                        </div>
                        <!--Modal Header End-->
                        <!--modal body-->
                        @include('course::partials.video_course_content', ['page' => 'dashboard', 'contents' => []])
                    </div>
                </div>
            </div>
        </div>
    </div>

@php
    $queries = (isset($queries) && !empty($queries))? $queries : [];
@endphp


    <div class="modal" id="course_queries_modal-d">
        <div class="modal-dialog modal-xl">
            <div class="modal-content d-flex">

                <!-- Modal Header Start -->
                <div class="container-fluid">
                    <div class="row">
                        <div class=" col-12 modal-header border-0">
                            <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                            <span class="w-100">
                                <a data-dismiss="modal">
                                    <img class="custom-close float-right" src="{{ asset('assets/images/modal_close_icon.svg') }}" alt="X" />
                                </a>
                            </span>
                        </div>
                    </div>
                </div>
                <!-- Modal Header End -->


                <!-- Modal Body Start -->
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row mb-5 px-3 course_queries_container-d">
                            @forelse ($queries as $item)
                                @php
                                    // dd($item->query_response);
                                @endphp
                                <div class="col-12 shadow pt-4 pb-5 mb-3 single_course_query_container-d uui_{{ $item->uuid ?? '' }}">
                                    <div class="row">
                                        <div class="col-9 ml-3">
                                            <a href="javascript:void(0)" class='no_link-s'>
                                                <img class="img_40_x_40-s rounded-circle student_profile_img-d" src="{{ getFileUrl($item->student->profile_image ?? null, null, 'profile') }}" alt="student" />
                                                <span class="fg-success-s student_name-d">{{ $item->student->first_name ?? 'Student Name' }}</span>
                                            </a>
                                        </div>
                                        <div class="col-9 ml-3">
                                            <p class='ml-4 mt-3 student_query-d'>
                                                {{ $item->body ?? 'Question Body' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="manage_answer_container-d" @if(!isset($item->query_response) || (null == $item->query_response->uuid)) style="display:none;" @endif>
                                        <div class="row">
                                            <div class="col-9">
                                                <div class="ml-3">
                                                    <strong>Ans:</strong>
                                                    <span class='teacher_answer-d'>{{ $item->query_response->body ?? 'no answer yet' }}</span>
                                                </div>
                                            </div>
                                            <div class="col-3 text-center">
                                                <input type="hidden" class="query_response_uuid-d" value='{{ $item->query_response->uuid ?? '' }}' />
                                                <input type="hidden" class="query_uuid-d" name="query_uuid" value='{{ $item->uuid ?? '' }}' />
                                                <a href="javascript:void(0)" class='delete_query_response-d'>
                                                    <img src="{{ asset('assets/images/delete_icon.svg') }}" alt="delete-query-response" />
                                                </a>
                                                <a href="javascript:void(0)" class='edit_query_response-d'>
                                                    <img src="{{ asset('assets/images/edit_icon.svg') }}" alt="edit-query-response" />
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="new_answer_container-d" @if(isset($item->query_response) && (null != $item->query_response->uuid))  style="display:none;" @endif>
                                        <form class='w-100 frm_respond_query-d' method="POST" action="{{ route('query.update-response') }}">
                                            @csrf
                                            <div class="row">
                                                <div class="col-10 col-sm-11">
                                                    <div class="ml-3">
                                                        <textarea class="bg_light-s w-100 min_height_75px-s textarea_query_response-s max_height_71px-s fg_light_black-s response_body-d" name="response_body" value="{{ $item->query_response->body ?? '' }}" placeholder="Your answer comes in here">{{ $item->query_response->body ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-2 col-sm-1 text-left pt-0 pl-0">
                                                    <input type="hidden" class="query_response_uuid-d" name="query_response_uuid" value='{{ $item->query_response->uuid ?? '' }}' />
                                                    <input type="hidden" class="query_uuid-d" name="query_uuid" value='{{ $item->uuid ?? '' }}' />
                                                    <button type="submit" class='btn p-0'>
                                                        <img src="{{ asset('assets/images/send_icon.svg') }}" alt="submit" />
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 no_item_container-d">
                                    <p class='mt-5 mb-5w-100 text-center'>
                                        <strong>No Record Found</strong>
                                    </p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                <!-- Modal Body End -->
            </div>
        </div>
    </div>
