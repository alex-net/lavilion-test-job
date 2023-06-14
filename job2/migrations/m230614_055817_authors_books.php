<?php

use yii\db\Migration;

/**
 * Class m230614_055817_authors_books
 */
class m230614_055817_authors_books extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('authors', [
            'id' => $this->primaryKey()->comment('Ключик автора'),
            'name' => $this->string(30)->notNull()->comment('Имя автора'),
            'active' => $this->boolean()->defaultValue(false)->comment('Активность автора'),
        ]);
        $this->createIndex('authors-name-ind', 'authors', 'name', true);
        $this->createIndex('authors-active-ind', 'authors', 'active');

        $this->createTable('books', [
            'id' => $this->primaryKey()->comment('Ключик книги'),
            'name' => $this->string(60)->notNull()->comment('Название книги'),
            'author_id' => $this->integer()->notNull()->comment('Ссылка на актора'),
            'active' => $this->boolean()->defaultValue(false)->comment('доступность книги'),
        ]);
        $this->createIndex('books-name-author-ind', 'books', ['name', 'author_id'], true);
        $this->createIndex('books-active-ind', 'books', 'active');
        $this->createIndex('books-authorid-ind', 'books', 'author_id');
        $this->addForeignKey('books-authorid-fk', 'books', 'author_id', 'authors', 'id', 'cascade', 'cascade');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('books');
        $this->dropTable('authors');

        return false;
    }
}
