<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260418194345 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE doctor_service (id INT AUTO_INCREMENT NOT NULL, notes JSON DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, service_id INT DEFAULT NULL, doctor_id INT DEFAULT NULL, patient_id INT DEFAULT NULL, INDEX IDX_7230F97FED5CA9E6 (service_id), INDEX IDX_7230F97F87F4FB17 (doctor_id), INDEX IDX_7230F97F6B899279 (patient_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE patient (id INT AUTO_INCREMENT NOT NULL, full_name VARCHAR(65) NOT NULL, date_admitted DATETIME NOT NULL, is_male TINYINT NOT NULL, blood_group VARCHAR(5) DEFAULT NULL, date_of_birth DATE DEFAULT NULL, address LONGTEXT DEFAULT NULL, email VARCHAR(50) DEFAULT NULL, phonenumber VARCHAR(25) DEFAULT NULL, patient_number VARCHAR(20) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE permission (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, type VARCHAR(20) DEFAULT NULL, module VARCHAR(50) NOT NULL, action VARCHAR(50) NOT NULL, is_active TINYINT NOT NULL, is_deleted TINYINT NOT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE permission_audit (id INT AUTO_INCREMENT NOT NULL, permission_granted INT DEFAULT NULL, permission_denied INT DEFAULT NULL, action_by INT DEFAULT NULL, target_user INT NOT NULL, INDEX IDX_449F920ADC80C46A (permission_granted), INDEX IDX_449F920A7405F905 (permission_denied), INDEX IDX_449F920A1DC04527 (action_by), INDEX IDX_449F920A408BC0F8 (target_user), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE person (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(25) DEFAULT NULL, first_name VARCHAR(15) NOT NULL, middle_name VARCHAR(15) DEFAULT NULL, last_name VARCHAR(15) NOT NULL, phonenumber VARCHAR(20) DEFAULT NULL, age INT DEFAULT NULL, gender VARCHAR(8) DEFAULT NULL, national_id INT DEFAULT NULL, nssf_number INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE refresh_tokens (refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid DATETIME NOT NULL, id INT AUTO_INCREMENT NOT NULL, UNIQUE INDEX UNIQ_9BACE7E1C74F2195 (refresh_token), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, description LONGTEXT DEFAULT NULL, color VARCHAR(10) DEFAULT NULL, icon VARCHAR(100) DEFAULT NULL, role_name_alias VARCHAR(25) NOT NULL, is_active TINYINT NOT NULL, is_deleted TINYINT NOT NULL, icon_thumb VARCHAR(100) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE role_permission (id INT AUTO_INCREMENT NOT NULL, is_active TINYINT DEFAULT 0 NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, role_id INT DEFAULT NULL, permission_id INT DEFAULT NULL, granted_by INT DEFAULT NULL, removed_by INT DEFAULT NULL, INDEX IDX_6F7DF886D60322AC (role_id), INDEX IDX_6F7DF886FED90CCA (permission_id), INDEX IDX_6F7DF886A5FB753F (granted_by), INDEX IDX_6F7DF88610CDAFDB (removed_by), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, code VARCHAR(50) NOT NULL, description LONGTEXT DEFAULT NULL, price INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(50) DEFAULT NULL, google_id VARCHAR(255) DEFAULT NULL, google_access_token TINYTEXT DEFAULT NULL, google_profile_pic_url VARCHAR(255) DEFAULT NULL, profile_pic_thumb VARCHAR(100) DEFAULT NULL, profile_pic_original VARCHAR(100) DEFAULT NULL, phonenumber VARCHAR(20) DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, person_id INT NOT NULL, UNIQUE INDEX UNIQ_8D93D649217BBB47 (person_id), INDEX IDX_8D93D649217BBB47 (person_id), UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE user_permission_override (id INT AUTO_INCREMENT NOT NULL, is_denied TINYINT NOT NULL, is_active TINYINT DEFAULT 0 NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, user_id INT DEFAULT NULL, permission_id INT DEFAULT NULL, granted_by INT DEFAULT NULL, denied_by INT DEFAULT NULL, INDEX IDX_5DF8BC21A76ED395 (user_id), INDEX IDX_5DF8BC21FED90CCA (permission_id), INDEX IDX_5DF8BC21A5FB753F (granted_by), INDEX IDX_5DF8BC21779B32AA (denied_by), INDEX idx_user_id (user_id, permission_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE user_role (id INT AUTO_INCREMENT NOT NULL, is_active TINYINT DEFAULT 0 NOT NULL, is_primary TINYINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, user_id INT DEFAULT NULL, role_id INT DEFAULT NULL, granted_by INT DEFAULT NULL, revoked_by INT DEFAULT NULL, INDEX IDX_2DE8C6A3A76ED395 (user_id), INDEX IDX_2DE8C6A3D60322AC (role_id), INDEX IDX_2DE8C6A3A5FB753F (granted_by), INDEX IDX_2DE8C6A38E5493E3 (revoked_by), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE doctor_service ADD CONSTRAINT FK_7230F97FED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE doctor_service ADD CONSTRAINT FK_7230F97F87F4FB17 FOREIGN KEY (doctor_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE doctor_service ADD CONSTRAINT FK_7230F97F6B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE permission_audit ADD CONSTRAINT FK_449F920ADC80C46A FOREIGN KEY (permission_granted) REFERENCES permission (id)');
        $this->addSql('ALTER TABLE permission_audit ADD CONSTRAINT FK_449F920A7405F905 FOREIGN KEY (permission_denied) REFERENCES permission (id)');
        $this->addSql('ALTER TABLE permission_audit ADD CONSTRAINT FK_449F920A1DC04527 FOREIGN KEY (action_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE permission_audit ADD CONSTRAINT FK_449F920A408BC0F8 FOREIGN KEY (target_user) REFERENCES user (id)');
        $this->addSql('ALTER TABLE role_permission ADD CONSTRAINT FK_6F7DF886D60322AC FOREIGN KEY (role_id) REFERENCES role (id)');
        $this->addSql('ALTER TABLE role_permission ADD CONSTRAINT FK_6F7DF886FED90CCA FOREIGN KEY (permission_id) REFERENCES permission (id)');
        $this->addSql('ALTER TABLE role_permission ADD CONSTRAINT FK_6F7DF886A5FB753F FOREIGN KEY (granted_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE role_permission ADD CONSTRAINT FK_6F7DF88610CDAFDB FOREIGN KEY (removed_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE user_permission_override ADD CONSTRAINT FK_5DF8BC21A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_permission_override ADD CONSTRAINT FK_5DF8BC21FED90CCA FOREIGN KEY (permission_id) REFERENCES permission (id)');
        $this->addSql('ALTER TABLE user_permission_override ADD CONSTRAINT FK_5DF8BC21A5FB753F FOREIGN KEY (granted_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_permission_override ADD CONSTRAINT FK_5DF8BC21779B32AA FOREIGN KEY (denied_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_role ADD CONSTRAINT FK_2DE8C6A3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_role ADD CONSTRAINT FK_2DE8C6A3D60322AC FOREIGN KEY (role_id) REFERENCES role (id)');
        $this->addSql('ALTER TABLE user_role ADD CONSTRAINT FK_2DE8C6A3A5FB753F FOREIGN KEY (granted_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_role ADD CONSTRAINT FK_2DE8C6A38E5493E3 FOREIGN KEY (revoked_by) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE doctor_service DROP FOREIGN KEY FK_7230F97FED5CA9E6');
        $this->addSql('ALTER TABLE doctor_service DROP FOREIGN KEY FK_7230F97F87F4FB17');
        $this->addSql('ALTER TABLE doctor_service DROP FOREIGN KEY FK_7230F97F6B899279');
        $this->addSql('ALTER TABLE permission_audit DROP FOREIGN KEY FK_449F920ADC80C46A');
        $this->addSql('ALTER TABLE permission_audit DROP FOREIGN KEY FK_449F920A7405F905');
        $this->addSql('ALTER TABLE permission_audit DROP FOREIGN KEY FK_449F920A1DC04527');
        $this->addSql('ALTER TABLE permission_audit DROP FOREIGN KEY FK_449F920A408BC0F8');
        $this->addSql('ALTER TABLE role_permission DROP FOREIGN KEY FK_6F7DF886D60322AC');
        $this->addSql('ALTER TABLE role_permission DROP FOREIGN KEY FK_6F7DF886FED90CCA');
        $this->addSql('ALTER TABLE role_permission DROP FOREIGN KEY FK_6F7DF886A5FB753F');
        $this->addSql('ALTER TABLE role_permission DROP FOREIGN KEY FK_6F7DF88610CDAFDB');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649217BBB47');
        $this->addSql('ALTER TABLE user_permission_override DROP FOREIGN KEY FK_5DF8BC21A76ED395');
        $this->addSql('ALTER TABLE user_permission_override DROP FOREIGN KEY FK_5DF8BC21FED90CCA');
        $this->addSql('ALTER TABLE user_permission_override DROP FOREIGN KEY FK_5DF8BC21A5FB753F');
        $this->addSql('ALTER TABLE user_permission_override DROP FOREIGN KEY FK_5DF8BC21779B32AA');
        $this->addSql('ALTER TABLE user_role DROP FOREIGN KEY FK_2DE8C6A3A76ED395');
        $this->addSql('ALTER TABLE user_role DROP FOREIGN KEY FK_2DE8C6A3D60322AC');
        $this->addSql('ALTER TABLE user_role DROP FOREIGN KEY FK_2DE8C6A3A5FB753F');
        $this->addSql('ALTER TABLE user_role DROP FOREIGN KEY FK_2DE8C6A38E5493E3');
        $this->addSql('DROP TABLE doctor_service');
        $this->addSql('DROP TABLE patient');
        $this->addSql('DROP TABLE permission');
        $this->addSql('DROP TABLE permission_audit');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE refresh_tokens');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE role_permission');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_permission_override');
        $this->addSql('DROP TABLE user_role');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
