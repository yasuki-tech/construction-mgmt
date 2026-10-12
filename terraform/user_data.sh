#!/bin/bash
# EC2起動時に1回だけ実行される初期化スクリプト
set -e

# パッケージ更新
dnf update -y

# Apache + PHP 8 インストール
dnf install -y httpd php php-mysqlnd php-json php-mbstring php-xml

# Apache 起動 & 自動起動設定
systemctl enable --now httpd

# DocumentRoot の権限設定
mkdir -p /var/www/html/construction-mgmt/app
chown -R apache:apache /var/www/html
chmod -R 755 /var/www/html

# mod_rewrite 有効化 (.htaccess 対応)
sed -i 's/AllowOverride None/AllowOverride All/g' /etc/httpd/conf/httpd.conf
systemctl restart httpd

# MySQL クライアント
dnf install -y mariadb105

# rsync インストール（CI/CDデプロイで使用）
dnf install -y rsync

# ログローテーション設定
cat > /etc/logrotate.d/httpd-construction << 'EOF'
/var/log/httpd/construction-*.log {
    daily
    rotate 30
    compress
    missingok
    notifempty
    sharedscripts
    postrotate
        systemctl reload httpd 2>/dev/null || true
    endscript
}
EOF

echo "Bootstrap complete: $(date)" >> /var/log/user_data.log
