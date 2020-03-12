<?php
/**
 * Created by PhpStorm.
 * User: xing.chen
 * Date: 2018/9/17
 * Time: 23:59
 */

namespace xing\article\logic;


use xing\article\logic\LanguageLogic;
use xing\article\map\ArticleMap;
use xing\article\map\LanguageMap;
use xing\article\models\Category;
use xing\article\modules\site\Region;
use xing\article\modules\site\SiteRegion;
use xing\helper\text\ToPinYinHelper;

class ArticleUrlLogic
{

    /**
     * 获取文章url
     * @param $articleId
     * @param null $catDir
     * @param null $lan
     * @return string
     * @throws \Exception
     */
    public static function articleUrl($articleId, $catDir = null, $lan = null)
    {
        if (is_null($catDir)) $catDir = ArticleLogic::getCategory($articleId)->dir ?? '';
        if (empty($catDir)) throw new \Exception('文章id为'. $articleId .'的栏目目录为空，请管理员修正');

        is_null($lan) && $lan = static::getByLanguage();
        return '/'. $lan . '/' . $catDir . '/view-' . $articleId . ArticleMap::SUFFIX;
    }

    /**
     * 获取栏目url（根据栏目目录名）
     * @param $catDir
     * @param int $page
     * @param null $lan
     * @return string
     * @throws \Exception
     */
    public static function categoryDirByUrl($catDir, $page = 1, $lan = null)
    {
        if (empty($catDir)) throw new \Exception('栏目目录为空');

        is_null($lan) && $lan = static::getByLanguage();
        $url = '/'. $lan . '/' . $catDir;
        // 第2页以后后面开始翻页
        $page > 1 && $url .= '/' . $page . ArticleMap::SUFFIX;
        return $url;
    }

    /**
     * 获取栏目url（根据栏目id）
     * @param $categoryId
     * @param int $page
     * @param null $lan
     * @return string
     * @throws \Exception
     */
    public static function categoryIdByUrl($categoryId, $page = 1, $lan = null)
    {
        $category = Category::findOne($categoryId);
        if (empty($category)) throw new \Exception('没有这个栏目');

        $catDir = $category->dir;
        if (empty($catDir)) throw new \Exception('栏目目录为空，请管理员修正');

        return static::categoryDirByUrl($catDir, $page, $lan);
    }
    /**
     * 从GET参数中中获取语言
     * @return string
     */
    public static function getByLanguage()
    {
        return \Yii::$app->request->get('lan') ?: LanguageLogic::getDefaultLanguage();
    }

    /**
     * 获取语言
     * @return string
     */
    public static function getLanguage()
    {
        $language = $_REQUEST['language'] ?? '';
        // 获取默认语言
        $lang = LanguageLogic::getDefaultLanguage();
        // 如果传递了语言参数，则根据参数定位语言
        if(!empty($language)) {
            $lang = LanguageLogic::getLanguageContrast($language);
            if(empty($lang)) {
                $array = explode('-', $language);
                $language = count($array) > 2 ? $array[0].'-'.$array[1] : $array[0];
                $lang = LanguageLogic::getLanguageContrast($language);
            }
        }

        return $lang;
    }

    public static function getTopDomain()
    {
        return TOP_DOMAIN;
    }

    /**
     * 地区二级域名
     * @param string $code
     * @return string
     */
    public static function region2url($code = 'www')
    {
        $scheme = isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] . '://' : '';
        return $scheme.  $code . '.' . static::getTopDomain();
    }
}