<?php

function generate_salt($length = 16)
{
    return bin2hex(random_bytes($length));
}

function encrypt_string($string, $key, $salt)
{
    $iv = random_bytes(16);
    $encrypted = openssl_encrypt($string, 'aes-256-gcm', $key, 0, $iv, $tag);
    return base64_encode($iv . $salt . $tag . $encrypted);
}

function decrypt_string($cipher, $key)
{
    $data = base64_decode($cipher);
    $iv = substr($data, 0, 16);
    $salt = substr($data, 16, 32);
    $tag = substr($data, 48, 16);
    $ciphertext = substr($data, 64);

    return openssl_decrypt($ciphertext, 'aes-256-gcm', $key, 0, $iv, $tag);
}

function exampleShow()
{
    // Example usage
    // Ganti dengan kunci rahasia yang kuat
    $key = 'Rahasia!58';

    // Generate a random salt
    $salt = generate_salt();

    // String yang akan dienkripsi
    $string_to_encrypt = 'Hello, World!';

    // Enkripsi string
    $encrypted_string = encrypt_string($string_to_encrypt, $key, $salt);

    // Dekripsi string
    $decrypted_string = decrypt_string($encrypted_string, $key);

    // Output hasil
    echo 'Original String: ' . $string_to_encrypt . '<br>';
    echo 'Encrypted String: ' . $encrypted_string . '<br>';
    echo 'Decrypted String: ' . $decrypted_string . '<br>';
}
