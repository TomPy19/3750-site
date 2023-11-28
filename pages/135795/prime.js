// Thomas Personett
// 10/04/2023
// CPSC 3750
// program exam #1 
// grade level you completed - A

document.addEventListener("DOMContentLoaded", function () {
  var primeList = document.getElementById("prime-numbers");
  var nonPrimeList = document.getElementById("non-prime-numbers");
  var startButton = document.getElementById("start-button");
  var totalNonP = document.getElementById("nonP-total");
  var totalPrime = document.getElementById("p-total");
  var inputNum = document.getElementById("number-input");

  isPrime = (n) => {
    if (n === 1) return false;
    for (var i = 2; i <= Math.sqrt(n); i++) {
      if (n % i === 0) {
        return false;
      }
    }
    return true;
  };

  genLists = (n) => {
    for (var i = 1; i <= n; i++) {
      var listItem = document.createElement("li");
        listItem.textContent = i;
      if (isPrime(i)) {
        primeList.appendChild(listItem);
      } else {
        nonPrimeList.appendChild(listItem);
    }
    }
  };

  changeColors = () => {
    primeList.style.backgroundColor = getRandomColor();
    nonPrimeList.style.backgroundColor = getRandomColor();
  };

  getRandomColor = () => {
    var varters = "0123456789ABCDEF";
    var color = "#";
    for (var i = 0; i < 6; i++) {
      color += varters[Math.floor(Math.random() * 16)];
    }
    return color;
  };

  calcSum = (type) => {
    var list, field, p;
    if (type === "prime") {
      list = primeList;
      field = totalNonP;
    } else {
      list = nonPrimeList;
      field = totalPrime;
    }
    var items = list.querySelectorAll("li");
    var sum = 0;
    items.forEach((item) => {
      sum += parseInt(item.innerHTML);
    });
    field.innerHTML = "Total: "+sum;
  };

  startClick = () => {
    genLists(inputNum.value);
    setInterval(changeColors, 5000);
  };

  enterSubmit = (event) => {
    if (event.keyCode === 13) {
      startButton.click();
    }
  };
});