<?php


use Phinx\Migration\AbstractMigration;

class CreateTableUser extends AbstractMigration
{
    public function up()
    {
        $this->table('user')
            ->addColumn('device_id', 'string', ['limit' => 80])
            ->create();
    }

    public function down()
    {
        $this->table('user')->drop();
    }
}
