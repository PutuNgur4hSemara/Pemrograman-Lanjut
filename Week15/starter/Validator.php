<?php
/**
 * Class Input & Validator Lanjutan
 * Modul Praktikum Minggu 15
 */

class Input
{
    private array $data;

    public function __construct(array $source)
    {
        $this->data = array_map(function ($item) {
            if (is_string($item)) {
                return trim($item);
            } elseif (is_array($item)) {
                return array_map('trim', $item);
            }
            return $item;
        }, $source);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }

    public function all(): array
    {
        return $this->data;
    }
}

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
                if ($rule === 'required' && ($value === null || $value === '' || $value === [])) {
                    $this->addError($field, "Kolom " . ucfirst(str_replace('_', ' ', $field)) . " wajib diisi.");
                    break;
                }

                if ($value === null || $value === '' || $value === []) {
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
                        $this->addError($field, "Minimal harus {$min} karakter.");
                    }
                }

                // Rule: same:target_field (Cross-Field Validation)
                if (str_starts_with($rule, 'same:')) {
                    $targetField = substr($rule, 5);
                    $targetValue = $input->get($targetField);
                    if ($value !== $targetValue) {
                        $this->addError($field, "Nilai tidak cocok dengan " . ucfirst(str_replace('_', ' ', $targetField)) . ".");
                    }
                }

                // Rule: in:a,b,c
                if (str_starts_with($rule, 'in:')) {
                    $allowed = explode(',', substr($rule, 3));
                    if (!in_array($value, $allowed)) {
                        $this->addError($field, "Pilihan tidak valid.");
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
