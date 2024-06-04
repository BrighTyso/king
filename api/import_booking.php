<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json");
header("Access-Control-Allow-Origin-Methods:POST");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin-Methods,Authorization,X-Requested-With");

require_once("conn.php");


$data = json_decode(file_get_contents("php://input"));

$response=array();
if (isset($data->userid)) {
    $encryptedFile = 'path/to/encryptedfile.txt';
    $decryptedFile = 'path/to/decryptedfile.txt';

    //$ciphertext="s";
    $ciphertext="s0lojRZCU5gmjA+kcqrti1oAMdAfYA/f7SjPBdsG3LIuIrIatNeJOgDDiaQLrIqvpj0E36hyScSecR61E4wsLDvMx7Vhx81O+pKQXn1xb+DlxB6FvjocxUkMtxQGvq/ibwjBxAIC8O2ShJihQm+I1NvmyGvtfQpCBuzcmcaDUTSA2osBOXhkoypLGzEXS1bxdD4lexcCxydIpsssKYIAlHBoO244a9VtxX7UNCD3m+HKVuiaLAxT2BFeTA2u3w9xNFzoAtWZRhvTCrE7U1KtPZBtlpg7buD3ikM0YGgbEU83FrQkyy0sIXWjmH78iIBqOhXkcggWxQhrQsJq1vCB0uYmSZkTiYIxFtyB209LVCZNrb/CNwAHrpuV4kp2MIiVIEYhzz5NO6+WNxrcRfvVWSsLmRD2WZCfgcTPZOw2SLFuzB+jH9z+s2RRzjOyS20elDkR0CwjS+/ee66G1ALHV9pXmL6KytHQzV1K9qae4AKSNaCva60iYRI3eTF5uGJkjb54xjmWpxSNVWSG8lA9SWNOm5wjr/s2zqmSZAawYRLwZcuBlJkMwEejWqmrq1S+N6DAO654/jz0NoK4ZbOUb0M5iF200VOCOi0Y/afduwEnYbz7VekRBeMrZhV9CPFJATWsVe0f4QXq5TMmnGY1B4Jyju8xJvWXnmBFQCawNM/oc0iAkux/G0jjOlOMmq39Qn7KMLVmjVrqOQ5CZENcSnszuGaHQTRXTyVgA+ColemS7ohXzinYVzbbfmgqevfclxj/TM7YYFEgYIe2TyY1eXqlL3d+dgqtrWKhCmtLlH97Uq8yOmghdPiBXd7i7bLmHzZjFjAyIH4lqkJC/3riM/gJ/L2gC9OdHxYxz9VgfyyrO/axGDHeXsdgLSjcGaz+eGh5uWL5e3Rdlm0iCGeYenT6B0jTxWYmLUIg878w/GW9KS09s6Zo+8yRxuVHCIrYZ/Oh5mdNErVQ+iCr/TxvfktT48dvHKfF3shOxe2WGGVANcCV48pJkD4m/1pSSuk7uQiHxA0bqashbwk3syDPKOi0uhftNiMuT+arUfP2xmFikZ+M4gPopPty7OkbUiUcdmQ9O58DMJZOgzVZ6O6Eyp6552VifI9Vb91h4byijZivbh+6z9WcbxT/At7fT7WD0pnzLUkiskhF0rp9TGQok6YieNezJKJQHBDoXUtEIa2VlDkG7bkePcLMifnSoLvv8wVndoRWZ/DuV15qENp0zx3pYqOCi71fsIpdrlCak5eHphVyYgj21YxWOWMluxd/TgOj1R2dVQDe760EI/V5DVGNE2JZGKW2HmV7QAHdPQEzHKSclIki/5Q7QDcsweWX5t6SdU+FZSyTB70O/nU5oCYKESJwO0acTDTr+ArXJXI/9vLklxdYETWFmOpOdeTRKXcu/9ftpBK9Cdt9HHISRGrPgkB8jiLGvzssrjtpL2gUsYlk+Wo5wJFrd6ZVbTGZuLpuVjGcnRh/KRk7wFAKAVHZCs+qUKyvz4e7QIA+Bj1MKxKyzZONWyKcS/Tar+HdRR68FZZ/oLzXVD5D6tzX3faxmiPelc/WKAPpR89JnyxQAG/Wri1vHd1ZDxTZ2uwBFGkVXQA7pmq6k2KsMpl5fBzj3QV8xLJMde07GAd36B3WaPJeNHPD5UUkIrKSVFeGLxPGq2fJ8aYqhMOmMtFWaN+eowiMiWsaI5Pq2h0qv5LKJHIM1WSvDNKjaL72ueKuLAZL0HCWTtjT/NMb6pOD83ajARBNmNjvBTYRYGh9Z02UjtTWtp6sQ9FMQiMOQJPnXzgFt8G09QlzdGwAJ5E/V1ktH081Y68txrH8fwdKdX2MPSKaNGibwSMSF0z9oQeZ3FHTddNan9rIpaRa0fuwsw==";

    $key = 'E13ED75C8046B825973B35C7AA5946F3';
    $iv = '142a2bbb11c64825';



     // 32-byte key
     // 16-byte IV

    $cipherText = file_get_contents($encryptedFile);

    $plainText = openssl_decrypt($cipherText, 'aes256', $key, OPENSSL_RAW_DATA, $iv);

    file_put_contents($decryptedFile, $plainText);

    echo json_encode($plainText);

}