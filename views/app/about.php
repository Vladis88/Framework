<?php
/** @var string $name */
/** @var \Framework\View\PhpViewRender $this */
$this->extend('layout/columns');
$this->params['title'] = 'About';

?>

<?php $this->startBlock(); ?>
<div class="panel panel-default">
    <div class="panel-heading">Site</div>
    <div class="panel-body">
        Site navigation
    </div>
</div>
<?php $this->endBlock('sidebar'); ?>

<ul class="breadcrumb">
    <li><a href="/">Home</a></li>
    <li class="active">About</li>
</ul>
<h1>About the site</h1>
