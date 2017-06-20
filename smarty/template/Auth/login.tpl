{%extends file="../layout.tpl"%}


{%block name="js"%}
<script>
    require(['/web/custom/js/auth/login']);
</script>

{%/block%}


{%block name="main"%}
<div>
    <div class="register_container">
        <form id="login_form" method="post">
            <label>手机:</label><br>
            <input type="text" name="mobile" class="edit_input_text" placeholder="请输入11位手机号码"><br>

            <label>密码:</label><br>
            <input type="password" name="password" class="edit_input_text" placeholder="清输入密码"><br>

            <div class="submit_container">
                <input id="login" type="button" class="edit_input_submit" value="登录">
            </div>
        </form>
    </div>
</div>
{%/block%}