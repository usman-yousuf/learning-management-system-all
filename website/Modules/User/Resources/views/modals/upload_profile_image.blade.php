
    <!-- Modal -->
    <div class="modal fade" id="uploadFileModalPopup" tabindex="-1" role="dialog" aria-labelledby="uploadFileModalPopup" aria-hidden="true">
        <div class="modal-dialog modal-lg " role="document">
            <div class="modal-content profile_model-s py-4 text-center">
                <div class="pt-3 mb-2">
                    <h3 class="upload_heading-s">Upload Profile Image</h3>
                </div>
                <div class="modal-body">
                    <img id="profile_image_thumb-d" src="{{ getFileUrl(null, null, 'profile') }}" class="rounded square_100p-s mb-2 profile_img-d" alt="">

                    <div class="file-loading mt-3">
                        <label class='click_profile_image-d'>
                            <img src="{{ asset('assets/images/upload_image_icon.svg') }}" alt="upload" />
                        </label>
                        <input id="upload_profile_image-d" type="file" onchange="previewUploadedFile(this, '.profile_img-d', '#hdn_profile_image-d');" data-allowed_fileExtensions="{{ getAllowedFileExtensions('image') }}">

                        <label data-dismiss="modal">
                            <button class="btn btn-success ml-3 ">Save</button>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
