<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241127011606 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Criação inicial das entidades';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE drinks (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, volume_ml INT NOT NULL, weight_kg NUMERIC(10, 4) NOT NULL, stock INT NOT NULL, price NUMERIC(10, 2) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, type VARCHAR(255) NOT NULL, grape VARCHAR(35) DEFAULT NULL, country VARCHAR(50) DEFAULT NULL, alcohol_perc DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN drinks.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN drinks.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE saleitems (id SERIAL NOT NULL, sale_id INT NOT NULL, drink_id INT NOT NULL, price NUMERIC(10, 2) NOT NULL, quantity INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EDB34A7E4868 ON saleitems (sale_id)');
        $this->addSql('CREATE INDEX IDX_75EDB336AA4BB4 ON saleitems (drink_id)');
        $this->addSql('COMMENT ON COLUMN saleitems.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN saleitems.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE sales (id SERIAL NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN sales.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN sales.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE saleitems ADD CONSTRAINT FK_75EDB34A7E4868 FOREIGN KEY (sale_id) REFERENCES sales (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE saleitems ADD CONSTRAINT FK_75EDB336AA4BB4 FOREIGN KEY (drink_id) REFERENCES drinks (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE saleitems DROP CONSTRAINT FK_75EDB34A7E4868');
        $this->addSql('ALTER TABLE saleitems DROP CONSTRAINT FK_75EDB336AA4BB4');
        $this->addSql('DROP TABLE drinks');
        $this->addSql('DROP TABLE saleitems');
        $this->addSql('DROP TABLE sales');
    }
}
