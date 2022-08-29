<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220815152910 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE conversations (id INT AUTO_INCREMENT NOT NULL, participant1_id INT NOT NULL, participant2_id INT NOT NULL, INDEX IDX_C2521BF1B29A9963 (participant1_id), INDEX IDX_C2521BF1A02F368D (participant2_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messages (id INT AUTO_INCREMENT NOT NULL, expediteur_id INT NOT NULL, conversation_id INT NOT NULL, date DATETIME NOT NULL, message LONGTEXT NOT NULL, INDEX IDX_DB021E9610335F61 (expediteur_id), INDEX IDX_DB021E969AC0396 (conversation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE conversations ADD CONSTRAINT FK_C2521BF1B29A9963 FOREIGN KEY (participant1_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE conversations ADD CONSTRAINT FK_C2521BF1A02F368D FOREIGN KEY (participant2_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E9610335F61 FOREIGN KEY (expediteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E969AC0396 FOREIGN KEY (conversation_id) REFERENCES conversations (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E969AC0396');
        $this->addSql('DROP TABLE conversations');
        $this->addSql('DROP TABLE messages');
    }
}
