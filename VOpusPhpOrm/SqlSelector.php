<?php
namespace VOpus\PhpOrm;

require_once __DIR__ . '/DataEntity.php';

class SqlSelector
{
    public const RETURN_ALL_ASSOC = 0;
    public const RETURN_SINGLE_ASSOC = 1;
    public const RETURN_ALL_NUM = 2;
    public const RETURN_SINGLE_NUM = 3;
    public const RETURN_FIRST_COLUMN_VALUE = 4;

    private array $selectColumns = [];
    private string $fromTable;
    private array $joins = [];
    private array $whereClauses = [];
    private string $groupBy;
    private string $orderBy;
    private string $limit;
    private array $values = [];
    private string $bindParamTypes = "";

    public function __construct()
    {
    }

    public function setTable($table)
    {
        $this->fromTable = $table;
        return $this;
    }

    public function addSelectColumn($col)
    {
        $this->selectColumns[] = $col;
        return $this;
    }

    public function removeSelectColumn($col)
    {
        if ($i = array_search($col, $this->selectColumns))
            if ($i !== false)
                array_splice($this->selectColumns, $i);

        return $this;
    }

    public function addJoin($join)
    {
        $this->joins[] = $join;
        return $this;
    }

    public function removeJoin($join)
    {
        if ($i = array_search($join, $this->joins))
            if ($i !== false)
                array_splice($this->joins, $i);
        return $this;
    }

    public function addWhereClause($where)
    {
        $this->whereClauses[] = $where;
        return $this;
    }

    public function removeWhereClause($where)
    {
        if ($i = array_search($where, $this->whereClauses))
            if ($i !== false)
                array_splice($this->whereClauses, $i);
        
        return $this;
    }

    public function setGroupBy($groupBy)
    {
        $this->groupBy = $groupBy;
        return $this;
    }

    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function addValue(string $bindParamType, $value)
    {
        $this->bindParamTypes .= $bindParamType;
        $this->values[] = $value;
        return $this;
    }

    public function addValues(string $bindParamTypes, array $valuesArray)
    {
        $this->bindParamTypes .= $bindParamTypes;
        $this->values = [...$this->values, ...$valuesArray];
        return $this;
    }

    public function clearValues()
    {
        $this->bindParamTypes = "";
        $this->values = [];
        return $this;
    }

    public function clearWhereClauses()
    {
        $this->whereClauses = [];
        return $this;
    }

    public function hasWhereClauses()
    {
        return count($this->whereClauses) > 0;
    }

    public function run(\mysqli $conn, int $mode = self::RETURN_SINGLE_ASSOC)
    {
        $finalSql = "SELECT " . implode(',', $this->selectColumns) . " FROM " . $this->fromTable . ' ' .
        implode(' ', $this->joins) . ' ';
        
        if (!empty($this->whereClauses))
            $finalSql .= 'WHERE ' . implode(' ', $this->whereClauses) . ' '; 
        
        if (!empty($this->groupBy))
            $finalSql .= 'GROUP BY ' . $this->groupBy . ' '; 

        if (!empty($this->orderBy))
            $finalSql .= 'ORDER BY ' . $this->orderBy . ' ';

        if (!empty($this->limit))
            $finalSql .= 'LIMIT ' . $this->limit . ' ';

        $stmt = $conn->prepare($finalSql);
        if (strlen($this->bindParamTypes) > 0)
            $stmt->bind_param($this->bindParamTypes, ...$this->values);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        $output = null;
        switch ($mode)
        {
            case self::RETURN_ALL_ASSOC:
                $output = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];
                break;
            case self::RETURN_ALL_NUM:
                $output = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_NUM) : [];
                break;
            case self::RETURN_SINGLE_ASSOC:
                $output = $result->num_rows > 0 ? $result->fetch_assoc() : null;
                break;
            case self::RETURN_SINGLE_NUM:
                $output = $result->num_rows > 0 ? $result->fetch_row() : null;
                break;
            case self::RETURN_FIRST_COLUMN_VALUE:
            default:
                $output = $result->num_rows > 0 ? $result->fetch_row()[0] : null;
                break;
        }

        $result->close();
        return $output;
    }
}