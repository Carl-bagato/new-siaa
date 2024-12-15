<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\FlashcardForm $model */
/** @var array $termsAndDefinitions */

$this->title = 'Edit Flashcard Set';
$this->registerCssFile('@web/css/userCreate.css', ['depends' => [\yii\bootstrap5\BootstrapAsset::class]]);

// Load the flashcard data (passed as variables from the controller)
$flashcardId = $model->id;
$setTitle = $model->set_title;
$setDescription = $model->set_description;
?>

<div class="container my-5">
    <div class="card shadow-lg border-0 p-4">
        <div class="d-flex justify-content-between mb-4">
            <button class="btn btn-secondary" onclick="history.back()">Back</button>
            <button type="reset" class="btn btn-danger" form="flashcardForm">Reset</button>
        </div>

        <h3 class="text-center mb-4">Edit Flashcard Set</h3>

        <?php $form = ActiveForm::begin([
            'id' => 'flashcardForm',
            'method' => 'post',
            'enableClientValidation' => true,
            'options' => ['enctype' => 'multipart/form-data'],
        ]); ?>

        <?= $form->field($model, 'set_title')->textInput(['name' => 'FlashcardForm[set_title]', 'value' => $setTitle, 'placeholder' => 'Enter set title', 'required']) ?>
        <?= $form->field($model, 'set_description')->textarea(['name' => 'FlashcardForm[set_description]', 'value' => $setDescription, 'placeholder' => 'Enter a brief description', 'rows' => 3, 'required']) ?>

        <div id="flashcards">
            <?php if (!empty($termsAndDefinitions)): ?>
                <?php foreach ($termsAndDefinitions as $index => $termDef): ?>
                    <div class="card-item border p-3 mb-3 rounded" id="card<?= $index + 1 ?>">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold">Card <?= $index + 1 ?></h6>
                            <button type="button" class="btn btn-danger btn-sm" onclick="deleteCard(<?= $index + 1 ?>)">Delete</button>
                        </div>
                        <div class="d-flex gap-3">
                            <div class="mb-3 flex-grow-1">
                                <label for="term<?= $index + 1 ?>" class="form-label fw-bold">Term</label>
                                <input type="text" name="terms[]" class="form-control" id="term<?= $index + 1 ?>" value="<?= Html::encode($termDef['term']) ?>" placeholder="Enter term" required>
                            </div>
                            <div class="mb-3 flex-grow-1">
                                <label for="definition<?= $index + 1 ?>" class="form-label fw-bold">Definition</label>
                                <textarea name="definitions[]" class="form-control" id="definition<?= $index + 1 ?>" rows="2" placeholder="Enter definition" required><?= Html::encode($termDef['answer']) ?></textarea>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <button type="button" class="btn btn-outline-success w-100 my-3" onclick="addCard()">Add More Card</button>

        <div class="text-center">
            <?= Html::submitButton('Update Flashcard Set', ['class' => 'btn btn-primary-submit w-100']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<script type="text/javascript">

    let cardCounter = <?= count($termsAndDefinitions) ?>;

    // Function to dynamically add cards
    function addCard() {
        const flashcardsDiv = document.getElementById("flashcards");

        const cardHTML = `
            <div class="card-item border p-3 mb-3 rounded" id="card${cardCounter + 1}">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold">Card ${cardCounter + 1}</h6>
                    <button type="button" class="btn btn-danger btn-sm" onclick="deleteCard(${cardCounter + 1})">Delete</button>
                </div>
                <div class="d-flex gap-3">
                    <div class="mb-3 flex-grow-1">
                        <label for="term${cardCounter + 1}" class="form-label fw-bold">Term</label>
                        <input type="text" name="terms[]" class="form-control" id="term${cardCounter + 1}" placeholder="Enter term" required>
                    </div>
                    <div class="mb-3 flex-grow-1">
                        <label for="definition${cardCounter + 1}" class="form-label fw-bold">Definition</label>
                        <textarea name="definitions[]" class="form-control" id="definition${cardCounter + 1}" rows="2" placeholder="Enter definition" required></textarea>
                    </div>
                </div>
            </div>
        `;

        flashcardsDiv.insertAdjacentHTML("beforeend", cardHTML);
        cardCounter++;
    }

    // Function to delete a specific card
    function deleteCard(cardNumber) {
        const cardElement = document.getElementById(`card${cardNumber}`);
        if (cardElement) {
            cardElement.remove();
        }
    }
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
