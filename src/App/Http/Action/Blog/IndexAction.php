<?php

namespace App\Http\Action\Blog;

use App\ReadModel\PostReadRepository;
use Framework\View\Twig\TwigRender;
use Zend\Diactoros\Response\HtmlResponse;

class IndexAction
{
    private PostReadRepository $posts;
    private TwigRender $template;

    /**
     * IndexAction constructor.
     * @param \App\ReadModel\PostReadRepository $posts
     * @param \Framework\View\Twig\TwigRender $template
     */
    public function __construct(PostReadRepository $posts, TwigRender $template)
    {
        $this->posts = $posts;
        $this->template = $template;
    }

    /**
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \Twig\Error\LoaderError
     */
    public function __invoke(): HtmlResponse
    {
        $allPost = $this->posts->getAll();

        return new HtmlResponse($this->template->render('app/blog/index', [
            'posts' => $allPost,
        ]));
    }
}