<?php
/**
 * @var \Framework\View\PhpViewRender $this
 */

$this->extend('layout/default');
?>

<?php $this->startBlock('title'); ?>404 - Not found<?php $this->endBlock(); ?>

<?php $this->startBlock('breadcrumbs'); ?>
    <ul class="breadcrumb">
        <li><a href="/">Home</a></li>
        <li class="active">Error</li>
    </ul>
<?php $this->endBlock(); ?>

<?php $this->startBlock('content'); ?>
    <h1>404 - Not Found</h1>
<?php $this->endBlock(); ?>