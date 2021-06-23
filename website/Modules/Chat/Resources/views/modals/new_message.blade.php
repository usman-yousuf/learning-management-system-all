@php

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
                            <input type="search" class="search_input-s" name="" id="" placeholder="Search User...">
                            <span class="search_icon_position-s">
                                <a href="javascript:void">
                                    <img class="img_search_icon-s" src="{{ asset('assets/images/chat_search_icon.svg') }}" alt="search-icon" />
                                </a>
                            </span>
                        </div>
                    </div>
                    <!-- chat search bar - END -->
                    <div class="chat_search_list-s">
                        <!-- chat members list - START -->
                        <div class="row">
                            <div class="col scroll_list_of_members_in_modal-s">
                                @include('chat::partials/chat_users_listing', ['listing_nature' => 'new_chat_modal'])
                            </div>
                        </div>
                        <!-- chat members list - END -->
                    </div>
                </div>

            </div>
        </div>
    </div>