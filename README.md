# tpshop

此资源为 正版用户提供的资源 全功能+全开源版本+可后台升级  

安装事项：
访问/install安装即可 登录后台后更新缓存，且升级到最新版
必须安装在域名根目录下, 不能圈套圈套在某个目录下 否则会路径出错
安装需要host一个域名根目录下安装. apache配置一个虚拟主机 不能圈套在某个目录下
示例:
http://www.xxx.com/index.php   正确
http://www.xxx.com/TPshop/index.php   错误 (很多路径出错)
手机访问目录 http://www.xxx.com/index.php/Mobile  最好用手机或手机浏览器打开测试 PC端浏览器会有兼容问题


如果你是nginx 服务器  lnmp 安装的, 并且是按照 lnmp 官网标准安装的 你可以 直接拿当前根目录下的 nginx.conf2 文件 改名字覆盖你的 对应域名的文件. 然后修改里面的 "www.tp-shop.cn"  域名换成你的即可比如我的TPshop项目在  D:\www\tpshop2.0  域名应该指向到 D:\www\tpshop2.0  因为index.php 在 D:\www\tpshop2.0 下面apache配置应该是
<VirtualHost *:80>
     DocumentRoot "D:\www\tpshop2.0\"
     ServerName www.tpshop.cn
</VirtualHost>
