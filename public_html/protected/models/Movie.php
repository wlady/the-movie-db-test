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
 * @property integer $vote_average
 * @property string $poster_path
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
			array('tmdbID, runtime, vote_average', 'numerical', 'integerOnly'=>true),
			array('title, original_title', 'length', 'max'=>255),
			array('overview, release_date, poster_path', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, tmdbID, title, original_title, overview, release_date, runtime, vote_average, poster_path', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'tmdbID' => 'Tmdb',
			'title' => 'Title',
			'original_title' => 'Original Title',
			'overview' => 'Overview',
			'release_date' => 'Release Date',
			'runtime' => 'Runtime',
			'vote_average' => 'Vote Average',
			'poster_path' => 'Poster Path',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('tmdbID',$this->tmdbID);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('original_title',$this->original_title,true);
		$criteria->compare('overview',$this->overview,true);
		$criteria->compare('release_date',$this->release_date,true);
		$criteria->compare('runtime',$this->runtime);
		$criteria->compare('vote_average',$this->vote_average);
		$criteria->compare('poster_path',$this->poster_path,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Movie the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
