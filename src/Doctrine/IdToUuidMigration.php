<?php

namespace App\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use Doctrine\Migrations\AbstractMigration;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\Doctrine\UuidOrderedTimeGenerator;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class IdToUuidMigration extends AbstractMigration implements ContainerAwareInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var array
     */
    protected $idToUuidMap = [];
    /**
     * @var UuidOrderedTimeGenerator
     */
    protected $generator;
    /**
     * @var array
     */
    protected $fks;
    /**
     * @var string
     */
    protected $table;

    /**
     * @var AbstractSchemaManager
     */
    protected $schemaManager;

    protected $extraRelationships;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->em = $container->get('doctrine')->getManager();
        $this->connection = $this->em->getConnection();
        $this->schemaManager = $this->connection->getSchemaManager();
        $this->generator = new UuidGenerator();
    }

    public function up(Schema $schema): void
    {
    }

    public function migrate(string $tableName, array $extraRelationships = [])
    {
        $this->extraRelationships = $extraRelationships;
        $this->write('Migrating ' . $tableName . '.id to UUIDs...');
        $this->prepare($tableName);
        $this->addUuidFields();
        $this->generateUuidsToReplaceIds();
        $this->changeToUUIDExtraRelationships();
        $this->addThoseUuidsToTablesWithFK();
        $this->deletePreviousFKs();
        $this->renameNewFKsToPreviousNames();
        $this->dropIdPrimaryKeyAndSetUuidToPrimaryKey();
        $this->restoreConstraintsAndIndexes();

        $this->write('Successfully migrated ' . $tableName . '.id to UUIDs!');
    }

    public function down(Schema $schema): void
    {
    }

    private function isForeignKeyNullable(Table $table, $key)
    {
        foreach ($table->getColumns() as $column) {
            if ($column->getName() === $key) {
                return !$column->getNotnull();
            }
        }
        throw new \Exception('Unable to find ' . $key . 'in ' . $table);
    }

    private function prepare(string $tableName)
    {
        $this->table = $tableName;
        $this->fks = [];
        $this->idToUuidMap = [];

        foreach ($this->schemaManager->listTables() as $table) {
            /* @var $table Table*/
            $foreignKeys = $this->schemaManager->listTableForeignKeys($table->getName());
            foreach ($foreignKeys as $foreignKey) {
                $key = $foreignKey->getColumns()[0];
                if ($foreignKey->getForeignTableName() === $this->table) {
                    $fk = [
                        'table' => $table->getName(),
                        'key' => $key,
                        'tmpKey' => $key . '_to_uuid',
                        'nullable' => $this->isForeignKeyNullable($table, $key),
                        'name' => $foreignKey->getName(),
                        'primaryKey' => $table->getPrimaryKeyColumns(),
                    ];
                    if ($foreignKey->onDelete()) {
                        $fk['onDelete'] = $foreignKey->onDelete();
                    }
                    $this->fks[] = $fk;
                }
            }
        }
        if (\count($this->fks) > 0) {
            $this->write('-> Detected the following foreign keys :');
            foreach ($this->fks as $fk) {
                $this->write('  * ' . $fk['table'] . '.' . $fk['key']);
            }

            return;
        }
        $this->write('-> 0 foreign key detected.');
    }

    private function addUuidFields()
    {
        $this->connection->executeQuery('ALTER TABLE `' . $this->table . '` ADD uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\' FIRST');
        foreach ($this->fks as $fk) {
            $this->connection->executeQuery('ALTER TABLE `' . $fk['table'] . '` ADD ' . $fk['tmpKey'] . ' CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        }

        foreach ($this->extraRelationships as &$relationship) {
            $relationship['tmpKey'] = $relationship['key'] . '_to_uuid';
            $this->connection->executeQuery('ALTER TABLE `' . $relationship['table'] . '` ADD ' . $relationship['tmpKey'] . ' CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        }
    }

    private function generateUuidsToReplaceIds()
    {
        $fetchs = $this->connection->fetchAll('SELECT id from `' . $this->table . '` order by id ASC');
        if (\count($fetchs) > 0) {
            $this->write('-> Generating ' . \count($fetchs) . ' UUID(s)...');
            foreach ($fetchs as $fetch) {
                $id = $fetch['id'];
                $uuid = $this->generator->generate($this->em, null)->toString();
                $this->idToUuidMap[$id] = $uuid;
                $this->connection->update($this->table, ['uuid' => $uuid], ['id' => $id]);
            }
        }
    }

    private function changeToUUIDExtraRelationships()
    {
        $this->write('-> Adding UUIDs to tables extra relations...');
        foreach ($this->extraRelationships as $extraRelationship) {
            $this->write('  * Adding UUIDs to "' . $extraRelationship['table'] . '.' . $extraRelationship['key'] . '"...');
            foreach ($this->idToUuidMap as $id => $uuid) {
                $this->connection->update(
                    $extraRelationship['table'],
                    [$extraRelationship['tmpKey'] => $uuid],
                    array_merge([$extraRelationship['key'] => $id], $extraRelationship['findExtra'])
                );
            }
            $this->connection->executeQuery('ALTER TABLE `' . $extraRelationship['table'] . '` DROP COLUMN `' . $extraRelationship['key'] . '`');
            $this->connection->executeQuery('ALTER TABLE `' . $extraRelationship['table'] . '` CHANGE `' . $extraRelationship['tmpKey'] . '` ' . $extraRelationship['key'] . ' CHAR(36) '  . ' COMMENT \'(DC2Type:uuid)\'');
        }
    }

    private function addThoseUuidsToTablesWithFK()
    {
        if (0 === \count($this->fks)) {
            return;
        }
        $this->write('-> Adding UUIDs to tables with foreign keys...');
        foreach ($this->fks as $fk) {
            $this->write('  * Adding UUIDs to "' . $fk['table'] . '.' . $fk['key'] . '"...');
            foreach ($this->idToUuidMap as $id => $uuid) {
                $this->connection->update(
                    $fk['table'],
                    [$fk['tmpKey'] => $uuid],
                    [$fk['key'] => $id]
                );
            }
        }
    }

    private function deletePreviousFKs()
    {
        $this->write('-> Deleting previous id foreign keys...');
        foreach ($this->fks as $fk) {
            if (isset($fk['primaryKey'])) {
                try {
                    // drop primary key if not already dropped
                    $this->connection->executeQuery('ALTER TABLE `' . $fk['table'] . '` DROP PRIMARY KEY');
                } catch (\Exception $e) {
                }
            }
            $this->connection->executeQuery('ALTER TABLE `' . $fk['table'] . '` DROP FOREIGN KEY `' . $fk['name'] . '`');
            $this->connection->executeQuery('ALTER TABLE `' . $fk['table'] . '` DROP COLUMN `' . $fk['key'] . '`');
        }
    }

    private function renameNewFKsToPreviousNames()
    {
        $this->write('-> Renaming temporary uuid foreign keys to previous foreign keys names...');
        foreach ($this->fks as $fk) {
            $this->connection->executeQuery('ALTER TABLE `' . $fk['table'] . '` CHANGE `' . $fk['tmpKey'] . '` ' . $fk['key'] . ' CHAR(36) ' . ($fk['nullable'] ? 'NULL ' : 'NOT NULL ') . 'COMMENT \'(DC2Type:uuid)\'');
            if ($fk['nullable']) {
                $this->connection->update(
                    $fk['table'],
                    [$fk['key'] => null],
                    [$fk['key'] => '']
                );
            }
        }
    }

    private function dropIdPrimaryKeyAndSetUuidToPrimaryKey()
    {
        $this->write('-> Creating the uuid primary key...');
        $this->connection->executeQuery('ALTER TABLE `' . $this->table . '` DROP PRIMARY KEY, DROP COLUMN id');
        $this->connection->executeQuery('ALTER TABLE `' . $this->table . '` CHANGE uuid id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->connection->executeQuery('ALTER TABLE `' . $this->table . '` ADD PRIMARY KEY (id)');
    }

    private function restoreConstraintsAndIndexes()
    {
        foreach ($this->fks as $fk) {
            if (isset($fk['primaryKey'])) {
                try {
                    // restore primary key if not already restored
                    $this->connection->executeQuery('ALTER TABLE `' . $fk['table'] . '` ADD PRIMARY KEY (' . implode(',', $fk['primaryKey']) . ')');
                } catch (\Exception $e) {
                }
            }
            $this->connection->executeQuery(
                'ALTER TABLE `' . $fk['table'] . '` ADD CONSTRAINT `' . $fk['name'] . '` FOREIGN KEY (' . $fk['key'] . ') REFERENCES ' . $this->table . ' (id)' .
                    (isset($fk['onDelete']) ? ' ON DELETE ' . $fk['onDelete'] : '')
            );
            $this->connection->executeQuery('CREATE INDEX `' . str_replace('FK_', 'IDX_', $fk['name']) . '` ON ' . $fk['table'] . ' (' . $fk['key'] . ')');
        }
    }
}
