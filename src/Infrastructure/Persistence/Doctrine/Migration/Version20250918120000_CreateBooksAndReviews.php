<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250918120000_CreateBooksAndReviews extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create tables book and review with FK (review.book_id → book.id), índices y columnas requeridas';
    }

    public function up(Schema $schema): void
    {
        // Tabla book
        $book = $schema->createTable('book');
        $book->addColumn('id', 'integer', ['autoincrement' => true]);
        $book->addColumn('title', 'string', ['length' => 255]);
        $book->addColumn('author', 'string', ['length' => 255]);
        $book->addColumn('published_year', 'integer', []);
        $book->setPrimaryKey(['id']);
        $book->addIndex(['title'], 'idx_book_title');
        $book->addIndex(['author'], 'idx_book_author');

        // Tabla review
        $review = $schema->createTable('review');
        $review->addColumn('id', 'integer', ['autoincrement' => true]);
        $review->addColumn('book_id', 'integer', []);
        $review->addColumn('rating', 'smallint', []);
        $review->addColumn('comment', 'text', []);
        $review->addColumn('created_at', 'datetime_immutable', []);
        $review->setPrimaryKey(['id']);
        $review->addIndex(['book_id'], 'idx_review_book');

        // FK review.book_id → book.id (ON DELETE CASCADE para saneo)
        $review->addForeignKeyConstraint(
            'book',
            ['book_id'],
            ['id'],
            ['onDelete' => 'CASCADE'],
            'fk_review_book'
        );
    }

    public function down(Schema $schema): void
    {
        // El orden importa por la FK
        $schema->dropTable('review');
        $schema->dropTable('book');
    }
}