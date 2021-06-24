<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210623144055 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE professor_attendance (id INT AUTO_INCREMENT NOT NULL, classroom_id INT NOT NULL, professor_id INT NOT NULL, subject_id INT NOT NULL, created_at DATETIME NOT NULL, latitude VARCHAR(15) NOT NULL, longitude VARCHAR(15) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student_attendance (id INT AUTO_INCREMENT NOT NULL, student_id INT NOT NULL, attendance_id INT NOT NULL, created_at DATETIME NOT NULL, latitude VARCHAR(15) NOT NULL, longitude VARCHAR(15) NOT NULL, distance VARCHAR(20) NOT NULL, distance_status VARCHAR(30) NOT NULL, location_exposure VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE professor_attendance');
        $this->addSql('DROP TABLE student_attendance');
    }
}
