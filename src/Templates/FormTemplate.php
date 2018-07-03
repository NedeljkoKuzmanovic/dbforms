<?php

namespace NedeljkoKuzmanovic\DbForms\Templates;

class FormTemplate {
    private $table_name,$table_data, $properties, $methods;

    public function __construct($table_name, $table_data){
        $this->table_name = $table_name;
        $this->table_data = $table_data;
        $this->preparePropertiesAndMethods();
    }
    /**
     * [getTemplate description]
     * @return [type] [description]
     */
    public function getTemplate(){
        return $this->generateForm();
    }
    /**
     * [generateForm description]
     * @return String [description]
     */
    private function generateForm() : String{
        $className = $this->getClassName();
        return trim("
            <?php \n\n".

            "namespace App\Database\Forms\\$className;\n\n".

            "use App\Database\Forms\Form;\n\n".

            "class {$className}Form extends Form {\n\n".

            "    $this->properties ;\n".

            "    $this->methods\n".
            "}"
        );
    }
    /**
     * [preparePropertiesAndMethods description]
     */
    private function preparePropertiesAndMethods() : void {
        $this->properties = null;
        $this->methods = "";
        foreach ($this->table_data as $column_name => $type) {
            if(empty($this->properties)){
                $this->properties = "public $" . $column_name;
            }else{
                $this->properties .= ", $" . $column_name;
            }

            $this->prepareMethod($column_name, $type['type']);
        }
    }
    /**
     * [prepareMethod description]
     * @param [type] $column_name [description]
     * @param [type] $type        [description]
     */
    private function prepareMethod($column_name, $type) : void {
        $method_name = 'set' . ucwords($column_name);
        $property = '$'.$column_name;
        $this->methods .= "

        /**
        * [$method_name description]
        * @param [type] $property [description]
        */
        public function $method_name($property) : void {
            \$this->{$column_name} = {$this->sanatize($property, $type)};
        }";

        $method_name = null; $property = null;
    }

    /**
     * [getClassName description]
     * @return String [description]
     */
    private function getClassName() : String{
        if(empty($this->table_name)){
            throw new \Exception("Table name is not defined", 400);
        }

        return str_replace('_', '', ucwords($this->table_name, '_'));
    }

    /**
     * [sanatize description]
     * @param  [type] $property [description]
     * @param  [type] $type     [description]
     * @return String           [description]
     */
    private function sanatize($property, $type) : String{
        switch($type){
            case 'int':
                return "(int)$property";
            case 'varchar':
                return "(string)$property";
            case 'double':
                return "floatval($property)";
            default :
                return $property;
        }
    }
}
 ?>
