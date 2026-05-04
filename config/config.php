<?php
if (!defined('APP_ROOT')) die('Direct access not permitted');

define('DB_HOST', 'bloodlink-db.mysql.database.azure.com');
define('DB_PORT', '3306');
define('DB_NAME', 'bloodbank');
define('DB_USER', 'bloodlinkadmin');
define('DB_PASS', 'Admin@1234!');

define('APP_URL', 'https://bloodlink-e5g2cpdpcahmgqg7.swedencentral-01.azurewebsites.net');

define('SESSION_TIMEOUT', 1800);
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_MINUTES', 15);
define('ADMIN_SECURITY_KEY', 'bloodlink-admin-2024');

define('BLOOD_COMPATIBILITY', [
    'A+'  => ['A+', 'A-', 'O+', 'O-'],
    'A-'  => ['A-', 'O-'],
    'B+'  => ['B+', 'B-', 'O+', 'O-'],
    'B-'  => ['B-', 'O-'],
    'AB+' => ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'],
    'AB-' => ['A-', 'B-', 'AB-', 'O-'],
    'O+'  => ['O+', 'O-'],
    'O-'  => ['O-'],
]);

define('UPLOAD_DIR', APP_ROOT . '/public/uploads/');
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024);