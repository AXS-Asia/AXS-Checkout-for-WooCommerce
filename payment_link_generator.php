<?php

require_once __DIR__ . '/vendor/autoload.php';

use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Encryption\Algorithm\KeyEncryption\PBES2HS512A256KW;
use Jose\Component\Encryption\Algorithm\ContentEncryption\A256GCM;
use Jose\Component\Encryption\JWEBuilder;
use Jose\Component\Encryption\JWEDecrypter;
use Jose\Component\Encryption\JWELoader;
use Jose\Component\Encryption\Serializer\CompactSerializer;
use Jose\Component\Encryption\Serializer\JWESerializerManager;
use Jose\Component\Core\JWK;
use Jose\Component\Encryption\Compression\CompressionMethodManager;
use Jose\Component\Encryption\Compression\Deflate;

class PaymentLinkGenerator
{
    public function generatePaymentLink(string $paymentLinkId, string $clientId, string $secretKey, array $params): string
    {
        $iterationCount = 1000;
        $payload = json_encode($params);
        $encryptedPayload = $this->encryptSensitiveData($payload, $iterationCount, $secretKey, $clientId);

        return $paymentLinkId . '?data=' . $encryptedPayload;
    }

    public function encryptSensitiveData(string $payload, int $iterationCount, string $secretKey, string $keyId): string
    {
        // Create a JWK key with base64-encoded secret
        $b64Key = rtrim(strtr(base64_encode($secretKey), '+/', '-_'), '=');

        $jwk = new JWK([
            'kty' => 'oct',
            'k' => $b64Key,
        ]);

        // Create algorithm managers
        $keyEncryptionAlgorithmManager = new AlgorithmManager([
            new PBES2HS512A256KW(16, 1000),
        ]);

        $contentEncryptionAlgorithmManager = new AlgorithmManager([
            new A256GCM(),
        ]);

        
        $compression = new CompressionMethodManager([
            new Deflate(),
        ]);


        // Create JWE Builder
        $jweBuilder = new JWEBuilder(
            $keyEncryptionAlgorithmManager,
            $contentEncryptionAlgorithmManager,
            $compression
        );

        // Prepare protected header
        $protectedHeader = [
            'alg' => 'PBES2-HS512+A256KW',
            'enc' => 'A256GCM',
            'kid' => $keyId,
            'p2c' => $iterationCount
        ];

        // Build JWE
        $jwe = $jweBuilder
            ->create()
            ->withPayload($payload)
            ->withSharedProtectedHeader($protectedHeader)
            ->addRecipient($jwk)
            ->build();

        // Serialize to compact format
        $serializer = new CompactSerializer();

        return $serializer->serialize($jwe, 0);
    }


    /**
     * Decrypt JWE token to verify the payload
     */
    public function decryptJWE(string $jweToken, string $secretKey): array
    {
        // Create a JWK key with base64-encoded secret (same as encryption)
        $b64Key = rtrim(strtr(base64_encode($secretKey), '+/', '-_'), '=');

        $jwk = new JWK([
            'kty' => 'oct',
            'k' => $b64Key,
        ]);

        // Create algorithm managers (same as encryption)
        $keyEncryptionAlgorithmManager = new AlgorithmManager([
            new PBES2HS512A256KW(16, 1000),
        ]);

        $contentEncryptionAlgorithmManager = new AlgorithmManager([
            new A256GCM(),
        ]);

        $compression = new CompressionMethodManager([
            new Deflate(),   
        ]);

        // Create JWE Decrypter
        $jweDecrypter = new JWEDecrypter(
            $keyEncryptionAlgorithmManager,
            $contentEncryptionAlgorithmManager,
            $compression
        );

        // Create serializer managers
        $serializerManager = new JWESerializerManager([
            new CompactSerializer(),
        ]);

        // Create JWE Loader
        $jweLoader = new JWELoader(
            $serializerManager,
            $jweDecrypter,
            null
        );

        try {
            // Load and decrypt the JWE token
            $jwe = $jweLoader->loadAndDecryptWithKey($jweToken, $jwk, $recipient);

            // Get the payload
            $payload = $jwe->getPayload();

            // Decode JSON payload
            $decodedPayload = json_decode($payload, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Failed to decode JSON payload: ' . json_last_error_msg());
            }

            return [
                'success' => true,
                'payload' => $decodedPayload,
                'header' => $jwe->getSharedProtectedHeader(),
                'raw_payload' => $payload
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Extract JWE token from payment link URL
     */
    public function extractJWEFromPaymentLink(string $paymentLink): ?string
    {
        $parsedUrl = parse_url($paymentLink);

        if (!isset($parsedUrl['query'])) {
            return null;
        }

        parse_str($parsedUrl['query'], $queryParams);

        return $queryParams['data'] ?? null;
    }

    /**
     * Check payment status with AXS API
     * 
     * @param string $merchant_link The merchant link
     * @param string $client_key The client key
     * @param string $secret_key The secret key
     * @param string $order_id The order ID to check
     * @return array|null The payment status response or null if failed
     */
    public function checkPaymentStatus($merchant_link, $client_key, $secret_key, $order_id)
    {
        try {
            // Prepare the request parameters
            $params = [
                'clientId' => $client_key,
                'merchantRef' => $order_id,
                'timestamp' => time()
            ];

            // Generate signature
            $signature = $this->generateSignature($params, $secret_key);
            $params['signature'] = $signature;

            // Construct the status check URL
            $status_url = rtrim($merchant_link, '/') . '/api/v1/payment/status';

            // Make the API request
            $response = wp_remote_post($status_url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ],
                'body' => json_encode($params),
                'timeout' => 30
            ]);

            if (is_wp_error($response)) {
                throw new Exception($response->get_error_message());
            }

            $body = wp_remote_retrieve_body($response);
            $result = json_decode($body, true);

            if (!$result || !isset($result['status'])) {
                throw new Exception('Invalid response from AXS API');
            }

            return $result;
        } catch (Exception $e) {
            // Log the error but don't throw it
            error_log('AXS Payment Status Check Error: ' . $e->getMessage());
            return null;
        }
    }
}
