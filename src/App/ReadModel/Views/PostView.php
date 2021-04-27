<?php


namespace App\ReadModel\Views;


class PostView
{
    public $id;
    public \DateTimeImmutable $date;
    public $title;
    public $content;

    /**
     * PostView constructor.
     * @param $id
     * @param \DateTimeImmutable $date
     * @param $title
     * @param $content
     */
    public function __construct($id, \DateTimeImmutable $date, $title, $content)
    {
        $this->id = $id;
        $this->date = $date;
        $this->title = $title;
        $this->content = $content;
    }



}