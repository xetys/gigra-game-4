<!-- Main Wrapper für den V3 Inhalt -->
<div id="V3_Content">

    <div class="class_content_wrapper">
        <div class="info_first_head">{:l('question_title')}</div>
        <div class="tcenter">{$text}</div>
        <br>
        <div class="tcenter">
            <a href='javascript:void(0)' onclick="{$func};tb_remove();"><span class="class_btn einst_btn_save green">{:l('question_yes')}</span></a>
            <a href='javascript:void(0)' onclick="tb_remove();"><span class="class_btn einst_btn_save red">{:l('question_no')}</span></a>
        </div>
        <br>
    </div>
    
</div>