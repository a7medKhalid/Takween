<?php

namespace Tests\Feature;

use App\Actions\ExportDatabase\SQLiteExport;
use App\Http\Controllers\ColumnController;
use App\Http\Controllers\DataBaseController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\TableController;
use App\Models\DataBase;
use App\Models\Table;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SeriesTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    //test if user can create a new database
    public function test_user_can_create_a_new_database()
    {
        $user = User::factory()->create(['name' => 'Test User', 'email' => 'testuser@test.com']);

        $this->actingAs($user);

        $databaseController = new DataBaseController();

        $database = $databaseController->create($user, 'test');

        $this->assertDatabaseHas('data_bases', ['name' => 'test']);

    }

    //test if user can add table to database
    public function test_user_can_add_table_to_database()
    {
        $user = User::whereName('Test User')->first();

        $database = DataBase::whereName('test')->first();

        $tableController = new TableController();

        $table = $tableController->create($user, $database, 'test');

        $this->assertDatabaseHas('tables', ['name' => 'test']);

    }

    //test if user can add column to table
    public function test_user_can_add_column_to_table()
    {
        $user = User::whereName('Test User')->first();

        $database = DataBase::whereName('test')->first();

        $table = Table::whereName('test')->first();

        $columnController = new ColumnController();

        $column = $columnController->create($user, $table, 'text', 'test');

        $this->assertDatabaseHas('columns', ['name' => 'test', 'type' => 'text']);

    }

    //test if user can add relation column to table
    public function test_user_can_add_relation_column_to_table()
    {
        $user = User::whereName('Test User')->first();

        $database = DataBase::whereName('test')->first();

        $table1 = Table::whereName('test')->first();

        $tableController = new TableController();
        $table2 = $tableController->create($user, $database, 'test2');

        $columnController = new ColumnController();

        $column = $columnController->create($user, $table2, 'relation', $table1->name . '_id', $table1->name, 'test');

        $this->assertDatabaseHas('columns', ['name' => $table1->name . '_id', 'type' => 'relation', 'relationTable' => $table1->name, 'relationColumnName' => 'test']);

    }

    //test if user can add row to table
    public function test_user_can_add_and_delete_row_to_table()
    {
        $user = User::whereName('Test User')->first();

        $database = DataBase::whereName('test')->first();

        $table1 = Table::whereName('test')->first();
        $table2 = Table::whereName('test2')->first();


        //add row for first table
        $tableController = new TableController();

//        //temp seeder
//        for ($i = 0; $i < 10000; $i++) {
//            $tableController->updateDataAddRow($user, $table1, ['test' => 'test']);
//        }
//
//        for ($i = 0; $i < 5000; $i++) {
//            $tableController->updateDataAddRow($user, $table2, ['test' => 'test', 'test_id' => $i + 1]);
//        }

        $tableController->updateDataAddRow($user, $table1, ['test' => 'test']);

        $table1 = $table1->fresh();
        $rows = $table1->data;
        $this->assertJsonStringEqualsJsonString("[{\"id\": 1, \"test\": \"test\"}]", $rows);

        $rows = json_decode($rows, true);

        //test if user can delete row from table1
        $tableController->updateDataDeleteRow($user, $table1, $rows[0]);

        $this->assertJsonStringNotEqualsJsonString("[{\"id\": 1, \"test\": \"test\"}]", $table1->data);


        //add row for second table
        $tableController->updateDataAddRow($user, $table2, ['test' => 'test', 'test_id' => $table1->id]);


        $table2 = $table2->fresh();
        $rows = $table2->data;
        $this->assertJsonStringEqualsJsonString("[{\"id\": 1,\"test\": \"test\",\"test_id\":1}]", $rows);



    }

    //test if user can add editor to database
    public function test_user_can_add_editor_to_database()
    {
        $user = User::whereName('Test User')->first();

        $database = DataBase::whereName('test')->first();

        $permissionController = new PermissionController();

        //create editor user
        $editor = User::factory()->create(['name' => 'editor' , 'email' => 'editor@test.com']);

        $permissionController->create($user, $database, $editor->email);

        $this->assertDatabaseHas('permissions', ['user_id' => $editor->id, 'data_base_id' => $database->id]);

    }

    //test if editor can add and delete table rows
    public function test_editor_can_add_and_delete_row_to_table()
    {
        $user = User::whereName('editor')->first();

        $database = DataBase::whereName('test')->first();

        $table1 = Table::whereName('test')->first();


        //add row for first table
        $tableController = new TableController();


        $tableController->updateDataAddRow($user, $table1, ['test' => 'test']);

        $table1 = $table1->fresh();
        $rows = $table1->data;
        $this->assertJsonStringEqualsJsonString("[{\"id\": 2, \"test\": \"test\"}]", $rows);

        $rows = json_decode($rows, true);

        //test if user can delete row from table1
        $tableController->updateDataDeleteRow($user, $table1, $rows[0]);

        $this->assertJsonStringNotEqualsJsonString("[{\"id\": 1, \"test\": \"test\"}]", $table1->data);

    }

    //TODO:test if user can export database
//    public function test_user_can_export_database(){
//
//        $user = User::whereName('Test User')->first();
//
//        $database = DataBase::whereName('test')->first();
//
//        $SQLiteExport = new SQLiteExport();
//
//        $SQLiteExport->execute($user, $database);
//
//    }

    //test if user can delete table
    public function test_user_can_delete_table()
    {
        $user = User::whereName('Test User')->first();

        $database = DataBase::whereName('test')->first();

        $tableController = new TableController();

        $tableController->delete($user, 1);

        $this->assertDatabaseMissing('tables', ['name' => 'test']);

    }

    //test if user can delete database
    public function test_user_can_delete_database(){
        $user = User::whereName('Test User')->first();

        $database = DataBase::whereName('test')->first();

        $databaseController = new DatabaseController();

        $databaseController->delete($user, $database->id);

        $this->assertDatabaseMissing('data_bases', ['name' => 'test']);

    }

}
