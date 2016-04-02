<div class="row">
    <div class="large-9 medium-8 columns">

        <div class="view">

            <div class="item">
                <div class="title">
                    <h4>关于失踪儿童定位系统</h4>
                </div>
                {*<div class="about">
                    <i class="calendar icon"></i>
                    <a class="date">创建日期</a>
                </div>*}
            </div>

            <div class="content">
                <h5>
                    <br>现在国内已有的类似系统为中国儿童失踪预警平台,是以民政部直接登记主管的全国首家支持和发展社会工作的全国性基金会——中社社会工作发展基金会为依托，由其下属专项基金中社儿童安全科技基金全权负责运作的中国儿童失踪社会应急响应系统。该平台的使用方法是.民众需要下载安装中国儿童失踪预警平台手机客户端,或者微信关注中国儿童失踪预警平台微信公众号.

                    <br><br>上述系统的普及率和民众参与度都非常有限，因为民众不会特意去安装一款关于寻找拐卖儿童的app，其次，对于微信平台，处于隐私考虑，绝大多数用户是不允许微信访问位置信息的，由此关于失踪儿童信息及位置的扩散范围及民众参与度会十分有限。

                    <br><br>对于拐卖儿童这件事情，从发达国家相关经验来看，应该从国家层面提供技术及数据支持。建设一个利用大量民众反馈信息对被拐卖儿童进行定位的系统。

                    <br><br>本课题的目标就是实现这样一个系统的简单版本或者雏形，为国家层面建立该系统提供理论和技术参考。
                    <br><br>基于动态口令的失踪儿童搜寻定位系统是借助成熟Web技术、动态口令技术和GIS地理信息系统的支持，结合中国国情和特点的中国儿童失踪社会应急响应系统。该系统可以在儿童走失发生时迅速启动，通过系统信息发布和民众信息反馈加警方联动的方式，尽可能快速精确地定位儿童位置，帮助家长迅速找回走失儿童，降低孩子发生意外的概率。

                    <br><br>在儿童丢失事件发生后,系统使用者在该系统内建立一个相关事件,系统向以儿童失踪地点为中心,以适当地理长度为半径的范围内所有的手机讯号发送短信。内容包括失踪儿童的特征,失踪地点，以及儿童失踪时间。收到短信的民众如果在儿童失踪时间之后见过该儿童，则反馈见到该儿童的时间及地点。

                    <br><br>警方将该反馈内容整理后将时间地点信息作为反馈信息输入系统。系统判断该输入数据的可信度，并决定是否依据该反馈信息更新失踪儿童当前地点，系统根据当前地点的变化继续向民众发布儿童失踪信息短信。直到警方找到失踪儿童。

                    <br><br>系统接入了第三方地图,可以查看失踪儿童的位置变化轨迹.为警方儿童搜寻工作提供支持.

                </h5>
            </div>


        </div>

    </div>
    <div class="large-3 medium-4 columns">
        <div class="side">
            {if Yii::$app->user->isGuest}
                <div class="row">
                    <a href="/user/index/login">
                        <button class=""><h4>登陆</h4></button>
                    </a>
                </div>
            {else}
                <div class="row">
                    <a href="/find/event/create-event">
                        <button class=""><h4>创建事件</h4></button>
                    </a>
                </div>
                <div class="row">
                    <a href="/find/event/event-lists/0">
                        <button class=""><h4>进行中事件</h4></button>
                    </a>
                </div>
                <div class="row">
                    <a href="/find/event/event-lists/1">
                        <button class=""><h4>已结束事件</h4></button>
                    </a>
                </div>
                {if $admin}
                    <div class="row">
                        <a href="/admin/index/index">
                            <button class=""><h4>管理入口</h4></button>
                        </a>
                    </div>
                {/if}
                <div class="row">
                    <a href="/user/index/profile">
                        <button class=""><h4>个人中心</h4></button>
                    </a>
                </div>
                <div class="row">
                    <a href="/user/index/logout">
                        <button class=""><h4>退出</h4></button>
                    </a>
                </div>
            {/if}
        </div>
    </div>
</div>
