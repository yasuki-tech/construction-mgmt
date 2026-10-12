<?php
// app/index.php — ダッシュボード
// DB接続設定は環境変数から取得
$db_host = getenv('DB_HOST') ?: 'localhost';
$db_name = getenv('DB_NAME') ?: 'construction_mgmt';
$db_user = getenv('DB_USER') ?: 'root';
$db_pass = getenv('DB_PASS') ?: '';

// サンプルデータ（DB未接続時のフォールバック）
$stats = [
  'active_projects' => 8,
  'total_workers'   => 24,
  'today_reports'   => 5,
  'pending_reports' => 3,
];

$recent_projects = [
  ['id'=>1, 'name'=>'○○ビル新築工事',     'status'=>'active',    'end_date'=>'2026-12-31', 'progress'=>65],
  ['id'=>2, 'name'=>'△△道路改修工事',     'status'=>'active',    'end_date'=>'2026-11-30', 'progress'=>40],
  ['id'=>3, 'name'=>'□□マンション外壁改修', 'status'=>'planned',  'end_date'=>'2027-03-31', 'progress'=>0],
  ['id'=>4, 'name'=>'◇◇橋梁補修工事',     'status'=>'completed', 'end_date'=>'2026-09-30', 'progress'=>100],
];

$status_label = ['active'=>'施工中', 'planned'=>'予定', 'completed'=>'完了', 'suspended'=>'中断'];
$status_class = ['active'=>'status-active', 'planned'=>'status-planned', 'completed'=>'status-done', 'suspended'=>'status-suspended'];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>工事管理システム — ダッシュボード</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="layout">
  <!-- サイドバー -->
  <nav class="sidebar">
    <div class="sidebar-brand">⚙ 工事管理</div>
    <ul class="sidebar-nav">
      <li class="active"><a href="index.php">📊 ダッシュボード</a></li>
      <li><a href="projects.php">🏗 工事一覧</a></li>
      <li><a href="workers.php">👷 作業員管理</a></li>
      <li><a href="reports.php">📝 日報管理</a></li>
      <li><a href="settings.php">⚙ 設定</a></li>
    </ul>
  </nav>

  <!-- メインコンテンツ -->
  <div class="main">
    <header class="topbar">
      <h1>ダッシュボード</h1>
      <span class="date"><?= date('Y年m月d日 (D)', strtotime('today')) ?></span>
    </header>

    <!-- サマリカード -->
    <div class="stat-grid">
      <div class="stat-card">
        <div class="stat-icon">🏗</div>
        <div class="stat-body">
          <div class="stat-num"><?= $stats['active_projects'] ?></div>
          <div class="stat-label">進行中工事</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">👷</div>
        <div class="stat-body">
          <div class="stat-num"><?= $stats['total_workers'] ?></div>
          <div class="stat-label">登録作業員数</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">📝</div>
        <div class="stat-body">
          <div class="stat-num"><?= $stats['today_reports'] ?></div>
          <div class="stat-label">本日の日報</div>
        </div>
      </div>
      <div class="stat-card stat-warn">
        <div class="stat-icon">⚠</div>
        <div class="stat-body">
          <div class="stat-num"><?= $stats['pending_reports'] ?></div>
          <div class="stat-label">未提出日報</div>
        </div>
      </div>
    </div>

    <!-- 工事一覧 -->
    <section class="card">
      <div class="card-header">
        <h2>工事一覧</h2>
        <a href="projects.php?action=new" class="btn btn-primary">+ 新規登録</a>
      </div>
      <table class="table">
        <thead>
          <tr><th>案件名</th><th>ステータス</th><th>完了予定</th><th>進捗</th><th></th></tr>
        </thead>
        <tbody>
          <?php foreach ($recent_projects as $p): ?>
          <tr>
            <td><?= htmlspecialchars($p['name']) ?></td>
            <td><span class="badge <?= $status_class[$p['status']] ?>"><?= $status_label[$p['status']] ?></span></td>
            <td><?= $p['end_date'] ?></td>
            <td>
              <div class="progress-bar">
                <div class="progress-fill" style="width:<?= $p['progress'] ?>%"></div>
              </div>
              <span class="progress-num"><?= $p['progress'] ?>%</span>
            </td>
            <td><a href="projects.php?id=<?= $p['id'] ?>" class="link">詳細 →</a></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>
  </div>
</div>
</body>
</html>
