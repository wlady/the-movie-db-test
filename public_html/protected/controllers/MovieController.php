<?php

class MovieController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column1';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array(
                'allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('view', 'update', 'delete', 'rate'),
                'users'   => array('@'),
            ),
            array(
                'deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Get requested record from TMDb and saves it to cache (local DB).
     */
    public function actionView()
    {
        Yii::app()->getClientScript()->registerCoreScript('yii');
        $id = Yii::app()->getRequest()->getParam('id');
        if ($id) {
            $movie = new Movie;
            if ($model = $movie->find('tmdbID=:tmdbID', array('tmdbID' => $id))) {
                // found in local DB
                $res = null;
            } else {
                // fetch it from TMDb
                $output = Yii::app()->curl->get('https://api.themoviedb.org/3/movie/' . $id . '?&api_key=' . TMDbSession::getApiKey());
                $res = json_decode($output);
            }
            if (isset($res->status_code)) {
                $this->render('index', array(
                        'error' => $res->status_message,
                    )
                );
            } else {
                if ($res) {
                    // save it to local DB if not yet exists
                    $model = $movie->persist($res);
                }
                $this->render('view', array(
                        'configurations' => TMDbSession::getConfigurations(),
                        'movie'          => $model,
                    )
                );
            }
        }
    }

    /**
     * Updates my personal rate and send it to TMDb
     */
    public function actionRate()
    {
        $id = Yii::app()->getRequest()->getParam('id');
        $rate = Yii::app()->getRequest()->getParam('rating');
        $model = $this->loadModel($id);

        if ($id) {
            Yii::app()->curl->get('https://api.themoviedb.org/3/movie/' . $model->tmdbID . '?guest_session_id='.TMDbSession::getSessionID().'&value='.$rate.'&api_key=' . TMDbSession::getApiKey());
            $model->rate = $rate;
            $model->save();
        }
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        if (isset($_POST['Movie'])) {
            $model->attributes = $_POST['Movie'];
            if ($model->save()) {
                $this->redirect(array('/movie/view', 'id' => $model->tmdbID));
            }
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful the cached images will be deleted and the browser will be redirected to the home page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $model = $this->loadModel($id);
        Yii::log($model->poster_path);
        if (file_exists(getcwd().$model->poster_path)) {
            unlink(getcwd().$model->poster_path);
            CFileHelper::removeDirectory(dirname(getcwd().$model->poster_path));
        }
        $model->delete();

        // return to fixed url (not to entry point)
        $this->redirect(array('/site/index'));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Movie the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Movie::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Movie $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'movie-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
