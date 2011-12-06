function saythis(request) 
{ 
    alert(request);
} 

function yesno(request) 
{ 
    if (confirm(request)) 
    { 
        return true; 
    } 
    else 
    { 
        return false; 
    } 
} 
    
function link_popup(src,xsize,ysize) 
{
    var atts = "top=15, left=15, resize=0, location=0, scrollbars=0, statusbar=0, menubar=0, width="+ xsize + ", height=" + ysize;
    var theWindow = window.open(src.getAttribute('href'), 'popup', atts);
    theWindow.focus();
    return theWindow;
}

function active_popup(src,name,xsize,ysize) 
{
    var atts = "top=15, left=15, resize=1, location=0, scrollbars=0, statusbar=0, menubar=0, width="+ xsize + ", height=" + ysize;
    var theWindow = window.open(src, name, atts);
    theWindow.focus();
}
