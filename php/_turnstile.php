<?php
/**
 * Cloudflare Turnstile server-side verification helper.
 *
 * Usage:
 *   require_once __DIR__ . '/_turnstile.php';
 *   if (!verifyTurnstile($body['turnstileToken'] ?? '')) {
 *       http_response_code(400);
 *       echo json_encode(['error' => 'Security check failed. Please refresh and try again.']);
 *       exit;
 *   }
 */

function verifyTurnstile(string $token): bool
{
    $secret = getenv('TURNSTILE_SECRET_KEY') ?: '';

    if (empty($secret) || empty($token)) {
        return false;
    }

    $payload = http_build_query([
        'secret'   => $secret,
        'response' => $token,
        'remoteip' => $_SERVER['REMOTE_ADDR'] ?? '',
    ]);

    $ctx = stream_context_create([
        'http' => [
            'method'  => 'POST',
            'header'  => 'Content-Type: application/x-www-form-urlencoded',
            'content' => $payload,
            'timeout' => 5,
        ],
    ]);

    $result = @file_get_contents(
        'https://challenges.cloudflare.com/turnstile/v0/siteverify',
        false,
        $ctx
    );

    if ($result === false) {
        return false;
    }

    $data = json_decode($result, true);
    return isset($data['success']) && $data['success'] === true;
}
