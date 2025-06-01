<div class="footer-area black-bg-2 pt-70">
    <div class="footer-top-area pb-18">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="footer-about mb-40">
                        <div class="footer-logo d-flex-md">
                            <a href="index.php" class="d-flex align-items-center justify-content-md-start justify-content-lg-start justify-content-center">
                                <img src="assets/img/logo/logo-icon.png" class="img-fluid footer-logo-img" alt="" />
                                <h2 class="ml-1 mb-0 text-white"><b>mine</b>food.</h2>
                            </a>
                        </div>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed
                            do eiusmod tempor incidi ut labore et dolore magna aliqua. Ut
                            enim ad minim veniam,
                        </p>
                        <div class="payment-img">
                            <a href="#">
                                <img src="assets/img/icon-img/payment.png" alt="" />
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-6">
                    <div class="footer-widget mb-40">
                        <div class="footer-title mb-22">
                            <h4>Information</h4>
                        </div>
                        <div class="footer-content">
                            <ul>
                                <li><a href="about-us.html">About Us</a></li>
                                <li><a href="#">Delivery Information</a></li>
                                <li><a href="#">Privacy Policy</a></li>
                                <li><a href="#">Terms & Conditions</a></li>
                                <li><a href="#">Customer Service</a></li>
                                <li><a href="#">Return Policy</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-6">
                    <div class="footer-widget mb-40">
                        <div class="footer-title mb-22">
                            <h4>My Account</h4>
                        </div>
                        <div class="footer-content">
                            <ul>
                                <li><a href="my-account.html">My Account</a></li>
                                <li><a href="#">Order History</a></li>
                                <li><a href="wishlist.html">Wish List</a></li>
                                <li><a href="#">Newsletter</a></li>
                                <li><a href="#">Order History</a></li>
                                <li><a href="../admin/index.php">Admin</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="footer-widget mb-40">
                        <div class="footer-title mb-22">
                            <h4>Get in touch</h4>
                        </div>
                        <div class="footer-contact">
                            <ul>
                                <li><b>Address:</b> <?php echo $getAdminDetails['address']; ?></li>
                                <li><b>Telephone Enquiry:</b> <?php echo $getAdminDetails['contact_phone']; ?></li>
                                <li><b>Email:</b> <a href="#"><?php echo $getAdminDetails['contact_email']; ?></a></li>
                            </ul>
                        </div>
                        <div class="mt-35 footer-title mb-22">
                            <h4>Opening & Closing</h4>
                        </div>
                        <div class="footer-time">
                            <ul>
                                <?php
                                if (!empty($getAdminDetails['opening_hours'])) {
                                    $openingHours = json_decode($getAdminDetails['opening_hours'], true);
                                    $days = [
                                        'Sun' => 'Sunday',
                                        'Mon' => 'Monday',
                                        'Tue' => 'Tuesday',
                                        'Wed' => 'Wednesday',
                                        'Thu' => 'Thursday',
                                        'Fri' => 'Friday',
                                        'Sat' => 'Saturday'
                                    ];

                                    $openDays = [];
                                    $closedDays = [];

                                    foreach ($days as $shortDay => $fullDay) {
                                        $status = isset($openingHours[$shortDay]['status']) ? $openingHours[$shortDay]['status'] : '0';
                                        if ($status == '1') {
                                            $openDays[] = $fullDay;
                                        } else {
                                            $closedDays[] = $fullDay;
                                        }
                                    }

                                    // Display open hours
                                    if (!empty($openDays)) {
                                        echo '<li>Open: <span>' . $openingHours['opening'] . '</span> - Close: <span>' . $openingHours['closing'] . '</span></li>';
                                    }

                                    // Display closed days
                                    if (!empty($closedDays)) {
                                        echo '<li>' . implode(', ', $closedDays) . ': <span>Close</span></li>';
                                    }
                                } else {
                                    // Default fallback
                                    echo '<li>Open: <span>8:00 AM</span> - Close: <span>6:00 PM</span></li>';
                                    echo '<li>Saturday - Sunday: <span>Close</span></li>';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom-area border-top-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-7">
                    <div class="copyright">
                        <p>
                            Copyright &copy;
                            <script type="text/javascript">
                                let year = new Date();
                                document.write(year.getFullYear());
                            </script>
                            <a href="https://github.com/ayush-999">mineFood</a>.
                            All rights reserved.
                        </p>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-5">
                    <div class="footer-social">
                        <ul>
                            <?php
                            if (!empty($getAdminDetails['social_links'])) {
                                $socialLinksJson = '[' . $getAdminDetails['social_links'] . ']';
                                $socialLinks = json_decode($socialLinksJson, true);

                                if (json_last_error() === JSON_ERROR_NONE && is_array($socialLinks)) {
                                    foreach ($socialLinks as $link) {
                                        if (isset($link['url']) && isset($link['icon'])) {
                                            echo '<li><a href="' . htmlspecialchars($link['url']) . '" target="_blank"><i class="' . htmlspecialchars($link['icon']) . '"></i></a></li>';
                                        }
                                    }
                                } else {
                                    // Fallback to default social links if there's an error in decoding
                            ?>
                                    <li><a href="#"><i class="ion-social-facebook"></i></a></li>
                                    <li><a href="#"><i class="ion-social-twitter"></i></a></li>
                                    <li><a href="#"><i class="ion-social-instagram-outline"></i></a></li>
                                <?php
                                }
                            } else {
                                // Fallback to default social links if empty
                                ?>
                                <li><a href="#"><i class="ion-social-facebook"></i></a></li>
                                <li><a href="#"><i class="ion-social-twitter"></i></a></li>
                                <li><a href="#"><i class="ion-social-instagram-outline"></i></a></li>
                            <?php
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-5 col-sm-5 col-xs-12">
                        <!-- Thumbnail Large Image start -->
                        <div class="tab-content">
                            <div id="pro-1" class="tab-pane fade show active">
                                <img
                                    src="assets/img/product-details/product-detalis-l1.jpg"
                                    alt="" />
                            </div>
                            <div id="pro-2" class="tab-pane fade">
                                <img
                                    src="assets/img/product-details/product-detalis-l2.jpg"
                                    alt="" />
                            </div>
                            <div id="pro-3" class="tab-pane fade">
                                <img
                                    src="assets/img/product-details/product-detalis-l3.jpg"
                                    alt="" />
                            </div>
                            <div id="pro-4" class="tab-pane fade">
                                <img
                                    src="assets/img/product-details/product-detalis-l4.jpg"
                                    alt="" />
                            </div>
                        </div>
                        <!-- Thumbnail Large Image End -->
                        <!-- Thumbnail Image End -->
                        <div class="product-thumbnail">
                            <div
                                class="thumb-menu owl-carousel nav nav-style"
                                role="tablist">
                                <button
                                    class="active"
                                    id="pro-1-tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#pro-1"
                                    type="button"
                                    role="tab"
                                    aria-controls="pro-1"
                                    aria-selected="true">
                                    <img
                                        src="assets/img/product-details/product-detalis-s1.jpg"
                                        alt="product-thumbnail" />
                                </button>
                                <button
                                    id="pro-2-tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#pro-2"
                                    type="button"
                                    role="tab"
                                    aria-controls="pro-2"
                                    aria-selected="true">
                                    <img
                                        src="assets/img/product-details/product-detalis-s2.jpg"
                                        alt="product-thumbnail" />
                                </button>
                                <button
                                    id="pro-3-tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#pro-3"
                                    type="button"
                                    role="tab"
                                    aria-controls="pro-3"
                                    aria-selected="true">
                                    <img
                                        src="assets/img/product-details/product-detalis-s3.jpg"
                                        alt="product-thumbnail" />
                                </button>
                                <button
                                    id="pro-4-tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#pro-4"
                                    type="button"
                                    role="tab"
                                    aria-controls="pro-4"
                                    aria-selected="true">
                                    <img
                                        src="assets/img/product-details/product-detalis-s4.jpg"
                                        alt="product-thumbnail" />
                                </button>
                            </div>
                        </div>
                        <!-- Thumbnail image end -->
                    </div>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <div class="modal-pro-content">
                            <h3>PRODUCTS NAME HERE</h3>
                            <div class="product-price-wrapper">
                                <span>$120.00</span>
                                <span class="product-price-old">$162.00 </span>
                            </div>
                            <p>
                                Pellentesque habitant morbi tristique senectus et netus et
                                malesuada fames ac turpis egestas. Vestibulum tortor quam,
                                feugiat vitae, ultricies eget, tempor sit amet.
                            </p>
                            <div class="quick-view-select">
                                <div class="select-option-part">
                                    <label>Size*</label>
                                    <select class="select">
                                        <option value="">S</option>
                                        <option value="">M</option>
                                        <option value="">L</option>
                                    </select>
                                </div>
                                <div class="quickview-color-wrap">
                                    <label>Color*</label>
                                    <div class="quickview-color">
                                        <ul>
                                            <li class="blue">b</li>
                                            <li class="red">r</li>
                                            <li class="pink">p</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="product-quantity">
                                <div class="cart-plus-minus">
                                    <input
                                        class="cart-plus-minus-box"
                                        type="text"
                                        name="qtybutton"
                                        value="02" />
                                </div>
                                <button>Add to cart</button>
                            </div>
                            <span><i class="fa fa-check"></i> In stock</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal end -->

<script src="assets/js/vendor/jquery-1.12.0.min.js"></script>
<script src="assets/js/popper.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/imagesloaded.pkgd.min.js"></script>
<script src="assets/js/isotope.pkgd.min.js"></script>
<script src="assets/js/owl.carousel.min.js"></script>
<script src="assets/js/ajax-mail.js"></script>
<script src="assets/js/plugins.js"></script>
<script src="assets/js/main.js"></script>
</body>

</html>