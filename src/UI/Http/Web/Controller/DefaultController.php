<?php

declare(strict_types=1);

namespace UI\Http\Web\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;


abstract class DefaultController extends AbstractController {

    public function index()
    {
        return new RedirectResponse('/api/doc');
    }

}