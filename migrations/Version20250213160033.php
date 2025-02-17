<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250213160033 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE events ADD id_sponsor INT DEFAULT NULL, DROP updated_at');
        $this->addSql('ALTER TABLE events ADD CONSTRAINT FK_5387574A5F1160A4 FOREIGN KEY (id_sponsor) REFERENCES sponsor (id)');
        $this->addSql('CREATE INDEX IDX_5387574A5F1160A4 ON events (id_sponsor)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE events DROP FOREIGN KEY FK_5387574A5F1160A4');
        $this->addSql('DROP INDEX IDX_5387574A5F1160A4 ON events');
        $this->addSql('ALTER TABLE events ADD updated_at DATETIME DEFAULT NULL, DROP id_sponsor');
    }
}
