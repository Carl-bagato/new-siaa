<?php
namespace app\controllers;

use Yii;

use yii\web\Controller;
use app\models\Flashcard;
use app\models\FlashcardForm;
use app\models\TermAnswer;
use yii\web\NotFoundHttpException;


class FlashcardController extends Controller
{
    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        return parent::beforeAction($action);
    }
    public function actionIndex()
    {
        $userId = Yii::$app->user->identity->user_id; // Get current logged-in user ID
        // Fetch flashcards for the logged-in user
        $flashcards = Flashcard::find()->where(['user_id' => $userId])->all();
        
        return $this->render('flashcardDashboard', [
            'flashcards' => $flashcards,
        ]);
    }

    public function actionCreate()
    {
        $model = new FlashcardForm();

        if ($model->load(Yii::$app->request->post())) {
            Yii::info('Form data: ' . json_encode(Yii::$app->request->post()), __METHOD__);
            if ($model->validate()) {
                try {
                    $user_id = Yii::$app->user->identity->user_id;
                    $flashcard = $model->saveFlashcardSet($user_id);
                    Yii::$app->session->setFlash('success', 'Flashcard set created successfully!');
                    return $this->redirect(['flashcard/user-display', 'id' => $flashcard->flashcard_id]);
                } catch (\Exception $e) {
                    Yii::$app->session->setFlash('error', 'Error: ' . $e->getMessage());
                }
            } else {
                Yii::$app->session->setFlash('error', 'Validation failed: ' . json_encode($model->errors));
            }
        }        

        return $this->render('user-create', [
            'model' => $model,
        ]);
    }

    
    public function actionUserDisplay($id)
{
    $flashcard = Flashcard::findOne($id);
    if (!$flashcard) {
        throw new NotFoundHttpException('The requested flashcard does not exist.');
    }

    // Assuming you have a TermAnswer model to fetch terms related to this flashcard
    $terms = TermAnswer::find()
        ->where(['flashcard_id' => $flashcard->flashcard_id])
        ->all();

    return $this->render('user-display', [
        'flashcard' => $flashcard,
        'terms' => $terms,
    ]);
}



}
?>