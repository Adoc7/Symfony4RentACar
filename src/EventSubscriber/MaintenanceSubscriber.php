<?php

namespace App\EventSubscriber;

use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MaintenanceSubscriber implements EventSubscriberInterface
{
    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function methodCalledOnKernelResponse(FilterResponseEvent $FilterResponseEvent)
    {
        $maintenance = false;

        if ($maintenance) {
            $content = $this->twig->render('maintenance/maintenance.html.twig');
            $response = new Response($content);


            return $FilterResponseEvent->setResponse($response);
        }
        return $FilterResponseEvent->getResponse()->getContent();
    }
    public static function getSubscribedEvents()
    {

        return [
            KernelEvents::RESPONSE => ['methodCalledOnKernelResponse', 255]
        ];
    }
}