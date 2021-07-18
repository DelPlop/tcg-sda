<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210718191125 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE tcg_user_owned_card (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, card_id INT NOT NULL, number INT NOT NULL, language VARCHAR(255) DEFAULT NULL, is_for_trade TINYINT(1) NOT NULL, INDEX IDX_76AE2B5CA76ED395 (user_id), INDEX IDX_76AE2B5C4ACC9A20 (card_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tcg_user_wanted_card (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, card_id INT NOT NULL, number INT NOT NULL, language VARCHAR(255) DEFAULT NULL, INDEX IDX_7008D584A76ED395 (user_id), INDEX IDX_7008D5844ACC9A20 (card_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tcg_user_owned_card ADD CONSTRAINT FK_76AE2B5CA76ED395 FOREIGN KEY (user_id) REFERENCES tcg_user (id)');
        $this->addSql('ALTER TABLE tcg_user_owned_card ADD CONSTRAINT FK_76AE2B5C4ACC9A20 FOREIGN KEY (card_id) REFERENCES tcg_card (id)');
        $this->addSql('ALTER TABLE tcg_user_wanted_card ADD CONSTRAINT FK_7008D584A76ED395 FOREIGN KEY (user_id) REFERENCES tcg_user (id)');
        $this->addSql('ALTER TABLE tcg_user_wanted_card ADD CONSTRAINT FK_7008D5844ACC9A20 FOREIGN KEY (card_id) REFERENCES tcg_card (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE tcg_user_owned_card');
        $this->addSql('DROP TABLE tcg_user_wanted_card');
    }
}
