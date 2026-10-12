# 工事管理システム

Apache + PHP + Terraform + GitHub Actions で構築する工事管理Webシステムです。  
Claude Code と **GitHub MCP サーバ** を使って自動生成・自動デプロイします。

## ドキュメント

- **[📖 構築ガイド (guide.html)](./guide.html)** — アーキテクチャ・Terraform・CI/CD の全解説
- **[📋 要件定義 (requirements.md)](./requirements/requirements.md)**

## ディレクトリ構成

```
construction-mgmt/
├── guide.html                    # 構築ガイド（ブラウザで開く）
├── requirements/requirements.md  # 要件定義
├── terraform/                    # インフラ as Code
│   ├── main.tf
│   ├── variables.tf
│   ├── outputs.tf
│   └── user_data.sh              # EC2起動時のApache自動インストール
├── .github/workflows/deploy.yml  # CI/CDパイプライン
└── app/                          # Webアプリ本体
    ├── index.php                 # ダッシュボード
    └── style.css
```

## 起動手順

### 1. 前提条件
- AWS アカウント + IAM ユーザー（EC2/VPC/S3権限）
- Terraform CLI v1.9+
- GitHub Secrets の設定（後述）

### 2. S3バケット作成（Terraform state用）
```bash
aws s3api create-bucket \
  --bucket construction-mgmt-tfstate \
  --region ap-northeast-1 \
  --create-bucket-configuration LocationConstraint=ap-northeast-1

aws s3api put-bucket-versioning \
  --bucket construction-mgmt-tfstate \
  --versioning-configuration Status=Enabled
```

### 3. GitHub Secrets 設定
| Secret名 | 値 |
|---|---|
| `AWS_ACCESS_KEY_ID` | IAMアクセスキー |
| `AWS_SECRET_ACCESS_KEY` | IAMシークレットキー |
| `EC2_KEY_NAME` | EC2キーペア名 |
| `EC2_SSH_KEY` | EC2秘密鍵（pemファイル内容） |
| `EC2_HOST` | EC2パブリックIP（初回apply後に設定） |
| `MY_IP` | SSH許可IPアドレス（例: 1.2.3.4/32） |

### 4. デプロイ
```bash
git push origin main
# → GitHub Actions が自動で Terraform apply + rsync デプロイ
```

## 技術スタック

| 層 | 技術 |
|---|---|
| インフラ | AWS EC2 (t3.micro), VPC, Security Group |
| IaC | Terraform 1.9 |
| CI/CD | GitHub Actions |
| Webサーバ | Apache httpd + PHP 8 |
| OS | Amazon Linux 2023 |
| DB | MySQL / MariaDB |

---

Built with [Claude Code](https://claude.ai/code) + GitHub MCP Server
