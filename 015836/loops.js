import {genCardArr} from "./cards.js";

var showBtn = document.getElementById("show-all");
showBtn.onclick = () => {
  showBtn.disabled = true;
  var names = genCardArr();
  for (var i=0; i < names.length; i++) names[i].printCard();
}