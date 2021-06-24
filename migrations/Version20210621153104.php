<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210621153104 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE classroom (id INT AUTO_INCREMENT NOT NULL, nume VARCHAR(50) NOT NULL, cod INT NOT NULL, nr_students INT NOT NULL, id_univ INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE university (id INT AUTO_INCREMENT NOT NULL, numele_universitatii VARCHAR(255) NOT NULL, numele_facultatii VARCHAR(255) NOT NULL, nr_tel VARCHAR(21) NOT NULL, adresa VARCHAR(21) NOT NULL, email VARCHAR(255) NOT NULL, id_admin INT NOT NULL, codul_institutiei INT NOT NULL, UNIQUE INDEX UNIQ_A07A85ECE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE student ADD user_id INT DEFAULT NULL, ADD classroom_id INT DEFAULT NULL, ADD institute_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF336278D5A8 FOREIGN KEY (classroom_id) REFERENCES classroom (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33697B0F4C FOREIGN KEY (institute_id) REFERENCES university (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B723AF33A76ED395 ON student (user_id)');
        $this->addSql('CREATE INDEX IDX_B723AF336278D5A8 ON student (classroom_id)');
        $this->addSql('CREATE INDEX IDX_B723AF33697B0F4C ON student (institute_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF336278D5A8');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF33697B0F4C');
        $this->addSql('DROP TABLE administrator');
        $this->addSql('DROP TABLE classroom');
        $this->addSql('DROP TABLE materii');
        $this->addSql('DROP TABLE prezente');
        $this->addSql('DROP TABLE repartizare_studenti');
        $this->addSql('DROP TABLE schedule');
        $this->addSql('DROP TABLE university');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF33A76ED395');
        $this->addSql('DROP INDEX UNIQ_B723AF33A76ED395 ON student');
        $this->addSql('DROP INDEX IDX_B723AF336278D5A8 ON student');
        $this->addSql('DROP INDEX IDX_B723AF33697B0F4C ON student');
        $this->addSql('ALTER TABLE student DROP user_id, DROP classroom_id, DROP institute_id');
    }
}
