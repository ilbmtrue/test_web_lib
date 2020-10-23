<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LibraryController extends AbstractController
{
    /**
     * @Route("/", name="library_list", methods={"GET"})
     */
    public function list()
    {
        $repository = $this->getDoctrine()->getRepository(Book::class);
        $books = $repository->findAll();
        return $this->render('library/list.html.twig', [
            'controller_name' => 'LibraryController',
            'books' => $books,
        ]);
    }
    /**
     * @Route("/add", name="library_add", methods={"GET", "POST"})
     */
    public function add(Request $request): Response
    {      
        $form = $this->createForm(BookType::class, new Book());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();
            $this->addFlash('success', 'Книга добавлена');
            return $this->redirectToRoute('library_list');
        }
        $addform = $form->createView();
        unset($addform->children["delete"]);
        
        $renderParams = [
            'controller_name' => 'LibraryController',
            'page_title' => 'Добавление книги',
            'form_title' => 'Добавить новую книгу',
            'button_title' => 'Добавить книгу',
            'add_book_form' => $addform,
        ];
        
        return $this->render('library/book.html.twig', $renderParams);
    }
    /**
     * @Route("/edit/{id}", name="library_edit", methods={"GET", "POST"})
     */
    public function update(int $id, Request $request) : Response
    {
        $em = $this->getDoctrine()->getManager();
        $book = $this->getDoctrine()->getRepository(Book::class)->find($id);
        $form = $this->createForm(BookType::class, $book);       
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('add')->isClicked()){
                $this->addFlash('warning', 'Книга изменена');
            }
            if ($form->get('delete')->isClicked()){
                $em->remove($book);
                $this->addFlash('danger', 'Книга удалена');
            }
            $em->flush();
            
            return $this->redirectToRoute('library_list');
        }
        
        $tempForm = $form->createView();
        $tempForm->children["name"]->vars["value"] = $book->getName();
        $tempForm->children["year"]->vars["value"] = $book->getYear();
        $tempForm->children["author"]->vars["value"] = $book->getAuthor();
        $tempForm->children["add"]->vars["label"] = 'Сохранить';
        $renderParams = [
            'controller_name' => 'LibraryController',
            'page_title' => 'Изменить запись о книге',
            'form_title' => 'Изменить запись о книге',
            'button_title' => 'Изменить запись',
            'add_book_form' => $tempForm,
            'book' => $book,
        ];

        return $this->render('library/book.html.twig', $renderParams);
    }

}
