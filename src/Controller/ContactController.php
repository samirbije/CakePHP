<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\Network\Email\Email;
/**
 * Contact Controller
 *
 * @property \App\Model\Table\ContactTable $Contact
 */
class ContactController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $contact = $this->paginate($this->Contact);

        $this->set(compact('contact'));
        $this->set('_serialize', ['contact']);
    }

    /**
     * View method
     *
     * @param string|null $id Contact id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $contact = $this->Contact->get($id, [
            'contain' => []
        ]);

        $this->set('contact', $contact);
        $this->set('_serialize', ['contact']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, send mail , renders view otherwise.
     */
    public function add()
    {
        $contact        = $this->Contact->newEntity();
        // post check 
        if ($this->request->is('post')) {
        	if ($this->request->data['reason'] !='Other') {
        		$this->request->data['specify'] = '';
        		// empty check and unset specify field  for specify and reason validation
        		unset($this->request->data['specify']); 
        		
        	} 
                $contact = $this->Contact->patchEntity($contact, $this->request->data);
                // save post data to database
                if ($this->Contact->save($contact)) {
 					// load email contact     	
					Configure::load('emailContact');
					$to         = Configure::read('send.to');
					$subject    = Configure::read('send.subject');
					$from       = Configure::read('send.from');
					$from_name  = Configure::read('send.from_name');
					//  cakephp email object
					$email      = new Email();
					// email layout template 
					$email->template('send','sendLayout');
					$email->emailFormat('html');
					$email->to($to);
					$email->from($from);
					$email->subject($subject);
                    //email template post data pass
					$email->viewVars(['value' => $this->request->data]);
					if ($email->send()) {                       
                    $this->Flash->success(__('The contact mail has been  sent and saved .'));                       
                    return $this->redirect(['action' => 'add']);

                } else {
                	$this->Flash->error(__('Mail not send .'));
                }
            } else {
                 $this->Flash->error(__('The contact could not be saved. Please input all fields, try again.'));
            }
        }
        $this->set(compact('contact'));
        $this->set('_serialize', ['contact']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Contact id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $contact = $this->Contact->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $contact = $this->Contact->patchEntity($contact, $this->request->data);
            if ($this->Contact->save($contact)) {
                $this->Flash->success(__('The contact has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The contact could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('contact'));
        $this->set('_serialize', ['contact']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Contact id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $contact = $this->Contact->get($id);
        if ($this->Contact->delete($contact)) {
            $this->Flash->success(__('The contact has been deleted.'));

        } else {
            $this->Flash->error(__('The contact could not be deleted. Please, try again.'));

        }
        return $this->redirect(['action' => 'index']);

    }
    /**
     * beforefilter method
     *
     * @param string|null $event .
     * @throws Front end allow controller and method.
     */
    function beforeFilter(Event $event) {
		$this->Auth->allow(array('controller' => 'contact', 'action' => 'add'));

	}
}
