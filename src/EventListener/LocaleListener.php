<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpKernel\Event\RequestEvent;

const AVAILABLE_LANGUAGES = array("en", "fr");

class LocaleListener
{
    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        $languages = $request->getLanguages();

        foreach($languages as $language)
        {
            foreach(AVAILABLE_LANGUAGES as $lang)
            {
                if(str_starts_with($language, $lang))
                {
                    $request->setLocale($language);
                    break 2;
                }
            }
        }
    }
}

