<?php
defined('IN_IA') or exit ('Access Denied');

class Core extends WeModuleSite
{
   


    public function getMainMenu()
    {
        global $_W, $_GPC;

        $do = $_GPC['do'];
        $navemenu = array();
        $cur_color = ' style="color:#d9534f;" ';
        if ($_W['role'] == 'operator') {
            $navemenu[0] = array(
                'title' => '<icon style="color:#8d8d8d;" class="fa fa-cog"></icon>  业务菜单',
                'items' => array(
                    0 => $this->createMainMenu('门店列表', $do, 'store', 'fa-home')
                )
            );}elseif($_W['isfounder'] || $_W['role'] == 'manager' || $_W['role'] == 'operator') {
            $navemenu[0] = array(
                'title' => '<icon style="color:#8d8d8d;" class="fa fa-cubes"></icon>  门店管理',
                'items' => array(
                    0 => $this->createMainMenu('门店列表', $do, 'store', ''),
                   // 1 => $this->createMainMenu('门店回收站 ', $do, 'yg4', ''),
                    1 => $this->createMainMenu('门店账号', $do, 'account', ''),
                    2 => $this->createMainMenu('小程序端账号', $do, 'admin', ''),
                )
            );
            $navemenu[1] = array(
                'title' => '<icon style="color:#8d8d8d;" class="fa fa-bars"></icon>  订单管理',
                'items' => array(
                     0 => $this->createMainMenu('外卖订单 ', $do, 'order', ''),
                    1 => $this->createMainMenu('店内订单', $do, 'dnorder', ''),
                      2 => $this->createMainMenu('预订订单', $do, 'ydorder', ''),
                       3 => $this->createMainMenu('当面付订单', $do, 'dmorder', '')
                )
            );
             $navemenu[2] = array(
                'title' => '<icon style="color:#8d8d8d;" class="fa fa-life-ring"></icon>  广告管理',
                'items' => array(
                     0 => $this->createMainMenu('广告列表 ', $do, 'ad', ''),
                    1 => $this->createMainMenu('广告添加', $do, 'addad', ''),
                )
            );
             $navemenu[3] = array(
                'title' => '<icon style="color:#8d8d8d;" class="fa fa-map-marker"></icon>  门店区域',
                'items' => array(
                     0 => $this->createMainMenu('区域列表', $do, 'area', ''),
                    1 => $this->createMainMenu('区域添加', $do, 'addarea', ''),
                )
            );
            $navemenu[4] = array(
                'title' => '<icon style="color:#8d8d8d;" class="fa fa-university"></icon>  门店类型',
                'items' => array(
                    0 => $this->createMainMenu('分类设置 ', $do, 'typeset', ''),
                    1 => $this->createMainMenu('类型管理 ', $do, 'storetype', ''),
                    2 => $this->createMainMenu('类型添加 ', $do, 'addstoretype', ''),
                    
                )
            );
            
            $navemenu[5] = array(
                'title' => '<icon style="color:#8d8d8d;" class="fa fa-cubes"></icon>  入驻管理',
                'items' => array(
                    0 => $this->createMainMenu('申请列表 ', $do, 'ruzhu', ''),
                    1 => $this->createMainMenu('入驻设置 ', $do, 'ruzhusz', ''),
                )
            );
           
            

            // 下面是复制的上面的数据
           
            $navemenu[8] = array(
                'title' => '<icon style="color:#8d8d8d;" class="fa fa-cog"></icon>  积分商城',
                'items' => array(
                    0 => $this->createMainMenu('商品列表', $do, 'jfgoods', ''),
                    1 => $this->createMainMenu('商品分类', $do, 'jftype', ''),
                    2 => $this->createMainMenu('积分设置', $do, 'jfsz', ''),
                )
            );
            $navemenu[9] = array(
                'title' => '<icon style="color:#8d8d8d;" class="fa fa-cog"></icon>  分销系统',
                'items' => array(
                    0 => $this->createMainMenu('分销商管理', $do, 'fxlist', ''),
                    1 => $this->createMainMenu('分销设置', $do, 'fxset', ''),
                    2 => $this->createMainMenu('提现申请', $do, 'fxtx', ''),
                )
            );
            // $navemenu[10] = array(
            //     'title' => '<icon style="color:#8d8d8d;" class="fa fa-cog"></icon>  充值中心',
            //     'items' => array(
            //        0 => $this->createMainMenu('充值优惠', $do, 'chongzhi', ''),
            //        1 => $this->createMainMenu('充值记录', $do, 'czjl', '')
            //     )
            // );
             $navemenu[11] = array(
                'title' => '<icon style="color:#8d8d8d;" class="fa fa-bars"></icon>  签到管理',
                'items' => array(
                     0 => $this->createMainMenu('签到规则 ', $do, 'integral', '')
                )
            );
          
            $navemenu[12] = array(
                'title' => '<icon style="color:#8d8d8d;" class="fa fa-user"></icon>  会员管理',
                'items' => array(
                     0 => $this->createMainMenu('会员列表 ', $do, 'user', ''),
                )
            );
            $navemenu[13] = array(
                'title' => '<icon style="color:#8d8d8d;" class="fa fa-recycle"></icon>  财务管理',
                'items' => array(
                    0 => $this->createMainMenu('提现管理 ', $do, 'txlist', ''),
                    1 => $this->createMainMenu('提现设置 ', $do, 'txsz', ''),
                    2 => $this->createMainMenu('充值优惠', $do, 'chongzhi', ''),
                   3 => $this->createMainMenu('充值记录', $do, 'czjl', '')
                )
            );
            $navemenu[14] = array(
                'title' => '<icon style="color:#8d8d8d;" class="fa fa-cog"></icon>  系统设置',
                'items' => array(
                    0 => $this->createMainMenu('基本信息 ', $do, 'settings', ''),
                    1 => $this->createMainMenu('小程序配置', $do, 'peiz', ''),
                    2 => $this->createMainMenu('支付配置', $do, 'pay', ''),
                     3 => $this->createMainMenu('达达配置 ', $do, 'dasettings', ''),
                    4 => $this->createMainMenu('模板消息', $do, 'template', ''),  
                    5 => $this->createMainMenu('邮件通知', $do, 'email', ''), 
                    6 => $this->createMainMenu('帮助中心设置', $do, 'help', ''),                      
                    // 6 => $this->createMainMenu('系统更新', $do, 'heli', ''),
                   
                )
            );
            
        }
        return $navemenu;
    }
     public function getMainMenu2()
    {
        global $_W, $_GPC;

        $do = $_GPC['do'];
        $navemenu = array();
        $cur_color = ' style="color:#d9534f;" ';
        if($_W['isfounder'] || $_W['role'] == 'manager' || $_W['role'] == 'operator') {
            $navemenu[0] = array(
                'title' => '<icon style="color:#8d8d8d;" class="fa fa-key"></icon>  门店设置',
                'items' => array(
                    0 => $this->createMainMenu('数据概况', $do, 'index', ''),
                    1 => $this->createMainMenu('门店信息 ', $do, 'storeinfo', ''),
                    // 2 => $this->createMainMenu('销售统计 ', $do, 'ygdata', ''),
                    // 4 => $this->createMainMenu('员工管理 ', $do, 'test2', ''),
                )
            );
             $navemenu[1] = array(
                'title' => '<icon style="color:#8d8d8d;" class="fa fa-bars"></icon>  订单管理',
                'items' => array(
                    0 => $this->createMainMenu('外卖订单', $do, 'inorder', ''),
                    1 => $this->createMainMenu('店内订单', $do, 'indnorder', ''),
                    2 => $this->createMainMenu('预订订单', $do, 'inydorder', ''),
                     3 => $this->createMainMenu('当面付订单', $do, 'indmorder', '')
                )
            );
            
           
            $navemenu[2] = array(
                'title' => '<icon style="color:#8d8d8d;" class="fa fa-trophy"></icon>  菜品管理',
                'items' => array(
                     0 => $this->createMainMenu('菜品列表 ', $do, 'dishes2', ''),
                    // 1 => $this->createMainMenu('添加菜品', $do, 'adddishes', ''),
                    2 => $this->createMainMenu('菜品分类', $do, 'dishestype', ''),
                    // 3 => $this->createMainMenu('添加分类', $do, 'adddishestype', ''),
                )
            );
            $navemenu[3] = array(
                'title' => '<icon style="color:#8d8d8d;" class="fa fa-binoculars"></icon>  餐桌管理',
                'items' => array(
                    0 => $this->createMainMenu('餐桌列表 ', $do, 'table2', ''),
                    // 1 => $this->createMainMenu('添加餐桌', $do, 'addtable', ''),
                    2 => $this->createMainMenu('餐桌类型', $do, 'tabletype2', ''),
                    // 3 => $this->createMainMenu('添加餐桌类型', $do, 'addtabletype', ''),
                  //  4 => $this->createMainMenu('预定付款管理', $do, 'yypay', ''),
                )
            );
            $navemenu[4] = array(
                'title' => '<icon style="color:#8d8d8d;" class="fa fa-gift"></icon>  营销设置',
                'items' => array(
                     0 => $this->createMainMenu('营销插件 ', $do, 'ygquan', ''),
                )
            );
            $navemenu[5] = array(
                'title' => '<icon style="color:#8d8d8d;" class="fa fa-key"></icon>  数据统计',
                'items' => array(
                    0 => $this->createMainMenu('销售统计', $do, 'ygdata', ''),
                    1 => $this->createMainMenu('消费排行 ', $do, 'ygranking', ''),
                    // 2 => $this->createMainMenu('销售统计 ', $do, 'ygdata', ''),

                )
            );
            
            $navemenu[6] = array(
                'title' => '<icon style="color:#8d8d8d;" class="fa fa-book"></icon>  提现管理',
                'items' => array(
                     0 => $this->createMainMenu('提现流水', $do, 'intxlist', ''),
                     1 => $this->createMainMenu('申请提现', $do, 'intx', ''),
                )
            );
            
         /*    $navemenu[8] = array(
                'title' => '<icon style="color:#8d8d8d;" class="fa fa-gift"></icon>  UU跑腿',
                'items' => array(
                     0 => $this->createMainMenu('UU跑腿设置 ', $do, 'uuset', ''),
                )
            );*/
            $navemenu[7] = array(
                'title' => '<icon style="color:#8d8d8d;" class="fa fa-book"></icon>  打印设置',
                'items' => array(
                     0 => $this->createMainMenu('打印设备 ', $do, 'print', ''),
                     1 => $this->createMainMenu('添加打印 ', $do, 'addprint', ''),
                )
            );
            $navemenu[8] = array(
                'title' => '<icon style="color:#8d8d8d;" class="fa fa-book"></icon>  评论管理',
                'items' => array(
                     0 => $this->createMainMenu('评论管理 ', $do, 'assess2', ''),
                )
            );

        }
        return $navemenu;
    }

    function createCoverMenu($title, $method, $op, $icon = "fa-image", $color = '#d9534f')
    {
        global $_GPC, $_W;
        $cur_op = $_GPC['op'];
        $color = ' style="color:'.$color.';" ';
        return array('title' => $title, 'url' => $op != $cur_op ? $this->createWebUrl($method, array('op' => $op)) : '',
            'active' => $op == $cur_op ? ' active' : '',
            'append' => array(
                'title' => '<i class="fa fa-angle-right"></i>',
            )
        );
    }

    function createMainMenu($title, $do, $method, $icon = "fa-image", $color = '')
    {
        $color = ' style="color:'.$color.';" ';

        return array('title' => $title, 'url' => $do != $method ? $this->createWebUrl($method, array('op' => 'display')) : '',
            'active' => $do == $method ? ' active' : '',
            'append' => array(
                'title' => '<i '.$color.' class="fa fa-angle-right"></i>',
            )
        );
    }

    function createSubMenu($title, $do, $method, $icon = "fa-image", $color = '#d9534f', $storeid)
    {
        $color = ' style="color:'.$color.';" ';
        $url = $this->createWebUrl($method, array('op' => 'display', 'storeid' => $storeid));
        if ($method == 'stores') {
            $url = $this->createWebUrl('stores', array('op' => 'post', 'id' => $storeid, 'storeid' => $storeid));
        }

        return array('title' => $title, 'url' => $do != $method ? $url : '',
            'active' => $do == $method ? ' active' : '',
            'append' => array(
                'title' => '<i class="fa '.$icon.'"></i>',
            )
        );
    }

    public function getStoreById($id)
    {
        $store = pdo_fetch("SELECT * FROM " . tablename('wpdc_store') . " WHERE id=:id LIMIT 1", array(':id' => $id));
        return $store;
    }


    public function set_tabbar($action, $storeid)
    {
        $actions_titles = $this->actions_titles;
        $html = '<ul class="nav nav-tabs">';
        foreach ($actions_titles as $key => $value) {
            if ($key == 'stores') {
                $url = $this->createWebUrl('stores', array('op' => 'post', 'id' => $storeid));
            } else {
                $url = $this->createWebUrl($key, array('op' => 'display', 'storeid' => $storeid));
            }

            $html .= '<li class="' . ($key == $action ? 'active' : '') . '"><a href="' . $url . '">' . $value . '</a></li>';
        }
        $html .= '</ul>';
        return $html;
    }
}