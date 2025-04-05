<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250405012604 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gift (id INT AUTO_INCREMENT NOT NULL, event_id INT DEFAULT NULL, category_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, img VARCHAR(255) DEFAULT NULL, web_site VARCHAR(255) DEFAULT NULL, place VARCHAR(255) DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_A47C990D71F7E88B (event_id), INDEX IDX_A47C990D12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE gift ADD CONSTRAINT FK_A47C990D71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE gift ADD CONSTRAINT FK_A47C990D12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE event CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE `member` ADD event_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `member` ADD CONSTRAINT FK_70E4FA7871F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('CREATE INDEX IDX_70E4FA7871F7E88B ON `member` (event_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE gift DROP FOREIGN KEY FK_A47C990D71F7E88B');
        $this->addSql('ALTER TABLE gift DROP FOREIGN KEY FK_A47C990D12469DE2');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE gift');
        $this->addSql('ALTER TABLE event CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `member` DROP FOREIGN KEY FK_70E4FA7871F7E88B');
        $this->addSql('DROP INDEX IDX_70E4FA7871F7E88B ON `member`');
        $this->addSql('ALTER TABLE `member` DROP event_id');
    }
}
