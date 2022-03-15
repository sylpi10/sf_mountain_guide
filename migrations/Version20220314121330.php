<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220314121330 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment CHANGE is_moderated is_accepted TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE about CHANGE who_title who_title VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE who_text who_text LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE who_english_title who_english_title VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE who_english_text who_english_text LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE why_title why_title VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE why_text why_text LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE why_english_title why_english_title VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE why_english_text why_english_text LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE blog CHANGE title title VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE content content LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE image image VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE english_title english_title VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE english_content english_content LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE location location VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE comment CHANGE content content LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE is_accepted is_moderated TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE contact CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE message message LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE discipline CHANGE title title VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE content content LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE pers_number pers_number LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE duration duration VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE location location LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE price price LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE english_title english_title VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE english_content english_content LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE english_nb_pers english_nb_pers LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE english_duration english_duration VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE english_location english_location LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE english_price english_price VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE image image VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE image_detail image_detail VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE news_letter_subscriber CHANGE fullname fullname VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE username username VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE roles roles VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
