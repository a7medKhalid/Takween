<?php

namespace App\Http\Controllers;

use App\Models\DataBase;
use App\Models\Table;
use Illuminate\Http\Request;

class ExportDataBaseController extends Controller
{

    public $reservedWords = [
    'ABORT',
    'ACTION',
    'ADD',
    'AFTER',
    'ALL',
    'ALTER',
    'ALWAYS',
    'ANALYZE',
    'AND',
    'AS',
    'ASC',
    'ATTACH',
    'AUTOINCREMENT',
    'BEFORE',
    'BEGIN',
    'BETWEEN',
    'BY',
    'CASCADE',
    'CASE',
    'CAST',
    'CHECK',
    'COLLATE',
    'COLUMN',
    'COMMIT',
    'CONFLICT',
    'CONSTRAINT',
    'CREATE',
    'CROSS',
    'CURRENT',
    'CURRENT_DATE',
    'CURRENT_TIME',
    'CURRENT_TIMESTAMP',
    'DATABASE',
    'DEFAULT',
    'DEFERRABLE',
    'DEFERRED',
    'DELETE',
    'DESC',
    'DETACH',
    'DISTINCT',
    'DO',
    'DROP',
    'EACH',
    'ELSE',
    'END',
    'ESCAPE',
    'EXCEPT',
    'EXCLUDE',
    'EXCLUSIVE',
    'EXISTS',
    'EXPLAIN',
    'FAIL',
    'FILTER',
    'FIRST',
    'FOLLOWING',
    'FOR',
    'FOREIGN',
    'FROM',
    'FULL',
    'GENERATED',
    'GLOB',
    'GROUP',
    'GROUPS',
    'HAVING',
    'IF',
    'IGNORE',
    'IMMEDIATE',
    'IN',
    'INDEX',
    'INDEXED',
    'INITIALLY',
    'INNER',
    'INSERT',
    'INSTEAD',
    'INTERSECT',
    'INTO',
    'IS',
    'ISNULL',
    'JOIN',
    'KEY',
    'LAST',
    'LEFT',
    'LIKE',
    'LIMIT',
    'MATCH',
    'MATERIALIZED',
    'NATURAL',
    'NO',
    'NOT',
    'NOTHING',
    'NOTNULL',
    'NULL',
    'NULLS',
    'OF',
    'OFFSET',
    'ON',
    'OR',
    'ORDER',
    'OTHERS',
    'OUTER',
    'OVER',
    'PARTITION',
    'PLAN',
    'PRAGMA',
    'PRECEDING',
    'PRIMARY',
    'QUERY',
    'RAISE',
    'RANGE',
    'RECURSIVE',
    'REFERENCES',
    'REGEXP',
    'REINDEX',
    'RELEASE',
    'RENAME',
    'REPLACE',
    'RESTRICT',
    'RETURNING',
    'RIGHT',
    'ROLLBACK',
    'ROW',
    'ROWS',
    'SAVEPOINT',
    'SELECT',
    'SET',
    'TABLE',
    'TEMP',
    'TEMPORARY',
    'THEN',
    'TIES',
    'TO',
    'TRANSACTION',
    'TRIGGER',
    'UNBOUNDED',
    'UNION',
    'UNIQUE',
    'UPDATE',
    'USING',
    'VACUUM',
    'VALUES',
    'VIEW',
    'VIRTUAL',
    'WHEN',
    'WHERE',
    'WINDOW',
    'WITH',
    'WITHOUT',
    ];

    public function sqliteExport($database_id){

        //get database
        $database = DataBase::find($database_id);

        $dbName = $database->name . time() . ".db";

        $sqlite = new JSON2SQL($dbName , '');

        $sqlite->debugMode(false);


        $tables = $database->tables;


        foreach ($tables as $table){




            //get table schema

            $columns = $table->columns;

            $schema = [];

            foreach ($columns as $column){

                //quote reserved sql words
                if(in_array(strtoupper($column->name), $this->reservedWords)){
                    $column->name = '"' . $column->name . '"';
                }

                $type = '';
                if (in_array($column->type, array('text', 'tel', 'email', 'url' ,'date')) ){
                    $type = (object) [
                        $column->name => 'text',
                    ];
                    array_push($schema,$type);
                }elseif (in_array($column->type, array('number', 'checkbox','relation' ))){
                    $type = (object) [
                        $column->name => 'integer',
                    ];
                    array_push($schema,$type);
                }



            }

            $schema = json_encode($schema);

            $sqlite->createTable($schema, $table->name);

            if ($table->data){


                $rows = [];

                foreach (json_decode($table->data) as $row){

                    unset($row->id);

                    array_push($rows, $row);

                }

                $sqlite->selectTable($table->name);
                $sqlite->add(json_encode($rows, JSON_NUMERIC_CHECK));
            }

        }

        return $dbName;



    }


    public function jsonExport($database_id){

        //get database
        $database = DataBase::find($database_id);

        $tables = $database->tables;

        $jsonExport = [];

        foreach ($tables as $table){

            //CP:it is added as string
            $parentRows = json_decode($table->data);


            $tableName = $table->name;

            array_push($jsonExport , [
                'tableName' => $tableName,
                'rows' => $parentRows
            ]);

        }


        return json_encode($jsonExport,JSON_NUMERIC_CHECK);


    }



    //transfer to update table form

//    function validateData($data){
//
//        //validate date by table columns
//
//    }


    public function web(Request $request) {

//        $exportType = $request['exportType'];
//
//        $db_id = $request['db_id'];
//
//        if ($exportType === 'json') {
//            $data = $this->jsonExport($db_id);
//        }
//
        $data = $this->jsonExport(1);

//        $data = 'test';

        return inertia('Export', compact('data'));
    }



}
