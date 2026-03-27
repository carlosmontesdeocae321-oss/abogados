<?php
require_once __DIR__ . '/../db.php';

// accept JSON body or form POST
$data = json_decode(file_get_contents('php://input'), true);
$message = '';
if (!empty($data) && isset($data['message'])) {
    $message = trim((string)$data['message']);
} elseif (isset($_POST['message'])) {
    $message = trim((string)$_POST['message']);
}

if ($message === '') {
    json_out(['answer' => null]);
}

$msg = mb_strtolower($message);

try {
    $stmt = $pdo->query('SELECT id, question, answer, keywords FROM faqs');
    $rows = $stmt->fetchAll();
    $fallback = null;
    $matched = null;
    foreach ($rows as $r) {
        if ($r['question'] && mb_strtolower(trim($r['question'])) === $msg) { $matched = $r; break; }
        if ($fallback === null) $fallback = $r['answer'];
        if ($r['keywords']) {
            $kws = array_filter(array_map('trim', explode(',', mb_strtolower($r['keywords']))));
            foreach ($kws as $k) {
                if ($k !== '' && mb_strpos($msg, $k) !== false) { $matched = $r; break 2; }
            }
        }
    }

    if (!$matched) {
        json_out(['answer' => $fallback ?? 'Gracias, recibimos tu mensaje y te contactaremos pronto.']);
    }

    // fetch followups
    $stmt2 = $pdo->prepare('SELECT question, answer FROM faq_followups WHERE faq_id = ? ORDER BY ord ASC');
    $stmt2->execute([(int)$matched['id']]);
    $fu = $stmt2->fetchAll();
    json_out(['answer' => $matched['answer'], 'followups' => $fu ?: []]);

} catch (Exception $e) {
    http_response_code(500);
    json_out(['error' => 'DB error']);
}
