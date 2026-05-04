<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260504203646 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE about (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(20) NOT NULL, description TINYTEXT NOT NULL, create_at DATETIME NOT NULL, update_at DATETIME DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE career (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, content LONGTEXT NOT NULL, position VARCHAR(100) NOT NULL, startdate DATETIME NOT NULL, enddate DATETIME DEFAULT NULL, status VARCHAR(255) NOT NULL, coverpicture VARCHAR(255) NOT NULL, coverpicturefilename VARCHAR(255) DEFAULT NULL, jobpicture VARCHAR(255) NOT NULL, jobpicturefilename VARCHAR(255) NOT NULL, create_at DATETIME NOT NULL, update_at DATETIME DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE career_skill (career_id INT NOT NULL, skill_id INT NOT NULL, INDEX IDX_D73066B4B58CDA09 (career_id), INDEX IDX_D73066B45585C142 (skill_id), PRIMARY KEY (career_id, skill_id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, lastname VARCHAR(20) NOT NULL, firstname VARCHAR(20) NOT NULL, email VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, create_at DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, description VARCHAR(150) NOT NULL, content LONGTEXT NOT NULL, coverpicture VARCHAR(255) NOT NULL, coverpicturefilename VARCHAR(255) DEFAULT NULL, projectpicture VARCHAR(255) NOT NULL, projectpicturefilename VARCHAR(255) DEFAULT NULL, link VARCHAR(255) DEFAULT NULL, create_at DATETIME NOT NULL, update_at DATETIME DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE project_skill (project_id INT NOT NULL, skill_id INT NOT NULL, INDEX IDX_4D68EDE9166D1F9C (project_id), INDEX IDX_4D68EDE95585C142 (skill_id), PRIMARY KEY (project_id, skill_id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE site_parameter (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, description VARCHAR(255) NOT NULL, keyword JSON DEFAULT NULL, mediadescription VARCHAR(255) NOT NULL, urlsite VARCHAR(255) DEFAULT NULL, create_at DATETIME NOT NULL, update_at DATETIME DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE skill (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, level INT NOT NULL, image VARCHAR(255) NOT NULL, imagefilename VARCHAR(255) DEFAULT NULL, progressbarcolor VARCHAR(7) NOT NULL, create_at DATETIME NOT NULL, update_at DATETIME DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, lastname VARCHAR(50) DEFAULT NULL, firstname VARCHAR(50) DEFAULT NULL, phone VARCHAR(10) DEFAULT NULL, linkgithub VARCHAR(255) DEFAULT NULL, linklinkedin VARCHAR(255) DEFAULT NULL, cv VARCHAR(255) DEFAULT NULL, img VARCHAR(255) DEFAULT NULL, cvfilename VARCHAR(255) NOT NULL, imgfilename VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, update_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('ALTER TABLE career_skill ADD CONSTRAINT FK_D73066B4B58CDA09 FOREIGN KEY (career_id) REFERENCES career (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE career_skill ADD CONSTRAINT FK_D73066B45585C142 FOREIGN KEY (skill_id) REFERENCES skill (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_skill ADD CONSTRAINT FK_4D68EDE9166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_skill ADD CONSTRAINT FK_4D68EDE95585C142 FOREIGN KEY (skill_id) REFERENCES skill (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE career_skill DROP FOREIGN KEY FK_D73066B4B58CDA09');
        $this->addSql('ALTER TABLE career_skill DROP FOREIGN KEY FK_D73066B45585C142');
        $this->addSql('ALTER TABLE project_skill DROP FOREIGN KEY FK_4D68EDE9166D1F9C');
        $this->addSql('ALTER TABLE project_skill DROP FOREIGN KEY FK_4D68EDE95585C142');
        $this->addSql('DROP TABLE about');
        $this->addSql('DROP TABLE career');
        $this->addSql('DROP TABLE career_skill');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE project_skill');
        $this->addSql('DROP TABLE site_parameter');
        $this->addSql('DROP TABLE skill');
        $this->addSql('DROP TABLE `user`');
    }
}
