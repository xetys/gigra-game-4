
tutObj = false;
function highlightObject(obj,text)
{
    tutObj = obj;//globalisieren
    var w = obj.outerWidth() + 'px';
    var h = obj.outerHeight() + 'px';
    var x = obj.offset().left + 'px';
    var y = obj.offset().top + 'px';
    var y2 = (obj.offset().top + obj.outerHeight()) + 'px';

    $('#tutBorder').css({'width' : w, 'height' : h, 'top' : y, 'left' : x});
    $('#tutText').css({'top' : y2, 'left' : x});
    $('#tutBorder').show();
    $('#tutText > div').html(text);
    $('#tutText').show();
    
}

function closeTutObj()
{
    $('#tutBorder').hide('fast');
    $('#tutText').hide('fast');
}

function nextTutorial()
{
    if(tutList.length == 0)
    {
        closeTutObj();
        raiseSuccess(l('tut_exit'));
    }
       var elem = tutList.shift();  
       highlightObject(elem.el,elem.text);
}

function startTutorial()
{
    if(tutList.length == 0)
        raiseError(l('tut_no_tuts'));
    else
        nextTutorial();
}

function enableWriteMode()
{
    $("body > *").click(function () {
    
        highlightObject($(this),'schreibe nen text zu ' + $(this).getPath());
        
    });   
}