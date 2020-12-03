<?php
/**
 * 知识付费在线课程v2.0.0
 * Author: 山西匠言网络科技有限公司
 * This is not a free software, it under the license terms, you can visit https://www.zsffzxkc.cn/ get more details.
 */
namespace app\admin\model;
use think\Model;
use think\Db;

class AdsModel extends Model
{
    protected $name = 'ads';
    
    // 开启自动写入时间戳字段
//    protected $autoWriteTimestamp = true;


    /**
     * 根据搜索条件获取文章列表信息
     * @author
     */
    public function getArticleByWhere($map, $Nowpage, $limits,$od)
    {
        return $this->alias ('r')
            ->field('r.id,r.title,r.cate_id,r.photo,r.remark,r.keyword,r.content,r.views,r.type,r.is_tui,r.from,r.writer,r.ip,r.create_time,r.update_time,r.status,rc.name,r.music')
            ->join('article_cate rc', 'r.cate_id = rc.id')
            ->where($map)
            ->page($Nowpage, $limits)
            ->order($od)
            ->select();
    }
    
    
    /**
     * [insertArticle 添加文章]
     * @author
     */
    public function insertAds($param)
    {
        Db::startTrans();// 启动事务
        try{
            $this->allowField(true)->save($param);
            Db::commit();// 提交事务
            writelog(session('uid'),session('username'),'幻灯片【'.$param['title'].'】添加成功',1);
            return ['code' => 200, 'data' => '', 'msg' => '幻灯片添加成功'];
        }catch( \Exception $e){
            Db::rollback();// 回滚事务
            writelog(session('uid'),session('username'),'幻灯片【'.$param['title'].'】添加失败',2);
            return ['code' => 100, 'data' => '', 'msg' =>'幻灯片添加失败'];
        }
    }



    /**
     * [updateArticle 编辑文章]
     * @author
     */
    public function updateArticle($param)
    {
        Db::startTrans();// 启动事务
        try{
            $this->allowField(true)->save($param, ['id' => $param['id']]);
            Db::commit();// 提交事务
            writelog(session('uid'),session('username'),'文章【'.$param['title'].'】编辑成功',1);
            return ['code' => 200, 'data' => '', 'msg' => '文章编辑成功'];
        }catch( \Exception $e){
            Db::rollback();// 回滚事务
            writelog(session('uid'),session('username'),'文章【'.$param['title'].'】编辑失败',2);
            return ['code' => 100, 'data' => '', 'msg' => '文章编辑失败'];
        }
    }



    /**
     * [getOneArticle 根据文章id获取一条信息]
     * @author
     */
    public function getOneArticle($id)
    {
        return $this->where('id', $id)->find();
    }



    /**
     * [delArticle 删除文章]
     * @author
     */
    public function delArticle($id)
    {
        $title = $this->where('id',$id)->value('title');
        Db::startTrans();// 启动事务
        try{
            $this->where('id', $id)->delete();
            Db::commit();// 提交事务
            writelog(session('uid'),session('username'),'文章【'.$title.'】删除成功',1);
            return ['code' => 200, 'data' => '', 'msg' => '文章删除成功'];
        }catch( \Exception $e){
            Db::rollback();// 回滚事务
            writelog(session('uid'),session('username'),'文章【'.$title.'】删除失败',2);
            return ['code' => 100, 'data' => '', 'msg' =>  '文章删除失败'];
        }
    }

    /**
     * batchDelArticle 批量删除文章
     * @param $param
     * @return array
     */
    public function batchDelArticle($param){
        Db::startTrans();// 启动事务
        try{
            ArticleModel::destroy($param);
            Db::commit();// 提交事务
            writelog(session('uid'),session('username'),'文章批量删除成功',1);
            return ['code' => 200, 'data' => '', 'msg' => '批量删除成功'];
        }catch( \Exception $e){
            Db::rollback();// 回滚事务
            writelog(session('uid'),session('username'),'文章批量删除失败',1);
            return ['code' => 100, 'data' => '', 'msg' => '批量删除失败'];
        }
    }

    /**
     * [articleState 文章状态]
     * @param $param
     * @return array
     */
    public function articleState($id,$num){
        $title = $this->where('id',$id)->value('title');
        if($num == 2){
            $msg = '禁用';
        }else{
            $msg = '启用';
        }
        Db::startTrans();// 启动事务
        try{
            $this->where('id',$id)->setField(['status'=>$num]);
            Db::commit();// 提交事务
            writelog(session('uid'),session('username'),'文章【'.$title.'】'.$msg.'成功',1);
//                return ['code' => 200, 'data' => '', 'msg' => '已'.$msg];
        }catch( \Exception $e){
            Db::rollback();// 回滚事务
            writelog(session('uid'),session('username'),'文章【'.$title.'】'.$msg.'失败',2);
            return ['code' => 100, 'data' => '', 'msg' => $msg.'失败'];
        }
    }

    /**
     * 批量禁用文章
     * @param $param
     * @return array
     */
    public function forbiddenArticle($param){
        Db::startTrans();// 启动事务
        try{
            if($param){
                $this->saveAll($param);
            }else{
                $this->where('1=1')->update(['status'=>2]);
            }
            Db::commit();// 提交事务
            writelog(session('uid'),session('username'),'批量禁用文章成功',1);
            return ['code' => 200, 'data' => '', 'msg' => '批量禁用成功'];
        }catch( \Exception $e){
            Db::rollback ();//回滚事务
            writelog(session('uid'),session('username'),'批量禁用文章失败',2);
            return ['code' => 100, 'data' => '', 'msg' => '批量禁用失败'];
        }
    }

    /**
     * 批量启用文章
     * @param $param
     * @return array
     */
    public function usingArticle($param){
        Db::startTrans();// 启动事务
        try{
            if($param){
                $this->saveAll($param);
            }else{
                $this->where('1=1')->update(['status'=>1]);
            }
            Db::commit();// 提交事务
            writelog(session('uid'),session('username'),'批量启用文章成功',1);
            return ['code' => 200, 'data' => '', 'msg' => '批量启用成功'];
        }catch( \Exception $e){
            Db::rollback ();//回滚事务
            writelog(session('uid'),session('username'),'批量启用文章失败',2);
            return ['code' => 100, 'data' => '', 'msg' => '批量启用失败'];
        }
    }

}