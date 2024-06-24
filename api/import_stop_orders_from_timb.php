<?php
$ciphertext="p57J/R5c2tn+nrPnk8mzHbQr4vJ18dEltCJj93SuUMd6InEpnnHfjlgm4Hh3pwAaVwMN9zrKES/YgXl7LWWqMprQ74Sr0lQR1R8/HIo+Sbc1FO/mYghDJ12Q5eQv7NY6gUSGD23wF+5IbgNbq4cP6wy4Z22ZJw1G/tV4o4vGAecbx4scts2Yh5uanyxm7r3mR6nG/1b50FXRO8ETN3pgL9sr5wFbvDbgQgCZQs1bdm33eOzaaqxgUKXqBy/knhxJIDdclLSo0EraehnPccoiGGMvAUvJZ0FtwKDYIT4cKbSZ4N8CWCVyAlqR3S9aV8avYbiJtR3gW8Jf79H9gfa2uEHv73zWpuMXdbKMMBi1fRzCsndp7MMDTXkvGxA86vn0QQy7GOMeMpm6j5bAJDJxw18wthz8lH/CZpob9KtQDX6/dPdrEbkA/8pqUToYJUbI5FqgUzFazYO8gh4jKd2x0/jzmnJJW/tIqIvUnvJxNOTxzBdM0DWqs1NXWquAif3pZS45mactzW4sOPzbku/JK4cVx4X1YtjpfJRaXAB/aeQF5Dz0udbUH4ji9/Ug3dnT+s5MDGAO+82ERitdQ0K5HS8Dx4PhsCvVZhlQBqlFtdamVNhBbXyt8Gswmnh1iDwEKBQr+cNzUcmviu48qeVNVVlswvfquOMkhLMgLFoJ4F75dvutXD1qUwjYbdWmdR8ClXTyKGJAhvyNUMBl4y53sN+sY4whFpObOMEOYt6jOlpOAdwfz+8KzwDY4jDZmL/1SyuroX7JjXoFfDV5UJ1J6HYNwzQ5gTZ1P6Mw03k/POa845VKAjSJzM4D97rInqKPRCjsdQgcvjz7OcQUuvTxLB2jaxiYLQxjW+0Lps2RC8M=";
$encryptedData = $ciphertext;
$cipherMethod = "AES256";
$key = "E13ED75C8046B825973B35C7AA5946F3";
$options = 0; // You can adjust this based on your needs
$iv = "142a2bbb11c64825"; // Make sure it matches the one used during encryption

$decryptedData = openssl_decrypt($encryptedData, $cipherMethod, $key, $options, $iv);
if ($decryptedData !== false) {
    echo  $decryptedData;
} else {
    echo "Decryption failed.";
}