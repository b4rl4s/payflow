-- Re-apply demo credentials without recreating the MySQL volume.
-- Default compose user: payflow_app / payflow_core (see docker-compose.yml)
-- Run: docker compose exec -T mysql mysql -upayflow_app -pchangeme_app_db payflow_core < database/patch-demo-login.sql

UPDATE users SET
  email = 'admin@admin.com',
  full_name = 'Demo workspace admin',
  password_hash = '$2y$10$HCwiZ88yYa.7Xzkrx.hz2OvLg6nS04QPTsEXQGtahubbKlMUa9yPq'
WHERE id = 1;

SELECT id, email FROM users WHERE id = 1;
