function showToast(message) {
  // Create a div element for the toast
  const toast = document.createElement('div');
  toast.className = 'toast'; // Add any class for styling
  toast.textContent = message;

  // Append the toast to the body
  document.body.appendChild(toast);

  // Remove the toast after a few seconds
  setTimeout(() => {
    toast.remove();
  }, 3000);
}

function handlePrintResponse(response) {
  console.log(response);
  if (!response.ok) {
    showToast('Failed to print. Response code: ' + response.status);
    throw new Error('Network response was not ok.');
  }
  return response.json();
}

function printContent(url) {
  fetch(url, {
    method: 'GET',
  })
  .then(handlePrintResponse)
  .then(data => {
    let printWindow = window.open('');
    printWindow.document.write(data.html);
    printWindow.document.close();
    printWindow.onload = function () {
      printWindow.print();
      printWindow.close();
    };
  })
  .catch(error => console.error('Error:', error));
}

function printBill(id) {
  printContent('print/bill/' + id);
}

function printKOT(id) {
  printContent('print/kot/' + id);
}

function printSticker(id) {
  printContent('print/stickers/' + id);
}
