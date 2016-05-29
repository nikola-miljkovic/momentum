<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160529190815 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE roles');
        $this->addSql('DROP INDEX IDX_8324EF374B89032C ON in_progress_post');
        $this->addSql('ALTER TABLE in_progress_post DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE in_progress_post CHANGE government_id government_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE in_progress_post ADD PRIMARY KEY (post_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE roles (id INT AUTO_INCREMENT NOT NULL, role VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, UNIQUE INDEX UNIQ_B63E2EC757698A6A (role), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE in_progress_post DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE in_progress_post CHANGE government_id government_id INT NOT NULL');
        $this->addSql('CREATE INDEX IDX_8324EF374B89032C ON in_progress_post (post_id)');
        $this->addSql('ALTER TABLE in_progress_post ADD PRIMARY KEY (post_id, government_id)');
    }
}
