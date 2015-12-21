# wecenterthing

wecenter 微信自动登录API (移动端/微信内的用户授权）
参照 微信中间服务结构图.png 

1. 可以后台配置第三方登录的name,url和token
2. 三方站点直接请求微信
3. 返回链接格式为  wecenterthing.com/m/wechatauth/redirect/?thirdlogin=wechat_shop&code=&state=
4. 三方站点 POST 请求用户数据的地址是 wecenterthing.com/wechatauth/user
5. 返回的是微信公众号标准授权接口中的数据，也可以自行修改为wecenter用户数据

