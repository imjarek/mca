<?php

declare(strict_types=1);

namespace UI\Http\Web\Controller;


use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @Route(
 *     "/",
 *     name="home",
 *     methods={"GET"}
 * )
 *
 * @throws LoaderError
 * @throws RuntimeError
 * @throws SyntaxError
 */
abstract class MainController extends AbstractRenderController {

    public function get()
    {
        return new RedirectResponse('/api/doc');
    }

}