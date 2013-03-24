<?php
namespace Homework\Model;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway;
use \Zend\Db\Adapter\Adapter;

class Grade
{
    public $studentId;
    public $homeworkId;
    public $grade;
	/** @var Student */
	public $student;
	/** @var TableGateway null */
	private $tableGateway = null;

    /**
     * @var array Maps Object prameterst to fields in database
     */
    public $dbMap = array(
        'studentId' => 'student_id',
        'homeworkId' => 'homework_id',
        'grade' => 'grade',
    );

    protected $inputFilter;

	public function __construct(\Zend\Db\TableGateway\TableGateway $gw = null){
		$this->tableGateway = $gw;
	}

    public function exchangeArray($data)
    {
        $this->studentId     = (isset($data['student_id'])) ? $data['student_id'] : null;
        $this->homeworkId = (isset($data['homework_id'])) ? $data['homework_id'] : null;
        $this->grade  = (isset($data['grade'])) ? $data['grade'] : null;
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name'     => 'student_id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'homework_id',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'grade',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'Between',
                        'options' => array(
                            'min'      => 0,
                            'max'      => 5,
                        ),
                    ),
                ),
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
    public function getArrayCopy()
    {
        $out = array();
        foreach( $this->dbMap as $parameter => $field ){
            $out[$field] = $this->{$parameter};
        }
        return $out;
    }

	/**
	 * @param Grade $grade
	 * @return array|\ArrayObject|null
	 */
	public function getStudent(){
		$table = new StudentTable($this->tableGateway);
		return $table->getStudent($this->studentId);
	}

}
