<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\FlashcardForm $model */

$this->title = 'Create Flashcard Set';
$this->registerCssFile('@web/css/userCreate.css', ['depends' => [\yii\bootstrap5\BootstrapAsset::class]]);
?>

<div class="container my-5">
    <div class="card shadow-lg border-0 p-4">
        <div class="d-flex justify-content-between mb-4">
            <button class="btn btn-secondary" onclick="history.back()">Back</button>
            <button type="reset" class="btn btn-danger" form="flashcardForm">Reset</button>
        </div>

        <h3 class="text-center mb-4">Create a new Flashcard Set</h3>

        <?php $form = ActiveForm::begin([
            'id' => 'flashcardForm',
            'method' => 'post',
            'enableClientValidation' => true,
            'options' => ['enctype' => 'multipart/form-data'],
        ]); ?>

        <?= $form->field($model, 'set_title')->textInput(['name' => 'FlashcardForm[set_title]', 'placeholder' => 'Enter set title']) ?>
        <?= $form->field($model, 'set_description')->textarea(['name' => 'FlashcardForm[set_description]', 'placeholder' => 'Enter a brief description', 'rows' => 3]) ?>

        <div id="flashcards">
            <!-- Dynamic flashcards will be added via JavaScript -->
        </div>

        <button type="button" class="btn btn-outline-success w-100 my-3" onclick="addCard()">Add More Card</button>

        <div class="text-center">
            <?= Html::submitButton('Create Flashcard Set', ['class' => 'btn btn-primary-submit w-100']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<script type="text/javascript">
    let cardCounter = 1;

    // Function to dynamically add cards
    function addCard() {
        const flashcardsDiv = document.getElementById("flashcards");

        const cardHTML = `
            <div class="card-item border p-3 mb-3 rounded" id="card${cardCounter}">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold">Card ${cardCounter}</h6>
                    <button type="button" class="btn btn-danger btn-sm" onclick="deleteCard(${cardCounter})">Delete</button>
                </div>
                <div class="d-flex gap-3">
                    <div class="mb-3 flex-grow-1">
                        <label for="term${cardCounter}" class="form-label fw-bold">Term</label>
                        <input type="text" name="terms[]" class="form-control" id="term${cardCounter}" placeholder="Enter term" required>
                    </div>
                    <div class="mb-3 flex-grow-1">
                        <label for="definition${cardCounter}" class="form-label fw-bold">Definition</label>
                        <textarea name="definitions[]" class="form-control" id="definition${cardCounter}" rows="2" placeholder="Enter definition" required></textarea>
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

    // Add initial 5 cards when the page loads
    window.onload = function() {
        for (let i = 0; i < 5; i++) {
            addCard();
        }
    };
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
