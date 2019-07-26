<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190714162626 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

       $sql = "CREATE FUNCTION `remove_accents`(`str` TEXT)"
        ."    RETURNS text"
        ."    LANGUAGE SQL"
        ."    DETERMINISTIC"
        ."    NO SQL"
        ."    SQL SECURITY INVOKER"
        ."    COMMENT ''"
        ." BEGIN"
        .""
        ."    SET str = REPLACE(str,'Š','S');"
        ."    SET str = REPLACE(str,'š','s');"
        ."    SET str = REPLACE(str,'Ð','Dj');"
        ."    SET str = REPLACE(str,'Ž','Z');"
        ."    SET str = REPLACE(str,'ž','z');"
        ."    SET str = REPLACE(str,'À','A');"
        ."    SET str = REPLACE(str,'Á','A');"
        ."    SET str = REPLACE(str,'Â','A');"
        ."    SET str = REPLACE(str,'Ã','A');"
        ."    SET str = REPLACE(str,'Ä','A');"
        ."    SET str = REPLACE(str,'Å','A');"
        ."    SET str = REPLACE(str,'Æ','A');"
        ."    SET str = REPLACE(str,'Ç','C');"
        ."    SET str = REPLACE(str,'È','E');"
        ."    SET str = REPLACE(str,'É','E');"
        ."    SET str = REPLACE(str,'Ê','E');"
        ."    SET str = REPLACE(str,'Ë','E');"
        ."    SET str = REPLACE(str,'Ì','I');"
        ."    SET str = REPLACE(str,'Í','I');"
        ."    SET str = REPLACE(str,'Î','I');"
        ."    SET str = REPLACE(str,'Ï','I');"
        ."    SET str = REPLACE(str,'Ñ','N');"
        ."    SET str = REPLACE(str,'Ò','O');"
        ."    SET str = REPLACE(str,'Ó','O');"
        ."    SET str = REPLACE(str,'Ô','O');"
        ."    SET str = REPLACE(str,'Õ','O');"
        ."    SET str = REPLACE(str,'Ö','O');"
        ."    SET str = REPLACE(str,'Ø','O');"
        ."    SET str = REPLACE(str,'Ù','U');"
        ."    SET str = REPLACE(str,'Ú','U');"
        ."    SET str = REPLACE(str,'Û','U');"
        ."    SET str = REPLACE(str,'Ü','U');"
        ."    SET str = REPLACE(str,'Ý','Y');"
        ."    SET str = REPLACE(str,'Þ','B');"
        ."    SET str = REPLACE(str,'ß','Ss');"
        ."    SET str = REPLACE(str,'à','a');"
        ."    SET str = REPLACE(str,'á','a');"
        ."    SET str = REPLACE(str,'â','a');"
        ."    SET str = REPLACE(str,'ã','a');"
        ."    SET str = REPLACE(str,'ä','a');"
        ."    SET str = REPLACE(str,'å','a');"
        ."    SET str = REPLACE(str,'æ','a');"
        ."    SET str = REPLACE(str,'ç','c');"
        ."    SET str = REPLACE(str,'è','e');"
        ."    SET str = REPLACE(str,'é','e');"
        ."    SET str = REPLACE(str,'ê','e');"
        ."    SET str = REPLACE(str,'ë','e');"
        ."    SET str = REPLACE(str,'ì','i');"
        ."    SET str = REPLACE(str,'í','i');"
        ."    SET str = REPLACE(str,'î','i');"
        ."    SET str = REPLACE(str,'ï','i');"
        ."    SET str = REPLACE(str,'ð','o');"
        ."    SET str = REPLACE(str,'ñ','n');"
        ."    SET str = REPLACE(str,'ò','o');"
        ."    SET str = REPLACE(str,'ó','o');"
        ."    SET str = REPLACE(str,'ô','o');"
        ."    SET str = REPLACE(str,'õ','o');"
        ."    SET str = REPLACE(str,'ö','o');"
        ."    SET str = REPLACE(str,'ø','o');"
        ."    SET str = REPLACE(str,'ù','u');"
        ."    SET str = REPLACE(str,'ú','u');"
        ."    SET str = REPLACE(str,'û','u');"
        ."    SET str = REPLACE(str,'ý','y');"
        ."    SET str = REPLACE(str,'ý','y');"
        ."    SET str = REPLACE(str,'þ','b');"
        ."    SET str = REPLACE(str,'ÿ','y');"
        ."    SET str = REPLACE(str,'ƒ','f');"
        ."    RETURN str;"
        ." END"
        ;
        
        $this->addSql($sql);
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP FUNCTION IF EXISTS `remove_accents`');
    }
}
