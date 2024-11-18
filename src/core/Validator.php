<?php

class Validator {
    private $data; // Data input
    private $errors = []; // Menyimpan pesan error
    private $rules = []; // Menyimpan aturan validasi
    private $db; // Koneksi database

    public function __construct($data, $db) {
        $this->data = $data; // Input data
        $this->db = $db;     // PDO Database Connection
    }

    /**
     * Set validation rules
     */
    public function setRules($rules) {
        $this->rules = $rules;
    }

    /**
     * Validate input data based on rules
     */
    public function validate() {
        foreach ($this->rules as $field => $ruleSet) {
            $rules = explode('|', $ruleSet); // Multiple rules per field
            foreach ($rules as $rule) {
                $this->applyRule($field, $rule);
            }
        }
        return empty($this->errors); // Jika tidak ada error, validasi berhasil
    }

    /**
     * Apply individual rule to a field
     */
    private function applyRule($field, $rule) {
        // Rule: required
        if (!isset($this->data[$field]) || trim($this->data[$field]) === '') {
            if ($rule === 'required') {
                $this->addError($field, "$field is required.");
            }
        }

        // Rule: email
        if ($rule === 'email' && isset($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, "$field must be a valid email.");
        }

        // Rule: unique
        if (strpos($rule, 'unique:') === 0) {
            $tableField = explode(':', $rule)[1];
            [$table, $dbField] = explode(',', $tableField);
            if ($this->checkUnique($table, $dbField, $this->data[$field])) {
                $this->addError($field, "$field is already taken.");
            }
        }

        // Rule: min
        if (strpos($rule, 'min:') === 0) {
            $min = explode(':', $rule)[1];
            if (strlen($this->data[$field] ?? '') < $min) {
                $this->addError($field, "$field must be at least $min characters.");
            }
        }

        // Rule: in
        if (strpos($rule, 'in:') === 0) {
            $allowedValues = explode(',', explode(':', $rule)[1]);
            if (!in_array($this->data[$field], $allowedValues)) {
                $this->addError($field, "$field must be one of: " . implode(', ', $allowedValues));
            }
        }
    }

    /**
     * Add error message to a specific field
     */
    private function addError($field, $message) {
        $this->errors[$field][] = $message;
    }

    /**
     * Check uniqueness of a field in the database
     */
    private function checkUnique($table, $field, $value) {
        try {
            // Securely handle table and column names
            $query = sprintf(
                "SELECT COUNT(*) FROM %s WHERE %s = :value",
                $this->escapeIdentifier($table),
                $this->escapeIdentifier($field)
            );

            $stmt = $this->db->prepare($query);
            $stmt->execute(['value' => $value]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            // Log error and return false to skip unique validation
            error_log("Database error in Validator: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Escape identifiers (table and column names)
     */
    private function escapeIdentifier($identifier) {
        // Wrap identifiers in quotes for PostgreSQL
        return '"' . str_replace('"', '""', $identifier) . '"';
    }

    /**
     * Retrieve all validation errors
     */
    public function errors() {
        return $this->errors;
    }
}
