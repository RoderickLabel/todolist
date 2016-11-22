<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Task;
use AppBundle\Repository\TaskRepository;


/**
 * @Route("/task")
 */
class TaskController extends Controller
{

    protected $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * @Route("/list", name="list_task")
     */
    public function listAction()
    {
        $taskRepository = $this->getDoctrine()->getRepository("AppBundle:Task");
        $tasks = $taskRepository->findAll();
        return $this->render('AppBundle:Task:list.html.twig', array(
            "tasks" => $tasks
        ));
    }

    /**
     * @Route("/view/{id}", name="view_task")
     */ 
    public function viewAction(int $id)
    {
        $taskRepository = $this->getDoctrine()->getRepository("AppBundle:Task");
        $task = $taskRepository->find($id);
        return $this->render('AppBundle:Task:view.html.twig', array(
            "task" => $task
        ));
    }

    /**
     * @Route("/save", name="save_task")
     * @Method("POST")
     */
    public function saveAction(Request $request) {

        $form = $this->createForm("AppBundle\Form\TaskType");
        $form->handleRequest($request);

        $task = $form->getData();
        
        if($form->isSubmitted() && $form->isValid()) {
            
            $taskRepository = $this->getDoctrine()->getRepository("AppBundle:Task");

            $taskRepository->merge($task);

            return $this->redirectToRoute("list_task");
        }

        return $this->render('AppBundle:Task:form.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/form", name="form_task")
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
     * @Route("/edit/{id}", name="edit_task")
     */
    public function editAction(int $id) {

        $taskRepository = $this->getDoctrine()->getRepository("AppBundle:Task");
        $task = $taskRepository->find($id);
        $form = $this->createForm("AppBundle\Form\TaskType",$task);
        return $this->render('AppBundle:Task:form.html.twig', array(
            'form' => $form->createView()
        ));

    }

     /**
     * @Route("/delete/{id}", name="delete_task")
     */
    public function deleteAction(int $id) {
        $taskRepository = $this->getDoctrine()->getRepository("AppBundle:Task");
        $task = $taskRepository->find($id);
        $taskRepository->remove($task);
        return $this->redirectToRoute("list_task");
    }

}
