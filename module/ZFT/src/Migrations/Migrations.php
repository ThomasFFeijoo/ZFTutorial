<?php

namespace ZFT\Migrations;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\Platform\PlatformInterface;
use Zend\Db\Metadata\MetadataInterface;
use Zend\Db\Metadata\Object\TableObject;
use Zend\Db\Metadata\Source\Factory as MetadataFactory;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Ddl;
use Zend\Db\Sql\Where;

class Migrations {

    const MINIMUM_SCHEMA_VERSION = 1;
    const INI_TABLE = 'ini';

    /** @var Adapter */
    private $adapter;

    /** @var  PlatformInterface */
    private $platform;

    /** @var  MetadataInterface */
    private $metadata;

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
        $this->platform = $adapter->getPlatform();
        $this->metadata = MetadataFactory::createSourceFromAdapter($adapter);
    }

    public function needsUpdate() {
        return ($this->getVersion() < self::MINIMUM_SCHEMA_VERSION);
    }

    private function execute(Ddl\SqlInterface $ddl) {
        $sql = new Sql($this->adapter);
        $sqlString = $sql->buildSqlString($ddl);

        $this->adapter->query($sqlString, Adapter::QUERY_MODE_EXECUTE);
    }

    private function getVersion()
    {
        $tables = $this->metadata->getTables('zftutorial');

        $iniTable = array_filter($tables, function (TableObject $table) {
            return strcmp($table->getName(), self::INI_TABLE) === 0;
        });

        if (count($iniTable) === 0) {
            return 0;
        }

        /*$sql = "SELECT value ".
            'FROM '.$this->platform->quoteIdentifier(self::INI_TABLE)." ".
            'WHERE '.$this->platform->quoteIdentifier('options').' = :option';*/

        $sql = new Sql($this->adapter);
        $select = $sql->select();
        $select->from(self::INI_TABLE);
        $select->where(['options' => 'ZftSchemaVersion']);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        $result = $result->current();
        $version = $result['value'];

        /*
        $result = $this->adapter->query($sql, ['option' => 'zftschema']);
        $result = $result->toArray();
        $version = $result[0]['value'];*/

        return $version;
    }

    public function run() {
        $migrationClass = new \ReflectionClass(Migrations::class);
        $methods = $migrationClass->getMethods(\ReflectionMethod::IS_PROTECTED);

        $updates = [];
        array_walk($methods, function(\ReflectionMethod $method) use (&$updates) {
            $version = substr($method->getName(), strpos($method->getName(), "_")+1);
            $version = (int) $version;
            $updates[$version] = $method->getName();
        });

        ksort($updates);

        $currentVersion = (int) $this->getVersion();
        for($v = $currentVersion +1; $v <= self::MINIMUM_SCHEMA_VERSION; $v++) {
            $update = $updates[$v];
            $this->{$update}();

            $this->setVersion($v);
        }
        return;
    }

    private function setVersion($version) {
        $sql = new Sql($this->adapter);
        $schemaVersionUpdate = $sql->update();
        $schemaVersionUpdate->table(self::INI_TABLE);
        $schemaVersionUpdate->set(['value' => $version]);

        $schemaVersionRow = new Where();
        $schemaVersionRow->equalTo('options', 'ZftSchemaVersion');

        $schemaVersionStatement = $sql->prepareStatementForSqlObject($schemaVersionUpdate);
        $schemaVersionStatement->execute();
    }

    protected function update_001() {
        /*$iniTable = new Ddl\CreateTable(self::INI_TABLE);

        $option = new Ddl\Column\Varchar('options');
        $value = new Ddl\Column\Varchar('value');

        $iniTable->addColumn($option);
        $iniTable->addColumn($value);

        $this->execute($iniTable);
*/
//        $sql = new Sql($this->adapter);
//        $insertInitialVersion = $sql->insert();
//        $insertInitialVersion->into(self::INI_TABLE);
////        $insertInitialVersion->columns(array('options','value'));
////        $insertInitialVersion->values(array('ZftSchemaVersion', 1));
//
//        $values = [
//            'options' => 'ZftSchemaVersion',
//            'value' => 1
//        ];
//        $insertInitialVersion->columns(array_keys($values));
//        $insertInitialVersion->values(array_values($values));
//
//        $insertStatement = $sql->prepareStatementForSqlObject($insertInitialVersion);
//        $insertStatement->execute();
    }

}