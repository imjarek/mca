<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211224155430 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE region_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE partner (uuid UUID NOT NULL, inn VARCHAR(14) NOT NULL, bik VARCHAR(10) NOT NULL, bank_account VARCHAR(20) NOT NULL, bank VARCHAR(200) NOT NULL, kpp VARCHAR(10) NOT NULL, legal_address VARCHAR(200) NOT NULL, actual_address VARCHAR(200) NOT NULL, phone VARCHAR(12) NOT NULL, region_code VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(uuid))');
        $this->addSql('COMMENT ON COLUMN partner.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN partner.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN partner.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE user_partner (partner_id UUID NOT NULL, user_id UUID NOT NULL, PRIMARY KEY(partner_id, user_id))');
        $this->addSql('CREATE INDEX IDX_6926201C9393F8FE ON user_partner (partner_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6926201CA76ED395 ON user_partner (user_id)');
        $this->addSql('COMMENT ON COLUMN user_partner.partner_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN user_partner.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE region (id INT NOT NULL, name VARCHAR(64) NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user" (uuid UUID NOT NULL, firstname VARCHAR(32) DEFAULT NULL, lastname VARCHAR(32) DEFAULT NULL, surname VARCHAR(32) DEFAULT NULL, email_verified_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('COMMENT ON COLUMN "user".uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "user".updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "user".email IS \'(DC2Type:email)\'');
        $this->addSql('COMMENT ON COLUMN "user".password IS \'(DC2Type:hashed_password)\'');
        $this->addSql('ALTER TABLE user_partner ADD CONSTRAINT FK_6926201C9393F8FE FOREIGN KEY (partner_id) REFERENCES partner (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_partner ADD CONSTRAINT FK_6926201CA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE user_partner DROP CONSTRAINT FK_6926201C9393F8FE');
        $this->addSql('ALTER TABLE user_partner DROP CONSTRAINT FK_6926201CA76ED395');
        $this->addSql('DROP SEQUENCE region_id_seq CASCADE');
        $this->addSql('DROP TABLE partner');
        $this->addSql('DROP TABLE user_partner');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE "user"');
    }
}
