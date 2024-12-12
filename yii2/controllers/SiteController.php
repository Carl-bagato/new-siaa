<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome(); // If the user is already logged in, redirect to the homepage.
        }

        $model = new LoginForm();
        
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack(); // Redirect user back to the page they were originally trying to visit
        }

        // If the login fails, clear the password field and render the login page with the model.
        $model->password = '';
        return $this->render('loginPage', [
            'model' => $model,
        ]);
    }


    /**
     * Logout action.
     *
     * @return Response
     */

     public function actionLogout()
     {
         Yii::$app->user->logout();
         
         Yii::$app->session->destroy();

         return $this->render(['index']);
     }
     public function actionCloseFlashcard()
    {
        Yii::$app->session->remove('flashcards'); // Clear flashcard data
        return $this->redirect(['site/index']);  // Redirect to the homepage
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Handles flashcard creation.
     *
     * @return string|Response
     */
    public function actionCreate()
    {
        if (Yii::$app->request->isPost) {
            // Gather title, description, and flashcard data from POST request
            $title = Yii::$app->request->post('setTitle', 'Untitled');
            $description = Yii::$app->request->post('setDescription', '');
            $flashcards = Yii::$app->request->post('flashcards', []);

            // Store data in session
            Yii::$app->session->set('flashcards', [
                'title' => $title,
                'description' => $description,
                'cards' => $flashcards,
            ]);

            Yii::$app->session->setFlash('flashcardCreated', 'Flashcard set created successfully!');
            return $this->redirect(['display-flashcard']);
        }

        return $this->render('create-flashcard');
    }

    /**
     * Displays flashcards.
     *
     * @return string
     */
    public function actionDisplayFlashcard()
    {
        // Retrieve flashcard data from session
        $flashcards = Yii::$app->session->get('flashcards', null);

        if (!$flashcards) {
            Yii::$app->session->setFlash('error', 'No flashcards found!');
        }

        return $this->render('display-flashcard', [
            'flashcards' => $flashcards,
        ]);
    }
}
