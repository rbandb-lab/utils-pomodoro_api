<?php

declare(strict_types=1);

namespace Symfony5\Persistence\ORM\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211212200701 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activity_inventory (id VARCHAR(36) NOT NULL, calendar_task_list_id VARCHAR(36) DEFAULT NULL, unplanned_task_id VARCHAR(36) DEFAULT NULL, todo_task_list_id VARCHAR(36) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_56356D81C33158C7 ON activity_inventory (calendar_task_list_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_56356D815A7B4D3B ON activity_inventory (unplanned_task_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_56356D81C93F1A9 ON activity_inventory (todo_task_list_id)');
        $this->addSql('CREATE TABLE calendar_task (id VARCHAR(36) NOT NULL, task_list_id VARCHAR(36) DEFAULT NULL, category_id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, state VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E85871224F3C61 ON calendar_task (task_list_id)');
        $this->addSql('CREATE TABLE calendar_task_list (id VARCHAR(36) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE orm_worker (id VARCHAR(36) NOT NULL, activity_inventory_id VARCHAR(36) DEFAULT NULL, username VARCHAR(120) NOT NULL, first_name VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, pomodoro_duration INT NOT NULL, short_break_duration INT NOT NULL, long_break_duration INT NOT NULL, start_first_task_in INT NOT NULL, email_validated BOOLEAN NOT NULL, roles JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AD30EE2D566518 ON orm_worker (activity_inventory_id)');
        $this->addSql('CREATE TABLE todo_task (id VARCHAR(36) NOT NULL, task_list_id VARCHAR(36) DEFAULT NULL, category_id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, state VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DAFBD3A224F3C61 ON todo_task (task_list_id)');
        $this->addSql('CREATE TABLE todo_task_list (id VARCHAR(36) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE token (id VARCHAR(36) NOT NULL, worker_id VARCHAR(36) DEFAULT NULL, token_string VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5F37A13B6B20BA36 ON token (worker_id)');
        $this->addSql('COMMENT ON COLUMN token.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE unplanned_task (id VARCHAR(36) NOT NULL, task_list_id VARCHAR(36) DEFAULT NULL, category_id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, state VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D99102D6224F3C61 ON unplanned_task (task_list_id)');
        $this->addSql('CREATE TABLE unplanned_task_list (id VARCHAR(36) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE activity_inventory ADD CONSTRAINT FK_56356D81C33158C7 FOREIGN KEY (calendar_task_list_id) REFERENCES calendar_task_list (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE activity_inventory ADD CONSTRAINT FK_56356D815A7B4D3B FOREIGN KEY (unplanned_task_id) REFERENCES unplanned_task_list (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE activity_inventory ADD CONSTRAINT FK_56356D81C93F1A9 FOREIGN KEY (todo_task_list_id) REFERENCES todo_task_list (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE calendar_task ADD CONSTRAINT FK_E85871224F3C61 FOREIGN KEY (task_list_id) REFERENCES calendar_task_list (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orm_worker ADD CONSTRAINT FK_AD30EE2D566518 FOREIGN KEY (activity_inventory_id) REFERENCES activity_inventory (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE todo_task ADD CONSTRAINT FK_DAFBD3A224F3C61 FOREIGN KEY (task_list_id) REFERENCES todo_task_list (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE token ADD CONSTRAINT FK_5F37A13B6B20BA36 FOREIGN KEY (worker_id) REFERENCES orm_worker (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE unplanned_task ADD CONSTRAINT FK_D99102D6224F3C61 FOREIGN KEY (task_list_id) REFERENCES unplanned_task_list (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE orm_worker DROP CONSTRAINT FK_AD30EE2D566518');
        $this->addSql('ALTER TABLE activity_inventory DROP CONSTRAINT FK_56356D81C33158C7');
        $this->addSql('ALTER TABLE calendar_task DROP CONSTRAINT FK_E85871224F3C61');
        $this->addSql('ALTER TABLE token DROP CONSTRAINT FK_5F37A13B6B20BA36');
        $this->addSql('ALTER TABLE activity_inventory DROP CONSTRAINT FK_56356D81C93F1A9');
        $this->addSql('ALTER TABLE todo_task DROP CONSTRAINT FK_DAFBD3A224F3C61');
        $this->addSql('ALTER TABLE activity_inventory DROP CONSTRAINT FK_56356D815A7B4D3B');
        $this->addSql('ALTER TABLE unplanned_task DROP CONSTRAINT FK_D99102D6224F3C61');
        $this->addSql('DROP TABLE activity_inventory');
        $this->addSql('DROP TABLE calendar_task');
        $this->addSql('DROP TABLE calendar_task_list');
        $this->addSql('DROP TABLE orm_worker');
        $this->addSql('DROP TABLE todo_task');
        $this->addSql('DROP TABLE todo_task_list');
        $this->addSql('DROP TABLE token');
        $this->addSql('DROP TABLE unplanned_task');
        $this->addSql('DROP TABLE unplanned_task_list');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
