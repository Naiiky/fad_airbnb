<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260825140904 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE age_verification_status (label VARCHAR(100) NOT NULL, id CHAR(36) NOT NULL, UNIQUE INDEX UNIQ_44A2C644EA750E8 (label), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE booking (check_in DATE NOT NULL, check_out DATE NOT NULL, adult_count INT NOT NULL, children_count INT NOT NULL, night_subtotal INT NOT NULL, cleaning_fee INT NOT NULL, deposit INT NOT NULL, total_amount INT NOT NULL, cancellation_reason LONGTEXT DEFAULT NULL, cancellation_date DATETIME DEFAULT NULL, id CHAR(36) NOT NULL, status_id CHAR(36) NOT NULL, property_id CHAR(36) NOT NULL, user_id CHAR(36) NOT NULL, INDEX IDX_E00CEDDE6BF700BD (status_id), INDEX IDX_E00CEDDE549213EC (property_id), INDEX IDX_E00CEDDEA76ED395 (user_id), INDEX idx_booking_property_status_dates (property_id, status_id, check_in, check_out), INDEX idx_booking_user_status (user_id, status_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE booking_status (label VARCHAR(100) NOT NULL, id CHAR(36) NOT NULL, UNIQUE INDEX UNIQ_C09A5EE2EA750E8 (label), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE conversation (created_at DATETIME NOT NULL, last_message_at DATETIME DEFAULT NULL, id CHAR(36) NOT NULL, user_id CHAR(36) NOT NULL, property_id CHAR(36) NOT NULL, INDEX IDX_8A8E26E9A76ED395 (user_id), INDEX IDX_8A8E26E9549213EC (property_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE country (label VARCHAR(100) NOT NULL, id CHAR(36) NOT NULL, UNIQUE INDEX UNIQ_5373C966EA750E8 (label), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE equipment (label VARCHAR(100) NOT NULL, id CHAR(36) NOT NULL, UNIQUE INDEX UNIQ_D338D583EA750E8 (label), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE favorite_property (user_id CHAR(36) NOT NULL, property_id CHAR(36) NOT NULL, INDEX IDX_21A5042FA76ED395 (user_id), INDEX IDX_21A5042F549213EC (property_id), PRIMARY KEY (user_id, property_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE language (label VARCHAR(100) NOT NULL, id CHAR(36) NOT NULL, UNIQUE INDEX UNIQ_D4DB71B5EA750E8 (label), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE message (content LONGTEXT NOT NULL, read_at DATETIME DEFAULT NULL, id CHAR(36) NOT NULL, conversation_id CHAR(36) NOT NULL, sender_id CHAR(36) NOT NULL, INDEX IDX_B6BD307F9AC0396 (conversation_id), INDEX IDX_B6BD307FF624B39D (sender_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE price (day DATE NOT NULL, price_night INT NOT NULL, is_block TINYINT NOT NULL, id CHAR(36) NOT NULL, property_id CHAR(36) NOT NULL, INDEX IDX_CAC822D9549213EC (property_id), UNIQUE INDEX uniq_price_property_day (property_id, day), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE property (title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, max_guest INT NOT NULL, bedrooms INT NOT NULL, bathrooms INT NOT NULL, beds INT NOT NULL, area_m2 INT NOT NULL, address VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, zip_code VARCHAR(20) NOT NULL, deposit INT NOT NULL, cleaning_fee INT NOT NULL, review_count INT NOT NULL, average_rating DOUBLE PRECISION NOT NULL, nightly_price INT NOT NULL, published_at DATETIME DEFAULT NULL, weekend_price INT NOT NULL, pets_allowed TINYINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, id CHAR(36) NOT NULL, user_id CHAR(36) NOT NULL, country_id CHAR(36) NOT NULL, category_id CHAR(36) NOT NULL, status_id CHAR(36) NOT NULL, INDEX IDX_8BF21CDEA76ED395 (user_id), INDEX IDX_8BF21CDEF92F3E70 (country_id), INDEX IDX_8BF21CDE12469DE2 (category_id), INDEX IDX_8BF21CDE6BF700BD (status_id), INDEX idx_property_status_city (status_id, city), INDEX idx_property_user_status (user_id, status_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE property_category (label VARCHAR(100) NOT NULL, id CHAR(36) NOT NULL, UNIQUE INDEX UNIQ_58CB2D85EA750E8 (label), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE property_equipment (property_id CHAR(36) NOT NULL, equipment_id CHAR(36) NOT NULL, INDEX IDX_A2D7D73E549213EC (property_id), INDEX IDX_A2D7D73E517FE9FE (equipment_id), PRIMARY KEY (property_id, equipment_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE property_image (image VARCHAR(255) NOT NULL, display_order INT NOT NULL, is_main TINYINT NOT NULL, id CHAR(36) NOT NULL, property_id CHAR(36) NOT NULL, INDEX IDX_32EC552549213EC (property_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE property_status (label VARCHAR(100) NOT NULL, id CHAR(36) NOT NULL, UNIQUE INDEX UNIQ_5770A60EA750E8 (label), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE review (rating INT NOT NULL, comment LONGTEXT NOT NULL, host_reply LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, host_reply_date DATETIME DEFAULT NULL, is_display TINYINT NOT NULL, id CHAR(36) NOT NULL, booking_id CHAR(36) NOT NULL, UNIQUE INDEX UNIQ_794381C63301C60 (booking_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE `user` (email VARCHAR(150) NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, phone VARCHAR(255) DEFAULT NULL, avatar VARCHAR(255) DEFAULT NULL, bio LONGTEXT DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, zip_code VARCHAR(20) DEFAULT NULL, birth_date DATE DEFAULT NULL, email_verified TINYINT NOT NULL, term_accepted_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, roles JSON NOT NULL, id CHAR(36) NOT NULL, status_id CHAR(36) NOT NULL, age_verification_status_id CHAR(36) NOT NULL, country_id CHAR(36) NOT NULL, INDEX IDX_8D93D6496BF700BD (status_id), INDEX IDX_8D93D649B4AA5264 (age_verification_status_id), INDEX IDX_8D93D649F92F3E70 (country_id), UNIQUE INDEX uniq_user_email (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user_language (user_id CHAR(36) NOT NULL, language_id CHAR(36) NOT NULL, INDEX IDX_345695B5A76ED395 (user_id), INDEX IDX_345695B582F1BAF4 (language_id), PRIMARY KEY (user_id, language_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user_status (label VARCHAR(100) NOT NULL, id CHAR(36) NOT NULL, UNIQUE INDEX UNIQ_1E527E21EA750E8 (label), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE6BF700BD FOREIGN KEY (status_id) REFERENCES booking_status (id) ON DELETE RESTRICT');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE549213EC FOREIGN KEY (property_id) REFERENCES property (id) ON DELETE RESTRICT');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE RESTRICT');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E9A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE RESTRICT');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E9549213EC FOREIGN KEY (property_id) REFERENCES property (id) ON DELETE RESTRICT');
        $this->addSql('ALTER TABLE favorite_property ADD CONSTRAINT FK_21A5042FA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorite_property ADD CONSTRAINT FK_21A5042F549213EC FOREIGN KEY (property_id) REFERENCES property (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F9AC0396 FOREIGN KEY (conversation_id) REFERENCES conversation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES `user` (id) ON DELETE RESTRICT');
        $this->addSql('ALTER TABLE price ADD CONSTRAINT FK_CAC822D9549213EC FOREIGN KEY (property_id) REFERENCES property (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT FK_8BF21CDEA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE RESTRICT');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT FK_8BF21CDEF92F3E70 FOREIGN KEY (country_id) REFERENCES country (id) ON DELETE RESTRICT');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT FK_8BF21CDE12469DE2 FOREIGN KEY (category_id) REFERENCES property_category (id) ON DELETE RESTRICT');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT FK_8BF21CDE6BF700BD FOREIGN KEY (status_id) REFERENCES property_status (id) ON DELETE RESTRICT');
        $this->addSql('ALTER TABLE property_equipment ADD CONSTRAINT FK_A2D7D73E549213EC FOREIGN KEY (property_id) REFERENCES property (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE property_equipment ADD CONSTRAINT FK_A2D7D73E517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipment (id) ON DELETE RESTRICT');
        $this->addSql('ALTER TABLE property_image ADD CONSTRAINT FK_32EC552549213EC FOREIGN KEY (property_id) REFERENCES property (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C63301C60 FOREIGN KEY (booking_id) REFERENCES booking (id) ON DELETE RESTRICT');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D6496BF700BD FOREIGN KEY (status_id) REFERENCES user_status (id) ON DELETE RESTRICT');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649B4AA5264 FOREIGN KEY (age_verification_status_id) REFERENCES age_verification_status (id) ON DELETE RESTRICT');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id) ON DELETE RESTRICT');
        $this->addSql('ALTER TABLE user_language ADD CONSTRAINT FK_345695B5A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_language ADD CONSTRAINT FK_345695B582F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDE6BF700BD');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDE549213EC');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDEA76ED395');
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY FK_8A8E26E9A76ED395');
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY FK_8A8E26E9549213EC');
        $this->addSql('ALTER TABLE favorite_property DROP FOREIGN KEY FK_21A5042FA76ED395');
        $this->addSql('ALTER TABLE favorite_property DROP FOREIGN KEY FK_21A5042F549213EC');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F9AC0396');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF624B39D');
        $this->addSql('ALTER TABLE price DROP FOREIGN KEY FK_CAC822D9549213EC');
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDEA76ED395');
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDEF92F3E70');
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDE12469DE2');
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDE6BF700BD');
        $this->addSql('ALTER TABLE property_equipment DROP FOREIGN KEY FK_A2D7D73E549213EC');
        $this->addSql('ALTER TABLE property_equipment DROP FOREIGN KEY FK_A2D7D73E517FE9FE');
        $this->addSql('ALTER TABLE property_image DROP FOREIGN KEY FK_32EC552549213EC');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C63301C60');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D6496BF700BD');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649B4AA5264');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649F92F3E70');
        $this->addSql('ALTER TABLE user_language DROP FOREIGN KEY FK_345695B5A76ED395');
        $this->addSql('ALTER TABLE user_language DROP FOREIGN KEY FK_345695B582F1BAF4');
        $this->addSql('DROP TABLE age_verification_status');
        $this->addSql('DROP TABLE booking');
        $this->addSql('DROP TABLE booking_status');
        $this->addSql('DROP TABLE conversation');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE equipment');
        $this->addSql('DROP TABLE favorite_property');
        $this->addSql('DROP TABLE language');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE price');
        $this->addSql('DROP TABLE property');
        $this->addSql('DROP TABLE property_category');
        $this->addSql('DROP TABLE property_equipment');
        $this->addSql('DROP TABLE property_image');
        $this->addSql('DROP TABLE property_status');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE user_language');
        $this->addSql('DROP TABLE user_status');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
