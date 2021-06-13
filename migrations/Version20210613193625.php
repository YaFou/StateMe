<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210613193625 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(
            'CREATE TABLE incident_update (id BLOB NOT NULL --(DC2Type:uuid)
        , incident_id BLOB DEFAULT NULL --(DC2Type:uuid)
        , status_id BLOB DEFAULT NULL --(DC2Type:uuid)
        , message CLOB NOT NULL, updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , PRIMARY KEY(id))'
        );
        $this->addSql('CREATE INDEX IDX_3519899C59E53FB9 ON incident_update (incident_id)');
        $this->addSql('CREATE INDEX IDX_3519899C6BF700BD ON incident_update (status_id)');
        $this->addSql(
            'CREATE TABLE service_status (id BLOB NOT NULL --(DC2Type:uuid)
        , name VARCHAR(255) NOT NULL, icon VARCHAR(255) NOT NULL, color VARCHAR(8) NOT NULL, "default" BOOLEAN NOT NULL, PRIMARY KEY(id))'
        );
        $this->addSql('CREATE TEMPORARY TABLE __temp__incident AS SELECT id FROM incident');
        $this->addSql('DROP TABLE incident');
        $this->addSql(
            'CREATE TABLE incident (id BLOB NOT NULL --(DC2Type:uuid)
        , PRIMARY KEY(id))'
        );
        $this->addSql('INSERT INTO incident (id) SELECT id FROM __temp__incident');
        $this->addSql('DROP TABLE __temp__incident');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE incident_update');
        $this->addSql('DROP TABLE service_status');
        $this->addSql('ALTER TABLE incident ADD COLUMN name VARCHAR(255) NOT NULL COLLATE BINARY');
        $this->addSql('ALTER TABLE incident ADD COLUMN created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE incident ADD COLUMN description VARCHAR(255) DEFAULT NULL COLLATE BINARY');
    }
}
