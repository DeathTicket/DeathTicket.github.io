var url = "https://deathticket.github.io/730656_";
var this_c = parseInt(document.getElementsByTagName("meta").chapter.content);
function main() {

    loadsound();

    for (var i=urlimageList.length-1; i>=0; i--) {
        add_option($("tools-selector"), urlimageList[i]);
    };

    if (urlimageList.indexOf(this_c-1)>=0) {
        $("ch_prev_btn").classList.remove('disabled');
        $("ch_prev_btn").href = url+(this_c-1)+'.html';
    }
    if (urlimageList.indexOf(this_c+1)>=0) {
        $("ch_next_btn").classList.remove('disabled');
        $("ch_next_btn").href = url+(this_c+1)+'.html';
    }
};


const $ = (id) => {return document.getElementById(id);}

const makeRequest = (method, url, data = {}) => {
    const xhr = new XMLHttpRequest();
    return new Promise(resolve => {
        xhr.open(method, url, true);
        xhr.onload = () => resolve({
            status: xhr.status,
            response: xhr.responseText
        });
        xhr.onerror = () => resolve({
            status: xhr.status,
            response: xhr.responseText
        });
        if (method != 'GET') xhr.setRequestHeader('Content-Type', 'application/json');
        data != {} ? xhr.send(JSON.stringify(data)) : xhr.send();
    })
}
const loadsound = async() => {
    let request = await makeRequest("GET", "https://deathticket.github.io/lib/listsound.json");
    var listsound = JSON.parse(request.response);
    if (listsound.hasOwnProperty(this_c)) {
        $("_audio").src = "sound/"+listsound[this_c];
        $("top_bgm").style.display = "block";
    }
}
function add_option(from, value) {
    var option  = document.createElement("option");
    option.text = "Chapter "+value.toString().padStart(3, ' ');
    option.value = url+value+'.html';
    option.rel = "nofollow";
    if (value==document.getElementsByTagName("meta").chapter.content) {option.selected=true;}
    from.add(option);
};


_btn.addEventListener('click', function() {
    if(_audio.paused){_btn.classList.remove('fa-off');_btn.classList.add('fa-on');_audio.play();}
    else{_btn.classList.remove('fa-on');_btn.classList.add('fa-off');_audio.pause();}
});

setInterval(function(){if(_audio.ended){_btn.classList.remove('fa-on');_btn.classList.add('fa-off');}},30);
main();
