<?php

/** @var \Framework\View\PhpViewRender $this */
$this->extend('layout/default');
?>

<?php $this->startBlock('title'); ?>
<?= $this->encode($post->title) ?>
<?php $this->endBlock(); ?>

<?php $this->startBlock('meta'); ?>
    <meta name="description" content="<?= $this->encode($post->title) ?>"/>
<?php $this->endBlock(); ?>

<?php $this->startBlock('content') ?>

    <ul class="breadcrumb">
        <li><a href="<?= $this->encode($this->path('home')) ?>">Home</a></li>
        <li><a href="<?= $this->encode($this->path('blog')) ?>">Blog</a></li>
        <li class="active"><?= $this->encode($post->title) ?></li>
    </ul>

    <h1><?= $this->encode($post->title) ?></h1>

    <div class="panel panel-default">
        <div class="panel-heading">
            <?= $post->date->format('Y-m-d') ?>
        </div>
        <div class="panel-body">
            <?= nl2br($this->encode($post->content)) ?>
        </div>
    </div>
<?php $this->endBlock(); ?>