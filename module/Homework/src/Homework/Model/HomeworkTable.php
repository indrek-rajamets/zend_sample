<?php
namespace Homework\Model;

use Zend\Db\TableGateway\TableGateway;

class HomeworkTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getHomework($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveHomework(Homework $homework)
    {
        $data = array(
            'name' => $homework->name,
            'date'  => date('Y-m-d',strtotime($homework->date))
        );

        $id = (int)$homework->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getHomework($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteHomework($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}