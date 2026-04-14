<?php

namespace App\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;

final class JWTCreatedListener
{

    private $requestStack;
    private $manager;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $manager) {
        $this->requestStack = $requestStack;
        $this->manager = $manager;
    }

    public function onJWTCreated(JWTCreatedEvent $event) {

        $request = $this->requestStack->getCurrentRequest();

        $user = $event->getUser();

        $payload = $event->getData();
        $payload['user_id'] = $user->getId();
        $payload['user_email'] = $user->getEmail();
        $payload['user_name'] = $user->getPerson()->getFirstName() . ' ' . $user->getPerson()->getMiddleName() . ' ' . $user->getPerson()->getLastName();

        $event->setData($payload);

        $header = $event->getHeader();
        /* $header['cty'] = 'JWT'; */

        $event->setHeader($header);
    }

}
