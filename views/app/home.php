<?php
/** @var string $name */
/** @var \Framework\View\PhpViewRender $this */

$this->extend('layout/default');
?>

<?php $this->startBlock('title') ?>Home<?php $this->endBlock(); ?>

<?php $this->startBlock('meta'); ?>
    <meta name="description" content="Home Page description"/>
<?php $this->endBlock(); ?>

<?php $this->startBlock('content'); ?>
    <div class="jumbotron">
        <h1>Hello, <?= htmlspecialchars($name) ?>!</h1>
        <p>
            Congratulations! You have successfully created your application.
        </p>
    </div>
<?php $this->endBlock(); ?>