function switchMsgTab(asTab)
{
    $('.msg_item_table').hide();
    $('#msg_' + asTab).show();
    
    $('.msg_tabs > li').removeClass('active');
    $('#tab_' + asTab).addClass('active');
}


function MsgReply()
{
    var ajax = new Ajax();
    
    ajax.action = "msg.php?id=" + $('#msg_id').val();
    ajax.method = "post";
    
    ajax.createFormArray({ reply : 1, text : $('#msgText').val() + $('#msgAppend').html()});
    
    ajax.onready = function ()
    {
        if(this.response == "ok")
        {
            raiseSuccess(l('msg_send'));
            tb_remove();        
        }
        else
        {
            raiseError(this.response);
        }
    }
    
    ajax.run();
}
function MsgCompose()
{
    var ajax = new Ajax();
    
    ajax.action = "msg.php?to=" + $('#msg_to').val();
    ajax.method = "post";
    
    ajax.createFormArray({ compose : 1, subj : $('#msgSubject').val(), text : $('#msgText').val()});
    
    ajax.onready = function ()
    {
        if(this.response == "ok")
        {
            raiseSuccess(l('msg_send'));
            tb_remove();        
        }
        else
        {
            raiseError(this.response);
        }
    }
    
    ajax.run();
}
function MsgToggle(asTab)
{
    var obs = $("#msg_"+asTab+".msg_item_table tbody tr td input");
    for(i = 1;i<obs.length;i++)
        obs[i].click();
}

function MsgBox(url)
{
    showModal(url,650,400,{});
}