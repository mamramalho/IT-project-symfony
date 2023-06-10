<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230610220614 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `review` (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, vehicle_id VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_794381C6A76ED395 (user_id), INDEX IDX_794381C6545317D1 (vehicle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vehicle (id VARCHAR(255) NOT NULL, user_id INT NOT NULL, name VARCHAR(100) NOT NULL, company VARCHAR(100) NOT NULL, type VARCHAR(20) NOT NULL, model VARCHAR(255) NOT NULL, year INT NOT NULL, register_year INT NOT NULL, price INT NOT NULL, description LONGTEXT NOT NULL, color VARCHAR(40) NOT NULL, fuel VARCHAR(255) NOT NULL, plate VARCHAR(255) NOT NULL, kms INT NOT NULL, images LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', city VARCHAR(255) NOT NULL, INDEX IDX_1B80E486A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `review` ADD CONSTRAINT FK_794381C6A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE `review` ADD CONSTRAINT FK_794381C6545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id)');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E486A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `review` DROP FOREIGN KEY FK_794381C6A76ED395');
        $this->addSql('ALTER TABLE `review` DROP FOREIGN KEY FK_794381C6545317D1');
        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E486A76ED395');
        $this->addSql('DROP TABLE `review`');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE vehicle');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
