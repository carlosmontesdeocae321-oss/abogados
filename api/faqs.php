<?php
require_once __DIR__ . '/../db.php';

try {
    $stmt = $pdo->query('SELECT id, question, answer, keywords FROM faqs ORDER BY id ASC');
    $faqs = $stmt->fetchAll();

    // load followups and attach
    $stmt2 = $pdo->query('SELECT faq_id, question, answer, ord FROM faq_followups ORDER BY faq_id, ord ASC');
    $fups = $stmt2->fetchAll();
    $followups = [];
    foreach ($fups as $fu) {
        $followups[$fu['faq_id']][] = ['question' => $fu['question'], 'answer' => $fu['answer']];
    }

    $out = [];
    foreach ($faqs as $f) {
        $out[] = [
            'id' => (int)$f['id'],
            'question' => $f['question'],
            'answer' => $f['answer'],
            'keywords' => $f['keywords'],
            'followups' => isset($followups[$f['id']]) ? $followups[$f['id']] : [],
        ];
    }

    json_out($out);
} catch (Exception $e) {
    http_response_code(500);
    json_out(['error' => 'DB error']);
}
