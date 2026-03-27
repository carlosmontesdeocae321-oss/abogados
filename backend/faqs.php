<?php
// backend/faqs.php - Admin CRUD for FAQs and followups
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';

$action = $_REQUEST['action'] ?? '';
$pdo = getPDO();

if (in_array($action, ['create','update','delete'], true)) {
    requireAdmin();
}

header('Content-Type: application/json');

try {
    if ($action === 'list') {
        $stmt = $pdo->query('SELECT id, question, answer, keywords FROM faqs ORDER BY id ASC');
        $faqs = $stmt->fetchAll();
        $stmt2 = $pdo->query('SELECT id, faq_id, question, answer, ord FROM faq_followups ORDER BY faq_id, ord ASC');
        $fups = $stmt2->fetchAll();
        $followups = [];
        foreach ($fups as $fu) {
            $followups[$fu['faq_id']][] = ['id'=>$fu['id'],'question'=>$fu['question'],'answer'=>$fu['answer'],'ord'=>$fu['ord']];
        }
        $out = [];
        foreach ($faqs as $f) {
            $out[] = [
                'id' => (int)$f['id'],
                'question' => $f['question'],
                'answer' => $f['answer'],
                'keywords' => $f['keywords'],
                'followups' => $followups[$f['id']] ?? []
            ];
        }
        echo json_encode($out);
        exit;
    }

    if ($action === 'get' && !empty($_GET['id'])) {
        $id = (int)$_GET['id'];
        $stmt = $pdo->prepare('SELECT id, question, answer, keywords FROM faqs WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if (!$row) { http_response_code(404); echo json_encode(['error'=>'Not found']); exit; }
        $stmt2 = $pdo->prepare('SELECT id, question, answer, ord FROM faq_followups WHERE faq_id = ? ORDER BY ord ASC');
        $stmt2->execute([$id]);
        $fups = $stmt2->fetchAll();
        $row['followups'] = $fups;
        echo json_encode($row);
        exit;
    }

    if ($action === 'create') {
        $question = trim($_POST['question'] ?? '');
        $answer = trim($_POST['answer'] ?? '');
        $keywords = trim($_POST['keywords'] ?? '');
        $followups = json_decode($_POST['followups'] ?? '[]', true);
        if ($question === '' || $answer === '') { http_response_code(422); echo json_encode(['error'=>'Question and answer required']); exit; }
        $stmt = $pdo->prepare('INSERT INTO faqs (question, answer, keywords) VALUES (?, ?, ?)');
        $stmt->execute([$question, $answer, $keywords]);
        $faqId = $pdo->lastInsertId();
        if (is_array($followups)) {
            $ord = 0;
            $ins = $pdo->prepare('INSERT INTO faq_followups (faq_id, question, answer, ord) VALUES (?, ?, ?, ?)');
            foreach ($followups as $fu) {
                $ins->execute([$faqId, $fu['question'] ?? '', $fu['answer'] ?? '', $ord++]);
            }
        }
        echo json_encode(['success'=>true,'id'=>$faqId]); exit;
    }

    if ($action === 'update') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) { http_response_code(422); echo json_encode(['error'=>'Invalid id']); exit; }
        $question = trim($_POST['question'] ?? '');
        $answer = trim($_POST['answer'] ?? '');
        $keywords = trim($_POST['keywords'] ?? '');
        $followups = json_decode($_POST['followups'] ?? '[]', true);
        if ($question === '' || $answer === '') { http_response_code(422); echo json_encode(['error'=>'Question and answer required']); exit; }
        $stmt = $pdo->prepare('UPDATE faqs SET question = ?, answer = ?, keywords = ? WHERE id = ?');
        $stmt->execute([$question, $answer, $keywords, $id]);
        // replace followups: delete existing then insert new
        $pdo->prepare('DELETE FROM faq_followups WHERE faq_id = ?')->execute([$id]);
        if (is_array($followups)) {
            $ord = 0;
            $ins = $pdo->prepare('INSERT INTO faq_followups (faq_id, question, answer, ord) VALUES (?, ?, ?, ?)');
            foreach ($followups as $fu) {
                $ins->execute([$id, $fu['question'] ?? '', $fu['answer'] ?? '', $ord++]);
            }
        }
        echo json_encode(['success'=>true,'id'=>$id]); exit;
    }

    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) { http_response_code(422); echo json_encode(['error'=>'Invalid id']); exit; }
        $stmt = $pdo->prepare('DELETE FROM faqs WHERE id = ?');
        $stmt->execute([$id]);
        echo json_encode(['success'=>true]); exit;
    }

    http_response_code(400);
    echo json_encode(['error'=>'Invalid action']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error'=>'Server error']);
}

?>
