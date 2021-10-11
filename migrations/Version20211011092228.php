<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211011092228 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointment DROP archived');
        $this->addSql('ALTER TABLE quote DROP archived');
        $this->addSql('ALTER TABLE task DROP archived');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointment ADD archived TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE quote ADD archived TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE task ADD archived TINYINT(1) DEFAULT NULL');
    }
}
