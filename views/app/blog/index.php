<?php

/** @var \Framework\View\Php\PhpViewRender $this */
$this->extend('layout/default');
?>

<?php $this->startBlock('title'); ?>Blog<?php $this->endBlock(); ?>

<?php $this->startBlock('meta'); ?>
<meta name="description" content="Blog description"/>
<?php $this->endBlock(); ?>

<?php $this->startBlock('content') ?>

<ul class="breadcrumb">
    <li><a href="<?= $this->encode($this->path('home')) ?>">Home</a></li>
    <li class="active">Blog</li>
</ul>

<h1>Blog</h1>

<?php foreach ($posts as $post): ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <span class="pull-right"><?= $post->date->format('Y-m-d') ?></span>
            <a href="<?= $this->encode($this->path('blog_show', ['id' => $post->id])) ?>">
                <?= $this->encode($post->title) ?>
            </a>
        </div>
        <div class="panel-body">
            <?= nl2br($this->encode($post->content)) ?>
        </div>
    </div>

<?php endforeach; ?>
<?php $this->endBlock(); ?>



