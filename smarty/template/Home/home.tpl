{%extends file="../layout.tpl"%}


{%block name="main"%}
    <input value='{%$info.config%}' id="input_config" type="hidden">
    <div class="content_container" id="content_container">

    </div>
{%/block%}


{%block name="js"%}
<script>
    require(['/web/custom/js/home/home']);
</script>
{%/block%}