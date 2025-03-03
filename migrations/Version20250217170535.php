<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250217170535 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE analyse (id INT AUTO_INCREMENT NOT NULL, laboratoire_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, prix NUMERIC(10, 0) NOT NULL, type_analyse VARCHAR(255) NOT NULL, statut VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, date_mise_a_jour DATETIME NOT NULL, INDEX IDX_351B0C7E76E2617B (laboratoire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE analyse_rendez_vous (analyse_id INT NOT NULL, rendez_vous_id INT NOT NULL, INDEX IDX_47DC275B1EFE06BF (analyse_id), INDEX IDX_47DC275B91EF7EAA (rendez_vous_id), PRIMARY KEY(analyse_id, rendez_vous_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE laboratoire (id INT AUTO_INCREMENT NOT NULL, nom_laboratoire VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, responsable VARCHAR(255) NOT NULL, specialite VARCHAR(255) NOT NULL, horaire_ouverture VARCHAR(255) NOT NULL, horaire_fermeture VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rendez_vous (id INT AUTO_INCREMENT NOT NULL, laboratoire_id INT DEFAULT NULL, patient_nom VARCHAR(255) NOT NULL, patient_email VARCHAR(255) NOT NULL, patient_telephone VARCHAR(255) NOT NULL, date DATE NOT NULL, heure TIME NOT NULL, etat VARCHAR(255) NOT NULL, INDEX IDX_65E8AA0A76E2617B (laboratoire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rendez_vous_analyse (rendez_vous_id INT NOT NULL, analyse_id INT NOT NULL, INDEX IDX_988D1C3991EF7EAA (rendez_vous_id), INDEX IDX_988D1C391EFE06BF (analyse_id), PRIMARY KEY(rendez_vous_id, analyse_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE analyse ADD CONSTRAINT FK_351B0C7E76E2617B FOREIGN KEY (laboratoire_id) REFERENCES laboratoire (id)');
        $this->addSql('ALTER TABLE analyse_rendez_vous ADD CONSTRAINT FK_47DC275B1EFE06BF FOREIGN KEY (analyse_id) REFERENCES analyse (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE analyse_rendez_vous ADD CONSTRAINT FK_47DC275B91EF7EAA FOREIGN KEY (rendez_vous_id) REFERENCES rendez_vous (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0A76E2617B FOREIGN KEY (laboratoire_id) REFERENCES laboratoire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rendez_vous_analyse ADD CONSTRAINT FK_988D1C3991EF7EAA FOREIGN KEY (rendez_vous_id) REFERENCES rendez_vous (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rendez_vous_analyse ADD CONSTRAINT FK_988D1C391EFE06BF FOREIGN KEY (analyse_id) REFERENCES analyse (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE analyse DROP FOREIGN KEY FK_351B0C7E76E2617B');
        $this->addSql('ALTER TABLE analyse_rendez_vous DROP FOREIGN KEY FK_47DC275B1EFE06BF');
        $this->addSql('ALTER TABLE analyse_rendez_vous DROP FOREIGN KEY FK_47DC275B91EF7EAA');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0A76E2617B');
        $this->addSql('ALTER TABLE rendez_vous_analyse DROP FOREIGN KEY FK_988D1C3991EF7EAA');
        $this->addSql('ALTER TABLE rendez_vous_analyse DROP FOREIGN KEY FK_988D1C391EFE06BF');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE analyse');
        $this->addSql('DROP TABLE analyse_rendez_vous');
        $this->addSql('DROP TABLE laboratoire');
        $this->addSql('DROP TABLE rendez_vous');
        $this->addSql('DROP TABLE rendez_vous_analyse');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
