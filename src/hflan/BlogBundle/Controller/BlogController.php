<?php

namespace hflan\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use hflan\BlogBundle\Entity\Article;
use JMS\SecurityExtraBundle\Annotation\Secure;
use hflan\BlogBundle\Form\ArticleType;

class BlogController extends Controller
{
    public function homeAction($_locale)
    {
        $repository = $this->getDoctrine()
            ->getRepository('hflanBlogBundle:Article');

        $articles = $repository->getPage(
            1,
            $this->container->getParameter('articles_on_homepage'),
            true,
            $_locale
        );

        return $this->render('hflanBlogBundle:Blog:home.html.twig', array(
            'articles' => $articles,
        ));
    }

    public function indexAction($page, $_locale)
    {
        $repository = $this->getDoctrine()
            ->getRepository('hflanBlogBundle:Article');

        $articles = $repository->getPage(
            $page,
            $this->container->getParameter('articles_per_page'),
            true,
            $_locale
        );

        if($articles === false)
            throw $this->createNotFoundException('La page '.$page.' est inexistante.');

        return $this->render('hflanBlogBundle:Blog:index.html.twig', array(
            'articles' => $articles,
            'page' => $page,
            'nb_pages' => $repository->getTotalPages($this->container->getParameter('articles_per_page'), true, $_locale)
        ));
    }

    /**
     * @Secure(roles="ROLE_NEWSER")
     */
    public function adminAction($page)
    {
        $repository = $this->getDoctrine()
            ->getRepository('hflanBlogBundle:Article');

        $articles = $repository->getPage(
            $page,
            $this->container->getParameter('articles_per_page_admin')
        );

        if($articles === false)
            throw $this->createNotFoundException('La page '.$page.' est inexistante.');

        $form = $this->createFormBuilder(array())
            ->add('batch_action', 'choice', array(
                'choices' => array(
                    'hide' => 'blog.action.hide',
                    'publish' => 'blog.action.publish',
                    'delete' => 'blog.action.delete'),
                'label' => 'blog.action.selection'));

        foreach($articles as $article)
            $form->add(''.$article->getId(), 'checkbox', array('required' => false));

        $form = $form->getForm();

        $request = $this->get('request');
        if ($request->getMethod() == 'POST')
        {
            $form->bind($request);
            $data = $form->getData();
            $em = $this->getDoctrine()->getManager();

            foreach($articles as $article)
            {
                if($data[$article->getId()] == 1)
                {
                    if($data['batch_action'] == 'hide')
                        $article->setPublished(false);
                    elseif($data['batch_action'] == 'publish')
                        $article->setPublished(true);

                    if($data['batch_action'] == 'delete')
                        $em->remove($article);
                    else
                        $em->persist($article);
                }
            }

            $em->flush();
            $messages = array(
                'hide' => "blog.message.success.hide_batch",
                'publish' => "blog.message.success.publish_batch",
                'delete' => "blog.message.success.delete_batch"
            );
            $this->get('session')->setFlash('success', $messages[ $data['batch_action'] ]);
            return $this->redirect( $this->generateUrl('hflan_blog_admin', array('page' => 1)) );
        }

        return $this->render('hflanBlogBundle:Blog:admin.html.twig', array(
            'articles' => $articles,
            'page' => $page,
            'nb_pages' => $repository->getTotalPages($this->container->getParameter('articles_per_page_admin')),
            'form' => $form->createView(),
        ));
    }

    public function showAction(Article $article)
    {
        if(!$article->getPublished() && !$this->get('security.context')->isGranted('ROLE_NEWSER'))
            throw $this->createNotFoundException("Cet article n'éxiste plus.");

        return $this->render('hflanBlogBundle:Blog:show.html.twig', array(
            'article' => $article
        ));
    }

    /**
     * @Secure(roles="ROLE_NEWSER")
     */
    public function deleteAction(Article $article)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($article);
        $em->flush();

        $this->get('session')->setFlash('success', 'blog.message.success.delete');
        return $this->redirect( $this->generateUrl('hflan_blog_admin', array('page' => 1)) );
    }

    /**
     * @Secure(roles="ROLE_NEWSER")
     */
    public function toggleAction(Article $article)
    {
        $article->setPublished( !$article->getPublished() );
        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->flush();

        $this->get('session')->setFlash('success', "blog.message.success.toggle");
        return $this->redirect( $this->generateUrl('hflan_blog_show', array('slug' => $article->getSlug())) );
    }


    /**
     * @Secure(roles="ROLE_NEWSER")
     */
    public function removeImageAction(Article $article)
    {
        $article->removeImage();
        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->flush();

        $this->get('session')->setFlash('success', "blog.message.success.delete_image");
        return $this->redirect( $this->generateUrl('hflan_blog_show', array('slug' => $article->getSlug())) );
    }

    /**
     * @Secure(roles="ROLE_NEWSER")
     */
    public function editAction(Article $article)
    {
        $form = $this->createForm(new ArticleType, $article);

        $request = $this->get('request');
        if( $request->getMethod() == 'POST' )
        {
            $form->bind($request);
            if( $form->isValid() )
            {
                $article->setSlug( $this->container->get('hflan_blog.slugify')->slugify($article->getTitle()) );
                $article->preUpload();
                $em = $this->getDoctrine()->getManager();
                $em->persist($article);
                $em->flush();

                $this->get('session')->setFlash('success', 'blog.message.success.edit');
                return $this->redirect( $this->generateUrl('hflan_blog_show', array('slug' => $article->getSlug())) );
            }
            else
                $this->get('session')->setFlash('error', "message.error.form");
        }
        return $this->render('hflanBlogBundle:Blog:edit.html.twig', array(
            'form' => $form->createView(),
            'article' => $article
        ));
    }

    /**
     * @Secure(roles="ROLE_NEWSER")
     */
    public function newAction()
    {
        $article = new Article();
        $article->setAuthor($this->container->get('security.context')->getToken()->getUser());

        $form = $this->createForm(new ArticleType, $article);

        $request = $this->get('request');
        if( $request->getMethod() == 'POST' )
        {
            $form->bind($request);
            if( $form->isValid() )
            {
                $article->setSlug( $this->container->get('hflan_blog.slugify')->slugify($article->getTitle()) );
                $em = $this->getDoctrine()->getManager();
                $em->persist($article);
                $em->flush();

                $this->get('session')->setFlash('success', "blog.message.success.new");
                return $this->redirect( $this->generateUrl('hflan_blog_show', array('slug' => $article->getSlug())) );
            }
            else
                $this->get('session')->setFlash('error', "message.error.form");
        }

        return $this->render('hflanBlogBundle:Blog:new.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}