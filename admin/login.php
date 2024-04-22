<?php
$pageTitle = "Login - Mine Food";
require_once ('header.php');
require_once ('classes/User.php');

$user = new User($conn);
$msg = "";
$inputErrorClass = "";
$iconErrorClass = "";
$textErrorClass = "";
$cardErrorClass = "";

if (isset($_POST['submit'])) {
    $username = $_POST['userInput'];
    // $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $password = $_POST['password'];
    $result = $user->login($username, $password);
    if ($result) {
        echo "Login successful!";
    } else {
        $msg = "Invalid login credentials! Please try again.";
        $inputErrorClass = "input-error";
        $iconErrorClass = "icon-error";
        $textErrorClass = "font-error";
        $cardErrorClass = "card-outline-error";
    }
}
?>
<div class="login-box">
    <div class="card card-outline card-primary login-card <?php echo $cardErrorClass; ?>">
        <div class="card-header text-center">
            <a href="" class="h1 <?php echo $textErrorClass; ?>"><b>mine</b>food.</a>
        </div>
        <div class="card-body">
            <p class="login-box-msg <?php echo $textErrorClass; ?>">Sign in to start your session</p>

            <form action="" method="post">
                <div class="input-group mb-3">
                    <input type="text" class="form-control <?php echo $inputErrorClass; ?>" name="userInput"
                        placeholder="Email" required>
                    <div class="input-group-append">
                        <div class="input-group-text <?php echo $inputErrorClass; ?>">
                            <span class="fas fa-envelope <?php echo $iconErrorClass; ?>"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control <?php echo $inputErrorClass; ?>" name="password"
                        id="password-field" placeholder="Password" required>
                    <div class="input-group-append">
                        <div class="input-group-text <?php echo $inputErrorClass; ?>">
                            <span id="password-icon" class="fas fa-lock <?php echo $iconErrorClass; ?>"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 mb-2">
                        <!-- <button type="submit" class="btn btn-primary btn-block">Sign In</button> -->
                        <input type="submit" class="btn bg-gradient-primary btn-block normal-btn" id="signIn-button"
                            name="submit" value="Sign In" disabled>
                    </div>
                </div>
            </form>
            <!-- <div class="social-auth-links text-center mt-2 mb-3">
                <a href="#" class="btn btn-block btn-danger">
                    <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
                </a>
            </div>
            -->
            <p class="mb-2">
                <a href="/" class="<?php echo $textErrorClass; ?>">I forgot my password</a>
            </p>
            <!-- <p class="mb-0">
                <a href="/" class="text-center">Register a new membership</a>
            </p> -->
        </div>
    </div>
</div>
<?php require_once ('footer.php') ?>