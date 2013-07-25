HTML5 WebGame Collecter
=============

#### Update:
+ 初始化 - 2013/7/25

#### Feature:
+ 自动分析页面并下载相关前端资源
+ 可制定特定站点抓取
+ 自动替换修正特定自定义特征信息
+ ...

#### TODO:
+ 内存管理优化
+ 多线程支持
+ 目录级扫描
+ md5对比，减少误判
+ 日志记录
+ ...

#### Adapter:
+ 多泡游戏 ( duopao.php )
+ 一窝88 ( yi588.php )

#### Useage:
	# 抓取较规范页面(通常游戏页面为 `index`.html)
    $ php adapter.php dirname
    
    # 抓取不太规范页面(游戏页可能会被自定义为 `anyname`.[htm|html])
    $ php adapter.php dirname anyname.html

#### Example:

###### 1. Basic
    $ php duopao.php ParkingTrainee

    $ php yi588.php buou

###### 2. Custom
    $ php duopao.php shouhushengwu shouhushengwu.html
	...


