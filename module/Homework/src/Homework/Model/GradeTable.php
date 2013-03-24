<?php
namespace Homework\Model;

use Zend\Db\TableGateway\TableGateway;

class GradeTable
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

	public function fetchAllWithRelated()
	{
		$sql = new \Zend\Db\Sql\Sql($this->tableGateway->adapter);
		$select = $sql->select();
		$select->from('grade')
			->join('student', 'grade.student_id = student.id')
			->join('homework', 'grade.homework_id = homework.id');

		//you can check your query by echo-ing :
		// echo $select->getSqlString();
		$statement = $sql->prepareStatementForSqlObject($select);
		$result = $statement->execute();
		return $result;
	}

    public function getGrade($studentId,$homeworkId)
    {
        $rowset = $this->tableGateway->select(array('student_id' => $studentId,'homework_id'=>$homeworkId));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $studentId,$homeworkId");
        }
        return $row;
    }

    public function saveGrade(Grade $grade)
    {
        $data = array(
            'student_id' => $grade->studentId,
            'homework_id'  => $grade->homeworkId,
            'grade'  => $grade->grade,
        );
        try{
	        //throws exception if not found
            $this->getGrade($grade->studentId,$grade->homeworkId);
	        $this->tableGateway->update(
		        array(
			        'grade' => $grade->grade
		        ),
		        array(
			        'student_id' => $grade->studentId,
			        'homework_id'  => $grade->homeworkId
		        )
	        );
        }catch(\Exception $e){
	        $this->tableGateway->insert($data);
        }
    }

    public function deleteGrade($studentId,$homeworkId)
    {
        $this->tableGateway->delete(
	        array(
		        'student_id' => $studentId,
		        'homework_id'  => $homeworkId
	        )
        );
    }

	/**
	 * @param Grade $grade
	 * @return array|\ArrayObject|null
	 */
	public function getHomework(Grade $grade){
		$table = new HomeworkTable($this->tableGateway);
		return $table->getHomework($grade->homeworkId);
	}

	/**
	 * @param Grade $grade
	 * @return array|\ArrayObject|null
	 */
	public function getStudent(Grade $grade){
		$table = new StudentTable($this->tableGateway);
		return $table->getStudent($grade->studentId);
	}
}