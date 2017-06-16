{%extends file="../layout.tpl"%}


{%block name="js"%}
<script>
    require(['/web/custom/js/article/edit']);
</script>

{%/block%}


{%block name="main"%}
    <div>
        <div id="edit_container" class="edit_container">
            <form id="edit_form" action="/api/article/insert" method="post">
                <label>标题:</label><br>
                <input type="text" id="title" name="title" class="edit_input_text" placeholder="标题..."><br>

                <label>标题图片链接:</label><br>
                <input type="text" id="title_pic" name="title_pic" class="edit_input_text" placeholder="http://..."><br>

                <label>分组:</label><br>
                <input type="text" id="group" name="group" class="edit_input_text" placeholder="分组..."><br>

                <label>密码:</label><br>
                <input type="password" id="key" name="key" class="edit_input_text" placeholder="密码..."><br>

                <label>正文:</label><br>
                <textarea id="markdown_area" name="content" class="edit_text_area"></textarea>

                <div class="submit_container">
                    <input type="button" class="edit_input_submit" id="do_save" value="保存">
                    <input type="button" class="edit_input_submit" id="button_hide" value="隐藏>>">
                </div>
            </form>
        </div>
        <div class="edit_container" id="markdown_show">

        </div>
    </div>
{%/block%}