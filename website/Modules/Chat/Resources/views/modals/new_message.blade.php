@php
    $users = (isset($users))? $users : [];
@endphp


    <div class="modal fade newMessage modal-small" id="modal_new_message-d" tabindex="-1" role="dialog" aria-modal="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title chat_edit_modal_title-s">New Messages</h5>
                    <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- chat search bar - START -->
                    <div class="row">
                        <div class="col chat_search-s mt-4 mb-4">
                            <form id='frm_search_new_chat_users-d' action="javascript:void(0)" method="POST">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend w-100">
                                        <span class="input-group-text">
                                            <img class="img_search_icon-s" src="{{ asset('assets/images/chat_search_icon.svg') }}" alt="search-icon" />
                                        </span>
                                        <input type="search" class="search_input-s new_chat_search_input-d" name="keywords" placeholder="Search User..." style="border-top-left-radius: 0px; border-bottom-left-radius: 0px;" />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- chat search bar - END -->
                    <div class="chat_search_list-s">
                        <!-- chat members list - START -->
                        <div class="row">
                            <div class="col scroll_list_of_members_in_modal-s new_chat_users_listing_container-d">
                                @include('chat::partials/chat_users_listing', ['listing_nature' => 'new_chat_modal', 'users' => $users])
                            </div>
                        </div>
                        <!-- chat members list - END -->
                    </div>
                </div>

            </div>
        </div>
    </div>


    <div class="cloneables_container-d" style="display:none;">
        <div class="row no_chat_user_container-d" id='no_chat_user_container-d'>
            <div class="col-12 py-3">
                <p class="mt-3 text-center">
                    <strong>No User Chat Found</strong>
                </p>
            </div>
        </div>
    </div>
