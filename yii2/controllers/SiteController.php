<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\SignupForm;
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
        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            // Explicitly redirect to the landing page upon successful login
            return $this->redirect(['site/landing-page']);
        }

        return $this->render('loginPage', [
            'model' => $model,
        ]);
    }



    public function actionLandingPage()
    {
        // Make sure the user is logged in
        if (Yii::$app->user->isGuest) {
            // If not logged in, redirect to login page
            return $this->redirect(['login']);
        }

        // Render the landing page if the user is logged in
        return $this->render('landingPage');
    }

    /**
     * Logout action.
     *
     * @return Response
     */

     public function actionLogout()
     {
         Yii::$app->user->logout();
         return $this->goHome();
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

    public function actionTestDb()
    {
        $db = Yii::$app->db;

        // Check and return connection status
        if ($db->isActive) {
            return "Database connection is successful!";
        } else {
            try {
                $db->open(); // Force connection
                return $db->isActive ? "Database connection established successfully!" : "Failed to connect.";
            } catch (\Exception $e) {
                return "Database connection failed: " . $e->getMessage();
            }
        }
    }


    public function actionCreateTestUser()
    {
        $hashedPassword = Yii::$app->security->generatePasswordHash('testpassword');
        Yii::$app->db->createCommand()->insert('user', [
            'user_name' => 'testuser',
            'password' => $hashedPassword,
        ])->execute();

        return "Test user created successfully!";
    }

    public function actionTestPassword()
    {
        $user = \app\models\User::findByUsername('testuser');
        if (!$user) {
            return "User not found!";
        }

        $isValid = Yii::$app->security->validatePassword('testpassword', $user->password);
        return $isValid ? "Password is valid!" : "Password is invalid!";
    }

    public function actionSignup()
    {
        $model = new SignupForm();

        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Account created successfully!');
            return $this->redirect(['site/login']);
        }

        return $this->render('signupPage', [
            'model' => $model,
        ]);
    }

    



}
