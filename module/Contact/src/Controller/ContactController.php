<?php

namespace Contact\Controller;

use Contact\Form\ContactForm;
use Contact\Model\Contact;
use Contact\Model\ContactTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class ContactController
 *
 * @package Contact\Controller
 * @author Hafiz "Pisyek" Suhaimi <hi@pisyek.com>
 * @copyright 2019 Pisyek Studios
 * @link www.pisyek.com
 */
class ContactController extends AbstractActionController
{
    private $table;

    public function __construct(ContactTable $table)
    {
        $this->table = $table;
    }

    public function indexAction()
    {
        $paginator = $this->table->fetchAll(true);
        $page = (int) $this->params()->fromQuery('page', 1);
        $page = ($page < 1) ? 1 : $page;
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage(2);

        return new ViewModel([
            'paginator' => $paginator,
        ]);
    }

    public function addAction()
    {
        $form = new ContactForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if (! $request->isPost()) {
            return [
                'form' => $form
            ];
        }

        $contact = new Contact();
        $form->setInputFilter($contact->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return [
                'form' => $form
            ];
        }

        $contact->exchangeArray($form->getData());
        $this->table->saveContact($contact);
        return $this->redirect()->toRoute('contact');
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if ($id === 0) {
            return $this->redirect()->toRoute('contact', ['action' > 'add']);
        }

        try {
            $contact = $this->table->getContact($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('contact', ['action' => 'index']);
        }

        $form = new ContactForm();
        $form->bind($contact);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = [
            'id' => $id,
            'form' => $form,
        ];

        if (! $request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($contact->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return $viewData;
        }

        $this->table->saveContact($contact);
        return $this->redirect()->toRoute('contact', ['action' => 'index']);
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id === 0) {
            return $this->redirect()->toRoute('contact');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $delete = $request->getPost('delete', 'No');

            if ($delete === 'Yes') {
                $id = (int) $request->getPost('id');
                $this->table->deleteContact($id);
            }

            return $this->redirect()->toRoute('contact');
        }

        return [
            'id' => $id,
            'contact' => $this->table->getContact($id),
        ];
    }
}