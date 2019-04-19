<?php


use Phinx\Migration\AbstractMigration;

class CreateTableCharacter extends AbstractMigration
{
    public function up()
    {
        $this->table('character')
            ->addColumn('user_id', 'integer')
            ->addColumn('floor_id', 'string', ['limit' => 80])
            ->create();
    }

    public function down()
    {
        $this->table('character')->drop();
    }
}
