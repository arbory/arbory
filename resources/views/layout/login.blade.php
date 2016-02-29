<!DOCTYPE html>
<html>
<head>
    <title>Leaf</title>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <link href="/application.css" media="all" rel="stylesheet"/>
    <link href="/session.css" media="all" rel="stylesheet"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
</head>
<body class="controller-releaf-sessions view-edit">
<div class="container">
    <div class="box">
        <div class="logo"></div>
        <form accept-charset="UTF-8" action="{{route('admin.sign_in')}}" class="login" id="new_releaf_permissions_user"
              method="post">
            {!!csrf_field()!!}

            <div class="control-group">
                <label class="control-label" for="email">Email</label>
                <input autofocus="autofocus" id="email" name="user[email]" type="email" value=""/>
            </div>
            <div class="control-group">
                <label class="control-label" for="password">Password</label>
                <input id="password" name="user[password]" type="password"/>
            </div>
            <div class="control-group">
                <label>
                    <input type="checkbox" name="remember" value="1"/>
                    Remember
                </label>
            </div>
            <div class="control-group">
                <button type="submit">Sign in</button>
            </div>
        </form>
    </div>
</div>
<script src="/application.js"></script>
</body>
</html>
