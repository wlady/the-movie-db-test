<?php

/**
 * This is the model class for table "tbl_movie".
 *
 * The followings are the available columns in table 'tbl_movie':
 * @property integer $id
 * @property integer $tmdbID
 * @property string $title
 * @property string $original_title
 * @property string $overview
 * @property string $release_date
 * @property integer $runtime
 * @property float $vote_average
 * @property string $poster_path
 * @property string $genres
 * @property integer $rate
 */
class Movie extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'tbl_movie';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title', 'required'),
            array('tmdbID, runtime', 'numerical', 'integerOnly' => true),
            array('vote_average', 'numerical'),
            array('rate', 'numerical', 'min' => 0, 'max' => 5),
            array('title, original_title', 'length', 'max' => 255),
            array('overview, release_date, poster_path, genres', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array(
                'id, tmdbID, title, original_title, overview, release_date, runtime, vote_average, poster_path, genres, rate',
                'safe',
                'on' => 'search'
            ),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id'             => 'ID',
            'tmdbID'         => 'TMDb ID',
            'title'          => 'Title',
            'original_title' => 'Original Title',
            'overview'       => 'Overview',
            'release_date'   => 'Release Date',
            'runtime'        => 'Runtime',
            'vote_average'   => 'Vote Average',
            'poster_path'    => 'Poster Path',
            'rate'           => 'My Rate',
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Movie the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function persist($data)
    {
        $fileName = $data->poster_path;
        $data->poster_path = TMDbSession::getConfigurations()->images->base_url . '/w185/' . $fileName;
        $data->tmdbID = $data->id;
        // save image
        if (!file_exists(getcwd() . '/images/' . $data->tmdbID)) {
            mkdir(getcwd() . '/images/' . $data->tmdbID);
        }
        if (copy($data->poster_path, getcwd() . '/images/' . $data->tmdbID . $fileName)) {
            $data->poster_path = '/images/' . $data->tmdbID . $fileName;
        }
        // collect genres
        $data->genres = array_map(function ($item) {
            return $item->name;
        }, $data->genres);
        // prepare & save record
        $this->attributes = get_object_vars($data);
        $this->insert();

        return $this->findByPk(Yii::app()->db->getLastInsertID());
    }

    protected function afterFind()
    {
        parent::afterFind();
        if (is_string($this->genres)) {
            $this->genres = explode(',', $this->genres);
        }
    }

    protected function beforeSave()
    {
        parent::beforeSave();
        if (is_array($this->genres)) {
            $this->genres = implode(',', $this->genres);
        }

        return true;
    }

}
