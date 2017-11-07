<?php if (\Common\UserHelper::isLoggedAdmin()): ?>
    <div class="navbar navbar-default navbar-fixed-top">
        <div class="navbar-panel">
            <button class="dropdown-toggle btn btn-info btn-sm" data-toggle="dropdown" type="button" id="user"
                    aria-expanded="false"><?php echo \Common\UserHelper::currentUser()['username'] ?></button>
            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="user">
                <li><a href="/logout">登出</a></li>
            </ul>
        </div>
        <div class="navbar-header">
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="/dashboard" class="navbar-brand">HOME</a>
        </div>
        <div class="navbar-collapse collapse" id="navbar-main">
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="main" aria-expanded="false">菜单
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu" aria-labelledby="main">
                        <li><a href="/dashboard">Dashboard</a></li>
                        <li class="divider"></li>
                        <li><a href="/character">用户</a></li>
                        <li><a href="/drop">掉落</a></li>
                        <li><a href="/mob">怪物</a></li>
                        <li><a href="/item">物品</a></li>
                        <li><a href="/item/stat_boosts">物品效果(属性)</a></li>
                        <li><a href="/item_set">套装</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="tools" aria-expanded="false">工具
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu" aria-labelledby="tools">
                        <li><a href="/tools/db">数据库结构</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
<?php endif; ?>