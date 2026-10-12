output "ec2_public_ip" {
  description = "EC2インスタンスのパブリックIPアドレス"
  value       = aws_instance.web.public_ip
}

output "ec2_public_dns" {
  description = "EC2インスタンスのパブリックDNS"
  value       = aws_instance.web.public_dns
}

output "app_url" {
  description = "アプリケーションURL"
  value       = "http://${aws_instance.web.public_ip}"
}
