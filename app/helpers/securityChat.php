<?php
class SecurityHelper
{
    // Key bí mật (Nên lưu trong file config hoặc .env, không nên hardcode như vầy nếu production)
    // Key phải đủ 32 ký tự cho AES-256
    private static $secret_key = 'Q9!xR@M7k#2E%ZpL4^WcF8B&yT$H6N';
    private static $method = 'AES-256-CBC';

    public static function encrypt($message)
    {
        // 1. Tạo IV ngẫu nhiên (16 bytes cho AES-256-CBC)
        $ivLength = openssl_cipher_iv_length(self::$method);
        $iv = openssl_random_pseudo_bytes($ivLength);

        // 2. Mã hóa tin nhắn
        $encryptedRaw = openssl_encrypt($message, self::$method, self::$secret_key, 0, $iv);

        // 3. Kết hợp IV và tin nhắn đã mã hóa, sau đó encode Base64 để lưu vào DB
        // Format lưu: IV::EncryptedData
        return base64_encode($iv . '::' . $encryptedRaw);
    }

    public static function decrypt($encryptedMessage)
    {
        // 1. Decode Base64
        $data = base64_decode($encryptedMessage);

        // 2. Tách IV và tin nhắn mã hóa ra
        if (strpos($data, '::') === false) return null; // Dữ liệu lỗi
        list($iv, $encryptedRaw) = explode('::', $data, 2);

        // 3. Giải mã
        return openssl_decrypt($encryptedRaw, self::$method, self::$secret_key, 0, $iv);
    }
}
