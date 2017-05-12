<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Article;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class PdfController extends Controller
{
    /**
     * -------------------------------------------------------------------------------------------------------------
     *==============   FONCTIONS PDF   =============================================================================
     * -------------------------------------------------------------------------------------------------------------
     */

    /**
     * Export Article to PDF
     * @Route("/{slug}/pdf", requirements={"id": "\d+"}, name="articletopdf")
     * @Method("GET")
     */
    public function pdfAction(Article $article)
    {
        $html = $this->renderView('Article/articleToPdf.html.twig', ['article' => $article,]);
        $htmltopdf = new \HTML2PDF('P', 'A4', 'fr', array(50, 50, 50, 50));
        $htmltopdf->pdf->SetDisplayMode('real');
        $htmltopdf->writeHTML($html);
        $htmltopdf->Output($article->getSlug() . '.pdf');

        return new Response();

    }

    /**
     * Export all articles to PDF file
     * @Route("/bookpdf", name="booktopdf")
     * @Security("has_role('ROLE_ADMIN')")
     * @Method("GET")
     */
    public function bookToPdfAction()
    {
        $listArticles = $this->getDoctrine()->getRepository('AppBundle:Article')->findAllAsc();
        $html = $this->renderView('Article/booktopdf.html.twig', ['listArticles' => $listArticles,]);
        $htmltopdf = $this->get('html2pdf_factory')->create('P', 'A4', 'en', true, 'UTF-8', array(10, 15, 10, 15));
        $htmltopdf->pdf->SetDisplayMode('real');
        $htmltopdf->writeHTML($html);
        $webroot = $this->getParameter('webroot');
        $htmltopdf->Output($webroot . '/billet-simple-pour-l-alaska.pdf', 'F');
        $response = array("code" => 100, "success" => true, "message" => 'La génération du PDF est terminée');

        return new Response(json_encode($response));
    }
}
