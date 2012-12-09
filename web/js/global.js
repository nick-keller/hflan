// widgets
$('input[type=number]:not(.star-meter)').spinner();
$( "input[name*=date]" ).datepicker({dateFormat:"dd/mm/yy", showOtherMonths: true, selectOtherMonths: true, dayNamesMin: [ "Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa" ], firstDay: 1});
$('input[title]').tooltip({ position: { my: "left+15 center", at: "right center" } });

// dialog-confirm
$(document).ready(function() {
    $("#dialog-confirm").dialog({
        autoOpen: false,
        modal: true
    });
});

$("a[href*=delete]").click(function(e) {
    //alert('hello');
    e.preventDefault();
    var targetUrl = $(this).attr("href");

    $("#dialog-confirm").dialog({
        buttons : {
            "Supprimer" : function() {
                window.location.href = targetUrl;
            },
            "Annuler" : function() {
                $(this).dialog("close");
            }
        }
    });

    $("#dialog-confirm").dialog("open");
});

// collection form
$('*[data-action=add-embedded-form]').click(function(){
    var parent = $(this).parent();
    var index = '';
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for(var i=0; i < 5; ++i)
        index += possible.charAt(Math.floor(Math.random() * possible.length));

    var element = $(parent.attr('data-prototype').replace(/__name__/g, index));
    parent.prepend(element);

    element.find('.del-btn').click(function(){
        $(this).parent().animate({opacity: 0, padding: 0, height: 0}, 500, function(){$(this).remove()});
    });
});

$('.del-btn').click(function(){
    $(this).parent().animate({opacity: 0, padding: 0, height: 0}, 500, function(){$(this).remove()});
});

// table
$('tr[href]:not(.selectable) td').click(function(){window.location.href=$(this).parent().attr('href');});

$('tr[href].selectable td').dblclick(function(){
    $(this).parent().addClass('hovered');
    window.location.href=$(this).parent().attr('href');
});

$('tr.selectable:has([type=checkbox]) td').click(function(){
    var checkbox = $(this).parent().find('[type=checkbox]');
    if(checkbox.attr('checked') !== undefined){
        checkbox.removeAttr('checked');
        $(this).parent().removeClass('hovered');
    }
    else{
        checkbox.attr('checked', 'checked');
        $(this).parent().addClass('hovered');
    }
});

// tabs
$('.tabs a:not(.static)').click(function(){
    $(this).addClass('active').parent().siblings().find('a').removeClass('active');
    $($(this).attr('href')).addClass('active').siblings().removeClass('active');
    return false;
});

// txt editor
$('.txtEditor').before(
    '<div class="toolbar">' +
        '<a wrap="**"><i class="icon-bold"></i></a>' +
        '<a wrap="//"><i class="icon-italic"></i></a>' +
        '<a wrap="__"><i class="icon-underline"></i></a>' +
        '<a wrap="~~"><i class="icon-strike"></i></a>' +
        '<a media="link"><i class="icon-link"></i></a>' +
        '<a media="image"><i class="icon-picture"></i></a>' +
        '<a media="video"><i class="icon-play-circle"></i></a>' +
        '<a rel="dropdown-toogle"><i class="icon-text-height"></i></a>' +
        '<div class="dropdown">' +
            '<ul>' +
                '<li><a wrap="+++" href="">Très grand</a></li>' +
                '<li><a wrap="++">Grand</a></li>' +
                '<li><a wrap="--">Petit</a></li>' +
                '<li><a wrap="---">Très pettit</a></li>' +
            '</ul>' +
        '</div>' +
    '</div>');

$('.toolbar > a:not([rel=dropdown-toogle])').click(function(){
    var textarea = $(this).parent().next();
    var text = textarea.val();
    var selection = text.substr(textarea[0].selectionStart, textarea[0].selectionEnd-textarea[0].selectionStart);
    var before = text.substr(0, textarea[0].selectionStart);
    var after = text.substr(textarea[0].selectionEnd);

    if($(this).attr('wrap') !== undefined)
        wrap(textarea, before, selection, after, $(this).attr('wrap'));
    else if($(this).attr('media') !== undefined)
        media(textarea, before, selection, after, $(this).attr('media'));
    return false;
});

$('.toolbar .dropdown a:not([rel=dropdown-toogle])').click(function(){
    closeDropdown($(this).parent().parent().parent());
    var textarea = $(this).parent().parent().parent().parent().next();
    var text = textarea.val();
    var selection = text.substr(textarea[0].selectionStart, textarea[0].selectionEnd-textarea[0].selectionStart);
    var before = text.substr(0, textarea[0].selectionStart);
    var after = text.substr(textarea[0].selectionEnd);

    if($(this).attr('wrap') !== undefined)
        wrap(textarea, before, selection, after, $(this).attr('wrap'));
    else if($(this).attr('media') !== undefined)
        media(textarea, before, selection, after, $(this).attr('media'));
    return false;
});

function wrap(textarea, before, selection, after, wrap)
{
    textarea.val(before + wrap + selection + wrap + after);
    textarea.focus();
    textarea[0].setSelectionRange(before.length+wrap.length, textarea.val().length-after.length-wrap.length);
}

function media(textarea, before, selection, after, type)
{
    var isurl = /^http:\/\/[a-z-A-Z0-9\/&=\?\.]+$/;

    if(type == 'link')
    {
        if(selection != '')
        {
            if(isurl.test(selection))
            {
                var texte = prompt("Texte du lien :", "");
                textarea.val(before + '<' + selection + ' "'+texte+'">' + after);
                textarea.focus();
                textarea[0].setSelectionRange(before.length, textarea.val().length-after.length);
            }
            else
            {
                var texte = prompt("URL du lien :", "");
                textarea.val(before + '<' + texte + ' "'+selection+'">' + after);
                textarea.focus();
                textarea[0].setSelectionRange(before.length, textarea.val().length-after.length);
            }
        }
        else
        {
            var url = prompt("URL du lien :", "");
            var texte = prompt("Texte du lien :", "");
            if(texte == '')
                textarea.val(before + '<' + url + '>' + after);
            else
                textarea.val(before + '<' + url + ' "'+texte+'">' + after);
            textarea.focus();
            textarea[0].setSelectionRange(before.length, textarea.val().length-after.length);
        }
    }
    else if(type == 'image' || type == 'video')
    {
        if(selection != '' && isurl.test(selection))
        {
            textarea.val(before + '<' + selection + '>' + after);
            textarea.focus();
            textarea[0].setSelectionRange(before.length, textarea.val().length-after.length);
        }
        else
        {
            if(type == 'image')
                var texte = prompt("URL de l'image :", "");
            else
                var texte = prompt("URL de la vidéo :", "");
            textarea.val(before + selection + '<' + texte + '>' + after);
            textarea.focus();
            textarea[0].setSelectionRange(before.length+selection.length, textarea.val().length-after.length);
        }
    }
}

// dropdown
var activeDropdown = null;

$('*[rel=dropdown-toogle]').click(function(event){
    var menu = $(this).next('div.dropdown');

    if(menu.hasClass('active'))
        closeDropdown(menu);
    else
        activateDropdown(menu);

    return false;
}).mousedown(function(){return false;});

function activateDropdown(menu){
    menu.parent().parent().find('.dropdown').removeClass('active').prev('*[rel=dropdown-toogle]').removeClass('hovered');
    menu.addClass('active').prev('*[rel=dropdown-toogle]').addClass('hovered');
    if(activeDropdown == null)
        activeDropdown = menu;
    else if(activeDropdown.has(menu).length == 0){
        closeDropdown(activeDropdown);
        activeDropdown = menu;
    }
}

function closeDropdown(menu){
    menu.removeClass('active');
    menu.find('.dropdown').removeClass('active');
    menu.find('*[rel=dropdown-toogle]').removeClass('hovered');
    menu.prev('*[rel=dropdown-toogle]').removeClass('hovered');
    if(menu.is(activeDropdown))
        activeDropdown = null;
}

$('html').click(function(){
    $('.dropdown').removeClass('active');
    $('*[rel=dropdown-toogle]').removeClass('hovered');
    activeDropdown = null;
});

//preview

$('.preview-img').parent().prev().find('[type=file]').css('display', 'none');

$('.preview-img').click(function(){
    $(this).parent().prev().find('[type=file]').css('display', 'block');
});

//star-meter

$('.star-meter').css('display', 'none').before(
    '<div class="star-meter-widget"></div>'
);

$('.star-meter-widget').mouseout(function(){
    var val = 100 - parseInt($(this).next().val()) * 20;
    $(this).css('background-position', val.toString() + '% 0');
});

$('.star-meter-widget').mousemove(function(e){
    var x = e.pageX - $(this).offset().left;
    var width = 100 - 20*Math.ceil(x/28);
    $(this).css('background-position', width.toString() + '% 0');
});

$('.star-meter-widget').click(function(e){
    var x = e.pageX - $(this).offset().left;
    $(this).next().val(Math.ceil(x/28));
});