<div id="V3_Content">

    <div class="class_content_wrapper">
        <div class="info_first_head">{:l('v3_news')}</div>
    
    
    <table id="einstellungen">
        {foreach $news as $content}
            <tr>
                <th>{$content['news_titel']}<span style="float:right">{:date("d.m.Y",$content["news_datum"])}</span></th>
            </tr>
            <tr>
                <td>
                    {$content["news_text"]|utf8_encode}
                </td>
            </tr>
        {/foreach}
    </table>
    </div>
</div>