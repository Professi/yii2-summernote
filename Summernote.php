<?php

namespace marqu3s\summernote;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;

class Summernote extends InputWidget
{
    /** @var array */
    private $defaultOptions = ['class' => 'form-control'];
    /** @var array */
    private $defaultClientOptions = [
        'height' => 200,
        'codemirror' => [
            'theme' => 'monokai'
        ]
    ];
    /** @var array */
    public $options = [];
    /** @var array */
    public $clientOptions = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->options = array_merge($this->defaultOptions, $this->options);
        $this->clientOptions = array_merge($this->defaultClientOptions, $this->clientOptions);
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerAssets();

        echo $this->hasModel()
            ? Html::activeTextarea($this->model, $this->attribute, $this->options)
            : Html::textarea($this->name, $this->value, $this->options);
        $clientOptions = empty($this->clientOptions)
            ? null
            : Json::encode($this->clientOptions);
        $this->getView()->registerJs('jQuery( "#' . $this->options['id'] . '" ).summernote(' . $clientOptions . ');');
    }

    private function registerAssets()
    {
        $view = $this->getView();

        if (ArrayHelper::getValue($this->clientOptions, 'codemirror')) {
            CodemirrorAsset::register($view);
        }

        SummernoteAsset::register($view);

        if ($language = ArrayHelper::getValue($this->clientOptions, 'lang', null)) {
            SummernoteLanguageAsset::register($view)->language = $language;
        }
    }
}
