<?php
/**
 * 知识付费在线课程v2.0.0
 * Author: 山西匠言网络科技有限公司
 * This is not a free software, it under the license terms, you can visit https://www.zsffzxkc.cn/ get more details.
 */
namespace app\index\controller;
use app\index\controller\Base;
use think\Controller;
use think\Db;
use Payment\Wechat;

class Pay extends  Base
{


    private function paysuccessmsg($orderid){
        $uniacid=1;
        $orderinfo=Db::name('mediaorder')
            ->where('orderid',$orderid)
            ->find();
        $goodstype=$orderinfo['goodstype'];
        if($goodstype=='course'){
            if($orderinfo['sonid']==0){
                $goodsinfo=db('coursemenu')->where(['acid'=>$uniacid,'id'=>$orderinfo['menuid']])->find();
                $goodsinfo['name']=$goodsinfo['menuname'];
            }else{
                $menuinfo=db('coursemenu')->where(['acid'=>$uniacid,'id'=>$orderinfo['menuid']])->find();
                $goodsinfo=db('course')->where(['acid'=>$uniacid,'menuid'=>$orderinfo['menuid'],'id'=>$orderinfo['sonid']])->find();
                $goodsinfo['name']=$goodsinfo['coursename'];
            }
        }elseif($goodstype=='mall'){
            $goodsinfo=db('goods')->where(['acid'=>$uniacid,'id'=>$orderinfo['menuid']])->find();
        }elseif($goodstype=='pxcourse'){
            $goodsinfo=db('pxcourse')->where(['acid'=>$uniacid,'id'=>$orderinfo['menuid']])->find();
        }
        $userdata=Db::name('user')
            ->where('id',$orderinfo['uid'])
            ->find();
        $set=$this->get_set('BASE');
        // 购买成功消息
        $msg = array(
            'first' => array(
                'value' => "您的订单支付成功",
                'color' => '#ff510'
            ),
            'keyword1' => array(
                'value' => $orderid,
                'color' => '#ff510'
            ),
            'keyword2' => array(
                'value' => $goodsinfo['name'],
                'color' => '#ff510'
            ),
            'keyword3' => array(
                'value' => date('Y-m-d H:i:s',time()),
                'color' => '#ff510'
            ),
            'remark' => array(
                'value' => "支付金额：￥".$orderinfo['price'],
                'color' => '#ff510'
            ),
        );
        if($userdata['gzh_openid']!=''){
            $this->sendTpl($userdata['gzh_openid'],$set['msg_kcbuyok'],$msg);
        }
    }
    //充值、会员升级、商品赠积分、减库存加销量
    public function check_order($orderid){
        $map=array(
            'acid'=>1
        );
        $orderinfo=Db::name('mediaorder')
            ->where('orderid',$orderid)
            ->find();
        $userinfo=Db::name('user')
            ->where('id',$orderinfo['uid'])
            ->find();
        if($orderinfo['goodstype']=='chongzhi'){
            Db::name('user')
                ->where('id',$orderinfo['uid'])
                ->setInc('money',$orderinfo['price']);
        }elseif($orderinfo['goodstype']=='vip'){
            $viptime=Db::name('viptime')
                ->where('id',$orderinfo['menuid'])
                ->find();
            $data1=array(
                'is_vip'=>1,
                'vip_state'=>'正常',
                'viptypeid'=>$viptime['id'],
                'vipsj_orderid'=>$orderid,
                'vipsj_fangshi'=>'usersj',
                'credit'=>$userinfo['credit']+$viptime['zsscore']
            );
            Db::name('user')
                ->where('id',$orderinfo['uid'])
                ->update($data1);
        }elseif($orderinfo['goodstype']=='course'){
            $coursemenu=Db::name('coursemenu')
                ->where('id',$orderinfo['menuid'])
                ->find();
            Db::name('user')
                ->where('id',$orderinfo['uid'])
                ->setInc('credit',$coursemenu['zsscore']);
        }elseif($orderinfo['goodstype']=='mall'){
            $coursemenu=Db::name('goods')
                ->where('id',$orderinfo['menuid'])
                ->find();
            if($coursemenu['stock']!=0){
                db('goods')->where('id',$orderinfo['menuid'])->setField('stock',$coursemenu['stock']-$orderinfo['num']);
            }
            db('goods')->where('id',$orderinfo['menuid'])->setField('sales',$coursemenu['sales']+$orderinfo['num']);
            Db::name('user')
                ->where('id',$orderinfo['uid'])
                ->setInc('credit',$coursemenu['zsscore']);
        }
    }
    public function xmlToArray($xml)
    {
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $result = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $result;
    }

    private function getOptions()
    {
        $options = new Config();
        $options->protocol = 'https';
        $options->gatewayHost = 'openapi.alipay.com';
        $options->signType = 'RSA2';
        //填入你的支付宝APPID
        $options->appId = '';

        // 为避免私钥随源码泄露，推荐从文件中读取私钥字符串而不是写入源码中
        //填入你的支付宝私钥 author QQ:6133848
        $options->merchantPrivateKey = '';
//        $options->alipayCertPath = '<-- 请填写您的支付宝公钥证书文件路径，例如：/foo/alipayCertPublicKey_RSA2.crt -->';
//        $options->alipayRootCertPath = '<-- 请填写您的支付宝根证书文件路径，例如：/foo/alipayRootCert.crt" -->';
//        $options->merchantCertPath = '<-- 请填写您的应用公钥证书文件路径，例如：/foo/appCertPublicKey_2019051064521003.crt -->';

        //注：如果采用非证书模式，则无需赋值上面的三个证书路径，改为赋值如下的支付宝公钥字符串即可
        //填入你的支付宝公钥
        $options->alipayPublicKey = '';
            //可设置异步通知接收服务地址（可选）
        $options->notifyUrl = 'https://'.$_SERVER['HTTP_HOST'].'/index.php/index/pay/alipay_notify';
        //可设置AES密钥，调用AES加解密相关接口时需要（可选）
        //$options->encryptKey = "<-- 请填写您的AES密钥，例如：aa4BtZ4tspm2wnXLb1ThQA== -->";
        return $options;
    }
    public function order_make(){
        $orderid = date("YmdHis",time()). rand('10000000','99999999');
        $input=input('post.');
        if($input['price']==0 && $input['pay_type']=='yhm'){
            $data = array(
                'orderid'=>$orderid,
                'ordertime'=>time(),
                'acid'=>1,
                'is_pay'=>1,
                'paytime'=>time()
            );
            $data1=array_merge($input,$data);
            $res=Db::name('mediaorder')
                ->insert($data1);
            if($res){
                db('yhm')->where(['num'=>$input['yhm'],'acid'=>1])->update(['uid'=>$input['uid'],'hexiaotime'=>time(),'is_jh'=>1]);
                $this->result(0,'优惠码支付成功','',0,'json');
            }
        }else{
            $data = array(
                'orderid'=>$orderid,
                'ordertime'=>time(),
                'acid'=>1,
                'is_pay'=>0,
            );
            $apptype=$input['apptype']?$input['apptype']:'';
            $data1=array_merge($input,$data);
            $res=Db::name('mediaorder')
                ->insert($data1);

            if($res){
                $map=array(
                    'acid'=>1
                );
                if($input['pay_type']=='wxpay'){
                    if($apptype=='MP-WEIXIN' || $apptype=='H5'){
                        $data2 = array(
                            'nonce_str' => md5(rand(100000,999999)),//随机串
                            'sign_type' => 'MD5',
                            'body' => '商品购买',//商品描述
                            'out_trade_no' => $orderid,//订单号
                            'total_fee' => $input['price']*100,//总金额(金额计数单位为：分)
                            'spbill_create_ip' => '127.0.0.1',//终端ip
                            'notify_url' => 'https://'.$_SERVER['HTTP_HOST'].'/index.php/index/pay/wechat_notify',
                            'trade_type' => 'JSAPI',//下订单类型
                            'openid' => $input['openid']//微信openID
                        );
                        if($apptype=='MP-WEIXIN'){
                            $db=Db::name('set_smallapp');

                        }elseif($apptype=='H5'){
                            $db=Db::name('set_h5');
                        }
                        $set=$db->where($map)->find();
                        $data2['appid']=$set['appid'];
                        $data2['mch_id']=$set['mchid'];
                        $wechat = new Wechat($set['appid'], $set['mchid'], $set['paysecret'], $set['appsecret']);
                        $jsApiPayData = $wechat->jsApiOrder($data2);
                        echo json_encode($jsApiPayData);
                    }elseif ($apptype=='APP-PLUS'){
                        $db=Db::name('set_app');
                        $data2 = array(
                            'nonce_str' => md5(rand(100000,999999)),//随机串
                            'sign_type' => 'MD5',
                            'body' => '订单支付',//商品描述
                            'out_trade_no' => $orderid,//订单号
                            'total_fee' => $input['price']*100,//总金额(金额计数单位为：分)
                            'spbill_create_ip' => '127.0.0.1',//终端ip
                            'notify_url' => 'https://'.$_SERVER['HTTP_HOST'].'/index.php/index/pay/wechat_notify',
                            'trade_type' => 'APP',//下订单类型
                        );
                        $set=$db->where($map)->find();
                        $data2['appid']=$set['appid'];
                        $data2['mch_id']=$set['mchid'];
                        $wechat = new Wechat($set['appid'], $set['mchid'], $set['paysecret'], $set['appsecret']);
                        $jsApiPayData = $wechat->appOrder($data2);
                        echo json_encode($jsApiPayData);
                    }

                }elseif($input['pay_type']=='alipay'){
                    if ($apptype=='H5') {
                        Factory::setOptions($this->getOptions());
                        $quitUrl = 'https://h5.sxjiangyan.com';
                        $result = Factory::payment()->wap()->pay("订单支付", $orderid, $input['price'], $quitUrl, 'https://' . $_SERVER['HTTP_HOST'] . '/index.php/index/pay/alipay_return');
                        echo json_encode($result);
                    }elseif ($apptype=='APP-PLUS'){
                        Factory::setOptions($this->getOptions());
                        $result = Factory::payment()->app()->pay("订单支付",$orderid,$input['price']);
                        echo json_encode($result);
                    }

                }elseif ($input['pay_type']=='credit'){
                    $userinfo=db('user')->where(['acid'=>1,'id'=>$input['uid']])->find();
                    if($userinfo['credit']>$input['credit']){
                        $res=db('user')->where(['acid'=>1,'id'=>$input['uid']])->setField('credit',$userinfo['credit']-$input['credit']);
                        if($res){
                            $res2=db('mediaorder')->where(['acid'=>1,'orderid'=>$orderid])->update(['is_pay'=>1,'paytime'=>time()]);
                            if($res2){
                                $this->result(0,'积分兑换成功','',0,'json');
                            }
                        }
                    }
                }
                elseif ($input['pay_type']=='balance'){
                    $userinfo=db('user')->where(['acid'=>1,'id'=>$input['uid']])->find();
                    if(floatval($userinfo['money'])>floatval($input['price'])){
                        $res=db('user')->where(['acid'=>1,'id'=>$input['uid']])->setField('money',floatval($userinfo['money'])-floatval($input['price']));
                        if($res){
                            $res2=db('mediaorder')->where(['acid'=>1,'orderid'=>$orderid])->update(['is_pay'=>1,'paytime'=>time()]);
                            if($res2){
                                $this->result(0,'余额支付成功','',0,'json');
                            }
                        }
                    }
                }

            }
        }

    }
}
