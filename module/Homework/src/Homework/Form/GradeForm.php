<?php
namespace Homework\Form;

use Zend\Form\Form;
use Homework\Model\HomeworkTable;

class GradeForm extends Form
{
	public $homeworksList = array();
	public $studentsList = array();

	public function __construct(
		$name = null,
		\Zend\Db\ResultSet\ResultSet $homeworksList,
		\Zend\Db\ResultSet\ResultSet $studentsList
	){
		// we want to ignore the name passed
		parent::__construct('grade');
		$this->setAttribute('method', 'post');
		$homeworks = array();

		if( $homeworksList->count() ){
			foreach( $homeworksList as $homework ){
				/** @var \Homework\Model\Homework $homework */
				$homeworks[$homework->id] = $homework->name;
			}
		}
		$students = array();
		if( $studentsList->count() ){
			foreach( $studentsList as $student ){
				/** @var \Homework\Model\Student $student */
				$students[$student->id] = $student->firstName." ".$student->lastName;
			}
		}
		$this->add(array(
			'name' => 'homework_id',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => 'Select homework',
				'value_options' => $homeworks
			)
		));
		$this->add(array(
			'name' => 'student_id',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => 'Select student',
				'value_options' => $students,
			)
		));
		$this->add(array(
			'name' => 'grade',
			'attributes' => array(
				'type' => 'int',
			),
			'options' => array(
				'label' => 'Grade',
			),
		));
		$this->add(array(
			'name' => 'submit',
			'attributes' => array(
				'type' => 'submit',
				'value' => 'Insert',
				'id' => 'submitbutton',
			),
		));
	}
}