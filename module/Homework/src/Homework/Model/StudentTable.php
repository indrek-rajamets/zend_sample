<?php
namespace Homework\Model;

use Zend\Db\TableGateway\TableGateway;

class StudentTable
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

    public function getStudent($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveStudent(Student $student)
    {
        $data = array(
            'first_name' => $student->firstName,
            'last_name'  => $student->lastName,
        );

        $id = (int)$student->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getStudent($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteStudent($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}