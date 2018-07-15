<?php

namespace NedeljkoKuzmanovic\DbForms\Classes;

use Illuminate\Http\Response;
use NedeljkoKuzmanovic\DbForms\Templates\FormTemplate;
use Illuminate\Support\Facades\DB;

class Generator {
    /**
     * Location toward folder where forms will be contained
     * @var [type]
     */
    static private $forms_folder_location = "app/Database/Forms/";
    /**
     * Names of tables wich for form will not be generated
     * @var [type]
     */
    static private $exception_table_list = array('migrations');

    /**
     * Configures all important variables
     * @return [type] [description]
     */
    private static function configure(){
        self::$exception_table_list = config('nedeljko-kuzmanovic.dbforms.ignored_tables') ?? array('migrations');
        self::$forms_folder_location = config('nedeljko-kuzmanovic.dbforms.forms_location') ??"app/Database/Forms/";
    }
    /**
     * Generates form classes for all database tables
     * @return [type] [description]
     */
    public static function generateAllForms() : Response {
        self::configure();

        $database = self::getDatabaseData();

        foreach($database as $table_name => $table_data){
            if(!self::inExceptionList($table_name)){
                self::createTableForm($table_name, $table_data);
            }
        }

        return new Response('Forms created successfully.');
    }

    /**
     * [createTableForm description]
     * @param  [type] $table_name [description]
     * @param  [type] $table_data [description]
     * @return [type]             [description]
     */
    public static function createTableForm($table_name, $table_data){
        $template = new FormTemplate($table_name, $table_data);

        self::generateFormFolder($table_name);
        $filename = self::$forms_folder_location . "/" . self::getFolderName($table_name) . "/" . self::getFileName($table_name);

        $file = fopen("{$filename}", "w+") or die("Unable to open file!");
        fwrite($file, $template->getTemplate());
        fclose($file);
    }
    /**
     * [inExceptionList description]
     * @param  [type] $table_name [description]
     * @return bool               [description]
     */
    private static function inExceptionList($table_name) : bool{
        foreach(self::$exception_table_list as $exception){
            if($table_name == $exception){
                return true;
            }
        }

        return false;
    }

    /**
     * Get database data, all tables with ther required columns data
     * Array is structured this way table_name(each)->column_name(each)->column_propery(each)
     *
     * @return Array [description]
     */
    private static function getDatabaseData() : Array {
        $columns = self::getDatabaseColumns();
        $tables = [];

        foreach($columns as $column){
            if(!isset($tables[$column->table]))
                $tables[$column->table] = [];

            $tables[$column->table][$column->name] = [
                "type" => $column->type
            ];
        }

        $columns = null;

        return $tables;
    }

    /**
     * Get all database columns
     * @return Array [description]
     */
    private static function getDatabaseColumns() : Array {
        $db_name = env('DB_DATABASE');

        return DB::select("SELECT S.`TABLE_NAME` as `table`, S.`COLUMN_NAME` as `name`, S.`DATA_TYPE` as type FROM INFORMATION_SCHEMA.COLUMNS S WHERE TABLE_SCHEMA = '$db_name' ORDER BY `table`");
    }

    /**
     * [generateFormFolder description]
     * @param [type] $table_name [description]
     */
    private static function generateFormFolder($table_name) : void {
        $path = self::$forms_folder_location . "/" . self::getFolderName($table_name);
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
    }

    /**
     * [getFileName description]
     * @param  [type] $table_name [description]
     * @return String             [description]
     */
    private static function getFileName($table_name) : String{
        return self::getFolderName($table_name) . "Form.php";
    }

    /**
     * [getFolderName description]
     * @param  [type] $table_name [description]
     * @return String             [description]
     */
    private static function getFolderName($table_name) : String {
        if(empty($table_name)){
            throw new \Exception("Table name is not defined", 400);
        }

        return str_replace('_', '', ucwords($table_name, '_'));
    }


}
