
var availableFields = new Map([['field1', 'Field 1'], ['field2', 'Field 2'], ['field3', 'Field 3'],['field4', 'Field 4']]);


function reorder_sequences() {
    let sequenceFields = document.getElementById("fields").getElementsByClassName("fieldSequence");


    for (var i = 0; i < sequenceFields.length; i++) {
        sequenceFields[i].value="" + i;

        console.log(sequenceFields[i]);
        //Do something
    }

}

function fieldAlreadySelected(field, select=null) {
    let selectFields = document.getElementById("fields").getElementsByClassName("fieldSelector");
    let result = false;
    console.log("field=" + field);
    console.log("select=" + select);
    for (var i = 0; i < selectFields.length; i++) {
        console.log("SelectFields.value=" + selectFields[i].value);
        result = select != selectFields[i] && selectFields[i].value == field;
        console.log("Result=" + result);
        if (result) {
            break;
        }
    }
    return result;
}



function selectField(select) {

    if (!fieldAlreadySelected(select.value,select=select)) {
        console.log("select=" + select);
        console.log("select.parentNode=" + select.parentNode);
        let inputFieldValue = select.parentElement.getElementsByClassName("fieldLabel")[0];
        inputFieldValue.name="_post_" + select.value;
        inputFieldValue.value=select.options[select.selectedIndex].text;

        inputFieldSequence = select.parentElement.getElementsByClassName("fieldSequence")[0];
        inputFieldSequence.name="_post_" + select.value + "_sequence";
    }
    else {
        // restore old value to select
        let inputFieldValue = select.parentElement.getElementsByClassName("fieldLabel")[0];
        let field_name = inputFieldValue;
        console.log("field_name=" + field_name);
        old_select_value = field_name.value.substring("_post_".length);
        console.log("old_select_value=" + old_select_value);
        select.value=old_select_value;
        console.error("Field already selected!. Try to choose another field");
    }
}


function up(button) {
    let row = button.parentElement;

    let div = row.parentElement;
    let previousRow = row.previousElementSibling;
    if (previousRow) {
        div.insertBefore(row,previousRow);
   }
   reorder_sequences();
}

function down(button) {
    let row = button.parentElement;

    let div = row.parentElement;
    let nextRow = row.nextElementSibling;

    if (nextRow) {
        div.insertBefore(nextRow, row);
   }
   reorder_sequences();
}

 // force the selectFunction to be called to initialise everything correctly
// selectFirstAvailabl


function reorder_sequences() {
    let sequenceFields = document.getElementById("fields").getElementsByClassName("fieldSequence");


    for (var i = 0; i < sequenceFields.length; i++) {
        sequenceFields[i].value="" + i;

        console.log(sequenceFields[i]);
        //Do something
    }

}


function selectFirstAvailableField(fieldSelector) {
    let firstAvailable = null;
    availableFields.forEach( (value,key,map)=> {
        if (!fieldAlreadySelected(key) && !firstAvailable) {
           firstAvailable=key;
        }
    });
    console.log("First Available: " + firstAvailable);

    return firstAvailable;
}

function up(button) {
    let row = button.parentElement;

    let div = row.parentElement;
    let previousRow = row.previousElementSibling;
    if (previousRow) {
        div.insertBefore(row,previousRow);
   }
   reorder_sequences();
}

function down(button) {
    let row = button.parentElement;

    let div = row.parentElement;
    let nextRow = row.nextElementSibling;

    if (nextRow) {
        div.insertBefore(nextRow, row);
   }
   reorder_sequences();
}

function add_field_button() {
     let fields = document.getElementById("fields");


     const div = document.createElement('div');
      div.className = 'row';

      div.innerHTML = `

        <select class="fieldSelector" onchange="selectField(this)">

        </select>
        <input class="fieldSequence" type="hidden">
        <input class="fieldLabel" type="text">
        <input type="button" name="up"  onclick="up(this)" value="Up"/>
        <input type="button" name="down"  onclick="down(this)" value="Down"/>
      `;



     fieldSelector = div.getElementsByClassName("fieldSelector")[0];
     console.log("Field Selector"+ fieldSelector);
     console.log("Field Selector Parent"+ fieldSelector.parentElement);
     availableFields.forEach( (value, key, map) => {
         var option = document.createElement("option");
         option.text=value;
         option.value=key;
         fieldSelector.add(option);
      });
     fieldSelector.value="";

    console.log("test");
     // force the selectFunction to be called to initialise everything correctly
    firstAvailableField=selectFirstAvailableField(fieldSelector);
    if (firstAvailableField) {
      fieldSelector.value=firstAvailableField;
      selectField(fieldSelector);
      fields.appendChild(div);
    }

    reorder_sequences();
}

