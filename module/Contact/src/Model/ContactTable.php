<?php

namespace Contact\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class ContactTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($paginated = false)
    {
        if ($paginated) {
            return $this->fetchPaginatedResults();
        }

        return $this->tableGateway->select();
    }

    public function fetchPaginatedResults()
    {
        $select = new Select($this->tableGateway->getTable());

        $resultPrototype = new ResultSet();
        $resultPrototype->setArrayObjectPrototype(new Contact());

        $paginatorAdapter = new DbSelect(
            $select,
            $this->tableGateway->getAdapter(),
            $resultPrototype
        );

        $paginator = new Paginator($paginatorAdapter);
        return $paginator;
    }

    public function getContact($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if (!$row) {
            throw new \RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }

        return $row;
    }

    public function saveContact(Contact $contact)
    {
        $data = [
            'name' => $contact->name,
            'phone' => $contact->phone,
        ];

        $id = (int) $contact->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        try {
            $this->getContact($id);
        } catch (\RuntimeException $e) {
            throw new \RuntimeException(sprintf(
                'Cannot update contact with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deleteContact($id)
    {
        $this->tableGateway->delete(['id'=> (int) $id]);
    }
}