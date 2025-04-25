<div class="modal fade" id="addPageModal" tabindex="-1" role="dialog" aria-labelledby="addPageModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPageModalLabel">Add New Page</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addPageForm">
                    <div class="form-group">
                        <label for="newPageName">Page Name (with .php)</label>
                        <input type="text" class="form-control" id="newPageName" name="page_name" required>
                    </div>
                    <div class="form-group">
                        <label for="newPageTitle">Page Title</label>
                        <input type="text" class="form-control" id="newPageTitle" name="page_title" required>
                    </div>
                    <div class="form-group mb-0">
                        <label for="newSubTitle">Sub Title</label>
                        <input type="text" class="form-control" id="newSubTitle" name="sub_title">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-success btn-block" id="saveNewPageBtn">Save Page</button>
            </div>
        </div>
    </div>
</div>