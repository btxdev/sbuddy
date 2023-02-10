<?php
/*                                                                     *
 * This file is brought to you by Georg Großberger                     *
 * (c) 2012 by Georg Großberger <georg@grossberger.at>                 *
 *                                                                     *
 * It is free software; you can redistribute it and/or modify it under *
 * the terms of the BSD 3-Clause License.                              *
 *                                                                     */

namespace RSA;

/**
 * Backend using the phpseclib
 *
 * @author Georg Großberger <georg@grossberger.at>
 * @copyright 2012 by Georg Großberger
 * @license GPL v3 http://www.gnu.org/licenses/gpl-3.0.txt
 */
class SeclibBackend implements BackendInterface {

	/**
	 * Test if this backend is available
	 *
	 * @return boolean
	 */
	public function isAvailable() {
		return class_exists('\\Crypt_RSA');
	}

	/**
	 * Generates a new key pair and returns it as an array, which has
	 * 0 => Public Key
	 * 1 => Exponent
	 * 3 => Private Key
	 *
	 * @return array
	 */
	public function createKeys() {
		$rsa  = new \Crypt_RSA();
		$data = $rsa->createKey();
		return array($data['publickey'], 0x10001, $data['privatekey']);
	}

	/**
	 * Encrypt the given text with the given key pair
	 *
	 * @param KeyPair $key
	 * @param string $plainText
	 * @return string
	 */
	public function encrypt(KeyPair $key, $plainText) {
		$rsa = new \Crypt_RSA();
		$rsa->loadKey( $key->getPrivateKey() );
		return $rsa->encrypt($plainText);
	}

	/**
	 * Decrypt the given message using the given key pair
	 *
	 * @param KeyPair $key
	 * @param string $encryptedText
	 * @return string
	 */
	public function decrypt(KeyPair $key, $encryptedText) {
		$rsa = new \Crypt_RSA();
		$rsa->loadKey( $key->getPrivateKey() );
		return $rsa->decrypt($encryptedText);
	}
}
