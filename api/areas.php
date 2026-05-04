<?php
// AJAX endpoint — returns areas for a given city (used by location picker)
define('APP_ROOT', dirname(__DIR__));
require_once APP_ROOT . '/config/config.php';
require_once APP_ROOT . '/config/database.php';
require_once APP_ROOT . '/includes/functions.php';
initSession();
apiRateLimit(30);
header('Content-Type: application/json');

$cityId = filter_input(INPUT_GET, 'city_id', FILTER_VALIDATE_INT);
$q = trim($_GET['q'] ?? '');
if (!$cityId || $cityId < 1) { echo json_encode(['error'=>'Invalid city_id','areas'=>[]]); exit; }
$q = preg_replace('/[^\p{L}\p{N}\s\-]/u', '', $q);

$db = Database::getInstance();
if (strlen($q) < 1) {
    // Return ALL areas for the city on focus/empty query
    $stmt = $db->prepare("SELECT id, name FROM areas WHERE city_id=:c AND is_active=1 ORDER BY name LIMIT 50");
    $stmt->execute([':c'=>$cityId]);
} else {
    $stmt = $db->prepare("SELECT id, name FROM areas WHERE city_id=:c AND is_active=1 AND name LIKE :q ORDER BY name LIMIT 10");
    $stmt->execute([':c'=>$cityId, ':q'=>$q.'%']);
}
echo json_encode(['areas'=>$stmt->fetchAll()]);
