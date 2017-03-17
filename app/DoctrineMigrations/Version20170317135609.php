<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170317135609 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('CREATE TABLE sessions ('
                . 'sess_id VARCHAR(128) NOT NULL COLLATE utf8_bin, '
                . 'sess_data BLOB NOT NULL, '
                . 'sess_time INT UNSIGNED NOT NULL, '
                . 'sess_lifetime INT NOT NULL, '
                . 'PRIMARY KEY(sess_id)) '
                . 'DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('sessions');
    }
}
