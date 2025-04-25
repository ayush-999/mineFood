<?php
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

function pre($arr)
{
    echo '<pre>';
    print_r($arr);
}

function preArr($arr): never
{
    echo '<pre>';
    print_r($arr);
    die();
}

function redirect($link): never
{
    ?>
    <script>
        window.location.href = '<?php echo $link; ?>';
    </script>
    <?php
    die();
}

function encryptData($data): string
{
    $key = base64_decode((string) $_ENV['ENCRYPTION_KEY']);
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);
    return base64_encode($encrypted . '::' . $iv);
}

function decryptData($data): bool|string
{
    $key = base64_decode((string) $_ENV['ENCRYPTION_KEY']);
    [$encrypted_data, $iv] = explode('::', base64_decode((string) $data), 2);
    return openssl_decrypt($encrypted_data, 'aes-256-cbc', $key, 0, $iv);
}

function truncateText($text, $word_limit)
{
    $words = explode(" ", (string) $text);
    if (count($words) > $word_limit) {
        $truncated = implode(" ", array_slice($words, 0, $word_limit)) . ' ... <span class="see-more" data-tippy-content="' . htmlspecialchars((string) $text) . '">see more</span>';
    } else {
        $truncated = $text;
    }
    return $truncated;
}

?>