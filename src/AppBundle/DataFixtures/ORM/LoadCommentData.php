<?php

namespace AppBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Comment as Comment;

class LoadCommentData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        //Attention, il faut retirer la première ligne (titre des colonnes) avant de lancer l'import
        if (($file = fopen(dirname(__FILE__).'/Resources/comments.csv', 'r')) !== FALSE) {


            $i = 0;

            while (($column = fgetcsv($file, 0, ";")) !== FALSE)
            {



                $comment = new Comment();


                if ($column[1] == 0)
                {
                    $comment->setParent(null);
                }
                else{
                    $comment->setParent($this->getReference('comment'.$column[1]));
                }


                $comment->setArticle($this->getReference($column[2]));
                $comment->setAuthor($this->getReference($column[3]));
                $comment->setSignaled($column[4]);
                $comment->setLevel($column[5]);
                $comment->setContent($column[6]);
                $this->setReference('comment'.$column[0], $comment);


                $manager->persist($comment);
            };
            fclose($file);
        };

        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }
}