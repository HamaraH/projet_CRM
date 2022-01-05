<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220105123549 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE note_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE note (id INT NOT NULL, content VARCHAR(5000) NOT NULL, corresponding_client INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE client ADD notes TEXT NOT NULL');
        $this->addSql('COMMENT ON COLUMN client.notes IS \'(DC2Type:array)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE note_id_seq CASCADE');
        $this->addSql('DROP TABLE note');
        $this->addSql('ALTER TABLE client DROP notes');
    }
}
