<?php
/** @var string $name */
/** @var \Framework\View\PhpViewRender $this */

$this->extend('layout/columns');
?>

<?php $this->startBlock('title') ?>About<?php $this->endBlock(); ?>

<?php $this->startBlock('meta'); ?>
<meta name="description" content="About Page description"/>
<?php $this->endBlock(); ?>

<?php $this->startBlock('breadcrumbs'); ?>
<ul class="breadcrumb">
    <li><a href="/">Home</a></li>
    <li class="active">About</li>
</ul>
<?php $this->endBlock(); ?>

<?php $this->startBlock('main'); ?>
<h1>About the site</h1>
<?php $this->endBlock(); ?>

