
const fetchJson = async () => {
    try {
        const data = await fetch('listsound.json');
        const response = await data.json();
    } catch (error) {
        console.log(error);
    }
};

// fetch('lib/listsound.json').then(response => {return response.json();}).then(data => console.log(data));

var url = "https://deathticket.github.io/730656_";
function main() {
    for (var i=urlimageList.length-1; i>=0; i--) {
        add_option(document.getElementById("tools-selector"), urlimageList[i]);
    };

    this_c = parseInt(document.getElementsByTagName("meta").chapter.content);
    if (listsound.hasOwnProperty(this_c)) {
        _audio.src = "sound/"+listsound[this_c];
        top_bgm.style.display = "block";
    }
    if (urlimageList.indexOf(this_c-1)>=0) {
        ch_prev_btn.classList.remove('disabled');
        ch_prev_btn.href = url+(this_c-1)+'.html';
    }
    if (urlimageList.indexOf(this_c+1)>=0) {
        ch_next_btn.classList.remove('disabled');
        ch_next_btn.href = url+(this_c+1)+'.html';
    }
};


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