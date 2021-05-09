<?php


namespace app\core;

use \PDO;
use \PDOException;

class QueryBuider
{
    private $conn;
    private $columns;
    private $from;
    private $distinct = false;
    private $joins;
    private $wheres;
    private $groups;
    private $havings;
    private $orders;
    private $limit;
    private $offset;

    public function __construct($tableName)
    {
        $this->from = $tableName;
        $this->conn = Registry::getIntance()->conn;
    }

    public static function table($tableName)
    {
        return new self($tableName);
    }

    public function select($cols)
    {
        $this->columns = is_array($cols) ? $cols : func_get_args();
        return $this;
    }

    public function distinct()
    {
        $this->distinct = true;
        return $this;
    }

    public function join($table, $first, $operator, $second, $type = 'INNER')
    {
        $this->joins[] = [$table, $first, $operator, $second, $type];
        return $this;
    }

    public function leftJoin($table, $first, $operator, $second)
    {
        return $this->join($table, $first, $operator, $second, 'LEFT');
    }

    public function rightJoin($table, $first, $operator, $second)
    {
        return $this->join($table, $first, $operator, $second, 'RIGHT');
    }

    public function where($column, $operator, $value, $logicalOperator = 'AND')
    {
        $this->wheres[] = [$column, $operator, $value, $logicalOperator];
        return $this;
    }

    public function orWhere($column, $operator, $value)
    {
        return $this->where($column, $operator, $value, 'OR');
    }

    public function groupBy($columns)
    {
        $this->groups = is_array($columns) ? $columns : func_get_args();
        return $this;
    }


    public function having($column, $operator, $value, $logicalOperator = 'AND')
    {
        $this->havings[] = [$column, $operator, $value, $logicalOperator];
        return $this;
    }

    public function orHaving($column, $operator, $value)
    {
        return $this->having($column, $operator, $value, 'OR');
    }

    public function orderBy($column, $direction = 'ASC')
    {
        $this->orders[] = [$column, $direction];
        return $this;
    }

    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    private function addWhere($sql)
    {
        $sql .= " WHERE";
        foreach ($this->wheres as $wk => $where) {
            $sql .= " $where[0] $where[1] $where[2]";
            if ($wk < count($this->wheres) - 1) {
                $sql .= " $where[3]";
            }
        }
        return $sql;
    }

    public function get()
    {
        if (!isset($this->from) || empty($this->from)) {
            return false;
        }
        $sql = $this->distinct ? 'SELECT DISTINCT ' : 'SELECT ';
        if (isset($this->columns) && is_array($this->columns)) {
            $sql .= implode(', ', $this->columns);
        } else {
            $sql .= '*';
        }
        $sql .= ' FROM ' . $this->from;

        if (isset($this->joins) && is_array($this->joins)) {
            foreach ($this->joins as $join) {
                $sql .= " $join[4] JOIN $join[0] ON $join[1] $join[2] $join[3]";
            }
        }

        if (isset($this->wheres) && is_array($this->wheres)) {
            $sql = $this->addWhere($sql);
        }

        if (isset($this->groups) && is_array($this->groups)) {
            $sql .= ' GROUP BY ' . implode(', ', $this->groups);
        }

        if (isset($this->havings) && is_array($this->havings)) {
            $sql .= " HAVING";
            foreach ($this->havings as $hk => $having) {
                $sql .= " $having[0] $having[1] $having[2]";
                if ($hk < count($this->havings) - 1) {
                    $sql .= " $having[3]";
                }
            }
        }

        if (isset($this->orders) && is_array($this->havings)) {
            $sql .= " ORDER BY";
            foreach ($this->orders as $ok => $order) {
                $sql .= " $order[0] $order[1]";
                if ($ok < count($this->orders) - 1) {
                    $sql .= ",";
                }
            }
        }
        if (isset($this->limit)) {
            $sql .= " LIMIT $this->limit";
        }
        if (isset($this->offset)) {
            $sql .= " OFFSET $this->offset";
        }

        $stmt = $this->conn->prepare("$sql;");
//
//        $stmt = $this->conn->query("$sql;");
//Thiết lập kiểu dữ liệu trả về
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
//
//Gán giá trị và thực thi
        $execute = $stmt->execute();
        if ($execute === false) {
            return new AppException("$execute thất bại!");
        }

//Hiển thị kết quả, vòng lặp sau đây sẽ dừng lại khi đã duyệt qua toàn bộ kết quả
        $data = [];
        while ($row = $stmt->fetch()) {
            $data[] = $row;
        }
        return json_encode($data);
    }

    public function insert($data)
    {
        if (!isset($this->from) || empty($this->from)) {
            return false;
        }
        $columns = implode(', ', array_keys($data));
        $values = implode(', ', $data);
        $sql = "INSERT INTO $this->from ($columns) VALUES ($values);";
        return $sql;
    }

    public function update($data)
    {
        if (!isset($this->from) || empty($this->from)) {
            return false;
        }
        $dataUpdate = [];
        foreach ($data as $dk => $val) {
            $dataUpdate[] = "$dk = $val";
        }
        $sql = "UPDATE $this->from SET " . implode(', ', $dataUpdate);

        if (isset($this->wheres) && is_array($this->wheres)) {
            $sql = $this->addWhere($sql);
        }
        return "$sql;";
    }

    public function delete()
    {
        if (!isset($this->from) || empty($this->from)) {
            return false;
        }
        $sql = "DELETE FROM $this->from";
        if (isset($this->wheres) && is_array($this->wheres)) {
            $sql = $this->addWhere($sql);
        }
        return "$sql;";
    }
}