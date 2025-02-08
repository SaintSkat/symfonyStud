<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250208072847 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add relations: Project -> ProjectGroup';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project ADD group_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN project.group_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEFE54D947 FOREIGN KEY (group_id) REFERENCES project_group (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_2FB3D0EEFE54D947 ON project (group_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE project DROP CONSTRAINT FK_2FB3D0EEFE54D947');
        $this->addSql('DROP INDEX IDX_2FB3D0EEFE54D947');
        $this->addSql('ALTER TABLE project DROP group_id');
    }
}
