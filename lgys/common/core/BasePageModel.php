<?php

namespace common\core;

use Yii;
use yii\base\Model;

/**
 * Description of BaseAjaxController
 *
 * @author xiaojx
 */
class BasePageModel extends Model {

    public $page_size=20;
    public $page_index=1;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['page_size', 'page_index'], 'integer'],
            ['page_size', 'default', 'value' => 20],
            ['page_index', 'default', 'value' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'page_size' => '每页行数',
            'page_index' => '页码'
        ];
    }

    /**
     * 分页信息
     * @param type $page_index
     * @param type $counts
     */
    public function getPageData($counts) {
        $page['counts'] = $counts;
        $page['page_index'] = $this->page_index;
        $page['page_count'] = intval(ceil($counts / $this->page_size));
        if($page['page_index']>=$page['page_count']){
            $page['page_index']=$page['page_count'];
        }
        $page['page_size'] = $this->page_size;
        return $page;
    }

}
