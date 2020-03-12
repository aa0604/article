<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel xing\article\models\search\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Articles';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-index form-inline">

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('增加', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 不显示搜索框 'filterModel' => $searchModel,
        'columns' => [

            'articleId',
            [
                'attribute' => 'title',
                'format' => 'raw',
                'value' => function($data) {
        return '<a href="' . \common\logic\article\ArticleUrlLogic::articleUrl($data->articleId) . '" target="_blank">' . $data->title . '</a>';
                }
            ],
            [
                'attribute' => 'categoryId',
                'format' => 'raw',
                'value' => function($data) {
                    $categoryName = \xing\article\models\Category::readByName($data->categoryId);
                    return Html::a($categoryName, '?ArticleSearch[categoryId]=' . $data->categoryId);
                }
            ],
            [
                'label' => '查看数量',
                'format' => 'raw',
                'value' => function($data) {
                    $view = \xing\article\models\ArticleView::one($data->articleId);
                    return $view->all ?? 0;
                }
            ],
            'keywords',
//            'description',
            'sorting',
            //'allowComment',
            'createTime:datetime',
            'updateTime:datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
