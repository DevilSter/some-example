<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201119160918 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE authors_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE books_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE authors (id INT NOT NULL, first_name VARCHAR(255) NOT NULL, middle_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX author_unique ON authors (first_name, middle_name, last_name)');
        $this->addSql('CREATE TABLE books (id INT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4A1B2A922B36786B ON books (title)');
        $this->addSql('CREATE TABLE book_author (book_id INT NOT NULL, author_id INT NOT NULL, PRIMARY KEY(book_id, author_id))');
        $this->addSql('CREATE INDEX IDX_9478D34516A2B381 ON book_author (book_id)');
        $this->addSql('CREATE INDEX IDX_9478D345F675F31B ON book_author (author_id)');
        $this->addSql('ALTER TABLE book_author ADD CONSTRAINT FK_9478D34516A2B381 FOREIGN KEY (book_id) REFERENCES books (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE book_author ADD CONSTRAINT FK_9478D345F675F31B FOREIGN KEY (author_id) REFERENCES authors (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE book_author DROP CONSTRAINT FK_9478D345F675F31B');
        $this->addSql('ALTER TABLE book_author DROP CONSTRAINT FK_9478D34516A2B381');
        $this->addSql('DROP SEQUENCE authors_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE books_id_seq CASCADE');
        $this->addSql('DROP TABLE authors');
        $this->addSql('DROP TABLE books');
        $this->addSql('DROP TABLE book_author');
    }
}
