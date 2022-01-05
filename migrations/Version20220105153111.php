<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220105153111 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE tache_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE tache (id INT NOT NULL, corresponding_client_id INT DEFAULT NULL, content VARCHAR(1000) NOT NULL, publication_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, titre VARCHAR(255) NOT NULL, etat VARCHAR(40) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_938720754DF89C0E ON tache (corresponding_client_id)');
        $this->addSql('ALTER TABLE tache ADD CONSTRAINT FK_938720754DF89C0E FOREIGN KEY (corresponding_client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE tache_id_seq CASCADE');
        $this->addSql('DROP TABLE tache');
    }
}
