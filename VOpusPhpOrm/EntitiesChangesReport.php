<?php

namespace VOpus\PhpOrm;


class EntitiesChangesReport
{
    private object $report;
    private $dataEntityClass;

    private array $insertEntities = [];
    private array $updateEntities = [];
    private array $deleteEntities = [];

    public function __construct($json, $dataEntityClass)
    {
        if (is_string($json))
            $this->report = json_decode($json);
        else if (is_array($json))
            $this->report = (object)$json;
        else if (is_object($json))
            $this->report = $json;
        else
            throw new \Exception('Erro ao criar instância de EntitiesChangesReport. Parâmetro JSON inválido.');

        $this->dataEntityClass = $dataEntityClass;
        $this->buildEntitiesObjects();
    }

    private function buildEntitiesObjects()
    {
        if (isset($this->report->delete))
        {
            foreach ($this->report->delete as $deleteReg)
            {
                $entity = new $this->dataEntityClass();
                $entity->fillPropertiesFromDataRow((array)$deleteReg);
                $this->deleteEntities[] = $entity;
            }
        }

        if (isset($this->report->update))
        {
            foreach ($this->report->update as $updateReg)
            {
                $entity = new $this->dataEntityClass();
                $entity->fillPropertiesFromDataRow((array)$updateReg);
                $this->updateEntities[] = $entity;
            }
        }

        if (isset($this->report->create))
        {
            foreach ($this->report->create as $createReg)
            {
                $entity = new $this->dataEntityClass();
                $entity->fillPropertiesFromDataRow((array)$createReg);
                $this->insertEntities[] = $entity;
            }
        }
    }

    public function applyToDatabase(\mysqli $conn) : int
    {
        $affectedRows = 0;
        foreach ($this->deleteEntities as $delEntity)
            $affectedRows += $delEntity->delete($conn)['affectedRows'];
        
        foreach ($this->updateEntities as $updEntity)
            $affectedRows += $updEntity->save($conn)['affectedRows'];

        foreach ($this->insertEntities as $insEntity)
            $affectedRows += $insEntity->save($conn)['affectedRows'];

        return $affectedRows;
    }

    public function setPropertyValueForAll($propName, $value)
    {
        foreach ($this->deleteEntities as $delEntity)
            $delEntity->$propName = $value;
        
        foreach ($this->updateEntities as $updEntity)
            $updEntity->$propName = $value;

        foreach ($this->insertEntities as $insEntity)
            $insEntity->$propName = $value;
    }

    public function callMethodForAll($methodName, $value)
    {
        foreach ($this->deleteEntities as $delEntity)
            if (method_exists($delEntity, $methodName))
                $delEntity->$methodName($value);
        
        foreach ($this->updateEntities as $updEntity)
            if (method_exists($updEntity, $methodName))
                $updEntity->$methodName($value);

        foreach ($this->insertEntities as $insEntity)
            if (method_exists($insEntity, $methodName))
                $insEntity->$methodName($value);
    }
}