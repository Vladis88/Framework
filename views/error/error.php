<?php
/**
 * @var \Framework\View\Php\PhpViewRender $this
 */

$this->extend('layout/default');
?>

<?php $this->startBlock('title'); ?>500 - Server error<?php $this->endBlock(); ?>

<?php $this->startBlock('breadcrumbs'); ?>
    <ul class="breadcrumb">
        <li><a href="/">Home</a></li>
        <li class="active">Error</li>
    </ul>
<?php $this->endBlock(); ?>

<?php $this->startBlock('content'); ?>
    <h1>500 - Server error</h1>
<?php $this->endBlock(); ?>