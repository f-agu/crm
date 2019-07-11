<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190711130437 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user CHANGE address address VARCHAR(512) DEFAULT NULL, CHANGE zipcode zipcode VARCHAR(32) DEFAULT NULL, CHANGE city city VARCHAR(255) DEFAULT NULL, CHANGE phone phone VARCHAR(32) DEFAULT NULL, CHANGE phone_emergency phone_emergency VARCHAR(32) DEFAULT NULL, CHANGE nationality nationality VARCHAR(64) DEFAULT NULL, CHANGE mails mails VARCHAR(512) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user CHANGE address address VARCHAR(512) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE zipcode zipcode VARCHAR(32) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE city city VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE phone phone VARCHAR(32) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE phone_emergency phone_emergency VARCHAR(32) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE nationality nationality VARCHAR(64) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE mails mails VARCHAR(512) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
