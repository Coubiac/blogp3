<?php

namespace AppBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Article as Article;

class LoadArticleData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        //Attention, il faut retirer la première ligne (titre des colonnes) avant de lancer l'import
        if (($file = fopen(dirname(__FILE__).'/Resources/articles.csv', 'r')) !== FALSE) {


            $i = 1;

            while (($column = fgetcsv($file, 0, ";")) !== FALSE)
            {

                $article = new Article();
                $article->setTitle($column[0]);
                $article->setContent($column[1]);
                $article->setSlug($column[2]);
                $manager->persist($article);
                $this->addReference('article'.$i, $article);
                $i++;


            }
            fclose($file);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}