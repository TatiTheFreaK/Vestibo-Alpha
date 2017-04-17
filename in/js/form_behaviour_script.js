<!--
/*
        ==============================================================================
        =====        VESTIBO ALPHA === FormBehaviour  (Javascript) === LAMPI     =====
        ==============================================================================

        Ultima revisao: 02/06/2015		(Dennys Pistoni, William Vecchini e Matheus Baroni)
*/
	var pageteste = 0;
	var end = 0;
	var p = qtdquestions-1;
	//at é de atual
	var numquesat = 0;
	var atpag = 1;
	var contapags = qtdquestions/10;
	var qtdpag = Math.ceil(contapags);
	var testvalue = false;
	locais = new Array(campoquestao);
	//ao se inicializa executa
	window.onload = function(){showpages();}
	//exibe os blocos de questoes ou os esconde
	function showpages() {
		$("html, body").animate({ scrollTop: 0 }, "slow");
		document.getElementById("curr_page").innerText = "Pagina: "+atpag+" de "+qtdpag+".";
		if(atpag == 1 && qtdpag <= 1 && p < 10) {
			for(var i=0;i<qtdquestions; i++) {
				document.getElementById("div"+i).style.visibility="visible";
				document.getElementById("div"+i).style.display = "block";
				numques = i;
			};
			numquesat = numques;
		} else if(atpag == 1 && qtdpag >= 1) {
			for(var i = 0; i < 10; i++) {
				document.getElementById("div"+i).style.visibility = "visible";
				document.getElementById("div"+i).style.display = "block";
				numques=i;
			};
			numquesat=numques;
			for(var i = 10; i < qtdquestions; i++) {
				document.getElementById("div"+i).style.visibility = "hidden";
				document.getElementById("div"+i).style.display = "none";
			};
			atpag++;
		} else if(atpag > 1) {
			var test = qtdquestions-((atpag*10)-10);
			if (test <= 10) {
				if(pageteste == 10) {
					for (var x = numquesat; x < qtdquestions; x++) {
						document.getElementById('div'+x).style.visibility="visible";
						document.getElementById('div'+x).style.display = "block";
						numques = x;
					};
					for(var i = numquesat; i >= 0; i--) {
						document.getElementById('div'+i).style.visibility="hidden";
						document.getElementById('div'+i).style.display = "none";
					};
					pageteste = 0;
					numquesat = numques;
				}
			} else {
				var numques2 = numques+11;
				if(pageteste == 10){
					for(var x = numquesat; x < numques2; x++)
					{
						document.getElementById("div"+x).style.visibility="visible";
						document.getElementById("div"+x).style.display = "block";
						numques = x;
					};
					for(var i = numquesat; i >= 0; i--){
						document.getElementById("div"+i).style.visibility="hidden";
						document.getElementById("div"+i).style.display = "none";
					};
					for(var x = numques2; x < qtdquestions; x++) {
						document.getElementById("div"+x).style.visibility="hidden";
						document.getElementById("div"+x).style.display = "none";
					};
					numquesat = numques;
					pageteste = 0;
					atpag++;
				}
			}
		}
	}
	//marca a quetao feita e liga as funcoes verifica e showpages
	function feito(local) {
		document.getElementById(local).style.color="#000";
		for (var x = 0; x < campoquestao; x++){
			if(locais[x] == local) {testvalue = true;}
		};
		if(testvalue == false){
			for (var x = 0; x < campoquestao; x++){
				if(locais[x] == undefined) {
					pageteste++;
					end++;
					locais[x] = local;
					testvalue = false;
					break;
				}
			};
		} testvalue = false;
	}
	//confere se todos as questoes foram respondidas
	function verifica() {
		var c = 1;
		for (var x = 0; x < campoquestao; x++) {
			var radios = document.getElementsByName("id_res"+x);
			if(document.getElementById("div"+x).style.visibility == "visible" && document.getElementById("div"+x).style.display == "block" ) {
				if (radios[0].checked == false && radios[1].checked == false && radios[2].checked == false && radios[3].checked == false && radios[4].checked == false) {
					if (c == 1) {alert("Você se esqueceu de alguma das questões."); c++;};
					document.getElementById('div'+x).style.color="#D20808";
				}
				else{ document.getElementById('div'+x).style.color="#000";}
			}
		};
	}
	//funcao para encerrar a prova
	function encerra() {
		var conta= qtdquestions - end;
		if(conta == 0){
			document.FormQuestions.action = actionDir;
			document.FormQuestions.submit();
		}			
	}
	//executa todas as funcoes relacionadas ao botao
	function btFaz() {
		verifica();
		showpages();
		encerra();
	}
/*
        =================================================================================
        =====        VESTIBO ALPHA === FormBehaviour  (Javascript) === LAMPI        =====
        =================================================================================
*/
//-->