<?php
namespace Homework\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Homework\Form\GradeForm;
use Homework\Model\Grade;

class GradeController extends AbstractActionController
{
    /**
     * @var \Homework\Model\GradeTable
     */
    protected $gradeTable;
	/**
	 * @var \Homework\Model\HomeworkTable
	 */
    protected $homeworkTable;
	/**
	 * @var \Homework\Model\StudentTable
	 */
    protected $studentTable;

    public function indexAction()
    {
	    /** @var $grades \Zend\Db\ResultSet\ResultSet */
	    $grades = $this->getGradeTable()->fetchAllWithRelated();

        return new ViewModel(array(
            'grades' => $grades,
        ));
    }

    public function addAction()
    {

        $request = $this->getRequest();
	    $form = new GradeForm(null,$this->getHomeworkTable()->fetchAll(),$this->getStudentTable()->fetchAll());
	    $form->get('submit')->setValue('Add');

        if ($request->isPost()) {
            $grade = new Grade();
            $form->setInputFilter($grade->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $grade->exchangeArray($form->getData());
                $this->getGradeTable()
	                ->saveGrade($grade);

                // Redirect to list of grades
                return $this->redirect()->toRoute('grade');
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        $studentId = (int) $this->params()->fromRoute('student_id', 0);
        $homeworkId = (int) $this->params()->fromRoute('homework_id', 0);
        if (!$studentId || !$homeworkId) {
            return $this->redirect()->toRoute('grade', array(
                'action' => 'add'
            ));
        }
        $grade = $this->getGradeTable()->getGrade($studentId,$homeworkId);
        $form  = new GradeForm(null,$this->getHomeworkTable()->fetchAll(),$this->getStudentTable()->fetchAll());

        $form->bind($grade);
        $form->get('submit')->setAttribute('value', 'Save');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($grade->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getGradeTable()->saveGrade($form->getData());

                // Redirect to list of grades
                return $this->redirect()->toRoute('grade');
            }
        }

        return array(
            'student_id' => $studentId,
            'homework_id' => $homeworkId,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
	    $studentId = (int) $this->params()->fromRoute('student_id', 0);
	    $homeworkId = (int) $this->params()->fromRoute('homework_id', 0);
	    if (!$studentId || !$homeworkId) {
		    return $this->redirect()->toRoute('grade');
	    }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $this->getGradeTable()->deleteGrade($studentId,$homeworkId);
            }

            // Redirect to list of grades
            return $this->redirect()->toRoute('grade');
        }

        return array(
	        'student_id' => $studentId,
	        'homework_id' => $homeworkId,
            'grade' => $this->getGradeTable()->getGrade($studentId,$homeworkId)
        );
    }

    /**
     * @return array|\Homework\Model\GradeTable|object
     */
    public function getGradeTable()
    {
        if (!$this->gradeTable) {
            $sm = $this->getServiceLocator();
            $this->gradeTable = $sm->get('Homework\Model\GradeTable');
        }
        return $this->gradeTable;
    }

	/**
	 * @return array|\Homework\Model\HomeworkTable|object
	 */
	public function getHomeworkTable()
	{
		if (!$this->homeworkTable) {
			$sm = $this->getServiceLocator();
			$this->homeworkTable = $sm->get('Homework\Model\HomeworkTable');
		}
		return $this->homeworkTable;
	}

	/**
	 * @return array|\Homework\Model\StudentTable|object
	 */
	public function getStudentTable()
	{
		if (!$this->studentTable) {
			$sm = $this->getServiceLocator();
			$this->studentTable = $sm->get('Homework\Model\StudentTable');
		}
		return $this->studentTable;
	}
}