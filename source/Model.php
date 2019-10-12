<?php
namespace source;
class Model
{

    private $connection = null;
    private $statement = null;
    protected $_where;
    protected $_join;
    protected $_groupby;
    protected $_orderby;
    protected $_query;
    protected $_select;
    protected $_limit;

    public function __construct($hostname = 'localhost', $username = DB_USERNAME, $password = DB_PASSWORD, $database = DB_NAME, $port = '3306') {

        $this->_select = '*';
        try {
            $this->connection = new \PDO("mysql:host=" . $hostname . ";port=" . $port . ";dbname=" . $database, $username, $password, array(\PDO::ATTR_PERSISTENT => true));
        } catch(\PDOException $e) {
            throw new \Exception('Failed to connect to database. Reason: \'' . $e->getMessage() . '\'');
        }

        $this->connection->exec("SET NAMES 'utf8'");
        $this->connection->exec("SET CHARACTER SET utf8");
        $this->connection->exec("SET CHARACTER_SET_CONNECTION=utf8");
        $this->connection->exec("SET SQL_MODE = ''");
    }

    protected function prepare($sql) {
        $this->statement = $this->connection->prepare($sql);
    }

    protected function bindParam($parameter, $variable, $data_type = \PDO::PARAM_STR, $length = 0) {
        if ($length) {
            $this->statement->bindParam($parameter, $variable, $data_type, $length);
        } else {
            $this->statement->bindParam($parameter, $variable, $data_type);
        }
    }

    protected function execute() {
        try {
            if ($this->statement && $this->statement->execute()) {
                $data = array();

                while ($row = $this->statement->fetch(\PDO::FETCH_ASSOC)) {
                    $data[] = $row;
                }

                $result = new \stdClass();
                $result->row = (isset($data[0])) ? $data[0] : array();
                $result->rows = $data;
                $result->num_rows = $this->statement->rowCount();
            }
        } catch(\PDOException $e) {
            throw new \Exception('Error: ' . $e->getMessage() . ' Error Code : ' . $e->getCode());
        }
    }

    protected function queryProtect($sql, $params = array()) {
        $this->statement = $this->connection->prepare($sql);

        $result = false;

        try {
            if ($this->statement && $this->statement->execute($params)) {
                $data = array();

                while ($row = $this->statement->fetch(\PDO::FETCH_ASSOC)) {
                    $data[] = $row;
                }

                $result = new \stdClass();
                $result->row = (isset($data[0]) ? $data[0] : array());
                $result->rows = $data;
                $result->num_rows = $this->statement->rowCount();
            }
        } catch (\PDOException $e) {
            throw new \Exception('Error: ' . $e->getMessage() . ' Error Code : ' . $e->getCode() . ' <br />' . $sql);
        }

        if ($result) {
            return $result;
        } else {
            $result = new \stdClass();
            $result->row = array();
            $result->rows = array();
            $result->num_rows = 0;
            return $result;
        }
    }

    protected function escape($value) {
        return str_replace(array("\\", "\0", "\n", "\r", "\x1a", "'", '"'), array("\\\\", "\\0", "\\n", "\\r", "\Z", "\'", '\"'), $value);
    }

    protected function countAffected() {
        if ($this->statement) {
            return $this->statement->rowCount();
        } else {
            return 0;
        }
    }

    public function getLastId() {
        return $this->connection->lastInsertId();
    }

    protected function isConnected() {
        if ($this->connection) {
            return true;
        } else {
            return false;
        }
    }
    public function where($row,$operator = '=',$search,$type = 'AND'){
        if(strstr($this->_where,'WHERE')){
            $this->_where .=" {$this->escape($type)} `{$this->escape($row)}`{$this->escape($operator)}'{$this->escape($search)}'";
        }else{
            $this->_where .=" WHERE `{$this->escape($row)}`{$this->escape($operator)}'{$this->escape($search)}'";
        }
        return $this;
    }
    public function join($second_table = '',$first_id = '',$second_id = '',$type='INNER'){
        if($first_id == ''){
            $first_id = 'id';
        }
        if($second_id == ''){
            $second_id = $this->table.'_id';
        }
        $this->_join = " {$this->escape($type)} JOIN `{$this->escape($second_table)}` ON {$this->table}.{$this->escape($first_id)} = {$this->escape($second_table)}.{$this->escape($second_id)}";
        return $this;
    }
    public function groupBy($column = ''){
        $this->_groupby = " GROUP BY {$this->escape($column)}";
        return $this;
    }
    public function orderBy($column = '',$type ='DESC'){
        $this->_orderby = " ORDER BY {$this->escape($column)} {$this->escape($type)}";
        return $this;
    }
    public function limit($limit){
        $this->_limit = " LIMIT {$this->escape($limit)}";
        return $this;
    }
    public function select($select){
        $this->_select = " {$this->escape($select)} ";
        return $this;
    }
    public function query($query = ''){
        $this->_query = $query;
        return $this;
    }
    protected function mergeWhereQuery(){
        if($this->_query == ''){
            $this->_query = "SELECT {$this->_select} FROM `{$this->table}`".$this->_join.$this->_where.$this->_groupby.$this->_orderby.$this->_limit;
        }
        return $this;
    }
    protected function mergeUpdateQuery($statements = ''){
        if($this->_query == ''){
            $this->_query = "UPDATE `{$this->table}` SET {$statements} ".$this->_join.$this->_where.$this->_groupby.$this->_orderby.$this->_limit;
        }
        return $this;
    }
    protected function mergeDeleteQuery(){
        if($this->_query == ''){
            $this->_query = "DELETE FROM `{$this->table}` ".$this->_join.$this->_where.$this->_groupby.$this->_orderby.$this->_limit;
        }
        return $this;
    }
    public function get($type = 'array'){

        $query = $this->queryProtect($this->mergeWhereQuery()->_query);
        if($type == 'object'){
            $this->resetClass();
            return json_decode(json_encode($query->rows));
        }else{
            $this->resetClass();
            return $query->rows;
        }
    }
    public function first($type = 'array'){
        $query = $this->queryProtect($this->mergeWhereQuery()->_query);
        if($type == 'object'){
            $this->resetClass();
            return json_decode(json_encode($query->row));
        }else{
            $this->resetClass();
            return $query->row;
        }
    }
    public function count($row = '*'){
        $this->select('COUNT('.$row.') as count');
        $query = $this->queryProtect($this->mergeWhereQuery()->_query);
        $this->resetClass();
        return $query->row['count'];
    }
    public function random($count = 0){
        $this->orderBy('','RAND()');
        $this->limit($this->escape('0,'.$count));
        if($count == 1){
            return $this->first();
        }else{
            return $this->get();
        }
    }
    public function insert($rows = []){
        $insert = '';
        foreach ($rows as $row => $value){
            $insert .= '`'.$this->escape($row).'`=\''.$this->escape($value).'\',';
        }
        $insert = substr($insert,0,-1);
        $query = $this->queryProtect("INSERT INTO {$this->table} SET {$insert}");

        if($query){
            $this->resetClass();
            return $this->getLastId();
        }else{
            $this->resetClass();
            return false;
        }
    }
    public function update($rows = []){
        $update = '';
        foreach ($rows as $row => $value){
            $update .= '`'.$this->escape($row).'`=\''.$this->escape($value).'\',';
        }
        $update = substr($update,0,-1);
        $query = $this->queryProtect($this->mergeUpdateQuery($update)->_query);
        if($query){
            $this->resetClass();
            return true;
        }else{
            $this->resetClass();
            return false;
        }
    }
    public function delete(){
        $query = $this->queryProtect($this->mergeDeleteQuery()->_query);
        if($query){
            $this->resetClass();
            return true;
        }else{
            $this->resetClass();
            return false;
        }
    }
    protected function resetClass(){
        $this->_where = null;
        $this->_join = null;
        $this->_groupby = null;
        $this->_orderby = null;
        $this->_query = null;
        $this->_select = '*';
        $this->_limit = null;
    }
    public function __destruct() {
        $this->connection = null;

    }
}