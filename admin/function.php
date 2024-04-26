<?php
function pre($arr)
{
    echo '<pre>';
    print_r($arr);
}

function preArr($arr)
{
    echo '<pre>';
    print_r($arr);
}

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