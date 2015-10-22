<header>
    <a class="brand" href="/admin"></a>
    <ul class="block">
        <li class="profile" data-settings-url="/admin/profile/settings">
            <a href="/admin/profile">
                <span class="name">Admin User</span>
                <img alt="Admin User" class="avatar" height="36"
                     src="http://gravatar.com/avatar/e64c7d89f26bd1972efa854d13d7dd61?default=mm&secure=false&size=36"
                     width="36"/>
            </a>
        </li>

        <li class="sign-out">
            <form accept-charset="UTF-8" action="/admin/sign_out" method="post">
                <div style="margin:0;padding:0;display:inline"><input name="utf8" type="hidden" value="&#x2713;"/><input
                            name="_method" type="hidden" value="delete"/><input name="authenticity_token" type="hidden"
                                                                                value="Ra4rkMZxg+xvEMbX1bzO6bKpVr3s4sClchdFtmGMnvo="/>
                </div>
                <button type="submit">
                    <i class="fa fa-power-off icon-header"></i>
                </button>
            </form>

        </li>
    </ul>
</header>
