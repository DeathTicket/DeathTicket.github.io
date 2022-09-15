
var listsound = {
    "22": "730656_22_1573443720586.mp3",
    "49": "730656_49_1589796480447.mp3",
    "67": "730656_67_1600668720425.mp3",
    "69": "730656_69_1601880240401.mp3",
    "92": "730656_92_1616417040592.mp3",
    "93": "730656_93_1617020640664.mp3",
    "94": "730656_94_1617626400671.mp3",
    "96": "730656_96_1618834920599.mp3",
    "115": "730656_115_1630328161344.mp3",
    "120": "730656_120_1633937161269.mp3",
    "121": "730656_121_1634555881364.mp3",
    "122": "730656_122_1635165961364.mp3",
    "139": "730656_139_1645448401280.mp3",
    "140": "730656_140_1646052601127.mp3",
    "141": "730656_141_1646658361159.mp3",
    "142": "730656_142_1647263641135.mp3",
    "143": "730656_143_1648472281067.mp3",
    "144": "730656_144_1649076721094.mp3",
    "145": "730656_145_1649681401137.mp3"
}

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