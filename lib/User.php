<?php
include_once 'UserData.php';
include_once 'UserUtils.php';

class User {
    public static function getData($code, $key) {
        $id = UserUtils::findIdByCode($code);
        if ($id !== null) {
            $userData = new UserData($id);
            echo $userData->get($key);
        }
    }
    public static function login($id, $pw) {
        $userData = new UserData($id);
        if (UserUtils::has($id)) {
            if ($userData->isAvailable()) {
                if ($userData->getPassword() === $pw) {
                    $userData->set('ip', $_SERVER['REMOTE_ADDR']);
                    echo 'Login successful!#' . $userData->getUserCode();
                } else {
                    echo 'Error: The password is incorrect.';
                }
            } else {
                echo 'Error: This ID is not accepted.';
            }
        } else {
            echo 'Error: This ID is not signed up the server.';
        }
    }
    public static function setData($code, $key, $value) {
        $id = UserUtils::findIdByCode($code);
        if ($id !== null) {
            $userData = new UserData($id);
            $userData->set($key, $value);
        }
    }
    public static function signUp($id, $pw, $email) {
        $userData = new UserData($id);
        if ($userData->has('id')) {
            echo 'Error: This ID is already used.';
        } else {
            $userData->set('available', 'false');
            $userData->set('since', date('Y.m.d H:i:s'));
            $userData->set('id', $id);
            $userData->set('password', $pw);
            $userData->set('email', $email);
            $userData->set('user_code', md5($id . mt_rand(0, 99) . $id));
            $userData->set('ip', $_SERVER['REMOTE_ADDR']);
            echo 'Success! You can use this ID after the permission of the admin.';
        }
    }
    /*public static function sortByData($key) {
        $dataFile = new DataFile('user.json');
        $users = $dataFile->getAllKeys();
        $arr = array();
        foreach ($users as $k => $v) {
            if ($v->isAvailable()) {
                if (empty($v->get($key))) {
                    $v->set('key', '0');
                }
                array_push($arr, $v);
            }
        }
        $pattern = '/^[+-]?\d+(\.\d+)?$/';
        usort($arr, function ($a, $b) use ($key, $pattern) {
            $valueA = $a->get($key);
            $valueB = $b->get($key);
            if (preg_match($pattern, $valueA) && preg_match($pattern, $valueB)) {
                $tmp = (int) $valueB - (int) $valueA;
                if ($tmp === 0) {
                    return strnatcmp($a->get('id'), $b->get('id'));
                } else {
                    return $tmp;
                }
            } else {
                $tmp = strnatcmp($valueA, $valueB);
                if ($tmp === 0) {
                    return strnatcmp($a->get('id'), $b->get('id'));
                } else {
                    return $tmp;
                }
            }
        }
        return $arr;
    }*/
}
?>