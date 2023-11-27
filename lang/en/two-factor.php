<?php

return [
    'title' => 'Two factor authentication',
    'code' => 'Code',
    'buttons' => [
        'enable' => 'Enable',
        'disable' => 'Disable',
        'confirm' => 'Confirm',
    ],
    '2fa_alert' => 'Two-Factor Authentication (2FA) is currently not active on your account. For enhanced security, click here to enable 2FA. This message will disappear once 2FA is enabled.',
    'enable' => [
        'description' => 'Scan this QR code using an authenticator (for example, Google Authenticator) to setup & enter OTP to activate 2FA',
    ],
    'messages' => [
        'enabled' => 'Two factor authentication has been enabled',
        'disabled' => 'Two factor authentication has been disabled',
        'invalid_code' => 'Invalid code',
    ],
    'recovery_codes' => [
        'description' => 'Recovery code are used to access your account in the event you cannot receive two-factor authentication codes.',
    ],
    'update_settings' => 'Update your two factor security settings',
    'description' => 'Two factor authentication (2FA) strengthens access security by requiring two methods (also referred to as factors) to verify your identity. Two factor authentication protects against phishing, social engineering and password brute force attacks and secures your logins from attackers exploiting weak or stolen credentials.',
];
