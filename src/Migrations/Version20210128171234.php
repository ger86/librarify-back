<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Doctrine\IdToUuidMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210128171234 extends IdToUuidMigration
{
    public function postUp(Schema $schema): void
    {
        $this->migrate('book');
        $this->migrate('category');
    }
}
