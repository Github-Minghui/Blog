<?php

namespace App\Admin\Controllers\wiki;

use App\Http\Controllers\Controller;
use App\Model\wiki\WikiProject;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

/***
 * Wiki 管理控制器
 * Class WikiController
 * @package App\Admin\Controllers\wiki
 */
class WikiController extends Controller
{
    /**
     * 表单对象，包含新增、删除等操作
     */
    use ModelForm;

    /**
     * 首页，显示Wiki 项目列表
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content->title('Wiki管理')
            ->body($this->projectList());

    }

    /**
     * 创建新项目
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content->title("新建项目")
            ->body($this->form());
    }

    /**
     * Wiki项目列表
     */
    private function projectList()
    {
        $grid = new Grid(new WikiProject());

        $grid->name('项目名称');
        $grid->description('项目描述');
        $grid->doc_count('文档数量');
        $grid->type('类型')->display(function ($type) {
            switch ($type) {
                case WikiProject::$TYPE_PRIVATE:
                    return "<span class='label label-danger'>私密</span>";
                case WikiProject::$TYPE_PUBLIC:
                    return "<span class='label label-success'>公开</span>";
                default:
                    return "<span class='label label-default'>未知-非法</span>";
            }
        });
        $grid->created_at('创建时间')->date('Y-m-d');
        $grid->updated_at('修改时间')->date('Y-m-d');

        $grid->disableFilter();
        $grid->disableRowSelector();
        $grid->disableExport();
        $grid->disableColumnSelector();
        return $grid;
    }

    /**
     * 创建项目的表单
     */
    private function form()
    {
        $form = new Form(new WikiProject());

        $form->text('name', "项目名称")->required();
        $form->textarea('description', "项目描述");
        $form->radio('type', "类型")
            ->options([WikiProject::$TYPE_PUBLIC => '公开', WikiProject::$TYPE_PRIVATE => '私密'])
            ->default(WikiProject::$TYPE_PUBLIC)
            ->required();

        $form->footer(function ($footer) {
            // 去掉`查看`checkbox
            $footer->disableViewCheck();

            // 去掉`继续编辑`checkbox
            $footer->disableEditingCheck();

            // 去掉`继续创建`checkbox
            $footer->disableCreatingCheck();
        });

        return $form;
    }
}