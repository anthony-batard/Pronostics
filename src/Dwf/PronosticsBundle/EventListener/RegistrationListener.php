<?php
namespace Dwf\PronosticsBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\Common\Util\Debug;

/**
 * Listener responsible for adding user to a contest at registration
 */
class RegistrationListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
                FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
        );
    }

    public function onRegistrationSuccess(FormEvent $event)
    {

        /** @var $user \FOS\UserBundle\Model\UserInterface */
        $user = $event->getForm()->getData();
        $invitation = $user->getInvitation();
        //Debug::dump($user,3);exit();
        if($invitation) {
            $user->addGroup($invitation->getContest());
        }
    }
}