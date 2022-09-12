<?php


namespace App\Traits;


use Exception;
use Illuminate\Support\Str;
use phpseclib3\Crypt\RSA;

trait BiometricCredential
{
    /**
     * Format the public key with key tags
     *
     * @param string $publicKey
     * @return string
     */
    public function formatPublickey(string $publicKey)
    {
        return "-----BEGIN PUBLIC KEY-----\n{$publicKey}\n-----END PUBLIC KEY-----";
    }

    /**
     * Validate the provided public key
     *
     * @param string $publicKey
     * @return bool
     */
    public function isPublicKeyValid(string $publicKey)
    {
        $shouldFormatKey = ! Str::contains($publicKey, 'PUBLIC KEY-----');

        $publicKey = openssl_get_publickey($shouldFormatKey ? $this->formatPublicKey($publicKey) : $publicKey);

        if (! $publicKey || ! is_resource($publicKey)) {
            return false;
        }

        $publicKeyDetails = openssl_pkey_get_details($publicKey);

        return (isset($publicKeyDetails['key']) && OPENSSL_KEYTYPE_RSA === $publicKeyDetails['type']);
    }

    /**
     * Validate a signature against the public key of the user
     *
     * @param string $signature
     * @param string $message
     * @param string $publicKey
     * @return false
     */
    public function isSignatureValid(string $signature, string $message, string $publicKey): bool
    {
        try {
            $rsa = RSA::loadFormat('PKCS1', $publicKey);
            $rsa->withHash('sha256');
            return $rsa->verify($message, base64_decode($signature, true));
        } catch (Exception $exception) {
            logger()->error("Could not validate signature against {$message}", $exception->getTrace());
            return false;
        }
    }
}
