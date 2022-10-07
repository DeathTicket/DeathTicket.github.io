// ]>> BEGIN SCRIPT
window.document.getElementById("h237").parentNode.removeChild(window.document.getElementById("h237"));

function main() {
    getChapter(getCookie("chapter"));
    for (var i=Object.keys(database).length-1; i>=0; i--) {
        data = Object.keys(database)[i]
        if (database[data][0]&&database[data][1]) {
            add_option($("tools-selector"), Object.keys(database)[i]);
        }
    };
    // eraseCookie("chapter");

    if (getChapter()) {
        if (Object.keys(database).indexOf(""+(getChapter()-1))>=0&&database[(getChapter()-1)][0]&&database[(getChapter()-1)][1]) {
            $("ch_prev_btn").classList.remove('disabled');
            $("ch_prev_btn").setAttribute('onclick', "window.scrollTo(0, 1);setCookie('currentTimeAudio', 0);setCookie('chapter', "+(getChapter()-1)+");");
        }
        if (Object.keys(database).indexOf(""+(getChapter()+1))>=0&&database[(getChapter()+1)][0]&&database[(getChapter()+1)][1]) {
            $("ch_next_btn").classList.remove('disabled');
            $("ch_next_btn").setAttribute('onclick', "window.scrollTo(0, 1);setCookie('currentTimeAudio', 0);setCookie('chapter', "+(getChapter()+1)+");");
        }
        loadsound();
        window.scrollTo(0, getCookie('scroll'));
        window.addEventListener('scroll', function() {setCookie('scroll', window.scrollY);});

        var url = "https://image-comic.pstatic.net/mobilewebimg/730656/";
        // var url = "730656_"+getChapter()+"/";

        url += getChapter()+"/"+dtb_deathticket(getChapter())[0]+"_";
        for (let index = 1; index <= dtb_deathticket(getChapter())[1]; ++index) {
            var liElement = document.createElement('li');
            var pElement = document.createElement('p');
            var imgElement = document.createElement('img');

            imgElement.setAttribute('data-src', index.toString().padStart(3, '0')+".jpg");
            imgElement.setAttribute('onerror', "onimgerror(this);");
            imgElement.setAttribute('oncontextmenu', "return false;")
            imgElement.setAttribute('ondblclick', "return false;")
            imgElement.setAttribute('onclick', "return false;")

            pElement.appendChild(imgElement);
            liElement.appendChild(pElement);
            $('daftarimg').appendChild(liElement)
        }

        var imgElements = document.querySelectorAll('img');
        imgElements.forEach((c) => {
            c.setAttribute('src', url+c.getAttribute('data-src'));
        });
    }
};


const $ = (id) => {return document.getElementById(id);}
const getChapter = (v) => {
    if (v) {document.getElementsByTagName("meta").chapter.content = v;}
    else {return parseInt(document.getElementsByTagName("meta").chapter.content);}
}
const dtb_deathticket = (c) => {return database[c];}
const onimgerror = (img) => {
    img.setAttribute('src', 'lib/404.jpeg');
    img.setAttribute('ondblclick', "alert('"+img.getAttribute('data-src')+"');");
    img.setAttribute('style', 'border: 0px solid purple;border-width: 5px 0px;pointer-events: all;');
}
const add_option = (from, value) => {
    var optionElement = document.createElement('option');
    optionElement.text = "Chapter "+value.toString().padStart(3, ' ');
    optionElement.value = value;
    optionElement.rel = "nofollow";
    if (value==getChapter()) {optionElement.selected=true;}
    from.add(optionElement);
}
const loadsound = () => {
    if (dtb_deathticket(getChapter())[2]) {
        $("_audio").src = "sound/"+dtb_deathticket(getChapter())[2];
        $("top_bgm").style.display = "block";
        
        setTimeout(function() {
            if ($("_audio").paused) {
                $('_audio').play();
                $('top_bgm').style = "opacity:1;";
                setTimeout(function(){$('top_bgm').style = "opacity:.1;";}, 1000);
            }
        }, (Timeout||1000));
    }
}
const setCookie = (name, value, xdays=7) => {
    var expires = "";
    if (xdays) {
        var date = new Date();
        date.setTime(date.getTime() + (xdays*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
}
const getCookie = (name) => {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}
const eraseCookie = (name) => {
    document.cookie = name + '=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}


(function() {
    var mouseTimer = null,
        cursorVisible = true;

    function disappearCursor() {
        mouseTimer = null;
        document.body.style.cursor = "none";
        cursorVisible = false;
    }

    document.onmousemove = function(){
        if (mouseTimer) {
            window.clearTimeout(mouseTimer);
        }
        if (!cursorVisible) {
            document.body.style.cursor = "default";
            cursorVisible = true;
        }
        mouseTimer = window.setTimeout(disappearCursor, 2500);
    }

    // document.onkeydown = function(e) {
    //     if (e.keyCode == 123) {return false;}
    //     if (e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) {return false;}
    //     if (e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)) {return false;}
    //     if (e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) {return false;}
    //     if (e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) {return false;}
    // }
})();


$('_btn').onclick = function() {
    if ($('_audio').paused) {$('_audio').play();}
    else {$('_audio').pause();}
}
$('_btn').ondblclick = function() {
    $('_audio').pause();
    $('_audio').currentTime = 0;
}
$('_audio').onplaying = function() {
    $('_btn').classList.remove('fa-off');
    $('_btn').classList.add('fa-on');
}
$('_audio').onpause = function() {
    $('_btn').classList.remove('fa-on');
    $('_btn').classList.add('fa-off');
}
$('_audio').onended = function() {
    setCookie("currentTimeAudio", 0);
    $('_btn').classList.remove('fa-on');
    $('_btn').classList.add('fa-off');
}
$('_audio').ontimeupdate = function() {
    setCookie("currentTimeAudio", $('_audio').currentTime);
}

window.onload = function() {
    var anchors = document.getElementsByClassName('fx1');
    for (var i = 0; i < anchors.length; i++) {
        var anchor = anchors[i];
        anchor.setAttribute('onmouseover', "this.style='transition:opacity 1s;opacity:1';");
        anchor.setAttribute('onmouseleave', "this.style='transition:opacity 1s;opacity:.1';");
    }
}

var Timeout = 0;
if (getCookie("currentTimeAudio")) {
    $('_audio').currentTime=getCookie("currentTimeAudio");
    Timeout = 1;
}
$('listChap').style = "opacity:1;";
setTimeout(function(){$('listChap').style = "opacity:.1;";}, 1000);

main();
// ENDED SCRIPT <<[
