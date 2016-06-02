<?php

class SiteController extends Controller
{

    const MAX_ITEMS_PER_PAGE = 20;
    const LIST_POPULAR = 1;
    const LIST_CURRENT = 2;

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    public function accessRules()
    {
        return array(
            array(
                'allow',  // allow all users to perform 'index' and 'view' actions
                'actions' => array('login', 'logout'),
                'users'   => array('*'),
            ),
            array(
                'allow',  // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'current', 'view'),
                'users'   => array('@'),
            ),
            array(
                'deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        $this->renderHomePage(self::LIST_POPULAR);
    }

    /**
     * This is the 'current' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionCurrent()
    {
        $this->renderHomePage(self::LIST_CURRENT);
    }

    private function renderHomePage($mode = self::LIST_POPULAR)
    {
        $page = Yii::app()->getRequest()->getParam('page');
        if (!$page) {
            $page = 1;
        }

        $url = $mode == self::LIST_CURRENT ?
            'https://api.themoviedb.org/3/movie/now_playing?sort_by=release_date.desc&page=' . $page . '&api_key=' . TMDbSession::getApiKey() :
            'https://api.themoviedb.org/3/movie/popular?sort_by=popularity.desc&page=' . $page . '&api_key=' . TMDbSession::getApiKey();

        $output = Yii::app()->curl->get($url);
        $res = json_decode($output);
        if (isset($res->status_code)) {
            $this->render('index', array(
                    'error' => $res->status_message,
                )
            );
        } else {
            $pagination = new CPagination($res->total_results);
            $pagination->pageSize = self::MAX_ITEMS_PER_PAGE;

            $this->render('index', array(
                    'configurations' => TMDbSession::getConfigurations(),
                    'movies'         => $res->results,
                    'pagination'     => $pagination
                )
            );
        }
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest) {
                echo $error['message'];
            } else {
                $this->render('error', $error);
            }
        }
    }

    /**
     * Displays the login page
     */
    public function actionLogin()
    {
        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login()) {
                $this->redirect(Yii::app()->user->returnUrl);
            }
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

}

