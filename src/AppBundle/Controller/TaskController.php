<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
//use AppBundle\Entity\Task;
use AppBundle\Repository\TaskRepository;


/**
 * @Route("/task")
 */
class TaskController extends Controller
{

    private $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * @Route("/list", name="list_task", service="controller.task")
     */
    public function listAction()
    {
        
        $tasks = $this->taskRepository->findAll();
        return $this->render('AppBundle:Task:list.html.twig', array(
            "tasks" => $tasks
        ));
    }

    /**
     * @Route("/view/{id}", name="view_task", service="controller.task")
     */ 
    public function viewAction(int $id)
    {

        $task = $this->taskRepository->find($id);
        return $this->render('AppBundle:Task:view.html.twig', array(
            "task" => $task
        ));
    }

    /**
     * @Route("/save", name="save_task", service="controller.task")
     * @Method("POST")
     */
    public function saveAction(Request $request) {

        $form = $this->createForm("AppBundle\Form\TaskType");
        $form->handleRequest($request);

        $task = $form->getData();
        
        if($form->isSubmitted() && $form->isValid()) {
            


            $this->taskRepository->merge($task);

            return $this->redirectToRoute("list_task");
        }

        return $this->render('AppBundle:Task:form.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/form", name="form_task", service="controller.task")
     */
    public function formAction()
    {
        $task = new Task();
        $form = $this->createForm("AppBundle\Form\TaskType",$task);

        return $this->render('AppBundle:Task:form.html.twig', array(
            'form' => $form->createView()
        ));
    }

     /**
     * @Route("/edit/{id}", name="edit_task", service="controller.task")
     */
    public function editAction(int $id) {

        
        $task = $this->taskRepository->find($id);
        $form = $this->createForm("AppBundle\Form\TaskType",$task);
        return $this->render('AppBundle:Task:form.html.twig', array(
            'form' => $form->createView()
        ));

    }

     /**
     * @Route("/delete/{id}", name="delete_task", service="controller.task")
     */
    public function deleteAction(int $id) {
        
        $task = $this->taskRepository->find($id);
        $taskRepository->remove($task);
        return $this->redirectToRoute("list_task");
    }

}
