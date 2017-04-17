<!--
/*
        VESTIBO ALPHA === Menu Switch (Javascript) === LAMPI
        Última revisão: 15/01/2015 (Matheus Baroni)
*/
function toggle_menu() {
	classe = document.getElementById('m').className;
	if (classe == 'esc') {
    	document.getElementById('m').className = 'menu';
	} else {
    	document.getElementById('m').className = 'esc';
	}
}
function toggle_user_menu() {
	classe = document.getElementById('u').className;
	if (classe == 'user-esc') {
    	document.getElementById('u').className = 'user-menu';
	} else {
    	document.getElementById('u').className = 'user-esc';
	}
}
/*
        ==============================================================================
        =====        VESTIBO ALPHA === Menu Switch (Javascript) === LAMPI        =====
        ==============================================================================
*/
//-->