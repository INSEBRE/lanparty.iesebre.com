/* CK Forms */

var editValue = null;

if (window.addEventListener){
	window.addEventListener("load",initPage,false);
} else if (window.attachEvent){ 
	var r = window.attachEvent("onload", initPage); 
} else {
	window.alert("Problem to add EventListener to Window Object !");  
}

function initPage() {
	initList('t_listRB','t_listHRB','t_valueRB','t_defaultRB');
	initList('t_listS','t_listHS','t_valueS','t_defaultS');
	typeFieldChange();
}

function addValueToList(elemlist,elemhidden,elemval,elemlab,elemcheck) {
	var elemlist=document.getElementById(elemlist);
	var elemhidden=document.getElementById(elemhidden);
	var elemval=document.getElementById(elemval);
	var elemlab=document.getElementById(elemlab);
	var elemcheck=document.getElementById(elemcheck);
	
	if (elemlist && elemhidden && elemval && elemlab && elemcheck) {
		var value = elemval.value;
		var label = elemlab.value;
		var check = 0;
				
		if (value != '') {
			value = value + "||" + label;
			if (elemcheck.checked) {
				value = value + ' [default]';
				check = 1;
				cleanOptions(elemlist);
			}
			
			if (editValue == null) {				
				addOption(elemlist,value,elemval,elemcheck);
			} else {
				var elemupdt=document.getElementById(editValue);
				if (elemupdt) {
					elemupdt.value = value;
					elemupdt.text = value;
					editValue = null;
				}
			}
			
			elemval.value = '';
			elemlab.value = '';
			elemcheck.checked = false;
			
			buildHiddenField(elemlist,elemhidden);
			
		} else {
			window.alert("Value field connot be empty !");
		}
		
	} else {
		window.alert("addValueToList : Problem to retreive values ");
	}
}

function buildHiddenField(selectbox,elemhidden) {
	if (selectbox && elemhidden) {
		var value = "";
		for(var i=0;i<selectbox.options.length;i++) {
			value = value + selectbox.options[i].id+"=="+selectbox.options[i].value;
			if (i<(selectbox.options.length-1)) value = value +"[-]"; 
		}
		elemhidden.value = value;
	} else {
		window.alert("buildHiddenField : Problem to retreive values /n" + selectbox + " : " + elemhidden);
	}
}

function editValueList(elemlist,elemval,elemlab,elemcheck) {
	
	var elemlist=document.getElementById(elemlist);
	var elemval=document.getElementById(elemval);
	var elemlab=document.getElementById(elemlab);
	var elemcheck=document.getElementById(elemcheck);
	
	if (elemlist && elemval && elemlab && elemcheck) {
		
		var objSelected = elemlist.options[elemlist.selectedIndex];
		if (!objSelected) {
			window.alert("No options selected !");
			return false;
		}
		
		for(i=elemlist.options.length-1;i>=0;i--) {
			if(elemlist.options[i].selected) {
				var sep = elemlist.options[i].value.indexOf('||');
				var label = "";
				var value = "";
								
				if(index != -1) {
					value = elemlist.options[i].value.substring(0,sep); 
					label = elemlist.options[i].value.substring(sep+2); 
				}
				
				//alert(label + " : " + value);
				
				elemval.value = value;
				
				var index = label.indexOf(' [default]');
				if(index != -1) {
					elemlab.value = label.substring(0,index); 
					elemcheck.checked = true;
				} else {
					elemlab.value = label; 
					elemcheck.checked = false;
				}
				
				editValue = objSelected.id;
			}
		}
	} else {
		window.alert("editValueList : Problem to retreive values \n" +  elemlist + " : " + elemval + " : " + elemcheck);
	}
}


function resetValues(elemval,elemlab,elemcheck) {
	
	var elemval=document.getElementById(elemval);
	var elemlab=document.getElementById(elemlab);
	var elemcheck=document.getElementById(elemcheck);
	
	if (elemval && elemlab && elemcheck) {
		
		elemval.value = "";
		elemlab.value = "";
		elemcheck.checked = false;
		editValue = null;
	} else {
		window.alert("editValueList : Problem to retreive values \n" +  elemlist + " : " + elemval + " : " + elemcheck);
	}
}

function cleanOptions(selectbox,elemhidden) {
	
	for(var i=selectbox.options.length-1;i>=0;i--) {
		var index = selectbox.options[i].value.indexOf(' [default]');
		if(index != -1) {
			selectbox.options[i].value = selectbox.options[i].value.substring(0,index); 
			selectbox.options[i].text = selectbox.options[i].value;
		}
	}
}

function addOption(selectbox,text,elemval,elemcheck) {
	
	var optn = document.createElement("OPTION");
	optn.text = text;
	optn.value = text;
	optn.id = "optrb" + selectbox.options.length;
	//addEvent(optn,'click', editValueList,selectbox,elemval,elemcheck); 
	selectbox.options.add(optn);	
}

function addEvent(obj, evType, fn,selectbox,elemval,elemlab,elemcheck){ 
	if (obj.addEventListener){
   		obj.addEventListener(evType, function(e) { fn(selectbox.name,elemval.name,elemlab.name,elemcheck.name,obj); }, false); 
   		return true; 
	} else if (obj.attachEvent){ 
   		var r = obj.attachEvent("selectstart", test); 
		window.alert(r);
   		return r; 
 	} else { 
   		return false; 
 	} 
}

function test() {
	window.alert("Test OK");
}

function removeOptions(selectbox,elemhidden) {
	var selectbox=document.getElementById(selectbox);
	var elemhidden=document.getElementById(elemhidden);
	
	var i;
	for(i=selectbox.options.length-1;i>=0;i--) {
		if(selectbox.options[i].selected)
			selectbox.remove(i);
	}
	
	// Rebuild IDs list
	for(i=0;i<selectbox.options.length;i++) {
		selectbox.options[i].id = "optrb" + i;
	}	
	
	buildHiddenField(selectbox,elemhidden);	
	
}

function initList(elemlist,elemhidden,elemval,elemlab,elemcheck) {
	var hiddenfield=document.getElementById(elemhidden);
	var selectbox=document.getElementById(elemlist);
	var elemval=document.getElementById(elemval);
	var elemlab=document.getElementById(elemlab);
	var elemcheck=document.getElementById(elemcheck);
	
	if (hiddenfield && selectbox ) {
		var pair=hiddenfield.value.split("[-]");
		for (var i=0; i<pair.length; i++) {
			var key=pair[i].split("==");
			if (key.length>1) {
				addOption(selectbox,key[1],elemval,elemlab,elemcheck);
			}
		}
		
		buildHiddenField(selectbox,hiddenfield);	
	} else {
		window.alert("initListRB : Problem to retreive values \n" +  hiddenfield + " : " + selectbox);
	}	
}

function hiddeProperties() {
	var ckf_text=document.getElementById('ckf_text');
	var ckf_hidden=document.getElementById('ckf_hidden');
	var ckf_textarea=document.getElementById('ckf_textarea');
	var ckf_checkbox=document.getElementById('ckf_checkbox');
	var ckf_radiobutton=document.getElementById('ckf_radiobutton');
	var ckf_select=document.getElementById('ckf_select');
	var ckf_button=document.getElementById('ckf_button');
	
	if (ckf_text) ckf_text.style.display='none';
	if (ckf_hidden) ckf_hidden.style.display='none';
	if (ckf_textarea) ckf_textarea.style.display='none';
	if (ckf_checkbox) ckf_checkbox.style.display='none';
	if (ckf_radiobutton) ckf_radiobutton.style.display='none';
	if (ckf_select) ckf_select.style.display='none';
	if (ckf_button) ckf_button.style.display='none';
	
	var mandatory_row = document.getElementById('mandatory_row');
	var custominfo_row = document.getElementById('custominfo_row');
	var customerror_row = document.getElementById('customerror_row');
	var ckf_length = document.getElementById('ckf_length');
	
	if (mandatory_row) mandatory_row.style.display='none';
	if (custominfo_row) custominfo_row.style.display='none';
	if (customerror_row) customerror_row.style.display='none';
	if (ckf_length) ckf_length.style.display='none';
}

function typeFieldChange() {
	
	hiddeProperties();
	
	var typefield=document.getElementById('typefield');
	var value = typefield.options[typefield.selectedIndex].value

	var ckf_type=document.getElementById('ckf_'+value);
	if (ckf_type) {
		ckf_type.style.display='block';
		
		if ('ckf_'+value != "ckf_hidden" && 'ckf_'+value != "ckf_checkbox" && 'ckf_'+value != "ckf_radiobutton" 
				&& 'ckf_'+value != "ckf_select" && 'ckf_'+value != "ckf_button") {
			var mandatory_row = document.getElementById('mandatory_row');
			var custominfo_row = document.getElementById('custominfo_row');
			var customerror_row = document.getElementById('customerror_row');
			var ckf_length = document.getElementById('ckf_length');
			
			if (mandatory_row && custominfo_row && customerror_row && ckf_length) {
				mandatory_row.style.display='block';
				custominfo_row.style.display='block';
				customerror_row.style.display='block';
				ckf_length.style.display='block';
			}
		} else if ('ckf_'+value == "ckf_checkbox") {
			var custominfo_row = document.getElementById('custominfo_row');
			var customerror_row = document.getElementById('customerror_row');
			
			if (custominfo_row && customerror_row) {
				custominfo_row.style.display='block';
				customerror_row.style.display='block';
			}
		} else if ('ckf_'+value == "ckf_select" || 'ckf_'+value == "ckf_radiobutton") {
			var mandatory_row = document.getElementById('mandatory_row');
			var custominfo_row = document.getElementById('custominfo_row');
			var customerror_row = document.getElementById('customerror_row');
			
			if (mandatory_row && custominfo_row && customerror_row) {
				mandatory_row.style.display='block';
				custominfo_row.style.display='block';
				customerror_row.style.display='block';
			}
		}
		
	}

}
