<?php


use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateTableCorpse extends AbstractMigration
{
    public function up()
    {
        $this->table('corpse')
            ->addColumn('character_id', 'string', ['limit' => 80])
            ->addColumn('status', 'string', ['limit' => 80])
            ->addColumn('floor_id', 'string', ['limit' => 80])
            ->addColumn('x', 'string', ['limit' => 80])
            ->addColumn('y', 'string', ['limit' => 80])
            ->addColumn('data', 'string', ['limit' => MysqlAdapter::TEXT_LONG])
            ->create();
    }

    public function down()
    {
        $this->table('corpse')->drop();
    }
}
