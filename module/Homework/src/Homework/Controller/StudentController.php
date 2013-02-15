<?php
namespace Homework\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Homework\Form\StudentForm;
use Homework\Model\Student;

class StudentController extends AbstractActionController
{
    /**
     * @var \Homework\Model\StudentTable
     */
    protected $studentTable;

    public function indexAction()
    {
        return new ViewModel(array(
            'students' => $this->getStudentTable()->fetchAll(),
        ));
    }

    public function addAction()
    {
        $form = new StudentForm();
        $form->get('submit')->setValue('Add');
        $request = $this->getRequest();

        if ($request->isPost()) {
            $student = new Student();
            $form->setInputFilter($student->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $student->exchangeArray($form->getData());
                $this->getStudentTable()->saveStudent($student);

                // Redirect to list of students
                return $this->redirect()->toRoute('student');
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('student', array(
                'action' => 'add'
            ));
        }
        $student = $this->getStudentTable()->getStudent($id);
        $form  = new StudentForm();
        $form->bind($student);
        $form->get('submit')->setAttribute('value', 'Save');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($student->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getStudentTable()->saveStudent($form->getData());

                // Redirect to list of students
                return $this->redirect()->toRoute('student');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('student');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getStudentTable()->deleteStudent($id);
            }

            // Redirect to list of students
            return $this->redirect()->toRoute('student');
        }

        return array(
            'id'    => $id,
            'student' => $this->getStudentTable()->getStudent($id)
        );
    }

    /**
     * @return array|\Homework\Model\StudentTable|object
     */
    public function getStudentTable()
    {
        if (!$this->studentTable) {
            $sm = $this->getServiceLocator();
            $this->studentTable = $sm->get('Homweork\Model\StudentTable');
        }
        return $this->studentTable;
    }
}