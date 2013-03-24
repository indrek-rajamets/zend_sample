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

	public function fetchAllWithGrades(){
		$sql = new \Zend\Db\Sql\Sql($this->tableGateway->adapter);

		$select = $sql->select();
		$select->from('student')
			->join('grade', 'grade.student_id = student.id',\Zend\Db\Sql\Select::SQL_STAR,\Zend\Db\Sql\Select::JOIN_LEFT)
			->columns(array(
				new \Zend\Db\Sql\Expression('AVG(grade.grade) as avgGrade'),
				'first_name',
				'last_name',
				'id',
			))
			->group("student.id");
		//echo $select->getSqlString();
		$statement = $sql->prepareStatementForSqlObject($select);
		$result = $statement->execute();
		return $result;
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