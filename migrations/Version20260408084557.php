<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260408084557 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE box_types (id UUID NOT NULL, name VARCHAR(255) NOT NULL, calculation_method VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN box_types.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE customers (id UUID NOT NULL, name VARCHAR(255) NOT NULL, tax_code VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN customers.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE orders (id UUID NOT NULL, customer_id UUID DEFAULT NULL, manufacturing_number VARCHAR(255) NOT NULL, order_date DATE DEFAULT NULL, delivery_date DATE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E52FFDEE9395C3F3 ON orders (customer_id)');
        $this->addSql('COMMENT ON COLUMN orders.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN orders.customer_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN orders.order_date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN orders.delivery_date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('CREATE TABLE paper_types (id UUID NOT NULL, provider_id UUID DEFAULT NULL, name VARCHAR(255) NOT NULL, roll_size INT DEFAULT NULL, buster INT DEFAULT NULL, weight INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DA15E456A53A8AA ON paper_types (provider_id)');
        $this->addSql('COMMENT ON COLUMN paper_types.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN paper_types.provider_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE products (id UUID NOT NULL, order_id UUID DEFAULT NULL, box_type_id UUID DEFAULT NULL, name VARCHAR(255) NOT NULL, size VARCHAR(255) NOT NULL, quantity INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B3BA5A5A8D9F6D38 ON products (order_id)');
        $this->addSql('CREATE INDEX IDX_B3BA5A5ADC8E12A2 ON products (box_type_id)');
        $this->addSql('COMMENT ON COLUMN products.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN products.order_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN products.box_type_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE providers (id UUID NOT NULL, name VARCHAR(255) NOT NULL, tax_code VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN providers.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE quality_presets (id UUID NOT NULL, face_paper_id UUID DEFAULT NULL, base_papers_id UUID DEFAULT NULL, product_id UUID DEFAULT NULL, customer_id UUID DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B221FA73C2D185C4 ON quality_presets (face_paper_id)');
        $this->addSql('CREATE INDEX IDX_B221FA736E0E08EE ON quality_presets (base_papers_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B221FA734584665A ON quality_presets (product_id)');
        $this->addSql('CREATE INDEX IDX_B221FA739395C3F3 ON quality_presets (customer_id)');
        $this->addSql('COMMENT ON COLUMN quality_presets.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN quality_presets.face_paper_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN quality_presets.base_papers_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN quality_presets.product_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN quality_presets.customer_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE quality_preset_flute_paper (quality_preset_id UUID NOT NULL, paper_type_id UUID NOT NULL, PRIMARY KEY(quality_preset_id, paper_type_id))');
        $this->addSql('CREATE INDEX IDX_5AB1A713D7145BC2 ON quality_preset_flute_paper (quality_preset_id)');
        $this->addSql('CREATE INDEX IDX_5AB1A713C5E8DE72 ON quality_preset_flute_paper (paper_type_id)');
        $this->addSql('COMMENT ON COLUMN quality_preset_flute_paper.quality_preset_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN quality_preset_flute_paper.paper_type_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE quality_preset_assembly_paper (quality_preset_id UUID NOT NULL, paper_type_id UUID NOT NULL, PRIMARY KEY(quality_preset_id, paper_type_id))');
        $this->addSql('CREATE INDEX IDX_97542BEFD7145BC2 ON quality_preset_assembly_paper (quality_preset_id)');
        $this->addSql('CREATE INDEX IDX_97542BEFC5E8DE72 ON quality_preset_assembly_paper (paper_type_id)');
        $this->addSql('COMMENT ON COLUMN quality_preset_assembly_paper.quality_preset_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN quality_preset_assembly_paper.paper_type_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE releases (id UUID NOT NULL, product_id UUID DEFAULT NULL, release_date DATE DEFAULT NULL, quantity INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7896E4D14584665A ON releases (product_id)');
        $this->addSql('COMMENT ON COLUMN releases.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN releases.product_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN releases.release_date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 ON messenger_messages (queue_name, available_at, delivered_at, id)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE9395C3F3 FOREIGN KEY (customer_id) REFERENCES customers (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE paper_types ADD CONSTRAINT FK_DA15E456A53A8AA FOREIGN KEY (provider_id) REFERENCES providers (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A8D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5ADC8E12A2 FOREIGN KEY (box_type_id) REFERENCES box_types (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quality_presets ADD CONSTRAINT FK_B221FA73C2D185C4 FOREIGN KEY (face_paper_id) REFERENCES paper_types (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quality_presets ADD CONSTRAINT FK_B221FA736E0E08EE FOREIGN KEY (base_papers_id) REFERENCES paper_types (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quality_presets ADD CONSTRAINT FK_B221FA734584665A FOREIGN KEY (product_id) REFERENCES products (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quality_presets ADD CONSTRAINT FK_B221FA739395C3F3 FOREIGN KEY (customer_id) REFERENCES customers (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quality_preset_flute_paper ADD CONSTRAINT FK_5AB1A713D7145BC2 FOREIGN KEY (quality_preset_id) REFERENCES quality_presets (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quality_preset_flute_paper ADD CONSTRAINT FK_5AB1A713C5E8DE72 FOREIGN KEY (paper_type_id) REFERENCES paper_types (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quality_preset_assembly_paper ADD CONSTRAINT FK_97542BEFD7145BC2 FOREIGN KEY (quality_preset_id) REFERENCES quality_presets (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quality_preset_assembly_paper ADD CONSTRAINT FK_97542BEFC5E8DE72 FOREIGN KEY (paper_type_id) REFERENCES paper_types (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE releases ADD CONSTRAINT FK_7896E4D14584665A FOREIGN KEY (product_id) REFERENCES products (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA pgbouncer');
        $this->addSql('CREATE SCHEMA realtime');
        $this->addSql('CREATE SCHEMA extensions');
        $this->addSql('CREATE SCHEMA vault');
        $this->addSql('CREATE SCHEMA graphql_public');
        $this->addSql('CREATE SCHEMA graphql');
        $this->addSql('CREATE SCHEMA auth');
        $this->addSql('CREATE SCHEMA storage');
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT FK_E52FFDEE9395C3F3');
        $this->addSql('ALTER TABLE paper_types DROP CONSTRAINT FK_DA15E456A53A8AA');
        $this->addSql('ALTER TABLE products DROP CONSTRAINT FK_B3BA5A5A8D9F6D38');
        $this->addSql('ALTER TABLE products DROP CONSTRAINT FK_B3BA5A5ADC8E12A2');
        $this->addSql('ALTER TABLE quality_presets DROP CONSTRAINT FK_B221FA73C2D185C4');
        $this->addSql('ALTER TABLE quality_presets DROP CONSTRAINT FK_B221FA736E0E08EE');
        $this->addSql('ALTER TABLE quality_presets DROP CONSTRAINT FK_B221FA734584665A');
        $this->addSql('ALTER TABLE quality_presets DROP CONSTRAINT FK_B221FA739395C3F3');
        $this->addSql('ALTER TABLE quality_preset_flute_paper DROP CONSTRAINT FK_5AB1A713D7145BC2');
        $this->addSql('ALTER TABLE quality_preset_flute_paper DROP CONSTRAINT FK_5AB1A713C5E8DE72');
        $this->addSql('ALTER TABLE quality_preset_assembly_paper DROP CONSTRAINT FK_97542BEFD7145BC2');
        $this->addSql('ALTER TABLE quality_preset_assembly_paper DROP CONSTRAINT FK_97542BEFC5E8DE72');
        $this->addSql('ALTER TABLE releases DROP CONSTRAINT FK_7896E4D14584665A');
        $this->addSql('DROP TABLE box_types');
        $this->addSql('DROP TABLE customers');
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP TABLE paper_types');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE providers');
        $this->addSql('DROP TABLE quality_presets');
        $this->addSql('DROP TABLE quality_preset_flute_paper');
        $this->addSql('DROP TABLE quality_preset_assembly_paper');
        $this->addSql('DROP TABLE releases');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
