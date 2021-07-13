<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210713072945 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('DROP INDEX IDX_773DE69D7975B7E7');
        $this->addSql('DROP INDEX IDX_773DE69D44F5D008');
        $this->addSql('CREATE TEMPORARY TABLE __temp__car AS SELECT id, brand_id, model_id FROM car');
        $this->addSql('DROP TABLE car');
        $this->addSql('CREATE TABLE car (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, brand_id INTEGER NOT NULL, model_id INTEGER NOT NULL, CONSTRAINT FK_773DE69D44F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_773DE69D7975B7E7 FOREIGN KEY (model_id) REFERENCES model (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO car (id, brand_id, model_id) SELECT id, brand_id, model_id FROM __temp__car');
        $this->addSql('DROP TABLE __temp__car');
        $this->addSql('CREATE INDEX IDX_773DE69D7975B7E7 ON car (model_id)');
        $this->addSql('CREATE INDEX IDX_773DE69D44F5D008 ON car (brand_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP INDEX IDX_773DE69D44F5D008');
        $this->addSql('DROP INDEX IDX_773DE69D7975B7E7');
        $this->addSql('CREATE TEMPORARY TABLE __temp__car AS SELECT id, brand_id, model_id FROM car');
        $this->addSql('DROP TABLE car');
        $this->addSql('CREATE TABLE car (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, brand_id INTEGER NOT NULL, model_id INTEGER NOT NULL)');
        $this->addSql('INSERT INTO car (id, brand_id, model_id) SELECT id, brand_id, model_id FROM __temp__car');
        $this->addSql('DROP TABLE __temp__car');
        $this->addSql('CREATE INDEX IDX_773DE69D44F5D008 ON car (brand_id)');
        $this->addSql('CREATE INDEX IDX_773DE69D7975B7E7 ON car (model_id)');
    }
}
