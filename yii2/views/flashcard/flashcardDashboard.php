<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Flashcard[] $flashcards */

$this->title = 'Your Flashcards';
$this->registerCssFile('@web/css/dashboard.css', ['depends' => [\yii\bootstrap5\BootstrapAsset::class]]);
?>

<div class="container my-5">
    <button class="btn btn-secondary" onclick="history.back()">Back</button>
    <?= Html::a('Log Out', ['site/logout'], [
        'class' => 'btn btn-danger position-absolute top-0 end-0 m-3',
        'data-method' => 'post',
    ]) ?>

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>
    <div id="flashcardList">
        <?php if (!empty($flashcards)): ?>
            <?php foreach ($flashcards as $flashcard): ?>
                <div class="card shadow-lg border-0 p-4" data-id="<?= $flashcard->flashcard_id ?>">
                    <h3 class="text-center mb-4"><?= Html::encode($flashcard->title) ?></h3>
                    <p><?= nl2br(Html::encode($flashcard->content)) ?></p>
                    <small class="text-muted">Created on: <?= Yii::$app->formatter->asDate($flashcard->date_created) ?></small>
                    <div class="text-center mt-3">
                        <?= Html::a('Display', ['flashcard/UserDisplay', 'id' => $flashcard->flashcard_id], ['class' => 'btn btn-primary btn-sm']) ?>
                        <?= Html::a('Edit', ['flashcard/update', 'id' => $flashcard->flashcard_id], ['class' => 'btn btn-warning btn-sm']) ?>
                        <button class="btn btn-danger btn-sm delete-btn">Delete</button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-info text-center">
                No flashcards found. Start creating some!
            </div>
        <?php endif; ?>
    </div>

    <div class="text-center mt-3">
        <?= Html::a('Add New Flashcard Set', ['flashcard/create'], ['class' => 'btn btn-primary']) ?>
    </div>
</div>

<?php
$this->registerJs("
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const card = this.closest('.card');
            const id = card.dataset.id;

            if (confirm('Are you sure you want to delete this flashcard?')) {
                fetch('" . Url::to(['flashcard/delete']) . "', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        card.style.transition = 'opacity 1s';
                        card.style.opacity = '0';
                        setTimeout(() => card.remove(), 1000);
                    } else {
                        alert('Failed to delete flashcard.');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    });
");
?>
