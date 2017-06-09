{%extends file="../layout.tpl"%}


{%block name="js"%}
<script>
    require(['/web/custom/js/article/detail']);
</script>
{%/block%}

{%block name="main"%}
    <div class="custom_article">
        <link href="/web/custom/css/markdown.css" rel="stylesheet" />
        <style>
            .markdown-body {
                box-sizing: border-box;
                min-width: 200px;
                max-width: 980px;
                margin: 0 auto;
                padding: 45px;
            }
        </style>
        <div id="main_content" class="markdown-body">

        </div>
    </div>
{%/block%}