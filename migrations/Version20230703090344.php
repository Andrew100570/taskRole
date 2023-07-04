<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230703090344 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE data DROP FOREIGN KEY FK_ADF3F3639D86650F');
        $this->addSql('DROP INDEX IDX_ADF3F3639D86650F ON data');
        $this->addSql('ALTER TABLE data CHANGE user_id_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE data ADD CONSTRAINT FK_ADF3F363A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_ADF3F363A76ED395 ON data (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE data DROP FOREIGN KEY FK_ADF3F363A76ED395');
        $this->addSql('DROP INDEX IDX_ADF3F363A76ED395 ON data');
        $this->addSql('ALTER TABLE data CHANGE user_id user_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE data ADD CONSTRAINT FK_ADF3F3639D86650F FOREIGN KEY (user_id_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_ADF3F3639D86650F ON data (user_id_id)');
    }
}
