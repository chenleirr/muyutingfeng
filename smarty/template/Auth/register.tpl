{%extends file="../layout.tpl"%}


{%block name="js"%}
<script>
    require(['/web/custom/js/auth/register']);
</script>

{%/block%}


{%block name="main"%}
<div>
    <div class="register_container">
        <form id="register_form" method="post">
            <label>名字:</label><br>
            <input type="text" name="name" class="edit_input_text" placeholder="不能超过10个字符"><br>

            <label>手机:</label><br>
            <input type="text" name="mobile" class="edit_input_text" placeholder="请输入11位手机号码"><br>

            <label>邮箱:</label><br>
            <input type="text" name="email" class="edit_input_text" placeholder="您的邮箱"><br>

            <label>密码:</label><br>
            <input type="password" name="password" class="edit_input_text" placeholder="至少6个字符"><br>

            <label>密码确认:</label><br>
            <input type="password" name="password_confirmation" class="edit_input_text" placeholder="请再输入一次密码"><br>

            <div class="submit_container">
                <input id="register" type="button" class="edit_input_submit" value="注册">
            </div>
        </form>
    </div>
</div>
{%/block%}