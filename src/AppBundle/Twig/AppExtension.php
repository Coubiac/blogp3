<?php
/**
 * Created by PhpStorm.
 * User: ben
 * Date: 19/03/2017
 * Time: 18:07
 */
namespace AppBundle\Twig;

class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('resume', array($this, 'resumeFilter')),
            new \Twig_SimpleFilter('tree', array($this, 'treeFilter')),
        );
    }

    function resumeFilter($string, $wordsreturned = 100)
    {
        //On supprime les balises html de la chaine
        $string = strip_tags($string);
        //On crée un tableau avec tous les mots de la chaine
        $array = explode(" ", $string);
        //Si la longeur du tableau est inférieure au nombre de mots maxi on ne fait rien
        if (count($array) <= $wordsreturned) {
            $retval = $string;
        } // Sinon on garde que le nombre de mots désirés.
        else {
            array_splice($array, $wordsreturned);
            $retval = implode(" ", $array)." ...";
        }

        return $retval;
    }

    /**
     * @param $parent
     * @param $level
     * @param $listComments
     * @return string
     */
    function treeFilter($parent, $level, $listComments)
    {
        dump($listComments);
        $html = "";
        foreach ($listComments AS $comment) {
            if ($parent == $comment->getParent()) {
                for ($i = 0; $i < $level; $i++) {
                    $html .= "-";
                }
                $html .= " <strong>".$comment->getAuthor."</strong><br>".$comment->getContent()."<br>";
                $html .= $this->treeFilter($comment->getId(), ($level + 1), $listComments);
            }
        }

        return $html;
    }


}