'use strict';



/**
 * navbar toggle
 */

const navbar = document.querySelector("[data-navbar]");
const navbarLinks = document.querySelectorAll("[data-nav-link]");
const menuToggleBtn = document.querySelector("[data-menu-toggle-btn]");

menuToggleBtn.addEventListener("click", function () {
  navbar.classList.toggle("active");
  this.classList.toggle("active");
});

for (let i = 0; i < navbarLinks.length; i++) {
  navbarLinks[i].addEventListener("click", function () {
    navbar.classList.toggle("active");
    menuToggleBtn.classList.toggle("active");
  });
}



/**
 * header sticky & back to top
 */

const header = document.querySelector("[data-header]");
const backTopBtn = document.querySelector("[data-back-top-btn]");

window.addEventListener("scroll", function () {
  if (window.scrollY >= 100) {
    header.classList.add("active");
    backTopBtn.classList.add("active");
  } else {
    header.classList.remove("active");
    backTopBtn.classList.remove("active");
  }
});


const deliveryBoy = document.querySelector("[data-delivery-boy]");

let deliveryBoyMove = -80;
let lastScrollPos = 0;

window.addEventListener("scroll", function () {

  let deliveryBoyTopPos = deliveryBoy.getBoundingClientRect().top;

  if (deliveryBoyTopPos < 500 && deliveryBoyTopPos > -250) {
    let activeScrollPos = window.scrollY;

    if (lastScrollPos < activeScrollPos) {
      deliveryBoyMove += 1;
    } else {
      deliveryBoyMove -= 1;
    }

    lastScrollPos = activeScrollPos;
    deliveryBoy.style.transform = `translateX(${deliveryBoyMove}px)`;
  }

});

// login

document.addEventListener('DOMContentLoaded', function() {
  const loginBtn = document.getElementById('loginBtn');
  const loginPopup = document.getElementById('loginPopup');
  const closeBtn = document.getElementById('closeBtn');

  const signupBtn = document.getElementById('signupBtn');
  const signupPopup = document.getElementById('signupPopup');
  const closeBtnSignup = document.getElementById('closeBtnSignup');

  loginBtn.addEventListener('click', function() {
      loginPopup.style.display = 'block';
  });

  closeBtn.addEventListener('click', function() {
      loginPopup.style.display = 'none';
  });

  signupBtn.addEventListener('click', function() {
      signupPopup.style.display = 'block';
  });

  closeBtnSignup.addEventListener('click', function() {
      signupPopup.style.display = 'none';
  });
});




document.addEventListener('DOMContentLoaded', function() {
  const locationButton = document.getElementById('locationButton');
  const locationOptions = document.querySelectorAll('.location-option');

  locationOptions.forEach(option => {
      option.addEventListener('click', function(e) {
          e.preventDefault();
          const selectedLocation = this.getAttribute('data-location');
          locationButton.textContent = selectedLocation;
      });
  });
});



// Add this JavaScript to your file

const textElement = document.querySelector('.hero-text');
const textContent = textElement.textContent;
textElement.textContent = '';

let index = 0;

function type() {
  if (index < textContent.length) {
    textElement.textContent += textContent.charAt(index);
    index++;
    setTimeout(type, 50); // Adjust the typing speed (in milliseconds) here
  }
}

type();





document.addEventListener('DOMContentLoaded', function() {
  const quantityInputs = document.querySelectorAll('.quantity');

  quantityInputs.forEach(input => {
      input.addEventListener('input', updateTotalPrice);
  });

  const quantityButtons = document.querySelectorAll('.quantity-button');

  quantityButtons.forEach(button => {
      button.addEventListener('click', handleQuantityButtonClick);
  });

  function updateTotalPrice() {
      const productItem = this.closest('.product-item');
      const price = parseFloat(productItem.querySelector('.price').textContent.replace('₹', ''));
      const quantity = parseInt(this.value);
      const totalPrice = price * quantity;
      productItem.querySelector('.total-price').textContent = `Total: ₹${totalPrice.toFixed(2)}`;
  }

  function handleQuantityButtonClick() {
      const input = this.parentElement.querySelector('.quantity');
      let quantity = parseInt(input.value);

      if (this.classList.contains('plus')) {
          quantity++;
      } else if (this.classList.contains('minus')) {
          if (quantity > 1) {
              quantity--;
          }
      }

      input.value = quantity;
      updateTotalPrice.call(input); // Call the updateTotalPrice function with the input as 'this'
  }
});

















document.addEventListener('DOMContentLoaded', function() {
  const chatContainer = document.getElementById('chat-container');
  const chatMessages = document.getElementById('chat-messages');
  const userInput = document.getElementById('user-input');
  const sendButton = document.getElementById('send-btn');
  const imageInput = document.getElementById('image-input');

  function addMessage(content, sender) {
      const message = document.createElement('div');
      message.classList.add('message');
      message.classList.add(sender);
      if (sender === 'user' && content.startsWith('data:image')) {
          const img = document.createElement('img');
          img.src = content;
          message.appendChild(img);
      } else {
          message.innerText = content;
      }
      chatMessages.appendChild(message);
  }

  // Display a greeting message
  addMessage('How can I assist you today?', 'bot');

  // Event listener for sending user messages
  sendButton.addEventListener('click', function() {
      const userMessage = userInput.value;
      if (userMessage) {
          addMessage(userMessage, 'user');
          userInput.value = '';
      }

      // Send user message to Dialogflow (not implemented in this example)
      // Receive response from Dialogflow and display it
      // For simplicity, you can manually call addMessage with bot responses
  });

  // Event listener for image input
  imageInput.addEventListener('change', function() {
      const file = imageInput.files[0];
      const reader = new FileReader();
      reader.onload = function() {
          const base64Image = reader.result;
          addMessage(base64Image, 'user');
      };
      reader.readAsDataURL(file);
  });
});
