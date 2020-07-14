<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200710173606 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE episode (id INT AUTO_INCREMENT NOT NULL, season_id INT NOT NULL, title VARCHAR(150) NOT NULL, duration INT NOT NULL, summary VARCHAR(500) NOT NULL, episode_number INT NOT NULL, release_date DATETIME NOT NULL, INDEX IDX_DDAA1CDA4EC001D1 (season_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movie (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(150) NOT NULL, summary VARCHAR(500) NOT NULL, slug VARCHAR(255) NOT NULL, type VARCHAR(40) NOT NULL, poster VARCHAR(50) NOT NULL, release_date DATETIME NOT NULL, duration INT NOT NULL, UNIQUE INDEX UNIQ_1D5EF26F989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE season (id INT AUTO_INCREMENT NOT NULL, serie_id INT NOT NULL, season_number INT NOT NULL, summary VARCHAR(500) NOT NULL, INDEX IDX_F0E45BA9D94388BD (serie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE serie (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(500) NOT NULL, type VARCHAR(40) NOT NULL, summary VARCHAR(500) NOT NULL, slug VARCHAR(255) NOT NULL, poster VARCHAR(50) DEFAULT NULL, start_year DATETIME NOT NULL, UNIQUE INDEX UNIQ_AA3A9334989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, firstname VARCHAR(50) NOT NULL, lastname VARCHAR(50) NOT NULL, registration_date DATETIME NOT NULL, picture VARCHAR(32) DEFAULT NULL, activated TINYINT(1) NOT NULL, activation_token VARCHAR(32) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_serie_favorite (user_id INT NOT NULL, serie_id INT NOT NULL, INDEX IDX_4BC6FEB8A76ED395 (user_id), INDEX IDX_4BC6FEB8D94388BD (serie_id), PRIMARY KEY(user_id, serie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_movie_favorite (user_id INT NOT NULL, movie_id INT NOT NULL, INDEX IDX_DFB6CA0DA76ED395 (user_id), INDEX IDX_DFB6CA0D8F93B6FC (movie_id), PRIMARY KEY(user_id, movie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_episode_watched (user_id INT NOT NULL, episode_id INT NOT NULL, INDEX IDX_DE8ECB01A76ED395 (user_id), INDEX IDX_DE8ECB01362B62A0 (episode_id), PRIMARY KEY(user_id, episode_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_movie_watched (user_id INT NOT NULL, movie_id INT NOT NULL, INDEX IDX_8843BC83A76ED395 (user_id), INDEX IDX_8843BC838F93B6FC (movie_id), PRIMARY KEY(user_id, movie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE episode ADD CONSTRAINT FK_DDAA1CDA4EC001D1 FOREIGN KEY (season_id) REFERENCES season (id)');
        $this->addSql('ALTER TABLE season ADD CONSTRAINT FK_F0E45BA9D94388BD FOREIGN KEY (serie_id) REFERENCES serie (id)');
        $this->addSql('ALTER TABLE user_serie_favorite ADD CONSTRAINT FK_4BC6FEB8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_serie_favorite ADD CONSTRAINT FK_4BC6FEB8D94388BD FOREIGN KEY (serie_id) REFERENCES serie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_movie_favorite ADD CONSTRAINT FK_DFB6CA0DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_movie_favorite ADD CONSTRAINT FK_DFB6CA0D8F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_episode_watched ADD CONSTRAINT FK_DE8ECB01A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_episode_watched ADD CONSTRAINT FK_DE8ECB01362B62A0 FOREIGN KEY (episode_id) REFERENCES episode (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_movie_watched ADD CONSTRAINT FK_8843BC83A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_movie_watched ADD CONSTRAINT FK_8843BC838F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_episode_watched DROP FOREIGN KEY FK_DE8ECB01362B62A0');
        $this->addSql('ALTER TABLE user_movie_favorite DROP FOREIGN KEY FK_DFB6CA0D8F93B6FC');
        $this->addSql('ALTER TABLE user_movie_watched DROP FOREIGN KEY FK_8843BC838F93B6FC');
        $this->addSql('ALTER TABLE episode DROP FOREIGN KEY FK_DDAA1CDA4EC001D1');
        $this->addSql('ALTER TABLE season DROP FOREIGN KEY FK_F0E45BA9D94388BD');
        $this->addSql('ALTER TABLE user_serie_favorite DROP FOREIGN KEY FK_4BC6FEB8D94388BD');
        $this->addSql('ALTER TABLE user_serie_favorite DROP FOREIGN KEY FK_4BC6FEB8A76ED395');
        $this->addSql('ALTER TABLE user_movie_favorite DROP FOREIGN KEY FK_DFB6CA0DA76ED395');
        $this->addSql('ALTER TABLE user_episode_watched DROP FOREIGN KEY FK_DE8ECB01A76ED395');
        $this->addSql('ALTER TABLE user_movie_watched DROP FOREIGN KEY FK_8843BC83A76ED395');
        $this->addSql('DROP TABLE episode');
        $this->addSql('DROP TABLE movie');
        $this->addSql('DROP TABLE season');
        $this->addSql('DROP TABLE serie');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_serie_favorite');
        $this->addSql('DROP TABLE user_movie_favorite');
        $this->addSql('DROP TABLE user_episode_watched');
        $this->addSql('DROP TABLE user_movie_watched');
    }
}
