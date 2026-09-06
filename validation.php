<?php
function checkRequiredField(string $value, string $label): ?string
{
    return trim($value) === '' ? "$label is required." : null;
}

function verifyEmailAddress(string $value): ?string
{
    return filter_var($value, FILTER_VALIDATE_EMAIL) ? null : "Enter a valid email address.";
}

function verifyMinimumLength(string $value, string $label, int $min): ?string
{
    return strlen($value) < $min ? "$label must be at least $min characters." : null;
}

function validateLoginPayload(array $post): array
{
    $email    = trim($post['email'] ?? '');
    $password = trim($post['password'] ?? '');

    $errors = array_filter([
        checkRequiredField($email, 'Email'),
        verifyEmailAddress($email),
        checkRequiredField($password, 'Password')
    ]);
    
    $errors = array_values($errors);

    if (empty($errors)) {
        $email = htmlspecialchars($email);
    }

    return [
        'errors' => $errors,
        'data'   => ['email' => $email, 'password' => $password],
    ];
}

function validateRegisterPayload(array $post): array
{
    $email    = trim($post['email'] ?? '');
    $password = trim($post['password'] ?? '');

    $errors = array_filter([
        checkRequiredField($email, 'Email'),
        verifyEmailAddress($email),
        checkRequiredField($password, 'Password'),
        verifyMinimumLength($password, 'Password', 6)
    ]);
    
    $errors = array_values($errors);

    if (empty($errors)) {
        $email = htmlspecialchars($email);
    }

    return [
        'errors' => $errors,
        'data'   => ['email' => $email, 'password' => $password],
    ];
}
?>