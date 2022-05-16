<?php

require_once(dirname(__FILE__)."/../../common.php");

function getConnection()
{
    require(dirname(__FILE__)."/../../config.php");
    return new PDO($dsn, $username, $password, $options);
}

class Record
{
    protected static $table_name = "default_table_name";
    protected static $has_many = array();
    protected static $belongs_to = array();
    protected static $has_one = array();

    protected $is_new_record = true;
    // Stores arrays of entities for each association (or entity for 1-to-1
    // relationships)
    protected $associations = array();
    // Stores booleans to remember if each association has been loaded from the database
    protected $associations_are_loaded = array();

    // Loads a record and marks it as not new, i.e.
    // it will be treated as a record that has already
    // been saved to the database.
    protected static function loadRecordFromData($data)
    {
        $class = get_called_class();
        $obj = new $class();

        foreach (array_keys($data) as $attr) {
            // $obj->$attr = $data[$attr];
            $obj->$attr = $data[$attr];
        }
        $obj->is_new_record = false;
        return $obj;
    }

    public static function getAll()
    {
        return get_called_class()::where(array(1=>1));
    }

    /*
        Find all attributes that match
    */
    public static function where($attrs)
    {
        $table_name = get_called_class()::$table_name;
        $sql_wheres = array();
        foreach ($attrs as $key => $value) {
            if (is_array($value)) {
                array_push($sql_wheres, sprintf("%s in (%s)", $key, join(",", $value)));
            } else {
                array_push($sql_wheres, sprintf("%s = '%s'", $key, $value));
            }
        }

        $sql = sprintf(
            "SELECT * FROM %s WHERE %s;",
            $table_name,
            implode(" AND ", $sql_wheres)
        );

        $statement = getConnection()->prepare($sql);
        $statement->execute();
        $res = $statement->fetchAll();

        return array_map([get_called_class(), 'loadRecordFromData'], $res);
    }

    /*
        Returns the first record that matches
    */
    public static function find_by($attrs)
    {
        $table_name = get_called_class()::$table_name;
        $sql_wheres = array();
        foreach ($attrs as $key => $value) {
            if (is_array($value)) {
                array_push($sql_wheres, sprintf("%s in (%s)", $key, join(",", $value)));
            } else {
                array_push($sql_wheres, sprintf("%s = '%s'", $key, $value));
            }
        }

        $sql = sprintf(
            "SELECT * FROM %s WHERE %s LIMIT 1;",
            $table_name,
            implode(" AND ", $sql_wheres)
        );

        $statement = getConnection()->prepare($sql);
        $statement->execute();
        $res = $statement->fetchAll();

        if (count($res) > 0) {
            return get_called_class()::loadRecordFromData($res[0]);
        } else {
            return null;
        }
    }

    public function delete($pk="id") // Deletes by assuming $id is always the pk. Not sure if robust enough.
    {
        // Check to see if we are deleting a record that has not been saved yet.
        if ($this->is_new_record) {
            throw new ErrorException("Cannot delete an object that is not yet in a table. Please set the object to null instead.");
        }

        $attrs = $this->getAttrs();

        // Ensure that the pk exists
        if (!in_array($pk, $attrs)) {
            throw new ErrorException("Record trying to delete using a primary key that does not exist: ".$pk);
        }

        $result = get_called_class()::where(array($pk=>$this->$pk));
        // Make sure that only one record with the supplied key exists in the table
        if (count($result) > 1) {
            throw new ErrorException("Primary key specified is not valid as it returns more than one record. PK: ".$pk);
        }

        if (is_numeric($pk) and !is_string($pk)) {
            $condition = sprintf("%s = %s", $pk, $this->$pk);
        } else {
            $condition = sprintf("%s = '%s'", $pk, $this->$pk);
        }

        $sql = sprintf(
            "DELETE FROM %s WHERE %s",
            get_called_class()::$table_name,
            $condition
        );

        $statement = getConnection()->prepare($sql);
        $statement->execute();

        // Set all attributes of the called object to none
        foreach ($attrs as $attr) {
            $this->$attr = null;
        }

        return;
    }

    public static function getAttrs()
    {
        $class = get_called_class();
        $obj = new $class();
        $reflect = new ReflectionClass($obj);
        // Assume that public properties are attributes of the record
        $props = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);

        $f = function ($value) {
            return $value->getName();
        };

        return array_map($f, $props);
    }

    // Saves a record to the database

    public function save()
    {
        if (!$this->is_new_record) {
            return $this->update();
        }

        $new_obj = array();

        foreach (get_called_class()::getAttrs() as $attr) {
            if (isset($this->$attr) && !is_null($this->$attr)) {
                $new_obj[$attr] = $this->$attr;
            }
        }

        $new_obj['created_at'] = date('Y-m-d H:i:s');
        $new_obj['updated_at'] = date('Y-m-d H:i:s');

        $sql = sprintf(
            "INSERT INTO %s (%s) values (%s)",
            $this::$table_name,
            implode(", ", array_keys($new_obj)),
            ":" . implode(", :", array_keys($new_obj))
        );
        $conn = getConnection();
        $statement = $conn->prepare($sql);
        $statement->execute($new_obj);
        if (in_array("id", get_called_class()::getAttrs())) {
            $this->id = $conn->lastInsertId();
        }
        $this->is_new_record = false;

        // TODO: Fix n+1 saving
        foreach (get_called_class()::$has_many as $association_name => $association_values) {
            $foreign_key = $association_values['foreign_key'];

            if (array_key_exists($association_name, $this->associations)) {
                foreach ($this->associations[$association_name] as $obj) {
                    $obj->$foreign_key = $this->id;
                    $obj->save();
                }
            }
        }

        foreach (get_called_class()::$has_one as $association_name => $association_values) {
            $foreign_key = $association_values['foreign_key'];

            if (array_key_exists($association_name, $this->associations)) {
                $this->associations[$association_name]->$foreign_key = $this->id;
                $this->associations[$association_name]->save();
            }
        }
    }
    private function update($pk='id')
    {
        // Get attributes of the called object
        $attrs = $this->getAttrs();

        // Ensure that the pk exists
        if (!in_array($pk, $attrs)) {
            throw new ErrorException("Record trying to update using a primary key that does not exist: ".$pk);
        }

        // Get the record with the primary key from the table
        $old = get_called_class()::find_by(array($pk => $this->$pk));

        // See which attributes are different from the one in the table to object
        $to_update = [];
        foreach ($attrs as $attr) {
            if ($this->$attr !== $old->$attr) {
                $to_update[$attr] = $this->$attr;
            }
        }

        if (empty($to_update)) {
            return;
        }

        // update the differing fields
        $update_fields = array();
        $where_condition = sprintf("%s = %s", $pk, $this->$pk);
        foreach ($to_update as $key => $value) {
            if (is_string($value)) {
                $value = sprintf("'%s'", $value);
            }
            array_push($update_fields, sprintf("%s = %s", $key, $value));
        }
        $sql = sprintf(
            "UPDATE %s SET %s WHERE %s",
            get_called_class()::$table_name,
            implode(', ', $update_fields),
            $where_condition
        );
        $statement = getConnection()->prepare($sql);
        $statement->execute();
        return;
    }

    public static function get_table_name()
    {
        return get_called_class()::$table_name;
    }

    public function __get($name)
    {
        if (array_key_exists($name, get_called_class()::$has_many)) {
            if (isset($this->associations_are_loaded[$name])
            && $this->associations_are_loaded[$name]) {
                return $this->associations[$name];
            }

            $association = get_called_class()::$has_many[$name];

            $data = $association['class_name']::where(
                array($association['foreign_key']=>$this->id)
            );

            $this->associations[$name] = $data;
            $this->associations_are_loaded[$name] = true;

            return $data;
        } elseif (array_key_exists($name, get_called_class()::$belongs_to)) {
            if (isset($this->associations_are_loaded[$name])
            && $this->associations_are_loaded[$name]) {
                return $this->associations[$name];
            }

            $association = get_called_class()::$belongs_to[$name];
            $foreign_key = $association['foreign_key'];

            $data = $association['class_name']::find_by(
                array("id"=>$this->$foreign_key)
            );

            $this->associations[$name] = $data;
            $this->associations_are_loaded[$name] = true;

            return $data;
        } elseif (array_key_exists($name, get_called_class()::$has_one)) {
            if (isset($this->associations_are_loaded[$name])
            && $this->associations_are_loaded[$name]) {
                return $this->associations[$name];
            }

            $association = get_called_class()::$has_one[$name];
            $foreign_key = $association['foreign_key'];

            $data = $association['class_name']::find_by(
                array($foreign_key=>$this->id)
            );

            $this->associations[$name] = $data;
            $this->associations_are_loaded[$name] = true;

            return $data;
        }

        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE
        );
        return null;
    }

    public function __set($name, $val)
    {
        if (preg_match('/\d+/', $name)) {
            return;
        }

        // Make it easier to access has_many, belongs_to and has_one associations
        if (array_key_exists($name, get_called_class()::$has_many)
            || array_key_exists($name, get_called_class()::$belongs_to)
            || array_key_exists($name, get_called_class()::$has_one)) {
            $this->associations[$name] = $val;
            $this->associations_are_loaded[$name] = true;

            return;
        }

        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __set(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE
        );
        return null;
    }

    public static function __callStatic($name, $arguments)
    {
        if (startsWith($name, "find_by_")) {
            preg_match('/find_by_(\w+)/', $name, $match);
            return get_called_class()::find_by(array($match[1]=>$arguments[0]));
        }

        $trace = debug_backtrace();
        trigger_error(
            'Undefined function via __call(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE
        );
    }
}

spl_autoload_register(function ($class_name) {
    // Convert camel case to snake case
    require_once strtolower(preg_replace('/([^A-Z])([A-Z])/', "$1_$2", $class_name)) . '.php';
});
