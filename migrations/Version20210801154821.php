<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210801154821 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE tcg_card (id INT AUTO_INCREMENT NOT NULL, edition_id INT NOT NULL, culture_id INT DEFAULT NULL, rarity_id VARCHAR(1) NOT NULL, type_id INT NOT NULL, subtype_id INT DEFAULT NULL, signet_id INT DEFAULT NULL, code VARCHAR(6) NOT NULL, name VARCHAR(255) NOT NULL, cost INT DEFAULT NULL, strength INT DEFAULT NULL, strength_modifier VARCHAR(255) DEFAULT NULL, vitality INT DEFAULT NULL, vitality_modifier VARCHAR(255) DEFAULT NULL, resistance INT DEFAULT NULL, resistance_modifier VARCHAR(255) DEFAULT NULL, site_number INT DEFAULT NULL, shadow_number INT DEFAULT NULL, is_unique TINYINT(1) NOT NULL, is_ring_bearer TINYINT(1) NOT NULL, is_authorized TINYINT(1) NOT NULL, text LONGTEXT DEFAULT NULL, quote VARCHAR(255) DEFAULT NULL, is_tengwar TINYINT(1) NOT NULL, is_rf TINYINT(1) NOT NULL, is_rfa TINYINT(1) NOT NULL, has_local_image TINYINT(1) NOT NULL, position INT NOT NULL, is_displayable TINYINT(1) NOT NULL, INDEX IDX_6F84364C74281A5E (edition_id), INDEX IDX_6F84364CB108249D (culture_id), INDEX IDX_6F84364CF3747573 (rarity_id), INDEX IDX_6F84364CC54C8C93 (type_id), INDEX IDX_6F84364C8E2E245C (subtype_id), INDEX IDX_6F84364CB0E39FBB (signet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tcg_card_phase (card_id INT NOT NULL, phase_id INT NOT NULL, INDEX IDX_4CCE10174ACC9A20 (card_id), INDEX IDX_4CCE101799091188 (phase_id), PRIMARY KEY(card_id, phase_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tcg_card_tag (card_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_3D24F1584ACC9A20 (card_id), INDEX IDX_3D24F158BAD26311 (tag_id), PRIMARY KEY(card_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tcg_multi_card (card_source INT NOT NULL, card_target INT NOT NULL, INDEX IDX_856B56BD2DB52C07 (card_source), INDEX IDX_856B56BD34507C88 (card_target), PRIMARY KEY(card_source, card_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tcg_culture (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, position INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tcg_edition (id INT AUTO_INCREMENT NOT NULL, edition_number INT NOT NULL, name VARCHAR(255) NOT NULL, is_special TINYINT(1) NOT NULL, is_displayable TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tcg_ext_translations (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(191) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX translations_lookup_idx (locale, object_class, foreign_key), UNIQUE INDEX lookup_unique_idx (locale, object_class, field, foreign_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB ROW_FORMAT = DYNAMIC');
        $this->addSql('CREATE TABLE tcg_phase (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tcg_rarity (id VARCHAR(1) NOT NULL, name VARCHAR(255) NOT NULL, position INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tcg_reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_6E867556A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tcg_signet (id INT AUTO_INCREMENT NOT NULL, character_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tcg_subtype (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tcg_tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tcg_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, position INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tcg_user (id INT AUTO_INCREMENT NOT NULL, anonymized_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', login VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_F40378D6AA08CB10 (login), UNIQUE INDEX UNIQ_F40378D6E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tcg_user_owned_card (user_id INT NOT NULL, card_id INT NOT NULL, INDEX IDX_76AE2B5CA76ED395 (user_id), INDEX IDX_76AE2B5C4ACC9A20 (card_id), PRIMARY KEY(user_id, card_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tcg_user_wanted_card (user_id INT NOT NULL, card_id INT NOT NULL, INDEX IDX_7008D584A76ED395 (user_id), INDEX IDX_7008D5844ACC9A20 (card_id), PRIMARY KEY(user_id, card_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tcg_card ADD CONSTRAINT FK_6F84364C74281A5E FOREIGN KEY (edition_id) REFERENCES tcg_edition (id)');
        $this->addSql('ALTER TABLE tcg_card ADD CONSTRAINT FK_6F84364CB108249D FOREIGN KEY (culture_id) REFERENCES tcg_culture (id)');
        $this->addSql('ALTER TABLE tcg_card ADD CONSTRAINT FK_6F84364CF3747573 FOREIGN KEY (rarity_id) REFERENCES tcg_rarity (id)');
        $this->addSql('ALTER TABLE tcg_card ADD CONSTRAINT FK_6F84364CC54C8C93 FOREIGN KEY (type_id) REFERENCES tcg_type (id)');
        $this->addSql('ALTER TABLE tcg_card ADD CONSTRAINT FK_6F84364C8E2E245C FOREIGN KEY (subtype_id) REFERENCES tcg_subtype (id)');
        $this->addSql('ALTER TABLE tcg_card ADD CONSTRAINT FK_6F84364CB0E39FBB FOREIGN KEY (signet_id) REFERENCES tcg_signet (id)');
        $this->addSql('ALTER TABLE tcg_card_phase ADD CONSTRAINT FK_4CCE10174ACC9A20 FOREIGN KEY (card_id) REFERENCES tcg_card (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tcg_card_phase ADD CONSTRAINT FK_4CCE101799091188 FOREIGN KEY (phase_id) REFERENCES tcg_phase (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tcg_card_tag ADD CONSTRAINT FK_3D24F1584ACC9A20 FOREIGN KEY (card_id) REFERENCES tcg_card (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tcg_card_tag ADD CONSTRAINT FK_3D24F158BAD26311 FOREIGN KEY (tag_id) REFERENCES tcg_tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tcg_multi_card ADD CONSTRAINT FK_856B56BD2DB52C07 FOREIGN KEY (card_source) REFERENCES tcg_card (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tcg_multi_card ADD CONSTRAINT FK_856B56BD34507C88 FOREIGN KEY (card_target) REFERENCES tcg_card (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tcg_reset_password_request ADD CONSTRAINT FK_6E867556A76ED395 FOREIGN KEY (user_id) REFERENCES tcg_user (id)');
        $this->addSql('ALTER TABLE tcg_user_owned_card ADD CONSTRAINT FK_76AE2B5CA76ED395 FOREIGN KEY (user_id) REFERENCES tcg_user (id)');
        $this->addSql('ALTER TABLE tcg_user_owned_card ADD CONSTRAINT FK_76AE2B5C4ACC9A20 FOREIGN KEY (card_id) REFERENCES tcg_card (id)');
        $this->addSql('ALTER TABLE tcg_user_wanted_card ADD CONSTRAINT FK_7008D584A76ED395 FOREIGN KEY (user_id) REFERENCES tcg_user (id)');
        $this->addSql('ALTER TABLE tcg_user_wanted_card ADD CONSTRAINT FK_7008D5844ACC9A20 FOREIGN KEY (card_id) REFERENCES tcg_card (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE tcg_card_phase DROP FOREIGN KEY FK_4CCE10174ACC9A20');
        $this->addSql('ALTER TABLE tcg_card_tag DROP FOREIGN KEY FK_3D24F1584ACC9A20');
        $this->addSql('ALTER TABLE tcg_multi_card DROP FOREIGN KEY FK_856B56BD2DB52C07');
        $this->addSql('ALTER TABLE tcg_multi_card DROP FOREIGN KEY FK_856B56BD34507C88');
        $this->addSql('ALTER TABLE tcg_user_owned_card DROP FOREIGN KEY FK_76AE2B5C4ACC9A20');
        $this->addSql('ALTER TABLE tcg_user_wanted_card DROP FOREIGN KEY FK_7008D5844ACC9A20');
        $this->addSql('ALTER TABLE tcg_card DROP FOREIGN KEY FK_6F84364CB108249D');
        $this->addSql('ALTER TABLE tcg_card DROP FOREIGN KEY FK_6F84364C74281A5E');
        $this->addSql('ALTER TABLE tcg_card_phase DROP FOREIGN KEY FK_4CCE101799091188');
        $this->addSql('ALTER TABLE tcg_card DROP FOREIGN KEY FK_6F84364CF3747573');
        $this->addSql('ALTER TABLE tcg_card DROP FOREIGN KEY FK_6F84364CB0E39FBB');
        $this->addSql('ALTER TABLE tcg_card DROP FOREIGN KEY FK_6F84364C8E2E245C');
        $this->addSql('ALTER TABLE tcg_card_tag DROP FOREIGN KEY FK_3D24F158BAD26311');
        $this->addSql('ALTER TABLE tcg_card DROP FOREIGN KEY FK_6F84364CC54C8C93');
        $this->addSql('ALTER TABLE tcg_reset_password_request DROP FOREIGN KEY FK_6E867556A76ED395');
        $this->addSql('ALTER TABLE tcg_user_owned_card DROP FOREIGN KEY FK_76AE2B5CA76ED395');
        $this->addSql('ALTER TABLE tcg_user_wanted_card DROP FOREIGN KEY FK_7008D584A76ED395');
        $this->addSql('DROP TABLE tcg_card');
        $this->addSql('DROP TABLE tcg_card_phase');
        $this->addSql('DROP TABLE tcg_card_tag');
        $this->addSql('DROP TABLE tcg_multi_card');
        $this->addSql('DROP TABLE tcg_culture');
        $this->addSql('DROP TABLE tcg_edition');
        $this->addSql('DROP TABLE tcg_ext_translations');
        $this->addSql('DROP TABLE tcg_phase');
        $this->addSql('DROP TABLE tcg_rarity');
        $this->addSql('DROP TABLE tcg_reset_password_request');
        $this->addSql('DROP TABLE tcg_signet');
        $this->addSql('DROP TABLE tcg_subtype');
        $this->addSql('DROP TABLE tcg_tag');
        $this->addSql('DROP TABLE tcg_type');
        $this->addSql('DROP TABLE tcg_user');
        $this->addSql('DROP TABLE tcg_user_owned_card');
        $this->addSql('DROP TABLE tcg_user_wanted_card');
    }
}
