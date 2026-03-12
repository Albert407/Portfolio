<?php
header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false]);
    exit;
}

// Read and decode JSON body
$data = json_decode(file_get_contents('php://input'), true);

$first = htmlspecialchars(trim($data['first'] ?? ''));
$last  = htmlspecialchars(trim($data['last']  ?? ''));
$email = htmlspecialchars(trim($data['email'] ?? ''));
$subj  = htmlspecialchars(trim($data['subj']  ?? 'Portfolio Contact'));
$msg   = htmlspecialchars(trim($data['msg']   ?? ''));

// Basic validation
if (empty($first) || empty($email) || empty($msg) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false]);
    exit;
}

$to      = 'albertniiacromond@gmail.com';
$subject = "Portfolio Contact: $subj";

$body  = "You received a new message from your portfolio website.\n\n";
$body .= "Name:    $first $last\n";
$body .= "Email:   $email\n";
$body .= "Subject: $subj\n\n";
$body .= "Message:\n$msg\n";

$headers  = "From: portfolio-noreply@yourdomain.com\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

$sent = mail($to, $subject, $body, $headers);

echo json_encode(['success' => $sent]);