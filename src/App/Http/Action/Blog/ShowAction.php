<?php

namespace App\Http\Action\Blog;

use App\ReadModel\PostReadRepository;
use Framework\View\Twig\TwigRender;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

class ShowAction
{
    private PostReadRepository $posts;
    private TwigRender $template;

    /**
     * ShowAction constructor.
     * @param \App\ReadModel\PostReadRepository $posts
     * @param \Framework\View\Twig\TwigRender $template
     */
    public function __construct(PostReadRepository $posts, TwigRender $template)
    {
        $this->posts = $posts;
        $this->template = $template;
    }


    /**
     * @throws \Twig\Error\SyntaxError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\LoaderError
     */
    public function __invoke(ServerRequestInterface $request, callable $next)
    {

        if (!$post = $this->posts->find($request->getAttribute('id'))) {
            return $next($request);
        }

        return new HtmlResponse($this->template->render('app/blog/show', [
            'post' => $post,
        ]));
    }
}