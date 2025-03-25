<?php

namespace App\Service;

class EncryptionService
{
    private string $encryptionKey;

    public function __construct(string $encryptionKey)
    {
        $this->encryptionKey = $encryptionKey;
    }

    public function encrypt(string $data): string
    {
        $iv = random_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encryptedData = openssl_encrypt($data, 'aes-256-cbc', $this->encryptionKey, 0, $iv);

        return base64_encode($iv . $encryptedData);
    }

    public function decrypt(string $encryptedData): string
    {
        $encodedData = base64_decode($encryptedData);
        $ivLength = openssl_cipher_iv_length('aes-256-cbc');
        $iv = substr($encodedData, 0, $ivLength);
        $encryptedText = substr($encodedData, $ivLength);

        return openssl_decrypt($encryptedText, 'aes-256-cbc', $this->encryptionKey, 0, $iv);
    }
}