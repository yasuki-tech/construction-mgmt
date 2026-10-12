variable "aws_region" {
  description = "AWSリージョン"
  type        = string
  default     = "ap-northeast-1"
}

variable "instance_type" {
  description = "EC2インスタンスタイプ"
  type        = string
  default     = "t3.micro"
}

variable "key_name" {
  description = "EC2キーペア名（AWS Consoleで事前作成）"
  type        = string
}

variable "my_ip" {
  description = "SSHアクセスを許可するIPアドレス（例: 123.456.789.0/32）"
  type        = string
}

variable "project_name" {
  description = "プロジェクト名（リソースのタグに使用）"
  type        = string
  default     = "construction-mgmt"
}
