<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250209123045 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE match_results (id SERIAL NOT NULL, match_id INT NOT NULL, player_id INT NOT NULL, kills INT NOT NULL, deaths INT NOT NULL, score INT NOT NULL, rank INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E805BB7B2ABEACD6 ON match_results (match_id)');
        $this->addSql('CREATE INDEX IDX_E805BB7B99E6F5DF ON match_results (player_id)');
        $this->addSql('CREATE TABLE matches (id SERIAL NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, duration INT NOT NULL, game_mode VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE players (id SERIAL NOT NULL, username VARCHAR(50) NOT NULL, score INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_264E43A6F85E0677 ON players (username)');
        $this->addSql('CREATE TABLE users (id SERIAL NOT NULL, email VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
        $this->addSql('ALTER TABLE match_results ADD CONSTRAINT FK_E805BB7B2ABEACD6 FOREIGN KEY (match_id) REFERENCES matches (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE match_results ADD CONSTRAINT FK_E805BB7B99E6F5DF FOREIGN KEY (player_id) REFERENCES players (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE match_results DROP CONSTRAINT FK_E805BB7B2ABEACD6');
        $this->addSql('ALTER TABLE match_results DROP CONSTRAINT FK_E805BB7B99E6F5DF');
        $this->addSql('DROP TABLE match_results');
        $this->addSql('DROP TABLE matches');
        $this->addSql('DROP TABLE players');
        $this->addSql('DROP TABLE users');
    }
}
