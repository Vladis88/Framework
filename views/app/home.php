<?php
/** @var string $name */
/** @var \Framework\View\PhpViewRender $this */

$this->extend('layout/default');
?>

<?php $this->startBlock('title') ?>Home<?php $this->endBlock(); ?>

<?php $this->startBlock('mete'); ?>
<meta name="description" content="Home Page description"/>
<?php $this->endBlock(); ?>

<div class="jumbotron">
    <h1>Hello, <?= htmlspecialchars($name) ?>!</h1>
    <p>
        Congratulations! You have successfully created your application.
    </p>
</div>