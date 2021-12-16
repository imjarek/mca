1) Скопировать содержимое примеров конфигураций .env.example и src/.env.example
в .env и src/.env соотвественно
2) Выполнить docker-compose up
3) Выполнить doctrine:migration:diff
4) Выполнить doctrine:migration:migrate 