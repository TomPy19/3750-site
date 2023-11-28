document.addEventListener("DOMContentLoaded", function () {
let movingButtons = [];
let isMoving = false;
const move_button = document.getElementById('ctrl_move');
const spawn_area = document.getElementById('spawn_area');
const make_button = document.getElementById('submit_make');
let movingInterval;

make_button.addEventListener("click", function() {
    this.button=document.createElement("input");
    this.button.type="button";
    this.button.className="fButton";
    this.button.id=Math.pow(-1,parseInt(100*Math.random()));
    this.button.style.backgroundColor=document.button_form.dropdown.value;
    if (this.button.style.backgroundColor==="red"||this.button.style.backgroundColor==="blue") this.button.style.color = "white";
    this.button.value=parseInt(100*Math.random());
    this.button.style.top=parseInt((spawn_area.clientHeight-30)*Math.random()) + "px";
    this.button.style.left=parseInt((spawn_area.clientWidth-50)*Math.random()) + "px";
    this.button.addEventListener("click",function() {
        this.style.backgroundColor=document.button_form.dropdown.value;
        (this.style.backgroundColor==="red"||this.style.backgroundColor==="blue")?this.style.color="white":this.style.color="black";
        let total = parseInt(document.getElementById("runTotal").innerHTML);
        document.getElementById('runTotal').innerHTML = total+parseInt(this.value);
    });
    spawn_area.appendChild(this.button);
    movingButtons.push(this.button)
});

move_button.addEventListener("click", function () {
    if (isMoving) {
        move_button.value = "MOVE";
        isMoving = false;
        clearInterval(movingInterval);
    } else {
        move_button.value = "PAUSE";
        isMoving = true;
        movingInterval = setInterval(moveButtons, 10);
    }
});

function moveButtons() {
    movingButtons.forEach((button) => {
        const left = parseInt(button.style.left);
        const dir = button.id;
        const newLeft = parseInt(left) + 5*parseInt(dir);

        if (newLeft >= 0 && newLeft <= spawn_area.clientWidth-50) {
            button.style.left = newLeft + "px";
        } else {
            button.id = parseInt(button.id) * -1;
        }
    });
};

});