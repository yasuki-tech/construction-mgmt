# 進捗状況 / Progress

> 他のPCで再開するときはこのファイルを確認してください。

---

## 現在の状態（2026-10-13 時点）

### ✅ 完了済み

- [x] GitHubリポジトリ作成: `yasuki-tech/construction-mgmt`
- [x] 解説HTML作成: `guide.html`（ブラウザで開いて全体像を確認できる）
- [x] 要件定義MD作成: `requirements/requirements.md`
- [x] Terraform設定: `terraform/main.tf` / `variables.tf` / `outputs.tf` / `user_data.sh`
- [x] CI/CDパイプライン: `.github/workflows/deploy.yml`
- [x] Webアプリ（PHP）: `app/index.php` / `app/style.css`
- [x] GitHub Secrets 登録済み:
  - `AWS_ACCESS_KEY_ID`
  - `AWS_SECRET_ACCESS_KEY`
  - `EC2_KEY_NAME`
  - `MY_IP`

---

## ⏳ 次にやること（ここから再開）

### STEP 1 — S3バケットを作成する【★今ここ★】

TerraformのStateファイル保存先が必要。AWSコンソールで手動作成：

1. https://console.aws.amazon.com/s3 を開く
2. 「バケットを作成」
3. バケット名: `construction-mgmt-tfstate`
4. リージョン: `アジアパシフィック (東京) ap-northeast-1`
5. バージョニング: **有効**にする
6. 「バケットを作成」

### STEP 2 — GitHub Actions を再実行する

S3バケット作成後、以下のいずれかで再実行：

```powershell
# ローカルで再実行トリガー（ローカルにクローンがある場合）
cd C:\Users\yasuki\construction-mgmt
git commit --allow-empty -m "re-trigger CI"
git push origin main
```

または GitHub Actions ページで「Re-run jobs」をクリック：
https://github.com/yasuki-tech/construction-mgmt/actions

### STEP 3 — EC2のIPアドレスを確認する

Actions成功後、TerraformのOutputにEC2のIPが表示される。
それを以下のSecretsに追加登録する：

- `EC2_HOST` : EC2のパブリックIPアドレス
- `EC2_SSH_KEY` : EC2キーペアの.pemファイルの中身

### STEP 4 — 再度 push してデプロイまで完走させる

```powershell
git commit --allow-empty -m "deploy app"
git push origin main
```

### STEP 5 — ブラウザで動作確認

`http://<EC2_HOST>/` にアクセスして工事管理システムが表示されれば完成。

---

## 構成メモ

| 項目 | 値 |
|---|---|
| GitHubリポジトリ | https://github.com/yasuki-tech/construction-mgmt |
| ローカルクローン | `C:\Users\yasuki\construction-mgmt` |
| AWSリージョン | ap-northeast-1 (東京) |
| EC2インスタンスタイプ | t3.micro |
| Terraform Stateバケット | construction-mgmt-tfstate |
| Webサーバ | Apache + PHP 8 on Amazon Linux 2023 |

---

## 参考リンク

- 解説HTML（全体アーキテクチャ）: `guide.html` をローカルで開く
- Actions実行状況: https://github.com/yasuki-tech/construction-mgmt/actions
- GitHub Secrets設定: https://github.com/yasuki-tech/construction-mgmt/settings/secrets/actions
- AWSコンソール: https://console.aws.amazon.com
