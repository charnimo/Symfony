<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240528190055 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE facture (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, total INT NOT NULL, card_number INT NOT NULL, expiry_date DATE NOT NULL, cvv INT NOT NULL, submission_date DATE NOT NULL, INDEX IDX_FE866410A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, facture_id_id INT NOT NULL, product_id_id INT NOT NULL, quantite INT NOT NULL, INDEX IDX_1F1B251EED7016E0 (facture_id_id), INDEX IDX_1F1B251EDE18E50B (product_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE866410A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251EED7016E0 FOREIGN KEY (facture_id_id) REFERENCES facture (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251EDE18E50B FOREIGN KEY (product_id_id) REFERENCES products (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE866410A76ED395');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251EED7016E0');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251EDE18E50B');
        $this->addSql('DROP TABLE facture');
        $this->addSql('DROP TABLE item');
    }
}
