# Notification Microservice

## Setup
1. Set environment variables
 - AWS_SES_MAILER_DSN
 - TWILIO_DSN

2. Run docker
```shell
  docker compose up -d --build
```

3. Run commands in Docker
```shell
  docker compose exec php composer install
```

4. Run consumers
```shell
docker compose exec php bin/console messenger:consume create_notification send_notification send_notification_throttled
```

## Legend
- Documentation https://localhost/api/doc
- Queue: http://localhost:15672
- Mailcatcher: http://localhost:1080
