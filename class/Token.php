<?php
class Token {
    public static function generate() {
        if (!isset($_SESSION['token'])) {
            $_SESSION['token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['token'];
    }

    public static function validate($token) {
        if (isset($_SESSION['token']) && hash_equals($_SESSION['token'], $token)) {
            return true;
        }
        return false;
    }

    public static function destroy() {
        unset($_SESSION['token']);
    }
}
?>
