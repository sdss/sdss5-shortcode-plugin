// JavaScript source code
function show_hide_vac(divname) {
    var cbdivname = "cb-" + divname;
    var cbdiv = document.getElementById(cbdivname);

    var thisdivlist = document.getElementsByClassName(divname);

    if (cbdiv.checked) {
        for (let i = 0; i < thisdivlist.length; i++) {
            thisdivlist[i].style.display = 'inline';
        }
    } else {
        for (let i = 0; i < thisdivlist.length; i++) {
            thisdivlist[i].style.display = 'none';
        }
    }
    count_vacs_visible();
    return;
}



function count_vacs_all() {  // called on page load
    allvaclist = document.getElementsByClassName('vac');
    visiblediv = document.getElementById('vac-count-visible');
    visiblediv.innerHTML = allvaclist.length;

    alldiv = document.getElementById('vac-count-all');
    alldiv.innerHTML = allvaclist.length;

    return;
}

function count_vacs_visible() {
    allvaclist = document.getElementsByClassName('vac');
    hiddencount = 0;
        for (let i = 0; i < allvaclist.length; i++) {
            if (allvaclist[i].style.display == 'none') {
                hiddencount = hiddencount + 1;
            }
    }
    visiblecount = allvaclist.length - hiddencount;
    visiblediv = document.getElementById('vac-count-visible');
    
    visiblediv.innerHTML = visiblecount;
    
    return;
}

function clear_all_filters() {
    alldivs = document.getElementsByTagName('input');
    for (let i = 0; i < alldivs.length; i++) {
        thename = alldivs[i].className;
        if (thename == 'vac-filter-checkbox') {
            alldivs[i].checked = false;
        }
    }
    visiblediv = document.getElementById('vac-count-visible');
    visiblediv.innerHTML = '0';
    return;
}

function select_all_catalogs() {
    alldivs = document.getElementsByTagName('input');
    for (let i = 0; i < alldivs.length; i++) {
        thename = alldivs[i].className;
        if (thename == 'vac-filter-checkbox') {
            alldivs[i].checked = true;
        }
    }
    allvaclist = document.getElementsByClassName('vac');
    visiblediv = document.getElementById('vac-count-visible');
    visiblediv.innerHTML = allvaclist.length;
    return;
}
