<?php
/**
 * Class Validator: Mesin Validasi Berbasis Aturan (Rule Engine)
 * Modul Praktikum Minggu 14
 */

require_once __DIR__ . '/Input.php';

class Validator
{
    private array $errors = [];

    public function validate(Input $input, array $rules): bool
    {
        $this->errors = [];

        foreach ($rules as $field => $fieldRules) {
            $value = $input->get($field);

            foreach ($fieldRules as $rule) {
                // Rule: required
                if ($rule === 'required' && ($value === null || $value === '')) {
                    $this->addError($field, "Kolom " . ucfirst($field) . " wajib diisi.");
                    break; // Jangan lanjutkan rule lain jika kosong
                }

                // Lewati aturan berikutnya jika value kosong dan bukan required
                if ($value === null || $value === '') {
                    continue;
                }

                // Rule: email
                if ($rule === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, "Format alamat email tidak valid.");
                }

                // Rule: min:n
                if (str_starts_with($rule, 'min:')) {
                    $min = (int)substr($rule, 4);
                    if (strlen((string)$value) < $min) {
                        $this->addError($field, "Kolom " . ucfirst($field) . " minimal harus {$min} karakter.");
                    }
                }

                // Rule: max:n
                if (str_starts_with($rule, 'max:')) {
                    $max = (int)substr($rule, 4);
                    if (strlen((string)$value) > $max) {
                        $this->addError($field, "Kolom " . ucfirst($field) . " maksimal {$max} karakter.");
                    }
                }

                // Rule: integer
                if ($rule === 'integer' && !filter_var($value, FILTER_VALIDATE_INT)) {
                    $this->addError($field, "Kolom " . ucfirst($field) . " harus berupa bilangan bulat.");
                }

                // Rule: numeric
                if ($rule === 'numeric' && !is_numeric($value)) {
                    $this->addError($field, "Kolom " . ucfirst($field) . " harus berupa angka.");
                }

                // Rule: between:min,max
                if (str_starts_with($rule, 'between:')) {
                    $bounds = explode(',', substr($rule, 8));
                    $min = (float)$bounds[0];
                    $max = (float)$bounds[1];
                    $numVal = (float)$value;
                    if ($numVal < $min || $numVal > $max) {
                        $this->addError($field, "Kolom " . ucfirst($field) . " harus berada dalam rentang {$min} sampai {$max}.");
                    }
                }
            }
        }

        return empty($this->errors);
    }

    private function addError(string $field, string $message): void
    {
        $this->errors[$field][] = $message;
    }

    public function passes(): bool { return empty($this->errors); }
    public function fails(): bool { return !empty($this->errors); }
    public function errors(): array { return $this->errors; }
    public function getFirstError(string $field): ?string { return $this->errors[$field][0] ?? null; }
    public function hasError(string $field): bool { return !empty($this->errors[$field]); }
}
