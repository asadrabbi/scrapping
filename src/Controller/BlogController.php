<?php

namespace App\Controller;

use App\Entity\News;
use App\Repository\NewsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/blog', name: 'blog_')]
class BlogController extends AbstractController
{
    // simple Blog API example
    #[Route('/api', name: 'api_index', methods: ['GET'])]
    public function all(NewsRepository $r): JsonResponse
    {
        $all = $r->findAll();

        return $this->json($all);
    }

    #[Route('/api/{slug}', name: 'api_show', methods: ['GET'])]
    public function single(string $slug, NewsRepository $r): JsonResponse
    {
        $item = $r->findOneBy(['slug' => $slug]);

        if (empty($item)) {
            return $this->json(['error' => 'Post Not found'], status: 404);
        }

        return $this->json($item);
    }

    // CRUD 
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(NewsRepository $r): Response
    {
        $all = $r->findAll();

        return $this->render('blog/index.html.twig', compact('all'));
    }

    #[Route('/create', name: 'create', methods: ['GET'])]
    public function create(Request $rq): Response
    {
        $old = $this->getOldValues($rq);

        return $this->render('blog/create.html.twig', $old);
    }

    #[Route('/store', name: 'store', methods: ['POST'])]
    public function store(Request $rq, NewsRepository $repo, ValidatorInterface $vd): Response
    {
        $_token = $rq->get('token');

        if (!$this->isCsrfTokenValid('create-item', $_token)) {

            $this->addFlash('warning', 'not validad form');
            return $this->redirectToRoute('blog_create');
        }

        $item = new News();

        if (!$this->isValid($item, $rq, $vd)) {
            return $this->redirectToRoute('blog_create');
        }

        $repo->save($item, true);

        return $this->redirectToRoute('blog_index');
    }

    #[Route('/{slug}', name: 'show', methods: ['GET'])]
    public function show(string $slug, NewsRepository $r): Response
    {
        $item = $r->findOneBy(['slug' => $slug]);

        if (empty($item)) {
            throw $this->createNotFoundException('Post Not Found: ' . $slug);
        }

        return $this->render('blog/show.html.twig', compact('item'));
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['DELETE'])]
    public function destroy(int $id, Request $rq, NewsRepository $r): Response
    {
        $_token = $rq->get('token');

        if (!$this->isCsrfTokenValid('delete-item', $_token)) {

            $this->addFlash('warning', 'not validad form');
            return $this->redirectToRoute('blog_index');
        }

        $item = $r->find($id);

        if (empty($item)) {
            throw $this->createNotFoundException('Post Not Found: ' . $id);
        }

        $r->remove($item, true);

        $this->addFlash('success', 'Post removed');
        return $this->redirectToRoute('blog_index');
    }

    #[Route('/{slug}/edit', name: 'edit', methods: ['GET'])]
    public function edit(string $slug, NewsRepository $r, Request $rq): Response
    {
        $old = $this->getOldValues($rq);

        $item = $r->findOneBy(['slug' => $slug]);

        if (empty($item)) {
            throw $this->createNotFoundException('Post Not Found: ' . $slug);
        }

        // #spread operator since 7.4
        return $this->render('blog/edit.html.twig', [...$old, ...compact('item')]);
    }

    #[Route('/{slug}/update', name: 'update', methods: ['PUT'])]
    public function update(string $slug, Request $rq, EntityManagerInterface $em, ValidatorInterface $vd): Response
    {

        $_token = $rq->get('token');

        if (!$this->isCsrfTokenValid('update-item', $_token)) {

            $this->addFlash('warning', 'not validad form');
            return $this->redirectToRoute('blog_edit', ['slug' => $slug]);
        }

        $item = $em->getRepository(News::class)->findOneBy(['slug' => $slug]);

        if (empty($item)) {
            throw $this->createNotFoundException('Post Not Found: ' . $slug);
        }

        if (!$this->isValid($item, $rq, $vd)) {
            return $this->redirectToRoute('blog_edit', ['slug' => $slug]);
        }

        $em->flush();

        $this->addFlash('success', 'Post updated');

        return $this->redirectToRoute('blog_index');
    }

    // utils methods
    private function getOldValues(Request $rq): array
    {
        $s = $rq->getSession();
        $old =  [
            'title'    =>  $s->get('title'),
            'body'     =>  $s->get('body'),
            'errors' => []
        ];

        $errors = (string) $s->get('errors');
        if (!empty($errors)) {

            $errorList = [];
            $e = $s->get('errors');
            foreach ($e as $val) {
                if (array_key_exists($val->getPropertyPath(), $errorList)) {
                    $errorList[$val->getPropertyPath()][] = $val->getMessage();
                    continue;
                }

                $errorList[$val->getPropertyPath()][] = $val->getMessage();
            }

            $s->clear();

            $old['errors'] = $errorList;
        }

        return $old;
    }

    private function isValid(News $item, Request $rq, ValidatorInterface $vd): bool
    {
        $item->setTitle($rq->get('title'))
            ->setBody($rq->get('body'))
            ->setSlug(str_replace(' ', '-', $rq->get('title')));

        $errors = $vd->validate($item);

        $rs = !empty((string)$errors);

        if ($rs) {
            $title = $rq->get('title');
            $body = $rq->get('body');

            $session = $rq->getSession();
            $session->set('title', $title);
            $session->set('body', $body);
            $session->set('errors', $errors);
        }

        return !$rs;
    }
}
