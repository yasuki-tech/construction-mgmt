# 工事管理システム 要件定義

## 1. 目的・背景

- 紙ベースの工事台帳・日報をデジタル化し、情報の一元管理を実現する
- 現場・事務所どちらからもリアルタイムに情報を参照・更新できる
- 工程の遅延や作業員の稼働状況を即座に把握できる

---

## 2. ユーザー・権限

| ロール     | できること                                          |
|-----------|---------------------------------------------------|
| 管理者    | 全操作・ユーザー管理・設定変更                        |
| 現場監督  | 工事・工程・日報の登録・編集・閲覧                    |
| 作業員    | 自分が関係する工事・日報の閲覧のみ                    |

---

## 3. 機能要件

### 3.1 工事管理
- [ ] 工事一覧表示（検索・ステータスフィルタ付き）
- [ ] 工事詳細登録（案件名・場所・発注者・工期・担当監督・メモ）
- [ ] 進捗ステータス管理（予定 / 施工中 / 完了 / 中断）
- [ ] 工事一覧のCSVエクスポート

### 3.2 工程管理
- [ ] マイルストーン設定（着工・中間検査・竣工）
- [ ] 工程表示（テーブル形式。ガントチャートは将来対応）
- [ ] 工程の遅延アラート表示

### 3.3 作業員管理
- [ ] 作業員一覧・詳細登録（氏名・連絡先・資格・所属）
- [ ] 資格有効期限アラート（30日前に警告表示）
- [ ] 工事への割り当て管理

### 3.4 日報管理
- [ ] 日報入力（日付・工事・作業内容・人員・天気・特記事項）
- [ ] 日報一覧・検索（工事別・日付範囲）
- [ ] PDF出力（将来対応）

### 3.5 ダッシュボード
- [ ] 進行中工事数・今日の作業員数・未提出日報数のサマリ
- [ ] 直近の更新履歴

---

## 4. 非機能要件

| 項目         | 要件                                          |
|-------------|----------------------------------------------|
| レスポンス   | 通常操作は3秒以内                              |
| 可用性       | 99.9%（月次ダウンタイム43分以内）              |
| セキュリティ | HTTPS必須 / SQLインジェクション対策 / XSS対策  |
| バックアップ | 毎日自動バックアップ・7日間保持                |
| ブラウザ対応 | Chrome最新版・Safari最新版・Edge最新版         |
| レスポンシブ | PC・タブレット・スマートフォン対応             |

---

## 5. 画面一覧

| No | 画面名          | URL                    |
|----|---------------|------------------------|
| 1  | ダッシュボード  | /                      |
| 2  | 工事一覧       | /projects              |
| 3  | 工事詳細・編集  | /projects/{id}         |
| 4  | 工事新規登録   | /projects/new          |
| 5  | 作業員一覧     | /workers               |
| 6  | 作業員詳細     | /workers/{id}          |
| 7  | 日報一覧       | /reports               |
| 8  | 日報入力       | /reports/new           |
| 9  | 設定          | /settings              |

---

## 6. API仕様（将来拡張）

```
GET    /api/projects           工事一覧取得
POST   /api/projects           工事登録
GET    /api/projects/{id}      工事詳細取得
PUT    /api/projects/{id}      工事更新
DELETE /api/projects/{id}      工事削除

GET    /api/workers            作業員一覧
POST   /api/workers            作業員登録

GET    /api/reports            日報一覧
POST   /api/reports            日報登録
```

---

## 7. データベース設計（概要）

```sql
-- 工事テーブル
CREATE TABLE projects (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(255) NOT NULL,
  location    VARCHAR(255),
  client      VARCHAR(255),
  start_date  DATE,
  end_date    DATE,
  status      ENUM('planned','active','completed','suspended') DEFAULT 'planned',
  supervisor  INT,
  notes       TEXT,
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 作業員テーブル
CREATE TABLE workers (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(100) NOT NULL,
  phone       VARCHAR(20),
  license     VARCHAR(100),
  license_exp DATE,
  company     VARCHAR(100),
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 日報テーブル
CREATE TABLE reports (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  project_id  INT NOT NULL,
  report_date DATE NOT NULL,
  weather     ENUM('晴','曇','雨','雪'),
  work_detail TEXT,
  worker_count INT,
  notes       TEXT,
  created_by  INT,
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## 8. 変更履歴

| 日付       | バージョン | 変更内容         | 担当    |
|-----------|----------|----------------|---------|
| 2026-10-12 | 1.0.0   | 初版作成        | Claude Code |
