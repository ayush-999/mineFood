<?php
include_once('header.php');
?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">
                            <b>
                                <?php
                                    if (!empty($pageSubTitle)) {
                                        echo $pageSubTitle;
                                    }
                                ?>
                            </b>
                        </h5>
                        <button class="btn bg-gradient-success btn-sm rounded-circle add-btn" type="button"
                                data-toggle="modal" data-target="#dish-modal">
                            <i class="fa-regular fa-plus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                </div>
            </div>
        </div>
    </div>
<?php include_once('footer.php') ?>