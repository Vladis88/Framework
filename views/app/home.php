<?php
/** @var string $name */
/** @var \Framework\View\PhpViewRender $this */
$this->extend('layout/columns');
$this->params['title'] = 'Home';

?>

<?php $this->startBlock(); ?>
<div class="panel panel-default">
    <div class="panel-heading">Home</div>
    <div class="panel-body">
        Home navigation
    </div>
</div>
<?php $this->endBlock('sidebar'); ?>

<div class="jumbotron">
    <h1>Hello, <?= htmlspecialchars($name) ?>!</h1>
    <p>
        Congratulations! You have successfully created your application.
    </p>
</div>