<?php
namespace app\controllers;

use Yii;

use yii\web\Controller;
use app\models\Flashcard;
use app\models\FlashcardForm;
use app\models\TermAnswer;
use yii\web\NotFoundHttpException;
use yii\web\Response;


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
        $model = new Flashcard();
        $transaction = Yii::$app->db->beginTransaction();

        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post();

            try {
                // Populate Flashcard model
                $model->user_id = Yii::$app->user->id; // Assuming user is logged in
                $model->title = $postData['FlashcardForm']['set_title'];
                $model->content = $postData['FlashcardForm']['set_description'];
                if (!$model->save()) {
                    throw new \Exception('Failed to save flashcard.');
                }

                // Save Term-Answer pairs
                if (!empty($postData['terms']) && !empty($postData['definitions'])) {
                    foreach ($postData['terms'] as $index => $term) {
                        $definition = $postData['definitions'][$index];
                        $termAnswer = new TermAnswer();
                        $termAnswer->flashcard_id = $model->flashcard_id;
                        $termAnswer->term = $term;
                        $termAnswer->answer = $definition;
                        if (!$termAnswer->save()) {
                            throw new \Exception('Failed to save term-answer pair.');
                        }
                    }
                }

                // Commit transaction
                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Flashcard set created successfully.');
                return $this->redirect(['flashcard/display', 'id' => $model->flashcard_id]);

            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('User-create', [
            'model' => $model,
        ]);
    }

    
    public function actionDisplay($id)
    {
        $flashcard = Flashcard::findOne($id);

        if (!$flashcard) {
            throw new \yii\web\NotFoundHttpException('Flashcard not found.');
        }

        // Fetch related terms and answers
        $terms = TermAnswer::find()
            ->where(['flashcard_id' => $id])
            ->asArray() // Fetch data as an array for JSON encoding
            ->all();

        return $this->render('user-display', [
            'flashcard' => $flashcard,
            'terms' => $terms,
        ]);
    }

    public function actionDelete()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $request = Yii::$app->request;

        if ($request->isPost) {
            $data = json_decode($request->rawBody, true);
            $flashcardId = $data['id'] ?? null;

            if ($flashcardId) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    // Delete associated terms and answers
                    TermAnswer::deleteAll(['flashcard_id' => $flashcardId]);

                    // Delete the flashcard itself
                    $flashcard = Flashcard::findOne($flashcardId);
                    if ($flashcard && $flashcard->delete()) {
                        $transaction->commit();
                        return ['success' => true];
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::error($e->getMessage(), __METHOD__);
                }
            }
        }

        return ['success' => false];
    }

    public function actionUpdate($id)
{
    $flashcard = Flashcard::findOne($id);

    if (!$flashcard) {
        throw new NotFoundHttpException('The requested flashcard does not exist.');
    }

    $termsAndDefinitions = TermAnswer::find()
        ->where(['flashcard_id' => $id])
        ->asArray()
        ->all();

    $model = new FlashcardForm([
        'id' => $flashcard->flashcard_id,
        'set_title' => $flashcard->title,
        'set_description' => $flashcard->content,
    ]);

    if ($this->request->isPost && $model->load($this->request->post())) {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $flashcard->title = $model->set_title;
            $flashcard->content = $model->set_description;

            if (!$flashcard->save()) {
                throw new \Exception('Failed to save flashcard.');
            }

            // Delete existing terms and definitions
            TermAnswer::deleteAll(['flashcard_id' => $flashcard->flashcard_id]);

            // Save the updated terms and definitions
            $terms = $this->request->post('terms', []);
            $definitions = $this->request->post('definitions', []);

            foreach ($terms as $index => $term) {
                $definition = $definitions[$index] ?? '';
                $termAnswer = new TermAnswer([
                    'flashcard_id' => $flashcard->flashcard_id,
                    'term' => $term,
                    'answer' => $definition,
                ]);

                if (!$termAnswer->save()) {
                    throw new \Exception('Failed to save term and definition.');
                }
            }

            $transaction->commit();
            Yii::$app->session->setFlash('success', 'Flashcard set updated successfully!');
            return $this->redirect(['flashcard/display', 'id' => $flashcard->flashcard_id]);
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Error updating flashcard set: ' . $e->getMessage());
        }
    }

    return $this->render('User-edit', [
        'model' => $model,
        'termsAndDefinitions' => $termsAndDefinitions,
    ]);
}
 


}
?>