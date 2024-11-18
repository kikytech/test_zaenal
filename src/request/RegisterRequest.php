<?php
require_once __DIR__ . '/../core/Validator.php';

class RegisterRequest {
    private $validator;

    public function __construct($data, $db) {
        $this->validator = new Validator($data, $db);

        // Set aturan validasi
        $this->validator->setRules([
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6',
            'role' => 'required|in:user,admin',
        ]);
    }

    public function validate() {
        return $this->validator->validate();
    }

    public function errors() {
        return $this->validator->errors();
    }
}
?>
