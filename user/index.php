<?php
include_once("./header.php");

if (!empty($user)) {
    try {
        $get_slider = json_decode((string) $user->get_slider(), true);
    } catch (Exception $e) {
        error_log($e->getMessage());
    }
}

?>
<!-- Slider Section -->
<div class="slider-area">
    <div class="slider-active owl-dot-style owl-carousel">
        <?php
        if (!empty($get_slider)) {
            foreach ($get_slider as $slider) {
                if ($slider["status"] == 1) {
                    $image_path = "../admin/uploads/admin/slider/" . $slider["order_number"] . "/" . $slider["image"];
                    $linkPath = $slider["link"] . ".php";
        ?>
                    <div class="single-slider pt-210 pb-220 bg-img" style="background-image:url(<?php echo $image_path; ?>);">
                        <div class="container">
                            <div class="slider-content slider-animated-1">
                                <?php if (!empty($slider["heading"])) { ?>
                                    <h1 class="animated"><?php echo $slider["heading"]; ?></h1>
                                <?php } ?>
                                <?php if (!empty($slider["sub_heading"])) { ?>
                                    <h3 class="animated"><?php echo $slider["sub_heading"]; ?></h3>
                                <?php } ?>
                                <?php if (!empty($slider["link"]) && !empty($slider["link_txt"])) { ?>
                                    <div class="slider-btn mt-90">
                                        <a class="animated" href="<?php echo $linkPath; ?>"><?php echo $slider["link_txt"]; ?></a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
        <?php
                }
            }
        }else{
            echo "Data not found";
        }
        ?>
    </div>
</div>

<!-- Popular Product Section -->
<section class="shop-area shop-area-2 pt-120 pb-120">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="section-title-2 text-left mb-65">
                    <h2>Popular Product</h2>
                </div>
            </div>
        </div>
        <div class="row">
        </div>
    </div>
</section>

<?php include_once("footer.php") ?>
