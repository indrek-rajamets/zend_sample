<?php
namespace Homework\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Homework\Form\HomeworkForm;
use Homework\Model\Homework;

class HomeworkController extends AbstractActionController
{
    /**
     * @var \Homework\Model\HomeworkTable
     */
    protected $homeworkTable;

    public function indexAction()
    {
        return new ViewModel(array(
            'homeworks' => $this->getHomeworkTable()->fetchAll(),
        ));
    }

    public function addAction()
    {
        $form = new HomeworkForm();
        $form->get('submit')->setValue('Add');
        $request = $this->getRequest();

        if ($request->isPost()) {
            $homework = new Homework();
            $form->setInputFilter($homework->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $homework->exchangeArray($form->getData());
                $this->getHomeworkTable()->saveHomework($homework);

                // Redirect to list of homeworks
                return $this->redirect()->toRoute('homework');
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('homework', array(
                'action' => 'add'
            ));
        }
        $homework = $this->getHomeworkTable()->getHomework($id);
        $form  = new HomeworkForm();
        $form->bind($homework);
        $form->get('submit')->setAttribute('value', 'Save');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($homework->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getHomeworkTable()->saveHomework($form->getData());

                // Redirect to list of homeworks
                return $this->redirect()->toRoute('homework');
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
            return $this->redirect()->toRoute('homework');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getHomeworkTable()->deleteHomework($id);
            }

            // Redirect to list of homeworks
            return $this->redirect()->toRoute('homework');
        }

        return array(
            'id'    => $id,
            'homework' => $this->getHomeworkTable()->getHomework($id)
        );
    }

    /**
     * @return array|\Homework\Model\HomeworkTable|object
     */
    public function getHomeworkTable()
    {
        if (!$this->homeworkTable) {
            $sm = $this->getServiceLocator();
            $this->homeworkTable = $sm->get('Homweork\Model\HomeworkTable');
        }
        return $this->homeworkTable;
    }
}