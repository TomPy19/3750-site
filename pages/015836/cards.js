var formBtn = document.getElementById("form-submit");
formBtn.onclick = (event)=>{
   event.preventDefault();
   new Card(
      document.getElementById("name").value,
      document.getElementById("email").value,
      document.getElementById("address").value,
      document.getElementById("phone").value,
      document.getElementById("birthdate").value
   ).printCard();
   return false;
}

// define the functions
function printCard() {
   var cardDiv = document.getElementById("cards-div");
   var newDiv = document.createElement("div");
   newDiv.style="border:1px solid;";
   var nameLine = "<strong>Name: </strong>" + this.name + "<br>";
   var emailLine = "<strong>Email: </strong>" + this.email + "<br>";
   var addressLine = "<strong>Address: </strong>" + this.address + "<br>";
   var phoneLine = "<strong>Phone: </strong>" + this.phone + "<br>";
   var birthdateLine = "<strong>Birthdate: </strong>" + this.birthdate;
   newDiv.innerHTML+=nameLine+emailLine+addressLine+phoneLine+birthdateLine;
   cardDiv.insertBefore(newDiv, document.getElementById("show-all").nextSibling);
}

function Card(name,email,address,phone,birthdate) {
   this.name = name;
   this.email = email;
   this.address = address;
   this.phone = phone;
   this.birthdate = birthdate;
   this.printCard = printCard;
}

// Create the objects
function genCardArr() {
   var cards = new Array();
   var sue = new Card("Sue Suthers", "sue@suthers.com", "123 Elm Street, Yourtown ST 99999", "555-555-9876", "07/19/2001");
   cards[0] = sue;
   var fred = new Card("Fred Fanboy", "fred@fanboy.com", "233 Oak Lane, Sometown ST 99399", "555-555-4444", "03/23/1973");
   cards[1] = fred;
   var jimbo = new Card("Jimbo Jones", "jimbo@jones.com", "233 Walnut Circle, Anotherville ST 88999", "555-555-1344", "08/04/1975");
   cards[2] = jimbo;
   return cards;
}

export {genCardArr}