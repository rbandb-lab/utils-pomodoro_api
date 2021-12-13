-- Doctrine Migration File Generated on 2021-12-12 13:50:38

-- Version Symfony5\Persistence\ORM\Doctrine\Migrations\Version20211212110926
CREATE TABLE orm_worker (id VARCHAR(36) NOT NULL, username VARCHAR(120) NOT NULL, first_name VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, pomodoro_duration INTEGER NOT NULL, short_break_duration INTEGER NOT NULL, long_break_duration INTEGER NOT NULL, start_first_task_in INTEGER NOT NULL, email_validated BOOLEAN NOT NULL, PRIMARY KEY(id));
CREATE TABLE token (id VARCHAR(36) NOT NULL, worker_id VARCHAR(36) DEFAULT NULL, token_string VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , PRIMARY KEY(id));
CREATE INDEX IDX_5F37A13B6B20BA36 ON token (worker_id);
CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL);
CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name);
CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at);
CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at);
