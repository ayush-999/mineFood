<?php
include_once('./header.php');
if (!empty($user)) {
    try {
        $get_banner = json_decode((string) $user->get_banner(), true);
    } catch (Exception $e) {
        error_log($e->getMessage());
    }
}

?>
<!-- Slider Section -->
<div class="slider-area">
    <div class="slider-active owl-dot-style owl-carousel">
        <?php
        if (!empty($get_banner)) {
            foreach ($get_banner as $banner) {
                if ($banner['status'] == 1) {
                    $image_path = "../admin/uploads/admin/banner/" . $banner['order_number'] . "/" . $banner['image'];
                    $linkPath = $banner['link'] . ".php";
        ?>
                    <div class="single-slider pt-210 pb-220 bg-img" style="background-image:url(<?php echo $image_path; ?>);">
                        <div class="container">
                            <div class="slider-content slider-animated-1">
                                <?php if (!empty($banner['heading'])) { ?>
                                    <h1 class="animated"><?php echo $banner['heading']; ?></h1>
                                <?php } ?>
                                <?php if (!empty($banner['sub_heading'])) { ?>
                                    <h3 class="animated"><?php echo $banner['sub_heading']; ?></h3>
                                <?php } ?>
                                <?php if (!empty($banner['link']) && !empty($banner['link_txt'])) { ?>
                                    <div class="slider-btn mt-90">
                                        <a class="animated" href="<?php echo $linkPath; ?>"><?php echo $banner['link_txt']; ?></a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
        <?php
                }
            }
        }
        ?>
    </div>
</div>

<?php include_once('./footer.php'); ?>