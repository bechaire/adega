<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241128232818 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sales ADD customer_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE sales ADD date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE sales ADD distance INT NOT NULL');
        $this->addSql('ALTER TABLE sales ADD total_weight NUMERIC(10, 4) NOT NULL');
        $this->addSql('ALTER TABLE sales ADD items_price NUMERIC(10, 2) NOT NULL');
        $this->addSql('ALTER TABLE sales ADD shipping_price NUMERIC(10, 2) NOT NULL');
        $this->addSql('ALTER TABLE sales ADD order_total NUMERIC(10, 2) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE sales DROP customer_id');
        $this->addSql('ALTER TABLE sales DROP date');
        $this->addSql('ALTER TABLE sales DROP distance');
        $this->addSql('ALTER TABLE sales DROP total_weight');
        $this->addSql('ALTER TABLE sales DROP items_price');
        $this->addSql('ALTER TABLE sales DROP shipping_price');
        $this->addSql('ALTER TABLE sales DROP order_total');
    }
}
