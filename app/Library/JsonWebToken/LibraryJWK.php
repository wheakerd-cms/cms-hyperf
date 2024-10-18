<?php /** @noinspection PhpUnused */
declare(strict_types=1);

namespace App\Library\JsonWebToken;

use Jose\Component\Core\JWK as JoseJWK;
use Jose\Component\KeyManagement\JWKFactory;

/**
 * Key (JWK)
 * @LibraryJWK
 * @\App\Library\JsonWebToken\LibraryJWK
 */
class LibraryJWK
{
    /**
     * Octet String
     * Additional parameters will be set to limit the scope of this key (e.g. signature/verification only with the HS256 algorithm).
     * @return JoseJWK
     */
    static public function createOctKey(): JoseJWK
    {
        return JWKFactory::createOctKey(
            1024, // Size in bits of the key. Should be at least of the same size as the hashing algorithm.
            [
                'alg' => 'HS256', // This key must only be used with the HS256 algorithm
                'use' => 'sig'    // This key is used for signature/verification operations only
            ]
        );
    }

    /**
     * Octet String
     * If you already have a shared secret, you can use it to create an oct key:
     * @return JoseJWK
     */
    static public function createFromSecret(): JoseJWK
    {
        return JWKFactory::createFromSecret(
            'My Secret Key',       // The shared secret
            [                      // Optional additional members
                'alg' => 'HS256',
                'use' => 'sig'
            ]
        );
    }

    /**
     * RSA Key Pair RSA
     * The key size must be of 384 bits at least, but nowadays, the recommended size is 2048 bits.
     * @return JoseJWK
     */
    static public function createRSAKey(): JoseJWK
    {
        return JWKFactory::createRSAKey(
            4096, // Size in bits of the key. We recommend at least 2048 bits.
            [
                'alg' => 'RSA-OAEP-256', // This key must only be used with the RSA-OAEP-256 algorithm
                'use' => 'enc'    // This key is used for encryption/decryption operations only
            ]
        );
    }


    /**
     * Elliptic Curve Key Pair
     * The following example will show you how to create an EC key.
     * The supported curves are:
     * P-256
     * P-384
     * P-521 (note that this is 521 and not 512) P-521
     * @return JoseJWK
     */
    static public function createECKey(): JoseJWK
    {
        return JWKFactory::createECKey('P-256');
    }


    /**
     * Elliptic Curve Key Pair
     * Octet Key Pair
     * The following example will show you how to create a OKP key.
     * The supported curves are:
     * Ed25519 for signature/verification only
     * X25519 for encryption/decryption only
     * @return JoseJWK
     */
    static public function createOKPKey(): JoseJWK
    {
        return JWKFactory::createOKPKey('X25519');
    }

    /**
     * None Key
     * The none key type is a special type used only for the none algorithm.
     * @return JoseJWK
     */
    static public function createNoneKey(): JoseJWK
    {
        return JWKFactory::createNoneKey();
    }

    //  ......
}