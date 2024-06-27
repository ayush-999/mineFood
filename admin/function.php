<?php
/**
 * The function pre() in PHP is used to display the contents of an array in a formatted and readable
 * manner.
 *
 * @param arr The `pre` function is a custom function in PHP that is used to display the contents of an
 * array in a more readable format by wrapping it in `<pre>` tags and using `print_r` to output the
 * array elements.
 */
function pre($arr)
{
    echo '<pre>';
    print_r($arr);
}

/**
 * The function preArr in PHP is used to display the contents of an array in a formatted way and then
 * terminate the script execution.
 *
 * @param arr The `preArr` function is a custom function that takes an array as a parameter, prints the
 * array in a human-readable format using `print_r`, and then stops the script execution using `die()`.
 * This can be useful for debugging purposes to quickly inspect the contents of an array.
 */
function preArr($arr)
{
    echo '<pre>';
    print_r($arr);
    die();
}

/**
 * The function `redirect` in PHP is used to redirect the user to a specified link using JavaScript and
 * then terminate the script execution.
 *
 * @param link The `redirect` function you provided is a PHP function that redirects the user to a
 * specified link using JavaScript. When this function is called with a link parameter, it generates a
 * JavaScript script that changes the window location to the specified link and then terminates the
 * script execution using `die()`.
 */
function redirect($link)
{
    ?>
    <script>
        window.location.href = '<?php echo $link; ?>';
    </script>
    <?php
    die();
}

?>