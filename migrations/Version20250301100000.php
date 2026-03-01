<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250301100000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add schema: provider, customer, box_type, paper_type, quality_preset, order, product, releases';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE provider (id VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(100) NOT NULL, tax_code VARCHAR(50) NOT NULL, phone VARCHAR(50) NOT NULL, email VARCHAR(255) NOT NULL, address LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer (id VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(100) NOT NULL, tax_code VARCHAR(50) NOT NULL, phone VARCHAR(50) NOT NULL, email VARCHAR(255) NOT NULL, address LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE box_type (id VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, number_of_layer INT NOT NULL, type VARCHAR(100) NOT NULL, calculation VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paper_type (id VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, roll_size DOUBLE PRECISION NOT NULL, buster DOUBLE PRECISION NOT NULL, provider_id VARCHAR(36) NOT NULL, INDEX IDX_4A0A9D1FA53A8AA (provider_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quality_preset (id VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, customer_id VARCHAR(36) NOT NULL, face_paper_id VARCHAR(36) NOT NULL, base_paper_id VARCHAR(36) NOT NULL, flute_id VARCHAR(36) NOT NULL, assembly_id VARCHAR(36) NOT NULL, INDEX IDX_QUALITY_PRESET_CUSTOMER (customer_id), INDEX IDX_QUALITY_PRESET_FACE (face_paper_id), INDEX IDX_QUALITY_PRESET_BASE (base_paper_id), INDEX IDX_QUALITY_PRESET_FLUTE (flute_id), INDEX IDX_QUALITY_PRESET_ASSEMBLY (assembly_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id VARCHAR(36) NOT NULL, order_number VARCHAR(100) NOT NULL, order_date DATE NOT NULL, delivery_date DATE NOT NULL, history_of_releasing JSON NOT NULL, customer_id VARCHAR(36) NOT NULL, INDEX IDX_E52FFDEE9395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(100) NOT NULL, size DOUBLE PRECISION NOT NULL, order_id VARCHAR(36) NOT NULL, box_type_id VARCHAR(36) NOT NULL, preset_id VARCHAR(36) NOT NULL, INDEX IDX_D34A04AD8D9F6D38 (order_id), INDEX IDX_D34A04ADD2F968B (box_type_id), INDEX IDX_D34A04AD985857F6 (preset_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE releases (id INT AUTO_INCREMENT NOT NULL, release_date DATE NOT NULL, quantity INT NOT NULL, product_id INT NOT NULL, INDEX IDX_RELEASES_PRODUCT (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE paper_type ADD CONSTRAINT FK_4A0A9D1FA53A8AA FOREIGN KEY (provider_id) REFERENCES provider (id)');
        $this->addSql('ALTER TABLE quality_preset ADD CONSTRAINT FK_QUALITY_PRESET_CUSTOMER FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE quality_preset ADD CONSTRAINT FK_QUALITY_PRESET_FACE FOREIGN KEY (face_paper_id) REFERENCES paper_type (id)');
        $this->addSql('ALTER TABLE quality_preset ADD CONSTRAINT FK_QUALITY_PRESET_BASE FOREIGN KEY (base_paper_id) REFERENCES paper_type (id)');
        $this->addSql('ALTER TABLE quality_preset ADD CONSTRAINT FK_QUALITY_PRESET_FLUTE FOREIGN KEY (flute_id) REFERENCES paper_type (id)');
        $this->addSql('ALTER TABLE quality_preset ADD CONSTRAINT FK_QUALITY_PRESET_ASSEMBLY FOREIGN KEY (assembly_id) REFERENCES paper_type (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_E52FFDEE9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD8D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADD2F968B FOREIGN KEY (box_type_id) REFERENCES box_type (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD985857F6 FOREIGN KEY (preset_id) REFERENCES quality_preset (id)');
        $this->addSql('ALTER TABLE releases ADD CONSTRAINT FK_RELEASES_PRODUCT FOREIGN KEY (product_id) REFERENCES product (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE paper_type DROP FOREIGN KEY FK_4A0A9D1FA53A8AA');
        $this->addSql('ALTER TABLE quality_preset DROP FOREIGN KEY FK_QUALITY_PRESET_CUSTOMER');
        $this->addSql('ALTER TABLE quality_preset DROP FOREIGN KEY FK_QUALITY_PRESET_FACE');
        $this->addSql('ALTER TABLE quality_preset DROP FOREIGN KEY FK_QUALITY_PRESET_BASE');
        $this->addSql('ALTER TABLE quality_preset DROP FOREIGN KEY FK_QUALITY_PRESET_FLUTE');
        $this->addSql('ALTER TABLE quality_preset DROP FOREIGN KEY FK_QUALITY_PRESET_ASSEMBLY');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_E52FFDEE9395C3F3');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD8D9F6D38');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADD2F968B');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD985857F6');
        $this->addSql('ALTER TABLE releases DROP FOREIGN KEY FK_RELEASES_PRODUCT');
        $this->addSql('DROP TABLE provider');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE box_type');
        $this->addSql('DROP TABLE paper_type');
        $this->addSql('DROP TABLE quality_preset');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE releases');
    }
}
