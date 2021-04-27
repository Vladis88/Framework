<?php

namespace App\Http\Action\Blog;

use App\ReadModel\PostReadRepository;
use Framework\View\Php\PhpViewRender;
use Zend\Diactoros\Response\HtmlResponse;

class IndexAction
{
    private PostReadRepository $posts;
    private PhpViewRender $template;

    /**
     * IndexAction constructor.
     * @param \App\ReadModel\PostReadRepository $posts
     * @param \Framework\View\Php\PhpViewRender $template
     */
    public function __construct(PostReadRepository $posts, PhpViewRender $template)
    {
        $this->posts = $posts;
        $this->template = $template;
    }

    public function __invoke(): HtmlResponse
    {
        $allPost = $this->posts->getAll();

        return new HtmlResponse($this->template->render('app/blog/index', [
            'posts' => $allPost,
        ]));
    }
}