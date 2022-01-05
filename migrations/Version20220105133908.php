<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220105133908 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client DROP notes');
        $this->addSql('ALTER TABLE note ADD corresponding_client_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA144DF89C0E FOREIGN KEY (corresponding_client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_CFBDFA144DF89C0E ON note (corresponding_client_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE client ADD notes TEXT NOT NULL');
        $this->addSql('COMMENT ON COLUMN client.notes IS \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE note DROP CONSTRAINT FK_CFBDFA144DF89C0E');
        $this->addSql('DROP INDEX IDX_CFBDFA144DF89C0E');
        $this->addSql('ALTER TABLE note DROP corresponding_client_id');
    }
}
