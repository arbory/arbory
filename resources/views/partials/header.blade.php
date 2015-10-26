<header>
    <a class="home" href="/admin">
        <img alt="Leaf" src="/assets/leaf/logo.png"/>
    </a>
    <a class="profile" href="/admin/profile" data-settings-url="/admin/profile/settings">
        <span class="name">Admin User</span>
        <img alt="Admin User" class="avatar" src="http://gravatar.com/avatar/e64c7d89f26bd1972efa854d13d7dd61?default=mm&secure=false&size=36" width="36" height="36"/>
    </a>
    <form class="sign-out" action="/admin/sign_out" accept-charset="UTF-8" method="post">
        <input name="utf8" type="hidden" value="&#x2713;"/>
        <input type="hidden" name="_method" value="delete"/>
        <input type="hidden" name="authenticity_token" value="BIO2FHFaq8aIQNsFQ6+ACi9h0UkKtRGM/4Zl/VpmVCB/rvTRIaY4VHDvXgIYAEa+X9wvDfY4rz4knvSyKseiGA=="/>
        <button type="submit">
            <i class="fa fa-power-off fa-icon-header"></i>
        </button>
    </form>
</header>
