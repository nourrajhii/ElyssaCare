<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250212220451 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE events ADD id_sponnsor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE events ADD CONSTRAINT FK_5387574AB1CCEDF3 FOREIGN KEY (id_sponnsor_id) REFERENCES sponsor (id)');
        $this->addSql('CREATE INDEX IDX_5387574AB1CCEDF3 ON events (id_sponnsor_id)');
        $this->addSql('ALTER TABLE sponsor ADD ids_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sponsor ADD CONSTRAINT FK_818CC9D412013DEC FOREIGN KEY (ids_id) REFERENCES events (id)');
        $this->addSql('CREATE INDEX IDX_818CC9D412013DEC ON sponsor (ids_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE events DROP FOREIGN KEY FK_5387574AB1CCEDF3');
        $this->addSql('DROP INDEX IDX_5387574AB1CCEDF3 ON events');
        $this->addSql('ALTER TABLE events DROP id_sponnsor_id');
        $this->addSql('ALTER TABLE sponsor DROP FOREIGN KEY FK_818CC9D412013DEC');
        $this->addSql('DROP INDEX IDX_818CC9D412013DEC ON sponsor');
        $this->addSql('ALTER TABLE sponsor DROP ids_id');
    }
}
