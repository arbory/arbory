<!DOCTYPE html>
<html>
<head>
    <title>Leaf</title>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <link href="/leaf/css/application.css" media="all" rel="stylesheet"/>
    <link href="/leaf/css/controllers/sessions.css" media="all" rel="stylesheet"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
</head>
<body class="controller-leaf-sessions view-edit">
<div class="container">
    <div class="box">
        <div class="logo"></div>
        <form class="login" id="new_releaf_permissions_user" action="{{route('admin.sign_in')}}" accept-charset="UTF-8"
              method="post">
            {!!csrf_field()!!}
            <div class="field">
                <label for="email">Email</label>
                <input autofocus="autofocus" id="email" class="text" type="email" value="" name="user[email]">
            </div>
            <div class="field">
                <label for="password">Password</label>
                <input id="password" class="text" type="password" name="user[password]">
            </div>
            <div class="field">
                <label>
                    <input type="checkbox" name="remember" value="1"/>
                    Remember
                </label>
            </div>
            <div class="field">
                <button class="button" type="submit">Sign in</button>
            </div>
        </form>
    </div>
</div>
<script src="/leaf/js/application.js"></script>
<script src="//cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>
</body>
</html>
