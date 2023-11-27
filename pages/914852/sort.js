// initialize the counter and the array
var numbernames = 0;
var names = new Array();

function SortNames() {
    // Get the name from the text field
    thename = document.theform.newname.value;
    // Add the name to the array
    names[numbernames] = thename;
    // Increment the counter
    numbernames++;
    // Sort the array
    names.sort();
    newNames = up(names);
    document.theform.sorted.value = newNames.join("\n");
}

function up(arr) {
    let temp = new Array();
    for (let i = 0; i < arr.length; i++) {
        temp[i] = i+1 + ". " + arr[i].toUpperCase();
    }
    return temp;
}

function clickbutton(event) {
    if (event.keyCode === 13) {
        event.preventDefault();
        document.theform.addname.click();
    }
}