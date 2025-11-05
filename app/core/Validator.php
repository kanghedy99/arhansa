<?php
// File: app/core/Validator.php
// Class untuk menangani semua validasi data dari form.

class Validator {
    private $data;
    private $errors = [];

    public function __construct(array $data) {
        $this->data = $data;
    }

    public function rule($rule, $field, $message = null) {
        $value = $this->data[$field] ?? null;

        switch ($rule) {
            case 'required':
                if (empty($value)) {
                    $this->addError($field, $message ?? "Kolom {$field} wajib diisi.");
                }
                break;
            
            case 'numeric':
                if (!empty($value) && !is_numeric($value)) {
                    $this->addError($field, $message ?? "Kolom {$field} harus berupa angka.");
                }
                break;

            case 'date':
                if (!empty($value) && !\DateTime::createFromFormat('Y-m-d', $value)) {
                    $this->addError($field, $message ?? "Format tanggal {$field} tidak valid.");
                }
                break;
        }
    }

    private function addError($field, $message) {
        $this->errors[$field][] = $message;
    }

    public function fails() {
        return !empty($this->errors);
    }

    public function getErrors() {
        return $this->errors;
    }
}
?>
