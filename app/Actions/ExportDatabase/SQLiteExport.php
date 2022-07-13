<?php

namespace App\Actions\ExportDatabase;

use App\Http\Controllers\JSON2SQL;
use App\Models\DataBase;

class SQLiteExport
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


    public function execute($database_id){

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

            $relationColumnsNames = [];

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

                    //collect relation columns
                    if ($column->type === 'relation'){
                        array_push($relationColumnsNames, $column->name);
                    }
                }



            }

            $schema = json_encode($schema);

            $sqlite->createTable($schema, $table->name);

            if ($table->data){

                $rows = [];

                foreach (json_decode($table->data, true) as $row){

//                    unset($row->id);
//
//
//                    foreach (array_keys((array)$row) as $item){
//                        if (in_array($item, $relationColumnsNames)){
//                            //get index of relation
//                            $relationColumn = $columns->where('name', $item)->first();
//
//                            $parentTable = Table::where('data_base_id', $table->data_base_id)->where('name',$relationColumn->relationTable)->first();
//
//                            $parentRows = $parentTable->data;
//
//                            $parentRowId = $row[$item];
//
//                            $counter = 0;
//                            foreach (json_decode($parentRows, true) as $parentRow){
//                                $counter += 1;
//
//                                if ($parentRow['id'] === $parentRowId) {
//                                    $row[$item] = $counter;
//                                }
//                            }
//
//                        }
//                    }

                    //add relation by index instead of id


//                    dd($row);

                    array_push($rows, $row);

                }

                $sqlite->selectTable($table->name);
                $sqlite->add(json_encode($rows, JSON_NUMERIC_CHECK));
            }

        }

        return $dbName;



    }



}
