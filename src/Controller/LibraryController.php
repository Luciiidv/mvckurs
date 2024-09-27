<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Library;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\LibraryRepository;

class LibraryController extends AbstractController
{
    #[Route('/library/create/form', name: 'create_form', methods: ['POST'])]
    public function createBookForm(): Response
    {
        return $this->render('library/create.html.twig');
    }

    #[Route('/library/create', name: 'library_create', methods: ['POST', 'GET'])]
    public function createLibrary(
        ManagerRegistry $doctrine,
        Request $request
    ): Response {
        $entityManager = $doctrine->getManager();

        $library = new Library();
        $library->setName($request->request->get('name'));
        $library->setIsbn($request->request->get('isbn'));
        $library->setAuthor($request->request->get('author'));
        $library->setImage($request->request->get('image'));

        // tell Doctrine you want to (eventually) save the library
        // (no queries yet)
        $entityManager->persist($library);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
        var_dump("success");
        return $this->redirectToRoute('library_show_all');
    }

    #[Route('/api/library/books', name: 'library_show_all_api')]
    public function showAllLibraryApi(
        LibraryRepository $libraryRepository
    ): Response {
        $libraryAll = $libraryRepository
            ->findAll();

        $response = $this->json($libraryAll);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }


    #[Route('/library/show', name: 'library_show_all')]
    public function showAllLibrary(
        LibraryRepository $libraryRepository
    ): Response {
        $libraryAll = $libraryRepository
            ->findAll();

        $data = [
            'library' => $libraryAll
        ];

        return $this->render('library/showAll.html.twig', $data);
    }

    #[Route('/api/library/book/{isbn}', name: 'library_by_isbn_api')]
    public function showLibraryByIsbnApi(
        LibraryRepository $libraryRepository,
        string $isbn
    ): Response {
        $book = $libraryRepository
            ->findOneBy(['ISBN' => $isbn]);

        // $response = $this->json($book);
        // $response->setEncodingOptions(
        //     $response->getEncodingOptions() | JSON_PRETTY_PRINT
        // );
        // return $response;
        return $this->json($book);
    }

    #[Route('/library/show/{id}', name: 'library_by_id')]
    public function showLibraryById(
        LibraryRepository $libraryRepository,
        int $id
    ): Response {
        $book = $libraryRepository
            ->find($id);

        $data = [
            'book' => $book
        ];

        return $this->render('library/showOne.html.twig', $data);
    }

    #[Route('/library/delete/{id}', name: 'library_delete_by_id', methods: ['POST', 'GET'])]
    public function deleteLibraryById(
        ManagerRegistry $doctrine,
        int $id
    ): Response {
        $entityManager = $doctrine->getManager();
        $library = $entityManager->getRepository(Library::class)->find($id);

        if (!$library) {
            throw $this->createNotFoundException(
                'No book found for id '.$id
            );
        }

        $entityManager->remove($library);
        $entityManager->flush();

        return $this->redirectToRoute('library_show_all');
    }

    #[Route('/library/update/form/{id}', name: 'update_form', methods: ['POST', 'GET'])]
    public function updateForm(
        LibraryRepository $libraryRepository,
        int $id
    ): Response {
        $book = $libraryRepository
        ->find($id);

        $data = [
            'book' => $book
        ];
        return $this->render('library/update.html.twig', $data);
    }

    #[Route('/library/update/{id}', name: 'library_update', methods: ['POST'])]
    public function updateLibrary(
        ManagerRegistry $doctrine,
        int $id,
        Request $request
    ): Response {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Library::class)->find($id);

        #Check updates.
        if($request->request->get('name')) {
            $book->setName($request->request->get('name'));
        }

        if($request->request->get('isbn')) {
            $book->setIsbn($request->request->get('isbn'));
        }

        if($request->request->get('author')) {
            $book->setAuthor($request->request->get('author'));
        }

        if($request->request->get('image')) {
            $book->setImage($request->request->get('image'));
        }

        $entityManager->flush();

        return $this->redirectToRoute('library_show_all');
    }
}
