<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240211041610 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comments (id INT AUTO_INCREMENT NOT NULL, user_comment_id INT DEFAULT NULL, event_comment_id INT DEFAULT NULL, description LONGTEXT NOT NULL, date_comment DATETIME NOT NULL, INDEX IDX_5F9E962A5F0EBBFF (user_comment_id), INDEX IDX_5F9E962AA087A43C (event_comment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A5F0EBBFF FOREIGN KEY (user_comment_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AA087A43C FOREIGN KEY (event_comment_id) REFERENCES evenement (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A5F0EBBFF');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AA087A43C');
        $this->addSql('DROP TABLE comments');
    }
}
