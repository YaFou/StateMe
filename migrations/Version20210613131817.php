<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210613131817 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(
            'CREATE TABLE incident (id BLOB NOT NULL --(DC2Type:uuid)
        , name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))'
        );
        $this->addSql(
            'CREATE TABLE incident_status (id BLOB NOT NULL --(DC2Type:uuid)
        , name VARCHAR(255) NOT NULL, icon VARCHAR(255) NOT NULL, color VARCHAR(8) NOT NULL, "default" BOOLEAN NOT NULL, PRIMARY KEY(id))'
        );
        $this->addSql(
            'CREATE TABLE service (id BLOB NOT NULL --(DC2Type:uuid)
        , name VARCHAR(255) NOT NULL, url VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))'
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE incident');
        $this->addSql('DROP TABLE incident_status');
        $this->addSql('DROP TABLE service');
    }
}
