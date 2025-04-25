<div class="modal fade" id="banner-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Banner</h6>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form method="POST" action="<?php echo htmlspecialchars((string) $_SERVER["PHP_SELF"]); ?>" id="bannerForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="bannerId" name="bannerId" value="">
                    <input type="hidden" id="submitAction" name="submitAction" value="">
                    <div class="row mb-2">
                        <div class="col-md-12 form-group mb-0">
                            <div class="uploader">
                                <div class="upload-area">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-photo-up">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M15 8h.01" />
                                        <path d="M12.5 21h-6.5a3 3 0 0 1 -3 -3v-12a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v6.5" />
                                        <path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l3.5 3.5" />
                                        <path d="M14 14l1 -1c.679 -.653 1.473 -.829 2.214 -.526" />
                                        <path d="M19 22v-6" />
                                        <path d="M22 19l-3 -3l-3 3" />
                                    </svg>
                                    <p>Drag and drop or click here to upload image</p>
                                </div>
                                <div class="remove-btn-container" style="display: none;">
                                    <button type="button" class="btn bg-gradient-danger btn-sm rounded-circle remove-image-btn">
                                        <i class="fa fa-close"></i>
                                    </button>
                                </div>
                                <input type="file" id="bannerImage" name="bannerImage" accept="image/*" style="display: none;">
                                <input type="hidden" id="removeImageFlag" name="removeImage" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12 form-group mb-0">
                            <label for="bannerHeading">Heading</label>
                            <input type="text" class="form-control" id="bannerHeading" name="bannerHeading"
                                placeholder="Enter banner heading" value="" required>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12 form-group mb-0">
                            <label for="bannerSubHeading">Sub Heading</label>
                            <input type="text" class="form-control" id="bannerSubHeading" name="bannerSubHeading"
                                placeholder="Enter banner sub heading" value="" required>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6 form-group mb-0">
                            <label for="bannerLink">Link</label>
                            <input type="text" class="form-control" id="bannerLink" name="bannerLink"
                                placeholder="Enter link" value="" required>
                        </div>
                        <div class="col-md-6 form-group mb-0">
                            <label for="bannerLinkText">Link Text</label>
                            <input type="text" class="form-control" id="bannerLinkText" name="bannerLinkText"
                                placeholder="Enter link text" value="" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 form-group mb-0">
                            <label for="bannerStatus">Status</label>
                            <select class="form-control" name="bannerStatus" id="bannerStatus">
                                <option value="">Select Status</option>
                                <option value="0">Inactive</option>
                                <option value="1">Active</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group mb-0">
                            <label for="bannerOrderNumber">Order Number</label>
                            <input type="number" class="form-control" id="bannerOrderNumber" name="bannerOrderNumber"
                                placeholder="Enter order number" value="" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group mb-0">
                            <button type="submit" class="btn bg-gradient-success btn-block">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>