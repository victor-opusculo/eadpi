<?php

namespace VOpus\PhpOrm;

require_once __DIR__ . '/SqlSelector.php';
require_once __DIR__ . '/DataProperty.php';
require_once __DIR__ . '/Exceptions/DatabaseEntityNotFound.php';

use mysqli;

#[\AllowDynamicProperties]
final class OtherProperties { }

abstract class DataEntity implements \IteratorAggregate, \JsonSerializable
{	
	protected object $properties;
	protected OtherProperties $otherProperties;
	
	protected array $primaryKeys = [];
	protected array $setPrimaryKeysValue = [];
	protected string $databaseTable = "";
	protected string $formFieldPrefixName = "";
	protected string $encryptionKey = "";
	protected ?array $postFiles;
	
	protected function __construct($initialValues = null)
	{
		$this->initializeWithValues($initialValues);
	}

	protected function initializeWithValues($dataRow)
	{
		if (!empty($dataRow))
			$this->fillPropertiesFromDataRow((array)$dataRow);
	}

	public function getIterator() : \Traversable
	{
		return new \ArrayIterator($this->properties);
	}
	
	public function __get($name)
	{
		if (!isset($this->properties->$name)) return null;
		return $this->properties->$name->getValue();
	}
	
	public function __set($name, $value)
	{
		if (!isset($this->properties->$name))
			$this->otherProperties->$name = $value;
		else
			$this->properties->$name->setValue($value);
	}

	#[\ReturnTypeWillChange]
	public function jsonSerialize()
	{
		$outputObj = [];

		foreach ($this->properties as $prop => $val)
			$outputObj[$prop] = $val;
		
		foreach ($this->otherProperties as $prop => $val)
			$outputObj[$prop] = $val;

		return $outputObj;
	}

	public function __isset($name)
	{
		if (isset($this->properties->$name))
		{
			$val = $this->properties->$name->getValue();
			return isset($val) && ($val instanceof Some);
		}
		
		return false;
	}
	
	public function afterDatabaseInsert(\mysqli $conn, $insertResult) { return $insertResult; }
	public function beforeDatabaseInsert(\mysqli $conn) : int { return 0; }
	
	public function afterDatabaseUpdate(\mysqli $conn, $updateResult) { return $updateResult; }
	public function beforeDatabaseUpdate(\mysqli $conn) : int { return 0; }
	
	public function afterDatabaseDelete(\mysqli $conn, $deleteResult) { return $deleteResult; }
	public function beforeDatabaseDelete(\mysqli $conn) : int { return 0; }
	
	public function getOtherProperties() : object
	{
		return $this->otherProperties;
	}

	public function getSingle(mysqli $conn) : static
	{
		$selector = $this->getGetSingleSqlSelector();
		$dataRow = $selector->run($conn, SqlSelector::RETURN_SINGLE_ASSOC);

		if (isset($dataRow)) 
			return $this->newInstanceFromDataRow($dataRow);
		else
			throw new Exceptions\DatabaseEntityNotFound('Dados não localizados!', $this->databaseTable);
	}

	public function save(mysqli $conn)
	{		
		$isUpdate = null;
		try
		{
			$isUpdate = null !== $this->getSingle($conn); 
			//array_reduce($this->primaryKeys, fn($carry, $pk) => $carry && !empty($this->$pk), true);
		}
		catch (Exceptions\DatabaseEntityNotFound $e)
		{
			$isUpdate = false;
		}
		
		$affectedRows = 0;
		$newId = null;

		if ($isUpdate)
		{
			$affectedRows += $this->beforeDatabaseUpdate($conn);
			
			$updateInfos = $this->getUpdateCommandInfos();
			$stmt = $conn->prepare("UPDATE {$this->databaseTable} SET $updateInfos[columnsAndFields] WHERE $updateInfos[whereClause] ");
			$stmt->bind_param($updateInfos['bindParamTypes'], ...$updateInfos['values']);
			$stmt->execute();
			$affectedRows += $stmt->affected_rows;
			$stmt->close();

			return $this->afterDatabaseUpdate($conn, ['affectedRows' => $affectedRows] );
		}
		else
		{
			$affectedRows += $this->beforeDatabaseInsert($conn);
			
			$insertInfos = $this->getInsertCommandInfos();
			$stmt = $conn->prepare("INSERT INTO {$this->databaseTable} ($insertInfos[columns]) VALUES ($insertInfos[fields]) ");
			$stmt->bind_param($insertInfos['bindParamTypes'], ...$insertInfos['values']);
			$stmt->execute();
			$affectedRows += $stmt->affected_rows;
			$newId = $conn->insert_id;
			$stmt->close();
			
			return $this->afterDatabaseInsert($conn, ['affectedRows' => $affectedRows, 'newId' => $newId] );
		}
	}

	public function delete(mysqli $conn)
	{
		$affectedRows = 0;

		$affectedRows += $this->beforeDatabaseDelete($conn);

		$deleteInfos = $this->getDeleteCommandInfos();
		$stmt = $conn->prepare("DELETE FROM {$this->databaseTable} WHERE $deleteInfos[whereClause] ");
		$stmt->bind_param($deleteInfos['bindParamTypes'], ...$deleteInfos['values']);
		$stmt->execute();
		$affectedRows += $stmt->affected_rows;
		$stmt->close();

		return $this->afterDatabaseDelete($conn, ['affectedRows' => $affectedRows] );
	}
	
	public function setCryptKey(string $key) : static
	{
		$this->encryptionKey = $key;
		return $this;
	}

	public function setPostFiles(?array $files) : static
	{
		$this->postFiles = $files;
		return $this;
	}

	public function setNone(string $propertyName) : static
	{
		$this->properties->$propertyName->setNone();
		return $this;
	}

	public function setDefault(string $propertyName) : static
	{
		$this->properties->$propertyName->resetValue();
		return $this;
	}
	
	public function fillPropertiesWithDefaultValues() : static
	{
		foreach ($this->properties as $po)
			$po->resetValue();
		return $this;
	}

	public function fillPropertiesFromDataRow($dataRow) : static
	{
		$this->otherProperties = new OtherProperties();

		foreach ($dataRow as $col => $val)
		{
			if (!isset($this->properties->$col))
				$this->otherProperties->$col = $val;
			else
				$this->properties->$col->setValue($val);
		}

		return $this;
	}

	public function fillPropertiesFromFormInput($post, $files = null) : static
	{
		$this->postFiles = $files;

		$foundEntityProperties = [];
		$this->otherProperties = new OtherProperties();

		foreach ($post as $formFieldName => $formFieldValue)
		{
			if ($colonPos = mb_strpos($formFieldName, ':'))
			{
				$formFieldPrefixName = mb_substr($formFieldName, 0, $colonPos);
				if ($formFieldPrefixName === $this->formFieldPrefixName)
				{
					$formFieldNameIdentifier = mb_substr($formFieldName, $colonPos + 1);
					$foundEntityProperties[$formFieldNameIdentifier] = $formFieldValue;
				}
			}
			else
			{
				$this->otherProperties->$formFieldName = $formFieldValue;
			}
		}
		
		foreach ($this->properties as $prop)
			$prop->fillFromFormInput($foundEntityProperties);

		return $this;
	}

	protected function newInstanceFromDataRow($dataRow) : static
	{
		return new static($dataRow);
	}
	
	protected function getGetSingleSqlSelector() : SqlSelector
	{
		$selector = new SqlSelector();
		$columnsAndValues = get_object_vars($this->properties);

		$isFirstWhereClause = true;
		foreach ($columnsAndValues as $propName => $propObject)
		{
			if ($propObject instanceof None)
				continue;

			$selector->addSelectColumn($this->getSelectQueryColumnName($propName));
			if (array_search($propName, $this->primaryKeys) !== false)
			{
				$selector->addWhereClause( $isFirstWhereClause ? $this->getSelectQueryColumnName($propName) . " = ? " : " AND " . $this->getSelectQueryColumnName($propName) . " = ? " );
				$selector->addValue($propObject->getBindParamType(), $propObject->getValueForDatabase());
				$isFirstWhereClause = false;
			}
		}

		$selector->setTable($this->databaseTable);

		return $selector;
	}

	protected function getInsertCommandInfos() : array
	{
		$columnsAndValues = get_object_vars($this->properties);
		$columns = [];
		$fields = [];
		$values = [];
		$bindParamTypes = "";
		
		foreach ($this->primaryKeys as $pk)
		{
			if (array_key_exists($pk, $columnsAndValues) === true && array_search($pk, $this->setPrimaryKeysValue) === false)
				unset($columnsAndValues[$pk]);
		}
		
		foreach ($columnsAndValues as $propName => $propObject)
		{
			if ($propObject->getValue() instanceof None)
				continue;

			$columns[] = $propName;
			$fields[] = $this->getQueryField($propObject);
			$values[] = $propObject->getValueForDatabase();
			$bindParamTypes .= $propObject->getBindParamType();
		}
		
		return [ 'columns' => implode(',', $columns), 'fields' => implode(',', $fields), 'values' => $values, 'bindParamTypes' => $bindParamTypes ];
	}
	
	protected function getUpdateCommandInfos() : array
	{
		$columnsAndValues = get_object_vars($this->properties);
		$columnsAndFields = [];
		$whereClause = "";
		$values = [];
		$whereClauseValues = [];
		$bindParamTypes = "";
		$whereClauseBindParamTypes = "";
		
		$whereClauseColsAndFields = [];
		foreach ($columnsAndValues as $propName => $propObject)
		{
			if ($propObject->getValue() instanceof None)
				continue;

			$columnsAndFields[] = $propName . '=' . $this->getQueryField($propObject);
			$values[] = $propObject->getValueForDatabase();
			$bindParamTypes .= $propObject->getBindParamType();
		
			if (array_search($propName, $this->primaryKeys) !== false)
			{
				$whereClauseColsAndFields[] = $propName . '=' . $this->getQueryField($propObject);
				$whereClauseValues[] = $propObject->getValueForDatabase();
				$whereClauseBindParamTypes .= $propObject->getBindParamType();
			}
		}

		$whereClause = implode(' AND ', $whereClauseColsAndFields);
		$values = [...$values, ...$whereClauseValues];
		$bindParamTypes = $bindParamTypes . $whereClauseBindParamTypes;

		return [ 'columnsAndFields' => implode(',', $columnsAndFields), 'whereClause' => $whereClause, 'values' => $values, 'bindParamTypes' => $bindParamTypes ];
	}

	protected function getDeleteCommandInfos() : array
	{
		$columnsAndValues = get_object_vars($this->properties);
		$whereClauseColsAndFields = [];
		$values = [];
		$bindParamTypes = "";

		foreach ($columnsAndValues as $propName => $propObject)
		{
			if ($propObject->getValue() instanceof None)
				continue;

			if (array_search($propName, $this->primaryKeys) !== false)
			{
				$whereClauseColsAndFields[] = $propName . '=' . $this->getQueryField($propObject);
				$values[] = $propObject->getValueForDatabase();
				$bindParamTypes .= $propObject->getBindParamType();
			}
		}

		$whereClause = implode(' AND ', $whereClauseColsAndFields);

		return [ 'whereClause' => $whereClause, 'values' => $values, 'bindParamTypes' => $bindParamTypes ];
	}

	protected function getQueryField(object $propObject) : string
	{
		return $propObject->getEncrypt() ? "aes_encrypt(?, '{$this->encryptionKey}')" : '?';
	}

	protected function getSelectQueryColumnName(string $propName) : string
	{
		if (isset($this->properties->$propName))
			return $this->properties->$propName->getEncrypt() ? "aes_decrypt({$this->databaseTable}.{$propName}, '{$this->encryptionKey}') AS $propName " : "{$this->databaseTable}.{$propName}";
		else
			return null;
	}

	protected function getWhereQueryColumnName(string $propName) : string
	{
		if (isset($this->properties->$propName))
			return $this->properties->$propName->getEncrypt() ? "aes_decrypt({$this->databaseTable}.{$propName}, '{$this->encryptionKey}')" : "{$this->databaseTable}.{$propName}";
		else
			return null;
	}
}