// JavaScript source code
function show_all_authors(pid) {

    var shortid = "authors-short-" + pid;
    var allid = "authors-all-" + pid;

    var shortspan = document.getElementById(shortid);
    var allspan = document.getElementById(allid);

    shortspan.style.display = 'none';
    allspan.style.display = 'inline';

    return;
}

function hide_all_authors(pid) {

    var shortid = "authors-short-" + pid;
    var allid = "authors-all-" + pid;

    var shortspan = document.getElementById(shortid);
    var allspan = document.getElementById(allid);

    shortspan.style.display = 'inline';
    allspan.style.display = 'none';

    return;
}