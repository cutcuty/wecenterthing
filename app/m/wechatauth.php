<?php
define('IN_AJAX', TRUE);
if (!defined('IN_ANWSION')){
	die;
}

define('IN_MOBILE', true);

class wechatauth extends AWS_CONTROLLER {
    
	public function get_access_rule(){
		$rule_action['rule_type'] = 'black';
		$rule_action['actions'] = array(
			'binding'
		);

		return $rule_action;
	}

	public function setup(){
		HTTP::no_cache_header();
	}

	public function redirect_action(){
        
		if (!in_weixin() OR get_setting('weixin_account_role') != 'service') {
			//HTTP::redirect(base64_decode($_GET['redirect']));
		}

		if ($_GET['code'] AND get_setting('weixin_app_id') AND get_setting('weixin_app_secret')) {
			if (!$_GET['thirdlogin'] || !$_GET['state']) {
                H::redirect_msg('授权失败: Redirect 微信三方登录发起的来源ID错误, Code: ' . htmlspecialchars($_GET['code']));
			}
			
            $thirdlogin = $_GET['thirdlogin'];
            
            $third_info = $this->model('openid_weixin_thirdlogin')->get_third_party_login_by_name($thirdlogin);
            
            if(!$third_info || $_GET['state']!=$third_info['token']){
                H::redirect_msg('授权失败: Redirect 微信三方登录发起的来源ID错误, Code: ' . htmlspecialchars($_GET['code']));
            }
            
			if($access_token = $this->model('openid_weixin_weixin')->get_sns_access_token_by_authorization_code($_GET['code'])) {
                
				if ($access_token['errcode']) {
					H::redirect_msg('授权失败: Redirect ' . $access_token['errcode'] . ' ' . $access_token['errmsg'] . ', Code: ' . htmlspecialchars($_GET['code']));
				}
                
				if ($weixin_user = $this->model('openid_weixin_weixin')->get_user_info_by_openid($access_token['openid'])){
                    //已有用户
					$user_info = $this->model('account')->get_user_info_by_uid($weixin_user['uid']);
                    
					HTTP::set_cookie('_user_login', get_login_cookie_hash($user_info['user_name'], $user_info['password'], $user_info['salt'], $user_info['uid'], false));
                    
				} else {
				    
                    $access_user = $this->model('openid_weixin_weixin')->get_user_info_by_oauth_openid($access_token['access_token'], $access_token['openid']);
                    

                    if($access_user){
                        if($user_info = $this->model('openid_weixin_weixin')->weixin_auto_register($access_token, $access_user)){
                            HTTP::set_cookie('_user_login', get_login_cookie_hash($user_info['user_name'], $user_info['password'], $user_info['salt'], $user_info['uid'], false));
                            
                        } else {
                            H::redirect_msg('用户注册失效,请重试！, State: ' . htmlspecialchars($_GET['state']) . ', Code: ' . htmlspecialchars($_GET['code']));
                        }
                    } else {
                        H::redirect_msg('远程服务器忙,请稍后再试, State: ' . htmlspecialchars($_GET['state']) . ', Code: ' . htmlspecialchars($_GET['code']));
                    }
                    
				}
                
                $callback_url = $third_info['url'];
                $query = array();
                $query['state'] = $third_info['token'];
                $query['openid'] = $access_token['openid'];
                $query['name'] = $third_info['name'];
                $callback_url = $callback_url.'?'.http_build_query($query);
                
                H::redirect_msg('授权成功,正在跳转...',$callback_url);
                
			} else {
				H::redirect_msg('远程服务器忙,请稍后再试, State: ' . htmlspecialchars($_GET['state']) . ', Code: ' . htmlspecialchars($_GET['code']));
			}
			
		} else {
			H::redirect_msg('授权失败, 请返回重新操作, URI: ' . $_SERVER['REQUEST_URI']);
		}
	}
    
    
    public function user_action(){
        
        $name = $_POST['name'];
        $token = $_POST['token'];
        $openid = $_POST['openid'];
        
        $third_info = $this->model('openid_weixin_thirdlogin')->get_third_party_login_by_name($name);
        if(!$third_info){
            H::redirect_msg('获取接口服务失败, name: ' . htmlspecialchars($name));
        }
        
        if($token!=$third_info['token']){
            H::redirect_msg('获取接口服务失败, token: ' . htmlspecialchars($token));
        }
        
		if ($weixin_user = $this->model('openid_weixin_weixin')->get_user_info_by_openid($openid)){
            //已有用户
			$user_info = $this->model('account')->get_user_info_by_uid($weixin_user['uid']);
            
            $result = array(
                'ret' => 0,
                'userinfo'=> $weixin_user
            );
            
            echo json_encode($result);
            exit;
            
		}else{
            $result = array(
                'ret' => '01',
                'msg'=>'用户不存在 openid:'.$openid,
                'userinfo'=> ''
            );
            echo json_encode($result);
            exit;
		}
        
    }
    
}