<?php
namespace Dwf\PronosticsBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Dwf\PronosticsBundle\Entity\GameType;
use Cocur\Slugify\Slugify;

class LoadTypeData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $types = array(
                'Groupe A',
                'Groupe B',
                'Groupe C',
                'Groupe D',
                'Groupe E',
                'Groupe F',
                'Huitième de Finale',
                'Quart de Finale',
                'Demi Finale',
                'Finale',
        );
        foreach ($types as $typeName) {
            
            $gameType = new GameType();
            
            $gameType->setName($typeName);
            $gameType->setCanHaveOvertime(false);
            
            $gameType->addEvent($this->getReference("UEFA Euro 2016"));
            
            $manager->persist($gameType);
            $manager->flush();
            
            $this->addReference($typeName, $gameType);
        }
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 4;
    }
}